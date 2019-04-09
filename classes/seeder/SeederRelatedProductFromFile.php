<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

use Lovata\Shopaholic\Models\Product;

/**
 * Class SeederRelatedProductFromFile
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederRelatedProductFromFile extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_related_products_shopaholic_link';
    protected $sFilePath = 'product_list.csv';

    protected $bSkipFirstColumn = false;

    /** @var \October\Rain\Database\Collection|\Lovata\Shopaholic\Models\Product[] */
    protected $obProductList;

    /**
     * AbstractModelSeeder constructor.
     * @param string $sContentType
     * @param int    $iRepeatLimit
     */
    public function __construct($sContentType, $iRepeatLimit)
    {
        parent::__construct($sContentType, $iRepeatLimit);

        $this->obProductList = Product::all();
    }

    protected function getModelName()
    {
        return \Lovata\Shopaholic\Models\Product::class;
    }

    /**
     * Process row from csv file
     */
    protected function process()
    {
        if (empty($this->arRowData)) {
            return;
        }

        $iProductID = array_get($this->arRowData, 0);
        $sProductIDList = array_get($this->arRowData, 7);
        $arProductIDList = explode('|', $sProductIDList);
        $arProductIDList = array_filter($arProductIDList);
        if (empty($iProductID) | empty($arProductIDList)) {
            return;
        }

        /** @var Product $obProduct */
        $obProduct = $this->obProductList->find($iProductID);
        if (empty($obProduct)) {
            return;
        }

        $obProduct->related()->sync($arProductIDList);
    }
}