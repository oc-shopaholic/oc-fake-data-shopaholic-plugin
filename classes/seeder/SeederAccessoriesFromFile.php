<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

use Lovata\Shopaholic\Models\Product;

/**
 * Class SeederAccessoriesFromFile
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederAccessoriesFromFile extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_accessories_shopaholic_link';
    protected $sFilePath = 'product_list.csv';

    protected $bSkipFirstColumn = false;

    /** @var \October\Rain\Database\Collection|\Lovata\Shopaholic\Models\Product[] */
    protected $obProductList;

    /**
     * AbstractModelSeeder constructor.
     * @param string $sContentType
     * @param int $iRepeatLimit
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
        if(empty($this->arRowData)) {
            return;
        }

        $iProductID = array_get($this->arRowData, 0);
        $sProductIDList = array_get($this->arRowData, 8);
        $arProductIDList = explode('|', $sProductIDList);
        $arProductIDList = array_filter($arProductIDList);
        if (empty($iProductID)) {
            return;
        }

        /** @var Product $obProduct */
        $obProduct = $this->obProductList->find($iProductID);
        if (empty($obProduct)) {
            return;
        }

        $obProduct->accessory()->sync($arProductIDList);
    }
}