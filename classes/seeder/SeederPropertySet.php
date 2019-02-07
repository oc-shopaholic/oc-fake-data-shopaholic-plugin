<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

/**
 * Class SeederPropertySet
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederPropertySet extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_properties_shopaholic_set';
    protected $sFilePath = 'property_set_list.csv';

    protected $arFieldList = ['name', 'code'];

    /** @var \Lovata\PropertiesShopaholic\Models\PropertySet */
    protected $obModel;

    /**
     * @return string
     */
    protected function getModelName()
    {
        return \Lovata\PropertiesShopaholic\Models\PropertySet::class;
    }
}