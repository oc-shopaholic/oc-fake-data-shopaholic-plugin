<?php namespace Lovata\FakeDataShopaholic\Classes\Console;

use Illuminate\Console\Command;

use Lovata\FakeDataShopaholic\Classes\ThemeSeeder\SneakersThemeSeeder;

/**
 * Class GenerateFakeThemeDataCommand
 * @package Lovata\FakeDataShopaholic\Classes\Console
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class GenerateFakeThemeDataCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'shopaholic:generate.theme.fake.data';

    /**
     * @var string The console command description.
     */
    protected $description = 'Generate fake data for theme';

    protected $arThemeList = [
        'sneakers' => SneakersThemeSeeder::class,
    ];

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $arThemeList = array_keys($this->arThemeList);
        $sThemeName = $this->choice('Select theme', $arThemeList, 0);
        if (!in_array($sThemeName, $arThemeList)) {
            $sThemeName = 'sneakers';
        }

        $sClassName = $this->arThemeList[$sThemeName];

        /** @var \Lovata\FakeDataShopaholic\Classes\ThemeSeeder\AbstractThemeSeeder $obSeeder */
        $obSeeder = new $sClassName();
        $obSeeder->seed();
    }
}
