<?php
class Product {
    /**
     * @return int count of product rows
     */
    public static function count() {
        global $pdoDb;
        $count = 0;
        try {
            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());
            $rows = $pdoDb->request("SELECT", "products");
            if (!empty($rows)) {
                $count = $rows[0]['count'];
            }
        } catch (PdoDbException $pde) {
            error_log("Product::count() - error: " . $pde->getMessage());
            $count = 0;
        }
        return $count;
    }

    /**
     * @param int $id of product record to select.
     * @return array select row or empty array if row not found.
     */
    public static function select($id) {
        global $pdoDb, $LANG;

        $row = array();
        try {
            $cs = new CaseStmt("enabled", "wording_for_enabled");
            $cs->addWhen("=", ENABLED, $LANG['enabled']);
            $cs->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($cs);

            $pdoDb->addSimpleWhere("id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "products");
            $row = (empty($rows) ? $rows : $rows[0]);
        } catch (PdoDbException $pde) {
            error_log("Product::select for id[$id] - error: " . $pde->getMessage());
        }
        return $row;
    }

    /**
     * @param bool $enabled true (default) if only enabled rows should be selected,
     *              false if all rows should be selected.
     * @return array Product rows selected or empty array if none.
     */
    public static function select_all($enabled = true) {
        global $pdoDb, $LANG;

        try {
            $cs = new CaseStmt("enabled", "wording_for_enabled");
            $cs->addWhen("=", ENABLED, $LANG['enabled']);
            $cs->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($cs);

            if ($enabled) {
                $pdoDb->addSimpleWhere("enabled", ENABLED, 'AND');
            }
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());

            $pdoDb->setOrderBy(array(array("description", "A"), array("id", "A")));

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "products");
        } catch (PdoDbException $pde) {
            error_log("Product::select_all() - error: " . $pde->getMessage());
            $rows = array();
        }
        return $rows;
    }

    /**
     * @param $type
     * @param $dir
     * @param $sort
     * @param $rp
     * @param $page
     * @return array
     */
    public static function xml_select($type, $dir, $sort, $rp, $page) {
        global $LANG, $pdoDb;

        $rows = array();
        try {
            $query = isset($_POST['query']) ? $_POST['query'] : null;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
            if (!empty($qtype) && !empty($query)) {
                $valid_search_fields = array('id', 'description', 'unit_price');
                if (in_array($qtype, $valid_search_fields)) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
                }
            }
            $pdoDb->addSimpleWhere("p.visible", ENABLED, "AND");
            $pdoDb->addSimpleWhere("p.domain_id", domain_id::get());

            if (($type == "count")) {
                $pdoDb->addToFunctions("COUNT(*) as count");
                $rows = $pdoDb->request("SELECT", "products", "p");
                return $rows[0]['count'];
            }

            if (intval($rp) != $rp) $rp = 25;

            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);

            if (in_array($sort, array('p.id', 'p.description', 'p.unit_price', 'p.enabled'))) {
                if (!preg_match('/^(a|asc|d|desc)$/iD', $dir)) $dir = 'D';
                $pdoDb->setOrderBy(array($sort, $dir));
            } else {
                // Default to major sort for enabled items first and secondary sort for descriptions.
                $pdoDb->setOrderBy(array(array("p.enabled", "D"), array("p.description", "A")));
            }

            // @formatter:off
            $pdoDb->setSelectList(array("p.id", "p.description", "p.unit_price", "p.enabled"));

            $fn = new FunctionStmt("COALESCE", "SUM(ii.quantity),0");
            $fr = new FromStmt("invoice_items", "ii");
            $fr->addTable("invoices", "iv");
            $fr->addTable("preferences", "pr");
            $wh = new WhereClause();
            $wh->addSimpleItem("ii.product_id", new DbField("p.id"), "AND");
            $wh->addSimpleItem("ii.domain_id", new DbField("p.domain_id"), "AND");
            $wh->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
            $wh->addSimpleItem("iv.preference_id", new DbField("pr.pref_id"), "AND");
            $wh->addSimpleItem("pr.status", ENABLED);
            $se = new Select($fn, $fr, $wh, "qty_out");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("COALESCE", "SUM(inv.quantity),0");
            $fr = new FromStmt("inventory", "inv");
            $wc = new WhereClause();
            $wc->addSimpleItem("inv.product_id", new DbField("p.id"), "AND");
            $wc->addSimpleItem("inv.domain_id" , new DbField("p.domain_id"));
            $se = new Select($fn, $fr, $wc, "qty_in");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("COALESCE", "p.reorder_level,0");
            $se = new Select($fn, null, null, "reorder_level");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("", "qty_in");
            $fn->addPart("-",  "qty_out");
            $se = new Select($fn, null, null, "quantity");
            $pdoDb->addToSelectStmts($se);

            $ca = new CaseStmt("p.enabled", "enabled");
            $ca->addWhen( "=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);
            // @formatter:on

            $rows = $pdoDb->request("SELECT", "products", "p");
        } catch (PdoDbException $pde) {
            error_log("Product::xml_select() - error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Insert a new record in the products table.
     * @param int $enabled Product enabled/disabled status used if not present in
     *        the <b>$_POST</b> array. Defaults to ENABLED (1) or set to DISABLED (0).
     * @param int $visible Flags record seen in list. Defaults to ENABLED (1) for
     *        visible or DISABLED (0) for not visible.
     * @return int New ID if insert OK. 0 if insert failed.
     * @throws PdoDbException
     */
    public static function insertProduct($enabled=ENABLED, $visible=ENABLED) {
        global $pdoDb;

        if (isset($_POST['enabled'])) $enabled = $_POST['enabled'];

        if (($attributes = $pdoDb->request("SELECT", "products_attributes")) === false) {
            error_log("Products::insertProduct - Unable to load \"products_attributes\"");
            return false;
        }

        $attr = array();
        foreach ($attributes as $v) {
            if (isset($_POST['attribute' . $v['id']]) && $_POST['attribute' . $v['id']] == 'true') {
                $attr[$v['id']] = $_POST['attribute' . $v['id']];
            }
        }

        $notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
        $show_description     = (isset($_POST['show_description']    ) && $_POST['show_description'    ] == 'true' ? 'Y' : NULL);

        $custom_flags = '0000000000';
        for ($i = 1; $i <= 10; $i++) {
            if (isset($_POST['custom_flags_' . $i]) && $_POST['custom_flags_' . $i] == ENABLED) {
                $custom_flags = substr_replace($custom_flags, ENABLED, $i - 1, 1);
            }
        }

        $description = (isset($_POST['description']) ? $_POST['description'] : "");
        $unit_price  = (isset($_POST['unit_price'])  ? siLocal::dbStd($_POST['unit_price']) : "0");
        $cost        = (isset($_POST['cost'])        ? siLocal::dbStd($_POST['cost'])       : "0");
        $fauxPost = array('domain_id'            => domain_id::get(),
                          'description'          => $description,
                          'unit_price'           => $unit_price,
                          'cost'                 => $cost,
                          'reorder_level'        => (isset($_POST['reorder_level'] ) ? $_POST['reorder_level']  : "0"),
                          'custom_field1'        => (isset($_POST['custom_field1'] ) ? $_POST['custom_field1']  : ""),
                          'custom_field2'        => (isset($_POST['custom_field2'] ) ? $_POST['custom_field2']  : ""),
                          'custom_field3'        => (isset($_POST['custom_field3'] ) ? $_POST['custom_field3']  : ""),
                          'custom_field4'        => (isset($_POST['custom_field4'] ) ? $_POST['custom_field4']  : ""),
                          'notes'                => (isset($_POST['notes']         ) ? $_POST['notes']          : ""),
                          'default_tax_id'       => (isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : ""),
                          'custom_flags'         => $custom_flags,
                          'enabled'              => $enabled,
                          'visible'              => $visible,
                          'attribute'            => json_encode($attr),
                          'notes_as_description' => $notes_as_description,
                          'show_description'     => $show_description);
        $pdoDb->setFauxPost($fauxPost);
        $pdoDb->setExcludedFields("id");

        $result = $pdoDb->request("INSERT", "products");
        if ($result > 0) {
            return $result;
        }
        error_log("Products::insertItems - Unable to store products description, {$description}");
        return 0;
    }

    /**
     * Update a product record.
     * @return bool true if update succeeded, false if not.
     */
    public static function updateProduct() {
        global $pdoDb;

        try {
            if (($attributes = $pdoDb->request("SELECT", "products_attributes")) === false) return false;

            $attr = array();
            foreach ($attributes as $v) {
                $tmp = (isset($_POST['attribute' . $v['id']]) ? $_POST['attribute' . $v['id']] : "");
                if ($tmp == 'true') {
                    $attr[$v['id']] = $tmp;
                }
            }

            // @formatter:off
            $notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
            $show_description     = (isset($_POST['show_description'])     && $_POST['show_description']     == 'true' ? 'Y' : NULL);

            $custom_flags = '0000000000';
            for ($i = 1; $i <= 10; $i++) {
                if (isset($_POST['custom_flags_' . $i]) && $_POST['custom_flags_' . $i] == ENABLED) {
                    $custom_flags = substr_replace($custom_flags, ENABLED, $i - 1, 1);
                }
            }

            $unit_price = (isset($_POST['unit_price']) ? siLocal::dbStd($_POST['unit_price']) : "0");
            $cost       = (isset($_POST['cost'])       ? siLocal::dbStd($_POST['cost'])       : "0");
            $fauxPost = array('description'          => (isset($_POST['description'])    ? $_POST['description']    : ""),
                              'enabled'              => (isset($_POST['enabled'])        ? $_POST['enabled']        : ""),
                              'notes'                => (isset($_POST['notes'])          ? $_POST['notes']          : ""),
                              'default_tax_id'       => (isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : ""),
                              'custom_field1'        => (isset($_POST['custom_field1'])  ? $_POST['custom_field1']  : ""),
                              'custom_field2'        => (isset($_POST['custom_field2'])  ? $_POST['custom_field2']  : ""),
                              'custom_field3'        => (isset($_POST['custom_field3'])  ? $_POST['custom_field3']  : ""),
                              'custom_field4'        => (isset($_POST['custom_field4'])  ? $_POST['custom_field4']  : ""),
                              'custom_flags'         => $custom_flags,
                              'unit_price'           => $unit_price,
                              'cost'                 => $cost,
                              'reorder_level'        => (isset($_POST['reorder_level'])  ? $_POST['reorder_level']  : "0"),
                              'attribute'            => json_encode($attr),
                              'notes_as_description' => $notes_as_description,
                              'show_description'     => $show_description);
            $pdoDb->setFauxPost($fauxPost);

            $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());

            $pdoDb->setExcludedFields(array("id", "domain_id"));
            // @formatter:on

            $result = $pdoDb->request("UPDATE", "products");
        } catch (PdoDbException $pde) {
            error_log("Product::updateProduct() - Database error: " . $pde->getMessage());
            $result = false;
        } catch (Zend_Locale_Exception $zle) {
            error_log("Product::updateProduct() - Zend_Locale_Exception: " . $zle->getMessage());
            $result = false;
        }
        return $result;
    }
}
