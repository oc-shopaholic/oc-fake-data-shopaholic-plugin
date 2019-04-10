<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

use System\Classes\PluginManager;

/**
 * Class SeederProduct
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederProduct extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_shopaholic_products';
    protected $sFilePath = 'product_list.csv';

    protected $arFieldList = ['active', 'name', 'slug', 'code', 'category_id', 'brand_id'];

    protected function getModelName()
    {
        return \Lovata\Shopaholic\Models\Product::class;
    }

    /**
     * Process row from csv file
     */
    protected function process()
    {
        parent::process();

        $this->fillPreviewText('Product');
        $this->fillDescription('Product');

        if (PluginManager::instance()->hasPlugin('Lovata.PopularityShopaholic')) {
            $this->obModel->popularity = random_int(0, 1000);
        }

        $this->createModelImages('product');
    }

    /**
     * Seed table
     */
    protected function seed()
    {
        if(empty($this->obFile) || !is_resource($this->obFile)) {
            return;
        }

        //Skip first line
        fgetcsv($this->obFile);

        $arRowList = [];

        //Process rows of csv file
        while (($arRow = fgetcsv($this->obFile)) !== false) {

            //Always skip first column
            array_shift($arRow);
            if(empty($arRow)) {
                continue;
            }

            $arRowList[] = $arRow;
        }

        for ($i = 0; $i < $this->iRepeatLimit; $i++) {
            foreach ($arRowList as $arRow) {
                $this->arRowData = $arRow;
                $this->arRowData[0] = $arRow[0].'-'.$i;
                $this->arRowData[1] = $arRow[1].'-'.$i;
                $this->arRowData[2] = $arRow[2].'-'.$i;

                $this->process();
            }
        }
    }

    /**
     * Create images
     * @param string $sFolderName
     */
    protected function createModelImages($sFolderName)
    {
        if ($this->iRepeatLimit > 10) {
            return;
        }

        parent::createModelImages($sFolderName);
    }

    /**
     * Create new preview image
     * @param string $sFolderName
     */
    protected function createPreviewImage($sFolderName)
    {
        if ($this->iRepeatLimit > 10) {
            return;
        }

        parent::createPreviewImage($sFolderName);
    }
}