<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

/**
 * Class SeederBrand
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederBrand extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_shopaholic_brands';
    protected $sFilePath = 'brand_list.csv';

    protected $arFieldList = ['active', 'name', 'slug'];

    /** @var \Lovata\Shopaholic\Models\Brand */
    protected $obModel;

    protected function getModelName()
    {
        return \Lovata\Shopaholic\Models\Brand::class;
    }

    /**
     * Process row from csv file
     */
    protected function process()
    {
        parent::process();

        $this->obModel->code = $this->obModel->slug;
        $this->fillPreviewText('Brand');
        $this->fillDescription('Brand');

        $this->createPreviewImage('brand');
    }
}
