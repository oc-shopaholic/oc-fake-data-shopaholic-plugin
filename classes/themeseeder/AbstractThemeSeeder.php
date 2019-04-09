<?php namespace Lovata\FakeDataShopaholic\Classes\ThemeSeeder;

/**
 * Class AbstractThemeSeeder
 * @package Lovata\FakeDataShopaholic\Classes\ThemeSeeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractThemeSeeder
{
    /**
     * Seed theme data
     * @return void
     */
    abstract public function seed();
}