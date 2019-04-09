<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

/**
 * Class SeederProperty
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederProperty extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_properties_shopaholic_properties';
    protected $sFilePath = 'property_list.csv';

    protected $arFieldList = ['active', 'name', 'code', 'type', 'measure_id', 'description', 'sort_order'];

    /** @var \Lovata\PropertiesShopaholic\Models\Property */
    protected $obModel;

    /**
     * @return string
     */
    protected function getModelName()
    {
        return \Lovata\PropertiesShopaholic\Models\Property::class;
    }

    /**
     * Process row from csv file
     */
    protected function process()
    {
        parent::process();

        //Get value list
        $sValueList = array_shift($this->arRowData);
        if(empty($sValueList)) {
            return;
        }

        $arValueList = explode('|', $sValueList);

        $arSettings = ['list' => []];

        //Prepare settings array
        foreach ($arValueList as $sValue) {

            if(empty($sValue)) {
                continue;
            }

            $arSettings['list'][] = ['value' => $sValue];
        }

        $this->obModel->settings = $arSettings;
        $this->obModel->save();
    }
}