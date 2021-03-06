<script>
{literal}
var columns = 8;
var padding = 10;
var grid_width = $('.col').width();

grid_width = grid_width - (columns * padding);
percentage_width = grid_width / 100;

$('#manageGrid').flexigrid ({
    url: 'index.php?module=customers&view=xml',
    dataType: 'xml',
    colModel : [
        {display: '{/literal}{$LANG.actions}{literal}'            , name : 'actions'       , width :  7 * percentage_width, sortable : false, align: 'center'},
        {display: '{/literal}{$LANG.id}{literal}'                 , name : 'CID'           , width :  5 * percentage_width, sortable : true , align: 'right' },
        {display: '{/literal}{$LANG.name}{literal}'               , name : 'name'          , width : 34 * percentage_width, sortable : true , align: 'left'  },
        {display: '{/literal}{$LANG.customer_department}{literal}', name : 'department'    , width : 12 * percentage_width, sortable : true , align: 'left'  },
        {display: '{/literal}{$LANG.last_invoice}{literal}'       , name : 'last_invoice'  , width :  8 * percentage_width, sortable : false, align: 'right' },
        {display: '{/literal}{$LANG.total}{literal}'              , name : 'customer_total', width :  8 * percentage_width, sortable : true , align: 'right' },
        {display: '{/literal}{$LANG.paid}{literal}'               , name : 'paid'          , width :  8 * percentage_width, sortable : true , align: 'right' },
        {display: '{/literal}{$LANG.owing}{literal}'              , name : 'owing'         , width :  8 * percentage_width, sortable : true , align: 'right' },
        {display: '{/literal}{$LANG.enabled}{literal}'            , name : 'enabled'       , width :  8 * percentage_width, sortable : true , align: 'center'}
        ],
    searchitems : [
        {display: '{/literal}{$LANG.id}{literal}'                         , name : 'c.id'},
        {display: '{/literal}{$LANG.name}{literal}'                       , name : 'c.name', isdefault: true},
        {display: '{/literal}{$LANG.customer_department}{literal}'        , name : 'c.department'},
        {display: '{/literal}{$LANG.address}{literal}'                    , name : 'c.street_address'},
        {display: '{/literal}{$LANG.city}{literal}'                       , name : 'c.city'},
        {display: '{/literal}{$LANG.state}{literal}'                      , name : 'c.state'},
        {display: '{/literal}{$LANG.phone}{literal}'                      , name : 'c.phone'},
        {display: '{/literal}{$LANG.mobile_phone}{literal}'               , name : 'c.mobile_phone'},
        {display: '{/literal}{$LANG.email}{literal}'                      , name : 'c.email'}
        ],
    sortname: 'name',
    sortorder: 'asc',
    usepager: true,
    pagestat: '{/literal}{$LANG.displaying_items}{literal}',
    procmsg: '{/literal}{$LANG.processing}{literal}',
    nomsg: '{/literal}{$LANG.no_items}{literal}',
    pagemsg: '{/literal}{$LANG.page}{literal}',
    ofmsg: '{/literal}{$LANG.of}{literal}',
    useRp: false,
    rp: 25,
    showToggleBtn: false,
    showTableToggleBtn: false,
    height: 'auto'
});
{/literal}
</script>
