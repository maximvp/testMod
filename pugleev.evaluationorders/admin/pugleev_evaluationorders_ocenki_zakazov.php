<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Grid\Options as GridOptions,
    \Bitrix\Main\UI\PageNavigation,
    \Bitrix\Main\Loader,
    \Bitrix\Main\Grid\Panel\Snippet;

Loader::includeModule('pugleev.evaluationorders');

Loc::loadMessages(__FILE__);

global $USER, $APPLICATION, $DB;

$module_id = 'pugleev.evaluationorders';

$APPLICATION->SetTitle(Loc::getMessage("TITLE"));

\Bitrix\Main\Page\Asset::getInstance()->addCss('/bitrix/css/main/grid/webform-button.css');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<?
$SUP_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($SUP_RIGHT >= "R"):
    $list_id = 'evaluation_list';
    $grid_options = new GridOptions($list_id);
    $sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['ORDER_ID' => 'ORDER_ID', 'CREATED' => 'CREATED']]);
    $nav_params = $grid_options->GetNavParams();

    $nav = new PageNavigation($list_id);
    $nav->allowAllRecords(true)
        ->setPageSize($nav_params['nPageSize'])
        ->initFromUri();
    if ($nav->allRecordsShown()) {
        $nav_params = false;
    } else {
        $nav_params['iNumPage'] = $nav->getCurrentPage();
    }

    $ui_filter = [
        ['id' => 'ORDER_ID', 'name' => Loc::getMessage('NUM_ORDER'), 'type'=>'number'],
        ['id' => 'CREATED', 'name' => Loc::getMessage('DATE_VALUETION'), 'type'=>'date'],
    ];
?>
    <script type="text/javascript">
        BX.addCustomEvent('BX.Main.Filter:apply', BX.delegate(function (command, params) {
            var workarea = $('#' + command); // в command будет храниться GRID_ID из фильтра

            $.post(window.location.href, function(data){
                workarea.html($(data).find('#' + command).html());
            })

            alert(command);
        }));
    </script>
    <div>
        <?$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            'FILTER_ID' => $list_id,
            'GRID_ID' => $list_id,
            'FILTER' => $ui_filter,
            'VALUE_REQUIRED_MODE' => true,
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true
        ]);?>
    </div>
    <div style="clear: both;"></div>
<?

    $filter = [];

    $filterOption = new Bitrix\Main\UI\Filter\Options($list_id);
    $filterData = $filterOption->getFilter([]);
    foreach ($filterData as $k => $v) {
        $filter[$k] = $v;
    }

    pre($filter);

    $date = date("d.m.Y H:m:s", time());
    $phpDateTime = new DateTime($date);
    $dateTime = \Bitrix\Main\Type\DateTime::createFromPhp($phpDateTime);

    //demo
    $rsUser = CUser::GetByID(1);
    $arUser = $rsUser->Fetch();
     if(!empty($arUser['NAME'])){
         $fulname = $arUser['NAME'].' '.$arUser['LAST_NAME'];
     }else{
         $fulname = $arUser['LOGIN'];
     }

    $filesAdd[] = [
        'ORDER_ID' => 101,
        'CURER' => 3,
        'PACKAGING' => 4,
        'QUALITY' => 5,
        'USER' => $fulname,
        'ORDER_DESCRIPTIONS'=> htmlspecialcharsEx("Комментарий по заказу 101"),
        'CREATED' => $dateTime
    ];
    $filesAdd[] = [
        'ORDER_ID' => 102,
        'CURER' => 4,
        'PACKAGING' => 5,
        'QUALITY' => 5,
        'USER' => $fulname,
        'ORDER_DESCRIPTIONS'=> htmlspecialcharsEx("Комментарий по заказу 102"),
        'CREATED' => $dateTime
    ];
    $filesAdd[] = [
        'ORDER_ID' => 103,
        'CURER' => 5,
        'PACKAGING' => 4,
        'QUALITY' => 5,
        'USER' => $fulname,
        'ORDER_DESCRIPTIONS'=> htmlspecialcharsEx("Комментарий по заказу 103"),
        'CREATED' => $dateTime
    ];



    $res = Pugleev\EvaluationOrders\OlTable::getList([
    'filter' => $filter,
    'select' => [
            "*",
        ],
    'offset'      => $nav->getOffset(),
    'limit'       => $nav->getLimit(),
    'order'       => $sort['sort'],
    ]);

    $columns = [];
    $columns[] = ['id' => 'ID', 'name' => Loc::getMessage('ID'), 'sort' => 'ID', 'default' => true];
    $columns[] = ['id' => 'ORDER_ID', 'name' => Loc::getMessage('NUM_ORDER'), 'sort' => 'ORDER_ID', 'default' => true];
    $columns[] = ['id' => 'CURER', 'name' => Loc::getMessage('CURER'), 'sort' => 'CURER', 'default' => true];
    $columns[] = ['id' => 'PACKAGING', 'name' => Loc::getMessage('PACKAGING'), 'sort' => 'PACKAGING', 'default' => true];
    $columns[] = ['id' => 'QUALITY', 'name' => Loc::getMessage('QUALITY'), 'sort' => 'QUALITY', 'default' => true];
    $columns[] = ['id' => 'USER', 'name' => Loc::getMessage('FIO_USER'), 'sort' => 'USER', 'default' => true];
    $columns[] = ['id' => 'ORDER_DESCRIPTIONS', 'name' => Loc::getMessage('VALUETION_DESCRIPTIONS'), 'sort' => 'CREATE', 'default' => true];
    $columns[] = ['id' => 'CREATED', 'name' => Loc::getMessage('DATE_VALUETION'), 'sort' => 'CREATE', 'default' => true];

    $elemArr = $res->fetchAll();

    if(count($elemArr) < 3){
        foreach ($filesAdd as $data){
            $addRes = Pugleev\EvaluationOrders\OlTable::add($data);
        }
    }

    foreach ($elemArr as $row) {

        $list[] = [
            'data' => [
                "ID" => $row['ID'],
                "ORDER_ID" => $row['ORDER_ID'],
                "CURER" => $row['CURER'],
                "PACKAGING" => $row['PACKAGING'],
                "QUALITY" => $row['QUALITY'],
                "USER" => $row['USER'],
                "ORDER_DESCRIPTIONS" => $row['ORDER_DESCRIPTIONS'],
                "CREATED" => $row['CREATED'],
            ],
            'actions' => [
                [
                    'text'    => 'Просмотр',
                    'default' => true,
                    'onclick' => 'document.location.href="?op=view&id='.$row['ID'].'"'
                ], [
                    'text'    => 'Удалить',
                    'default' => true,
                    'onclick' => 'if(confirm("Точно?")){document.location.href="?op=delete&id='.$row['ID'].'"}'
                ]
            ]
        ];
    }

?>
<?
    $snippet = new Snippet();

    $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
        'GRID_ID' => $list_id,
        'COLUMNS' => $columns,
        'ROWS' => $list,
        'SHOW_ROW_CHECKBOXES' => true,
        'NAV_OBJECT' => $nav,
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'PAGE_SIZES' =>  [
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
            ['NAME' => '100', 'VALUE' => '100']
        ],
        'AJAX_OPTION_JUMP'          => 'N',
        'ACTION_PANEL' => array(
            'GROUPS' => array(
                array(
                    'ITEMS' => array(
                        $snippet->getRemoveButton(),
                        $snippet->getForAllCheckbox(),
                    )
                )
            )
        ),
        'SHOW_CHECK_ALL_CHECKBOXES' => true,
        'SHOW_ROW_ACTIONS_MENU'     => true,
        'SHOW_GRID_SETTINGS_MENU'   => true,
        'SHOW_NAVIGATION_PANEL'     => true,
        'SHOW_PAGINATION'           => true,
        'SHOW_SELECTED_COUNTER'     => true,
        'SHOW_TOTAL_COUNTER'        => true,
        'SHOW_PAGESIZE'             => true,
        'SHOW_ACTION_PANEL'         => true,
        'ALLOW_COLUMNS_SORT'        => true,
        'ALLOW_COLUMNS_RESIZE'      => true,
        'ALLOW_HORIZONTAL_SCROLL'   => true,
        'ALLOW_SORT'                => true,
        'ALLOW_PIN_HEADER'          => true,
        'AJAX_OPTION_HISTORY'       => 'N'
    ]);
    ?>
<?endif;?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>