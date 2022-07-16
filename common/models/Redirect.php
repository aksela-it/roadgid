<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * @property integer $id
 * @property integer $link_id
 * @property integer $created_at
 * @property string $ip
 */

class Redirect extends ActiveRecord
{
    public function rules()
    {
        return [
            ['link_id', 'integer'],
            ['ip', 'ip']
        ];
    }

    public function beforeValidate()
    {
        $this->created_at = time();
        return parent::beforeValidate();
    }

    public static function create(Link $link)
    {
        $params = Json::encode([
            'link_id' => $link->id,
            'ip' => Yii::$app->request->getUserIP(),
            'user_agent' => Yii::$app->request->userAgent,
        ]);
        Yii::$app->consoleRunner->run("actions/create-redirect '{$params}'");
    }

}