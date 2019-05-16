<?php
use \Bitrix\Main\Config\Option,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Loader;
Loader::includeModule('pugleev.evaluationorders');

$module_id = 'pugleev.evaluationorders';

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
Loc::loadMessages(__FILE__);
$SUP_RIGHT = $APPLICATION->GetGroupRight($module_id);

if ($SUP_RIGHT >= "R") :?>
<?
    $aTabs = array(
        array(
            "DIV" => "rights",
            "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
            "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS"),
            "OPTIONS" => array()
        ),
    );
    $request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();
    #Сохранение

    if ($request->isPost() && $request['Apply'] && check_bitrix_sessid()) {

        foreach ($aTabs as $aTab) {
            foreach ($aTab['OPTIONS'] as $arOption) {
                if (!is_array($arOption)) {
                    continue;
                }

                if ($arOption['note']) {
                    continue;
                }
                __AdmSettingsSaveOption($module_id, $arOption);

                $optionValue = $request->getPost($optionName);

                Option::set($module_id, $optionName,
                    is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            }
        }
    }

    $tabControl = new CAdminTabControl('tabControl', $aTabs);

    ?>
    <? $tabControl->Begin(); ?>
    <form method='post'
          action='<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($request['mid']) ?>&lang=<?= $request['lang'] ?>'
          name='pugleev_evaluationorders_setting'>

        <? foreach ($aTabs as $aTab):
            if ($aTab['OPTIONS']):?>
                <? $tabControl->BeginNextTab(); ?>
                <? __AdmSettingsDrawList($module_id, $aTab['OPTIONS']); ?>

            <? endif;
        endforeach; ?>

        <?
        $tabControl->BeginNextTab();

        require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");

        $tabControl->Buttons(); ?>
        <script language="JavaScript">
            function RestoreDefaults() {
                if (confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
                    window.location = "<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?echo LANG?>&mid=<?echo urlencode($mid)?>&<?echo bitrix_sessid_get()?>";
            }
        </script>
        <input <?
        if ($SUP_RIGHT < "W") echo "disabled" ?> type="submit" name="Apply"  value="<? echo GetMessage('MAIN_SAVE') ?>">
        <input type="hidden" name="Update" value="Y">
        <input type="reset" name="reset" value="<?= GetMessage("MAIN_RESET") ?>">
        <input <?
        if ($SUP_RIGHT < "W") echo "disabled" ?> type="button" title="<?
        echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>" OnClick="RestoreDefaults();" value="<?
        echo GetMessage("MAIN_RESTORE_DEFAULTS") ?>">
        <?= bitrix_sessid_post() ?>
    </form>
    <? $tabControl->End(); ?>
<?
else:
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
endif;
?>
