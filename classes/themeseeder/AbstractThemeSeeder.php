<?php namespace Lovata\FakeDataShopaholic\Classes\ThemeSeeder;

use DB;

/**
 * Class AbstractThemeSeeder
 *
 * @package Lovata\FakeDataShopaholic\Classes\ThemeSeeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractThemeSeeder
{
    /**
     * Seed theme data
     */
    public function seed()
    {
        $this->seedThemeData();
        $this->copyMediaFiles();
    }

    /**
     * Seed theme data in ThemeExport model
     */
    protected function seedThemeData()
    {
        $obThemeData = DB::table('cms_theme_data')
            ->where('theme', static::THEME_CODE)
            ->first();
        if (empty($obThemeData)) {
            DB::table('cms_theme_data')->insert([
                'theme' => static::THEME_CODE,
                'data'  => $this->sThemeData,
            ]);
        } else {
            DB::table('cms_theme_data')
                ->where('theme', static::THEME_CODE)
                ->update(['data' => $this->sThemeData]);
        }
    }

    /**
     * Copy images from theme folder to media folder
     */
    protected function copyMediaFiles()
    {
        $sSrc  = base_path('plugins/lovata/fakedatashopaholic/assets/img/'.static::THEME_CODE.'/media');
        $sDest = storage_path('app/media');

        if (!file_exists($sDest)) {
            try {
                mkdir($sDest, 0777, true);
            } catch (\Exception $obException) {}
        }

        $this->copy($sSrc, $sDest);
    }

    /**
     * Copy.
     * @param string $sSrc
     * @param string $sDest
     */
    protected function copy($sSrc, $sDest) {
        if (!file_exists($sSrc)) {
            return;
        }

        $arFileNameList = scandir($sSrc);

        foreach ($arFileNameList as $sFileName) {
            $sSrcFilePath  = $sSrc.'/'.$sFileName;
            $sDestFilePath = $sDest.'/'.$sFileName;
            if ($sFileName == '.' || $sFileName == '..' || !is_readable($sSrcFilePath) || !file_exists($sSrcFilePath)) {
                continue;
            }

            if (is_dir($sSrcFilePath)) {
                try {
                    mkdir($sDestFilePath, 0777, true);
                } catch (\Exception $obException) {
                    continue;
                }

                $this->copy($sSrcFilePath, $sDestFilePath);
            } else {
                try {
                    copy($sSrcFilePath, $sDestFilePath);
                } catch (\Exception $obException) {
                    continue;
                }
            }
        }
    }
}
