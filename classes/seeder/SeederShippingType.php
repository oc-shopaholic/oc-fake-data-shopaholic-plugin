<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

/**
 * Class SeederShippingType
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederShippingType extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_orders_shopaholic_shipping_types';
    protected $sFilePath = 'shipping_type_list.csv';

    protected $arFieldList = ['active', 'name', 'code', 'preview_text'];

    protected function getModelName()
    {
        return \Lovata\OrdersShopaholic\Models\ShippingType::class;
    }
}