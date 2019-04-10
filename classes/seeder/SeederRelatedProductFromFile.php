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
    protected $iNumber = 0;
    protected $iCount = 0;

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
     * Seed table
     */
    protected function seed()
    {
        if (empty($this->obFile) || !is_resource($this->obFile)) {
            return;
        }

        //Skip first line
        fgetcsv($this->obFile);

        $arRowList = [];

        //Process rows of csv file
        while (($arRow = fgetcsv($this->obFile)) !== false) {
            if (empty($arRow)) {
                continue;
            }

            $arRowList[] = $arRow;
        }

        $this->iCount = count($arRowList);
        for ($i = 0; $i < $this->iRepeatLimit; $i++) {
            $this->iNumber = $i;
            foreach ($arRowList as $arRow) {
                $this->arRowData = $arRow;
                $this->process();
            }
        }
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
        $iProductID += $this->iCount * $this->iNumber;

        $sProductIDList = array_get($this->arRowData, 7);
        $arProductIDList = explode('|', $sProductIDList);
        $arProductIDList = array_filter($arProductIDList);
        foreach ($arProductIDList as &$iRelatedProductID) {
            $iRelatedProductID += $this->iCount * $this->iNumber;
        }

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