<?
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

AddEventHandler("main", "OnBuildGlobalMenu", "global_menu_pugleev_evaluationorders");

function global_menu_pugleev_evaluationorders(&$aGlobalMenu, &$aModuleMenu){
	$aModuleMenu[] = array(
		"parent_menu" => "global_menu_store",
		"icon"        => "default_menu_icon",
		"page_icon"   => "default_page_icon",
		"text"        => Loc::getMessage("PUGLEEV_EVALUATIONORDERS_ADMIN_MENU_OCENKI_ZAKAZOV_TEXT"),
		"title"       => Loc::getMessage("PUGLEEV_EVALUATIONORDERS_ADMIN_MENU_OCENKI_ZAKAZOV_TITLE"),
		"url"         => "pugleev_evaluationorders_ocenki_zakazov.php",
	);

}
?>