<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

use Seeder;
use System\Models\File;

/**
 * Class SeederSystemImages
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederSystemImages extends Seeder
{
    protected $arAttachmentType = [
        'Lovata\Shopaholic\Models\Product',
        'Lovata\Shopaholic\Models\Offer',
        'Lovata\Shopaholic\Models\Category',
        'Lovata\Shopaholic\Models\Brand',
    ];

    public function run()
    {
        //Get all files
        $obFileList = File::all();
        if ($obFileList->isEmpty()) {
            return;
        }

        /** @var File $obFile */
        foreach ($obFileList as $obFile) {
            if (!in_array($obFile->attachment_type, $this->arAttachmentType)) {
                continue;
            }

            try {
                $obFile->deleteThumbs();
            } catch (\Exception $obException) {}

            try {
                $obFile->delete();
            } catch (\Exception $obException) {}
        }
    }
}