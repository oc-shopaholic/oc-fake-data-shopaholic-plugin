<?php namespace Lovata\FakeDataShopaholic\Classes\Seeder;

use DB;
use Schema;
use System\Models\File;

/**
 * Class AbstractModelSeeder
 * @package Lovata\FakeDataShopaholic\Classes
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractModelSeeder
{
    const SMALL_TEXT = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
    const BIG_TEXT = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

    protected $sContentType;
    protected $iRepeatLimit;
    protected $bSkipFirstColumn = true;

    protected $sTableName;
    protected $sFilePath;
    protected $obFile;

    /** @var \Model */
    protected $obModel;

    protected $arFieldList = [];
    protected $arModelData = [];
    protected $arRowData = [];

    protected $sImagePath = 'lovata/fakedatashopaholic/assets/img/';

    /**
     * AbstractModelSeeder constructor.
     * @param string $sContentType
     * @param int $iRepeatLimit
     */
    public function __construct($sContentType, $iRepeatLimit)
    {
        $this->sContentType = $sContentType;
        $this->iRepeatLimit = $iRepeatLimit;

        $this->sImagePath .= $sContentType.'/';
        $this->sFilePath = 'lovata/fakedatashopaholic/csv/'.$sContentType.'/'.$this->sFilePath;
    }

    /**
     * Run command
     */
    public function run()
    {
        if(!Schema::hasTable($this->sTableName)) {
            return;
        }

        $this->sImagePath = plugins_path($this->sImagePath);

        $this->clear();

        $this->openFile();
        $this->seed();
        $this->closeFile();
    }

    /**
     * Get model name
     * @return string
     */
    abstract protected function getModelName();

    /**
     * Process row from csv file
     */
    protected function process()
    {
        if(empty($this->arRowData) || empty($this->arFieldList)) {
            return;
        }

        //Clear model data array
        $this->arModelData = [];

        foreach ($this->arFieldList as $sFieldName) {

            switch ($sFieldName) {
                case 'active':
                    $this->arModelData['active'] = true;
                    break;
                default:
                    $this->arModelData[$sFieldName] = array_shift($this->arRowData);
            }
        }


        $sModelName = $this->getModelName();
        try {
            $this->obModel = $sModelName::create($this->arModelData);
        } catch (\October\Rain\Database\ModelException $obException) {
            echo $obException->getMessage();
        }
    }

    /**
     * Fill preview text field
     * @param string $sModelName
     * @param string $sFieldName
     */
    protected function fillPreviewText($sModelName, $sFieldName = 'name')
    {
        $this->obModel->preview_text = 'Preview text. '.$sModelName.': '.$this->obModel->$sFieldName.' '.self::SMALL_TEXT;
    }

    /**
     * Fill description field
     * @param string $sModelName
     * @param string $sFieldName
     */
    protected function fillDescription($sModelName, $sFieldName = 'name')
    {
        $this->obModel->description = '<p>Description text. '.$sModelName.': <strong>'.$this->obModel->$sFieldName.'</strong></p><p>'.self::BIG_TEXT.'</p><p>'.self::BIG_TEXT.'</p>';
    }

    /**
     * Create new preview image
     * @param string $sFolderName
     */
    protected function createPreviewImage($sFolderName)
    {
        if(empty($sFolderName) || empty($this->obModel)) {
            return;
        }

        $sFileName = array_shift($this->arRowData);
        if (empty($sFileName)) {
            return;
        }

        $obImage = new File();
        $obImage->fromFile($this->sImagePath.$sFolderName.'/'.$sFileName);
        $this->obModel->preview_image()->add($obImage);
        $this->obModel->save();
    }

    /**
     * Create images
     * @param string $sFolderName
     */
    protected function createModelImages($sFolderName)
    {
        if(empty($sFolderName) || empty($this->obModel)) {
            return;
        }

        $arImageList = explode('|', array_shift($this->arRowData));
        if(empty($arImageList)) {
            return;
        }

        $bFirst = true;
        foreach ($arImageList as $sFileName) {

            $obImage = new File();
            $obImage->fromFile($this->sImagePath.$sFolderName.'/'.$sFileName);
            $this->obModel->images()->add($obImage);

            if($bFirst) {
                $obImage = new File();
                $obImage->fromFile($this->sImagePath.$sFolderName.'/'.$sFileName);
                $this->obModel->preview_image()->add($obImage);
                $bFirst= false;
            }
        }

        $this->obModel->save();
    }

    /**
     * Seed table
     */
    protected function seed()
    {
        if(empty($this->obFile) || !is_resource($this->obFile)) {
            return;
        }

        //Skip first line
        fgetcsv($this->obFile);

        //Process rows of csv file
        while (($arRow = fgetcsv($this->obFile)) !== false) {

            //Always skip first column
            if ($this->bSkipFirstColumn) {
                array_shift($arRow);
            }
            if(empty($arRow)) {
                continue;
            }

            $this->arRowData = $arRow;
            $this->process();
        }
    }

    /**
     * Open csv file for reading
     */
    protected function openFile()
    {
        if(empty($this->sFilePath)) {
            return;
        }

        $sFilePath = plugins_path($this->sFilePath);
        if(!file_exists($sFilePath)) {
            return;
        }

        $this->obFile = fopen($sFilePath, 'r');
    }

    /**
     * Close opened file
     */
    protected function closeFile()
    {
        if(empty($this->obFile) || !is_resource($this->obFile)) {
            return;
        }

        fclose($this->obFile);
    }

    /**
     * Clear table
     */
    protected function clear()
    {
        DB::table($this->sTableName)->truncate();
    }
}