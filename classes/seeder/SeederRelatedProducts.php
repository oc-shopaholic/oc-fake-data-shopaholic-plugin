<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;

/**
 * Class SeederRelatedProducts
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederRelatedProducts extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_related_products_shopaholic_link';

    protected function getModelName()
    {
        return \Lovata\Shopaholic\Models\Product::class;
    }

    /**
     * Seed table
     */
    protected function seed()
    {
        //Get product list
        $obProductList = Product::all();
        if ($obProductList->isEmpty()) {
            return;
        }

        //Get active product list
        $obProductCollection = ProductCollection::make()->active();

        /** @var Product $obProduct */
        foreach ($obProductList as $obProduct) {

            $iCount = mt_rand(0, 6);
            if($iCount == 0) {
                continue;
            }

            $obTempProductList = clone $obProductCollection;
            $arProductList = $obTempProductList->exclude($obProduct->id)->random($iCount);

            /** @var \Lovata\Shopaholic\Classes\Item\ProductItem $obProductItem */
            foreach ($arProductList as $obProductItem) {

                $obProduct->related()->attach($obProductItem->id);
            }

            $obProduct->save();
        }
    }
}