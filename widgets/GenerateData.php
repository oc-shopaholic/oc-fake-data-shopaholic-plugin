<?php namespace Lovata\FakeDataShopaholic\Widgets;

use Artisan;
use Input;
use Lovata\FakeDataShopaholic\Classes\Console\GenerateFakeDataCommand;
use Backend\Classes\ReportWidgetBase;
use Lovata\FakeDataShopaholic\Classes\Console\GenerateFakeThemeDataCommand;
use Validator;
use Flash;

/**
 * Class GenerateData
 *
 * @package Lovata\FakeDataShopaholic\Widgets
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class GenerateData extends ReportWidgetBase
{
    /**
     * Render method
     * @return mixed|string
     * @throws \SystemException
     */
    public function render()
    {
        $this->initData();

        return $this->makePartial('widget');
    }

    /**
     * Generate fake data
     */
    public function onGenerateFakeData()
    {
        $this->generateFaceData();
    }

    /**
     * Generate fake data for theme
     */
    public function onGenerateFakeDataForTheme()
    {
        $this->generateFaceDataForTheme();
    }

    /**
     * Init data
     */
    protected function initData()
    {
        $this->vars['arContentType'] = $this->getContentType();
        $this->vars['arThemes']      = $this->getThemes();
    }

    /**
     * Get content type
     *
     * @return array
     */
    protected function getContentType() : array
    {
        $arResult      = [];
        $arContentType = GenerateFakeDataCommand::getContentType();

        foreach ($arContentType as $sValue) {
            $arResult[$sValue] = trans('lovata.fakedatashopaholic::lang.field.'.$sValue);
        }

        return $arResult;
    }

    /**
     * Get themes
     *
     * @return array
     */
    protected function getThemes() : array
    {
        $arResult = [];
        $arThemes = GenerateFakeThemeDataCommand::getThemes();

        foreach ($arThemes as $sValue) {
            $arResult[$sValue] = trans('lovata.fakedatashopaholic::lang.theme.'.$sValue);
        }

        return $arResult;
    }

    /**
     * Generate face data
     */
    protected function generateFaceData()
    {
        $arContentType = GenerateFakeDataCommand::getContentType();
        $sContentType  = implode(',', $arContentType);

        $arData             = Input::all();
        $arRules            = [
            'content' => 'required|in:'.$sContentType,
            'repeat'  => 'required|integer|min:1|max:1000',
        ];
        $arCustomAttributes = [
            'content' => trans('lovata.fakedatashopaholic::lang.field.content'),
            'repeat'  => trans('lovata.fakedatashopaholic::lang.field.repeat'),
        ];

        $bValidator = $this->validator($arData, $arRules, [], $arCustomAttributes);

        if (!$bValidator) {
            return;
        }

        $arParameters = [
            '--content' => array_get($arData, 'content'),
            '--repeat'  => array_get($arData, 'repeat'),
        ];

        Artisan::call('shopaholic:generate.fake.data', $arParameters);
    }

    /**
     * Generate face data for theme
     */
    protected function generateFaceDataForTheme()
    {
        $arThemes = GenerateFakeThemeDataCommand::getThemes();
        $sTheme   = implode(',', $arThemes);

        $arData             = Input::all();
        $arRules            = ['theme' => 'required|in:'.$sTheme];
        $arCustomAttributes = ['theme' => trans('lovata.fakedatashopaholic::lang.field.theme')];

        $bValidator = $this->validator($arData, $arRules, [], $arCustomAttributes);

        if (!$bValidator) {
            return;
        }

        $arParameters = ['--theme' => array_get($arData, 'theme')];

        Artisan::call('shopaholic:generate.theme.fake.data', $arParameters);
    }

    /**
     * Validator
     *
     * @param array $arData
     * @param array $arRules
     * @param array $arMessages
     * @param array $arCustomAttributes
     *
     * @return boolean
     */
    protected function validator(array $arData, array $arRules, array $arMessages, array $arCustomAttributes) : bool
    {
        if (empty($arData) || empty($arRules)) {
            return false;
        }

        $obValidator = Validator::make($arData, $arRules, $arMessages, $arCustomAttributes);

        $obValidator->messages()->getMessages();

        if ($obValidator->fails()) {
            $this->validatorFlashMessage($obValidator);
        }

        return !$obValidator->fails();
    }

    /**
     * Validator flash message
     *
     * @param \Illuminate\Validation\Validator $obValidator
     */
    protected function validatorFlashMessage($obValidator)
    {
        if (empty($obValidator) || !$obValidator instanceof \Illuminate\Validation\Validator) {
            return;
        }

        $arMessages = [];

        if (!empty($obValidator->messages())) {
            $arMessages = $obValidator->messages()->getMessages();
        }

        if (empty($arMessages) || !is_array($arMessages)) {
            return;
        }

        $arMessage = array_first($arMessages);

        if (empty($arMessage) || !is_array($arMessage)) {
            return;
        }

        $sMessage = array_first($arMessage);

        if (!empty($sMessage)) {
            Flash::error($sMessage);
        }
    }
}
