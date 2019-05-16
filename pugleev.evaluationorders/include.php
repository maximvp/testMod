<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Bitrix\Main\Loader::registerAutoloadClasses(
    "pugleev.evaluationorders",
    array(
        "Pugleev\\EvaluationOrders\\OlTable" => "lib/oltable.php",
    )
);