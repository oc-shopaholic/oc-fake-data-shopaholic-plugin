<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

use Schema;

/**
 * Class SeederOrderProperty
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederOrderProperty extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_orders_shopaholic_addition_properties';
    protected $sFilePath = 'order_property_list.csv';

    protected $arFieldList = ['active', 'name', 'code', 'type'];

    /** @var \Lovata\OrdersShopaholic\Models\OrderProperty */
    protected $obModel;

    /**
     * Run command
     */
    public function run()
    {
        if(!Schema::hasTable($this->sTableName)) {
            return;
        }

        $this->openFile();
        $this->seed();
        $this->closeFile();
    }

    /**
     * @return string
     */
    protected function getModelName()
    {
        return \Lovata\OrdersShopaholic\Models\OrderProperty::class;
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