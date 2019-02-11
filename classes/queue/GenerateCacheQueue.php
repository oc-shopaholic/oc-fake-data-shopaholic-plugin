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

        $sModelClass = array_get($arData, 'model');
        $sItemClass = array_get($arData, 'item');
        $iPage = array_get($arData, 'page');

        $obElementList = $sModelClass::skip(($iPage - 1) * 100)->take(100)->get();
        if ($obElementList->isEmpty()) {
            return;
        }

        foreach ($obElementList as $obElement) {
            $sItemClass::make($obElement->id, $obElement);
        }
    }
}