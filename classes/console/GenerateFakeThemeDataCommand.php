<?php namespace Lovata\FakeDataShopaholic\Classes\Console;

use Illuminate\Console\Command;

use Lovata\FakeDataShopaholic\Classes\ThemeSeeder\SneakersThemeSeeder;
use Symfony\Component\Console\Input\InputOption;

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

    /**
     * @var array
     */
    protected static $arThemeList = [
        'sneakers' => SneakersThemeSeeder::class,
    ];

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $arThemeList = array_keys(self::$arThemeList);

        $sThemeName = $this->option('theme');

        if (empty($sThemeName)) {
            $sThemeName = $this->choice('Select theme', $arThemeList, 0);
        }

        if (!in_array($sThemeName, $arThemeList)) {
            $sThemeName = 'sneakers';
        }

        $sClassName = self::$arThemeList[$sThemeName];

        /** @var \Lovata\FakeDataShopaholic\Classes\ThemeSeeder\AbstractThemeSeeder $obSeeder */
        $obSeeder = new $sClassName();
        $obSeeder->seed();
    }

    /**
     * Get themes
     *
     * @return array
     */
    public static function getThemes() : array
    {
        $arThemes = self::$arThemeList;

        if (!isset($arThemes) || !is_array($arThemes)) {
            return [];
        }

        $arThemes = array_keys($arThemes);

        return $arThemes;
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions() : array
    {
        return [
            [
                'theme',
                null,
                InputOption::VALUE_OPTIONAL,
                'Theme',
                null
            ],
        ];
    }
}
