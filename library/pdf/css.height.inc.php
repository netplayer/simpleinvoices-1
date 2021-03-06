<?php
// $Header: /cvsroot/html2ps/css.height.inc.php,v 1.27 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR . 'value.height.php');

class CSSHeight extends CSSPropertyHandler {
    var $_autoValue;

    function __construct()
    {
        parent::__construct(true, false);
        $this->_autoValue = ValueHeight::fromString('auto');
    }

    /**
     * 'height' CSS property should be inherited by table cells from table rows
     */
    function inherit($old_state, &$new_state)
    {
        $parent_display = $old_state[CSS_DISPLAY];
        $this->replace_array(
            ($parent_display === 'table-row') ? $old_state[CSS_HEIGHT] : $this->default_value(),
            $new_state);
    }

    function _getAutoValue()
    {
        return $this->_autoValue->copy();
    }

    function default_value()
    {
        return $this->_getAutoValue();
    }

    public static function parse($value)
    {
        return ValueHeight::fromString($value);
    }

    public static function getPropertyCode()
    {
        return CSS_HEIGHT;
    }

    public static function getPropertyName()
    {
        return 'height';
    }
}

$css_height_inc_reg1 = new CSSHeight();
CSS::register_css_property($css_height_inc_reg1);
