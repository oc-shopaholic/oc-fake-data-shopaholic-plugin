<?php namespace Lovata\FakeDataShopaholic\Classes\Console;

use Queue;
use Illuminate\Console\Command;

use Lovata\FakeDataShopaholic\Classes\Queue\GenerateCacheQueue;

/**
 * Class GenerateCacheCommand
 * @package Lovata\FakeDataShopaholic\Classes\Console
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class GenerateCacheCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'shopaholic:item.generate.cache';

    /**
     * @var string The console command description.
     */
    protected $description = 'Generate cache for Item classes';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->generateCache(\Lovata\Shopaholic\Models\Brand::class, \Lovata\Shopaholic\Classes\Item\BrandItem::class);
        $this->generateCache(\Lovata\Shopaholic\Models\Category::class, \Lovata\Shopaholic\Classes\Item\CategoryItem::class);
        $this->generateCache(\Lovata\Shopaholic\Models\Offer::class, \Lovata\Shopaholic\Classes\Item\OfferItem::class);
        $this->generateCache(\Lovata\Shopaholic\Models\Product::class, \Lovata\Shopaholic\Classes\Item\ProductItem::class);

        $this->generateCache(\Lovata\ReviewsShopaholic\Models\Review::class, \Lovata\ReviewsShopaholic\Classes\Item\ReviewItem::class);

        $this->generateCache(\Lovata\PropertiesShopaholic\Models\Group::class, \Lovata\PropertiesShopaholic\Classes\Item\GroupItem::class);
        $this->generateCache(\Lovata\Shopaholic\Models\Measure::class, \Lovata\Shopaholic\Classes\Item\MeasureItem::class);
        $this->generateCache(\Lovata\PropertiesShopaholic\Models\Property::class, \Lovata\PropertiesShopaholic\Classes\Item\PropertyItem::class);
        $this->generateCache(\Lovata\PropertiesShopaholic\Models\PropertySet::class, \Lovata\PropertiesShopaholic\Classes\Item\PropertySetItem::class);
        $this->generateCache(\Lovata\PropertiesShopaholic\Models\PropertyValue::class, \Lovata\PropertiesShopaholic\Classes\Item\PropertyValueItem::class);
    }

    /**
     * Added jobs for generation cache data
     * @param string $sModelClass
     * @param string $sItemClass
     */
    protected function generateCache($sModelClass, $sItemClass)
    {
        if (!class_exists($sModelClass) || !class_exists($sItemClass)) {
            return;
        }

        //Get all models
        $iCount = $sModelClass::count();
        if ($iCount == 0) {
            return;
        }

        $iPageCount = ceil($iCount / 100);

        for ($i = 0; $i < $iPageCount; $i++) {
            Queue::pushOn( env('BRANCH_NAME').'cache', GenerateCacheQueue::class, ['model' => $sModelClass, 'item' => $sItemClass, 'page' => $i]);
        }
    }
}
