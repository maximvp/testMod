<?
namespace Pugleev\EvaluationOrders;
use \Bitrix\Main,
    \Bitrix\Main\Entity,
    \Bitrix\Main\Localization\Loc;


IncludeModuleLangFile(__FILE__);
/**
 * Class OlTable
 **/

class OlTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'pugleev_order';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('OL_ENTITY_ID_FIELD'),
            ),
            'ORDER_ID' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('OL_ENTITY_ORDER_ID_FIELD'),
            ),
            'CURER' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('OL_ENTITY_CURER_FIELD'),
            ),
            'PACKAGING' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('OL_ENTITY_PACKAGING_FIELD'),
            ),
            'QUALITY' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('OL_ENTITY_QUALITY_FIELD'),
            ),
            'USER' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('OL_ENTITY_USER_FIELD'),
            ),
            'ORDER_DESCRIPTIONS' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('OL_ENTITY_ORDER_DESCRIPTIONS_FIELD'),
            ),
            'CREATED' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('OL_ENTITY_CREATED_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for SYSTEM_TYPE field.
     *
     * @return array
     */
    public static function validateSystemType()
    {
        return array(
            new Entity\Validator\Length(null, 20),
        );
    }
}