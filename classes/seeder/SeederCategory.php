<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

/**
 * Class SeederCategory
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederCategory extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_shopaholic_categories';
    protected $sFilePath = 'category_list.csv';

    protected $arFieldList = ['active', 'name', 'slug', 'parent_id', 'nest_depth', 'nest_left', 'nest_right'];

    /** @var \Lovata\Shopaholic\Models\Category */
    protected $obModel;

    protected function getModelName()
    {
        return \Lovata\Shopaholic\Models\Category::class;
    }

    /**
     * Process row from csv file
     */
    protected function process()
    {
        \Lovata\Shopaholic\Models\Category::extend(function ($obCategory) {
            /** @var \Lovata\Shopaholic\Models\Category $obCategory */
            $obCategory->fillable[] = 'parent_id';
            $obCategory->fillable[] = 'nest_depth';
            $obCategory->fillable[] = 'nest_left';
            $obCategory->fillable[] = 'nest_right';
        });

        parent::process();

        $this->obModel->code = $this->obModel->slug;
        $this->fillPreviewText('Category');
        $this->fillDescription('Category');

        $this->createPreviewImage('category');
    }
}
