<?php namespace Lovata\FakeDataShopaholic\Classes\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

use Lovata\FakeDataShopaholic\Classes\Seeder\SeederAccessories;
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederAccessoriesFromFile;
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
use Lovata\FakeDataShopaholic\Classes\Seeder\SeederRelatedProductFromFile;
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

    /**
     * @var array
     */
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
        SeederRelatedProductFromFile::class,
        SeederAccessories::class,
        SeederAccessoriesFromFile::class,
    ];

    /**
     * @var array
     */
    protected $arSkipSeeder = [
        'clothes' => [
            SeederRelatedProductFromFile::class,
            SeederAccessoriesFromFile::class,
        ],
        'sneakers' => [
            SeederRelatedProducts::class,
            SeederAccessories::class,
        ],
        'fruits' => [
            SeederBrand::class,
            SeederRelatedProducts::class,
            SeederRelatedProductFromFile::class,
            SeederAccessories::class,
            SeederAccessoriesFromFile::class,
            SeederReviews::class,
        ],
    ];

    /**
     * @var array
     */
    protected static $arContentType = [
        'clothes',
        'sneakers',
        'fruits',
    ];

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $sContentType = $this->option('content');
        if (empty($sContentType)) {
            $sContentType = $this->choice('Select content type', self::$arContentType, 0);
        }

        $iRepeatLimit = $this->option('repeat');
        if (empty($iRepeatLimit)) {
            $iRepeatLimit = (int) $this->ask('How many times to repeat the creating of products? Enter a value from 1 to 1000', 1);
        }

        if (!in_array($sContentType, self::$arContentType)) {
            $sContentType = 'clothes';
        }

        if ($iRepeatLimit < 1) {
            $iRepeatLimit = 1;
        }

        if ($iRepeatLimit > 1000) {
            $iRepeatLimit = 1000;
        }

        foreach ($this->arSeederList as $sClassName) {
            if (in_array($sClassName, $this->arSkipSeeder[$sContentType])) {
                continue;
            }

            /** @var \Lovata\FakeDataShopaholic\Classes\Seeder\AbstractModelSeeder $obSeeder */
            $obSeeder = new $sClassName($sContentType, $iRepeatLimit);
            $obSeeder->run();
        }
    }

    /**
     * Get content type
     *
     * @return array
     */
    public static function getContentType() : array
    {
        $arContentType = self::$arContentType;

        if (!isset($arContentType) || !is_array($arContentType)) {
            return [];
        }

        return $arContentType;
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions() : array
    {
        return [
            [
                'content',
                null,
                InputOption::VALUE_OPTIONAL,
                'Content type',
                null
            ],
            [
                'repeat',
                null,
                InputOption::VALUE_OPTIONAL,
                'Repeat',
                null
            ],
        ];
    }
}
