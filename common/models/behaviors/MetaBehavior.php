<?php
/**
 * Created by PhpStorm.
 * User: anvik
 * Date: 08.08.2019
 * Time: 6:53
 */

namespace common\models\behaviors;

use yii\db\ActiveRecord;

/**
 * @property mixed description
 * @property mixed title
 * @property mixed keywords
 */
class MetaBehavior extends \yii\base\Behavior
{
    public $nameAttribute = 'name';
    public $metaField = 'meta';
    public $useKeywords = 'false';
    public $slugField = 'slug';
    public $generateSlug = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'generate',
            ActiveRecord::EVENT_AFTER_FIND => 'jsonDecodeMeta',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'generate'
        ];
    }

    public function generate()
    {
        if (!is_array($this->owner->{$this->metaField})) $this->owner->{$this->metaField} = [];
       if ($this->generateSlug) $this->generateSlug();
        $this->generateDescription();
        $this->generateTitle();
        $this->generateKeywords();
        $this->jsonEncodeMeta();
    }

    public function jsonDecodeMeta()
    {
        $this->owner->{$this->metaField} = json_decode($this->owner->{$this->metaField}, true);
    }

    public function jsonEncodeMeta()
    {
        $this->owner->{$this->metaField} = json_encode($this->owner->{$this->metaField});
    }

    public
    static function transliterate($str)
    {
        $tr = array(
            "срм" => 'crm',
            "А" => "a", "Б" => "b", "В" => "v", "Г" => "g", "Д" => "d",
            "Е" => "e", "Ё" => "yo", "Ж" => "zh", "З" => "z", "И" => "i",
            "Й" => "j", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
            "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
            "У" => "u", "Ф" => "f", "Х" => "kh", "Ц" => "ts", "Ч" => "ch",
            "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "y", "Ь" => "",
            "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo",
            "ж" => "zh", "з" => "z", "и" => "i", "й" => "j", "к" => "k",
            "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p",
            "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f",
            "х" => "kh", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch",
            "ъ" => "", "ы" => "y", "ь" => "", "э" => "e", "ю" => "yu",
            "я" => "ya", " " => "-", "." => "", "," => "", "/" => "-",
            ":" => "", ";" => "", "—" => "", "–" => "-"
        );
        return strtr(mb_strtolower($str), $tr);
    }

    public
    static function transliterateBack($str)
    {

        $tr = array_flip(array(
            "срм" => 'crm',
            "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo",
            "ж" => "zh", "з" => "z", "и" => "i", "й" => "j", "к" => "k",
            "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p",
            "р" => "r", "с" => "c", "т" => "t", "у" => "u", "ф" => "f",
            "х" => "kh", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch",
            "ы" => "y", "э" => "e", "ю" => "yu",
            "я" => "ya",
        ));
        // \Yii::error($tr);
        return strtr(mb_strtolower($str), $tr);
    }


    public
    function generateDescription()
    {

        if (is_array($this->owner->{$this->metaField})) {
            if (!trim($this->owner->{$this->metaField}['description'] ?? '')) $this->metaDescription = $this->owner->{$this->nameAttribute};
        }
    }


    public
    function generateSlug()
    {
        if (!trim($this->owner->{$this->slugField})) {

            $this->owner->{$this->slugField} = self::transliterate($this->owner->{$this->nameAttribute});
        }

    }

    public
    function generateTitle()
    {
        if (is_array($this->owner->{$this->metaField})) {
            if (!trim($this->owner->{$this->metaField}['title'] ?? '')) $this->metaTitle = $this->owner->{$this->nameAttribute};
        }
    }

    public
    function generateKeywords()
    {
        if (is_array($this->owner->{$this->metaField})) {
            if (!trim($this->owner->{$this->metaField}['keywords'] ?? '')) $this->metaKeywords = $this->owner->{$this->nameAttribute};
        }
    }

    public
    function getMetaDescription()
    {

        return $this->owner->{$this->metaField}['description'] ?? '';
    }

    public
    function getMetaTitle()
    {
        return $this->owner->{$this->metaField}['title'] ?? '';
    }

    public
    function getMetaKeywords()
    {
        return $this->owner->{$this->metaField}['keywords'] ?? '';
    }


    public
    function setMetaDescription($string)
    {
        $attribute = $this->owner->{$this->metaField};
        $attribute['description'] = $string;
        $this->owner->{$this->metaField} = $attribute;
    }

    public
    function setMetaTitle($string)
    {
        $attribute = $this->owner->{$this->metaField};
        $attribute['title'] = $string;
        $this->owner->{$this->metaField} = $attribute;
    }

    public
    function setMetaKeywords($string)
    {
        $attribute = $this->owner->{$this->metaField};
        $attribute['keywords'] = $string;
        $this->owner->{$this->metaField} = $attribute;
    }


    public
    function injectMeta($prefix = '')
    {

        if ($this->description) {
            \Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $prefix . " " . $this->description
            ]);
        }

        if ($this->title) \Yii::$app->view->title = $this->title;

        if ($this->keywords) {
            \Yii::$app->view->registerMetaTag([
                'name' => 'keywords',
                'content' => $this->keywords
            ]);
        }


    }


}