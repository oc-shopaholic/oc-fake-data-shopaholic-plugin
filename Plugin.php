<?php namespace Lovata\FakeDataShopaholic;

use System\Classes\PluginBase;

// Widgets
use Lovata\FakeDataShopaholic\Widgets\GenerateData;

/**
 * Class Plugin
 * @package Lovata\FakeDataShopaholic
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{
    /**
     * Register plugin method
     */
    public function register()
    {
        $this->registerConsoleCommand('shopaholic:item.generate.cache', 'Lovata\FakeDataShopaholic\Classes\Console\GenerateCacheCommand');
        $this->registerConsoleCommand('shopaholic:generate.fake.data', 'Lovata\FakeDataShopaholic\Classes\Console\GenerateFakeDataCommand');
        $this->registerConsoleCommand('shopaholic:generate.theme.fake.data', 'Lovata\FakeDataShopaholic\Classes\Console\GenerateFakeThemeDataCommand');
    }

    /**
     * Boot plugin method
     */
    public function boot()
    {
        $this->addEventListener();
    }

    /**
     * Add event listeners
     */
    protected function addEventListener()
    {
    }

    /**
     * @return array
     */
    public function registerReportWidgets()
    {
        return [
            GenerateData::class => [
                'label' => 'lovata.fakedatashopaholic::lang.widget.fake_data',
            ],
        ];
    }
}
