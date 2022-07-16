<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $links = Yii::$app->db->createCommand('
            WITH c AS (
                SELECT l.link, count(l.link) as count, from_unixtime(r.created_at, "%m-%Y") as month
                FROM link l 
                LEFT JOIN redirect r ON l.id = r.link_id 
                GROUP BY l.link, month, r.link_id
            ) SELECT row_number() over(partition by month order by count DESC) position, link, count, month FROM c ORDER BY month DESC, count DESC; 
        ')->queryAll();
        return $this->render('index', ['links' => $links]);
    }
}
