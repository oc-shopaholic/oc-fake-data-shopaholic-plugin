<?php namespace Lovata\FakeDataShopaholic\Classes\Console;

use Illuminate\Console\Command;

use Lovata\FakeDataShopaholic\Classes\Seeder\SeederAccessories;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederBrand;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederCategory;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederMeasure;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederOffer;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederOfferPropertyLink;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederOrderProperty;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederPaymentMethod;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederPluginSettings;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederProduct;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederProductPropertyLink;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederProperty;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederPropertyGroup;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederPropertySet;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederPropertySetLink;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederPropertyValue;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederRelatedProducts;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederReviews;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederShippingType;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederSystemImages;

/**
 * Class GenerateFakeDataCommand
 * @package Lovata\FakeDataShopaholic\Classes\Console
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class GenerateFakeDataCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'shopaholic:generate.fake.data';

    /**
     * @var string The console command description.
     */
    protected $description = 'Generate fake data';

    protected $arSeederList = [
        SeederPluginSettings::class,
        SeederSystemImages::class,
        SeederCategory::class,
        SeederBrand::class,
        SeederProduct::class,
        SeederOffer::class,
        SeederPropertySet::class,
        SeederPropertySetLink::class,
        SeederMeasure::class,
        SeederPropertyGroup::class,
        SeederProperty::class,
        SeederProductPropertyLink::class,
        SeederOfferPropertyLink::class,
        SeederPropertyValue::class,
        SeederOrderProperty::class,
        SeederPaymentMethod::class,
        SeederShippingType::class,
        SeederReviews::class,
        SeederRelatedProducts::class,
        SeederAccessories::class,
    ];

    protected $arContentType = [
        'clothes'
    ];

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $iContentType = (int) $this->choice('Select content type', $this->arContentType, 0);
        $iRepeatLimit = (int) $this->ask('How many times to repeat the creating of products? Enter a value from 1 to 1000', 1);

        if (!isset($this->arContentType[$iContentType])) {
            $iContentType = 0;
        }

        $sContentType = $this->arContentType[$iContentType];
        if ($iRepeatLimit < 1) {
            $iRepeatLimit = 1;
        }

        if ($iRepeatLimit > 1000) {
            $iRepeatLimit = 1000;
        }

        foreach ($this->arSeederList as $sClassName) {
            /** @var \Lovata\FakeDataShopaholic\Classes\Seeder\AbstractModelSeeder $obSeeder */
            $obSeeder = new $sClassName($sContentType, $iRepeatLimit);
            $obSeeder->run();
        }
    }
}
