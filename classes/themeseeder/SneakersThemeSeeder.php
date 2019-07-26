<?php namespace Lovata\FakeDataShopaholic\Classes\ThemeSeeder;

use DB;

/**
 * Class SneakersThemeSeeder
 * @package Lovata\FakeDataShopaholic\Classes\ThemeSeeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SneakersThemeSeeder extends AbstractThemeSeeder
{
    const THEME_CODE = 'lovata-shopaholic-sneakers';

    protected $sThemeData = '{"logo_image":"\/logo.png","logo_title":"","logo_alt":"","description_footer":"<p>Online store of lifestyle sneakers.<\/p>\r\n\r\n<p>Original brands only.<\/p>","privacy_policy_title":"Privacy Policy","privacy_policy_url":"\/privacy-policy","copyright":"2018 Lowbor Ltd.","social_networks_active":"0","social_networks_facebook":"https:\/\/www.facebook.com\/","social_networks_twitter":"https:\/\/twitter.com\/","social_networks_instagram":"https:\/\/www.instagram.com\/","social_networks_telegram":"https:\/\/telegram.org\/","payment_methods_active":"0","payment_methods_title":"","index_subscription_description":"Register now with our newsletter and get the latest updates available","index_slider_active":"0","index_products_active":"0","index_products_title":"Tranding arrivals","index_banners_active":"0","index_blog_active":"0","index_blog_title":"","index_brands_active":"0","index_brands_title":"","index_subscribe_active":"0","index_subscribe_title":"","index_subscribe_description":"","message_delivery":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<\/p>","message_contact_form":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<\/p>","address":"Minsk","address_description":"","phone":"+375 29 193 43 45","phone_description":"","email":"info@lovata.com","email_description":"","map_active":"1","map_popup_title":"Lovata","map_lat":"53.902463","map_lng":"27.561580","map_api_key":"","payment_methods_list":"[{\"title\":\"\",\"image\":\"\\\/paypal.svg\",\"description\":\"\",\"url\":\"https:\\\/\\\/www.paypal.com\"},{\"title\":\"\",\"image\":\"\\\/visa.svg\",\"description\":\"\",\"url\":\"\"}]","index_slider":"[{\"title\":\"\",\"image\":\"\\\/slider-2.jpg\",\"description\":\"\",\"url\":\"\",\"mobile_image\":\"\\\/slider-mob-2.jpg\"},{\"title\":\"\",\"image\":\"\\\/slider.jpg\",\"description\":\"\",\"url\":\"\",\"mobile_image\":\"\\\/slider-mob-2.jpg\"}]","index_banners":"[]","search_by_category":"1","search_by_product":"1","index_product_tabs_title":"Tranding arrivals","index_banner_slider":"[{\"title\":\"\",\"image\":\"\\\/slider.jpg\",\"description\":\"\",\"url\":\"\",\"mobile_image\":\"\\\/slider-mob-2.jpg\"},{\"title\":\"\",\"image\":\"\\\/slider-2.jpg\",\"description\":\"\",\"url\":\"\",\"mobile_image\":\"\\\/slider-mob-2.jpg\"}]","company_name":"Lowbor"}';

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
        $obThemeData = DB::table('cms_theme_data')->where('theme', self::THEME_CODE)->first();
        if (empty($obThemeData)) {
            DB::table('cms_theme_data')->insert([
                'theme' => self::THEME_CODE,
                'data' => $this->sThemeData,
            ]);
        } else {
            DB::table('cms_theme_data')->where('theme', self::THEME_CODE)->update(['data' => $this->sThemeData]);
        }
    }

    /**
     * Copy images from theme folder to media folder
     */
    protected function copyMediaFiles()
    {
        $sFolderPath = base_path('plugins/lovata/fakedatashopaholic/assets/img/'.self::THEME_CODE.'/media');
        if (!file_exists($sFolderPath)) {
            return;
        }

        $arFileList = scandir($sFolderPath);
        foreach ($arFileList as $sFileName) {
            if ($sFileName == '.' || $sFileName == '..') {
                continue;
            }

            copy($sFolderPath.'/'.$sFileName, storage_path('app/media/'.$sFileName));
        }
    }
}