<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

/**
 * Class SeederPropertyGroup
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederPropertyGroup extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_properties_shopaholic_groups';
    protected $sFilePath = 'property_group_list.csv';

    protected $arFieldList = ['name', 'code'];

    protected function getModelName()
    {
        return \Lovata\PropertiesShopaholic\Models\Group::class;
    }
}