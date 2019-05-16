<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("PUGLEEV_VYSTAVLENIYA_OCENOK_COMPONENT_NAME"),
	"DESCRIPTION" => GetMessage("PUGLEEV_VYSTAVLENIYA_OCENOK_COMPONENT_DESCRIPTION"),
	"ICON" => "/images/regions.gif",
	"SORT" => 500,
	"PATH" => array(
		"ID" => "pugleev_evaluationorders_components",
		"SORT" => 500,
		"NAME" => GetMessage("PUGLEEV_VYSTAVLENIYA_OCENOK_COMPONENTS_FOLDER_NAME"),
	),
);

?>