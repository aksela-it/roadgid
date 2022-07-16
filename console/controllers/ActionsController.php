<?php

namespace console\controllers;

use common\models\Link;
use common\models\Redirect;
use ErrorException;
use yii\console\Controller;
use yii\helpers\Json;

class ActionsController extends Controller
{

    public function actionCreateRedirect($params)
    {
        if ($params) {
            $params = Json::decode($params);
            $link = $user_agent = $ip = null;
            if (!empty($params['link_id'])) {
                $link = Link::findOne($params['link_id']);
            }
            if (!empty($params['ip'])) {
                $ip = $params['ip'];
            }
            if (!empty($params['user_agent'])) {
                $user_agent = $params['user_agent'];
            }
            if ($link && $user_agent && $ip) {
                if (!Link::isBot($user_agent)) {
                    $redirect = new Redirect();
                    $redirect->setAttributes([
                        'link_id' => $link->id,
                        'ip' => $ip,
                    ]);
                    $redirect->save();
                }
            }
        }
    }
}