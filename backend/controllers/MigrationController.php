<?php
namespace backend\controllers;

use backend\models\Migration;
use Yii;
use yii\helpers\FileHelper;
use yii\web\Controller;

class MigrationController extends Controller
{

    public function actionIndex()
    {
        $migrations = [];
        $files = FileHelper::findFiles(Migration::getMigrationPath());
        Migration::checkMigrationTable();
        $apply_migrations = Migration::find()->all();
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) != 'php') {
                continue;
            }
            $migration = str_replace('.php', '', basename($file));
            $apply = $retry = false;
            foreach ($apply_migrations as $apply_migration) {
                if ($apply_migration->migration == $migration) {
                    if (filemtime($file) > $apply_migration->apply_time) {
                        $retry = true;
                    }
                    $apply = true;
                }
            }
            $migrations[Migration::getFileDir($file)][] = [
                'name' => $migration,
                'apply' => $apply,
                'retry' => $retry,
            ];
        }
        foreach ($migrations as $path => $migration) {
            usort($migration, function ($a, $b) {
                return strcmp($a["name"], $b["name"]);
            });
            $migrations[$path] = $migration;
        }

        return $this->render('index', ['migrations' => $migrations]);
    }

    public function actionApply($version, $migration)
    {
        if ($version && $migration)
            Migration::migrateUp($version, $migration);

        return $this->redirect(['index']);
    }

    public function actionRollback($version, $migration)
    {
        if ($version && $migration)
            Migration::migrateDown($version, $migration);

        return $this->redirect(['index']);
    }

    public function actionRetry($version, $migration)
    {
        if ($version && $migration)
            Migration::migrateRetry($version, $migration);

        $this->redirect(['index']);
    }

    public function actionCancel($version, $migration)
    {
        if ($version && $migration)
            Migration::migrateCancel($version, $migration);

        $this->redirect(['index']);
    }


}