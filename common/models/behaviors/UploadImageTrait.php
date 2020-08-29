<?php


namespace common\models\behaviors;


use common\models\helpers\PhotoResizer;
use common\models\helpers\TimeHelper;
use common\models\landings\UserFiles;
use Exception;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

trait UploadImageTrait
{

    public function createDirectoryIfNotExists($path)
    {

        if (!file_exists($path)) {
            try {
                FileHelper::createDirectory($path);

            } catch (Exception $e) {
                return false;
            }
        }
        return true;

    }


    public function uploadImage()
    {

        /*if ($this->{static::$imagesUploadAttribute} = UploadedFile::getInstancesByName(static::$imagesUploadAttribute)) {
            foreach ($this->{static::$imagesUploadAttribute} as $imageInstance) {
                $file = new UserFiles();
                $file->path = $this->loadFile($imageInstance);
                $file->save();
            }
        };*/


        if ($this->{static::$imageUploadAttribute} = UploadedFile::getInstanceByName(static::$imageUploadAttribute)) {
            $this->{static::$imagePathAttribute} = $this->loadFile($this->{static::$imageUploadAttribute});
            return true;
        } else {
            return false;
        }
    }

    public function loadFile($fileInstance)
    {
        $extension = $fileInstance->extension;
        $name = \Yii::$app->security->generateRandomString(6);

        $path = $this->getFilePath();
        if ($this->createDirectoryIfNotExists($path)) {
            $this->{static::$imageUploadAttribute}->saveAs($path . $name . '.' . $extension, false);
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                PhotoResizer::resize($path . $name . '.' . $extension, static::imageSizes()['width'], static::imageSizes()['height']);
            }
            return $this->getWebPath() . $name . '.' . $fileInstance->extension;
        }
    }


    public function getWebPath()
    {
        return "/images/" . TimeHelper::DateTimeDirectory();
    }

    public function baseFilePath()
    {
        return Yii::getAlias('@frontend') . "/web";
    }

    public function getFilePath()
    {
        return $this->baseFilePath() . $this->getWebPath();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        if ($this->{static::$imagePathAttribute}
            && file_exists($path = $this->baseFilePath() . $this->{static::$imagePathAttribute})
            && !is_dir($path)) {
            unlink($path);
        }


    }


}