{*
 *  Script: manage.tpl
 *      Customer manage template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-10-06 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if $first_run_wizard == true}
    <div class="si_message">
        {$LANG.thank_you} {$LANG.before_starting}
    </div>
    <table class="si_table_toolbar">
        {if empty($billers)}
            <tr>
                <th>{$LANG.setup_as_biller}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=billers&amp;view=add" class="positive">
                        <img src="images/common/user_add.png" alt=""/>
                        {$LANG.add_new_biller}
                    </a>
                </td>
            </tr>
        {/if}
        {if empty($customers)}
            <tr>
                <th>{$LANG.setup_add_customer}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=customers&amp;view=add" class="positive">
                        <img src="images/common/vcard_add.png" alt=""/>
                        {$LANG.customer_add}
                    </a>
                </td>
            </tr>
        {/if}
        {if empty($products)}
            <tr>
                <th>{$LANG.setup_add_products}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=products&amp;view=add" class="positive">
                        <img src="images/common/cart_add.png" alt=""/>
                        {$LANG.add_new_product}
                    </a>
                </td>
            </tr>
        {/if}
        <tr>
            <th>{$LANG.setup_customisation}</th>
            <td class="si_toolbar">
                <a href="index.php?module=system_defaults&amp;view=manage" class="">
                    <img src="images/common/cog_edit.png" alt=""/>
                    {$LANG.system_preferences}
                </a>
            </td>
        </tr>
    </table>
{else}
    <div class="si_toolbar si_toolbar_top">
        <a href="index.php?module=customers&amp;view=add" class="">
            <img src="images/famfam/add.png" alt=""/>
            {$LANG.customer_add}
        </a>
    </div>
    {if $number_of_customers == 0}
        <div class="si_message">{$LANG.no_customers}</div>
    {else}
        <br/>
        <table id="manageGrid" style="display: none"></table>
        {include file='modules/customers/manage.js.php'}
    {/if}
{/if}