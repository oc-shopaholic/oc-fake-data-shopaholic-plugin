<?php namespace Lovata\FakeDataShopaholic\Classes\Queue;

/**
 * Class GenerateCacheQueue
 * @package Lovata\FakeDataShopaholic\Classes\Queue
 */
class GenerateCacheQueue
{
    public function fire($obJob, $arData)
    {
        $obJob->delete();

        $sItemClass = $arData['item'];
        $sItemClass::make($arData['id']);
    }
}