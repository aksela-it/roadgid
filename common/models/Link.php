<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * @property integer $id
 * @property string $link
 * @property string $short_link
 * @property integer $created_at
 * @property integer $updated_at
 */

class Link extends ActiveRecord
{
    public function rules()
    {
        return [
            [['short_link', 'link'], 'string'],
        ];
    }

    public static function create($link)
    {
        if ($isset_link = self::findByLink($link)) {
            return self::createLink($isset_link->short_link);
        }
        if (self::isLink($link)) {
            $short_link = self::generateShortLink();
            $obj = new self();
            $obj->setAttributes([
                'link' => urlencode($link),
                'short_link' => $short_link,
            ]);
            if ($obj->save()) {
                return self::createLink($short_link);
            }
        }
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function isBot($user_agent)
    {
        $link = 'http://qnits.net/api/checkUserAgent?userAgent=' . urlencode($user_agent);
        $result = Json::decode(file_get_contents($link));
        if (isset($result['isBot'])) {
            return $result['isBot'];
        }
        return true;
    }

    public static function isLink($link)
    {
        return preg_match('%^(?:(?:http|https)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $link);
    }

    public static function generateShortLink()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 6; $i++) {
            $randstring .= $characters[rand(0, strlen($characters))];
        }
        if (self::findByShortLink($randstring)) {
            return self::generateShortLink();
        }
        return $randstring;
    }

    public static function findByShortLink($link)
    {
        return self::find()->where(['short_link' => $link])->one();
    }

    public static function createLink($link)
    {
        return Yii::$app->urlManager->createAbsoluteUrl($link);
    }

    public static function findByLink($link)
    {
        return self::find()->where(['link' => urlencode($link)])->one();
    }

}
