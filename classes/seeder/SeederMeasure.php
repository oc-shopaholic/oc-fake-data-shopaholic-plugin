<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

/**
 * Class SeederMeasure
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederMeasure extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_shopaholic_measure';
    protected $sFilePath = 'measure_list.csv';

    protected $arFieldList = ['name'];

    protected function getModelName()
    {
        return \Lovata\Shopaholic\Models\Measure::class;
    }
}