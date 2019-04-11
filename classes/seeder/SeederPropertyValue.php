<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Models\Product;

use Lovata\PropertiesShopaholic\Models\PropertyValue;

/**
 * Class SeederPropertyValue
 * @package Lovata\FakeDataShopaholic\Classes\Seeder
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class SeederPropertyValue extends AbstractModelSeeder
{
    protected $sTableName = 'lovata_properties_shopaholic_values';
    protected $sFilePath = 'property_value_list.csv';

    protected function getModelName()
    {
        return \Lovata\PropertiesShopaholic\Models\PropertyValue::class;
    }

    /**
     * Process row from csv file
     */
    protected function process()
    {
        if (empty($this->arRowData)) {
            return;
        }

        $arLinkData = [];
        $arLinkData['property_id'] = array_shift($this->arRowData);

        $sModel = array_shift($this->arRowData);
        $arLinkData['element_type'] = $sModel == 'offer' ? Offer::class : Product::class;

        $sValue = array_shift($this->arRowData);
        if (empty($sValue)) {
            return;
        }

        $arElementIDList = explode('|', array_shift($this->arRowData));

        try {
            $obValue = \Lovata\PropertiesShopaholic\Models\PropertyValue::create([
                'value' => $sValue,
                'slug'  => PropertyValue::getSlugValue($sValue),
            ]);

            $obProperty = $obValue->property->find(array_get($arLinkData, 'property_id'));
            if (empty($obProperty)) {
                $obValue->property()->attach(array_get($arLinkData, 'property_id'));
            }
        } catch (\October\Rain\Database\ModelException $obException) {
            echo $obException->getMessage();
            return;
        }

        $arLinkData['value_id'] = $obValue->id;

        $iLimitCount = $sModel == 'offer' ? Offer::count() / $this->iRepeatLimit : Product::count() / $this->iRepeatLimit;

        for ($i = 0; $i < $this->iRepeatLimit; $i++) {
            foreach ($arElementIDList as $iElementID) {
                if ($arLinkData['element_type'] == Product::class) {
                    $arLinkData['product_id'] = $iElementID + $i * $iLimitCount;
                } else {
                    $obOffer = Offer::find($iElementID + $i * $iLimitCount);
                    if (empty($obOffer)) {
                        continue;
                    }

                    $arLinkData['product_id'] = $obOffer->product_id;

                }

                $arLinkData['element_id'] = $iElementID + $i * $iLimitCount;

                try {
                    $obValue = \Lovata\PropertiesShopaholic\Models\PropertyValueLink::create($arLinkData);
                } catch (\October\Rain\Database\ModelException $obException) {
                    echo $obException->getMessage();
                }
            }
        }
    }

    /**
     * Clear table
     */
    protected function clear()
    {
        parent::clear();
        \DB::table('lovata_properties_shopaholic_value_link')->truncate();
    }
}