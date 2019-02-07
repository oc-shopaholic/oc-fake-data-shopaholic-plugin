<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

/**
 * Class SeederPaymentMethod
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederPaymentMethod extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_orders_shopaholic_payment_methods';
    protected $sFilePath = 'payment_method_list.csv';

    protected $arFieldList = ['active', 'name', 'code', 'preview_text'];

    protected function getModelName()
    {
        return \Lovata\OrdersShopaholic\Models\PaymentMethod::class;
    }
}