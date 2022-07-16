<?php


namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * Class Migration
 * @package backend\models
 * @property string $migration
 * @property string $version
 * @property integer $apply_time
 */


class Migration extends ActiveRecord
{
    private static $default_class = 'm1_create_migration_table';
    private static $default_version = 'v1.0';

    public function attributeLabels()
    {
        return [
            'name' => 'Миграция',
        ];
    }

    public function rules()
    {
        return [
            [['migration, version'], 'required'],
        ];
    }

    public static function getMigrationPath()
    {
        return Yii::getAlias('@migrations');
    }

    public static function getFileDir($file)
    {
        if (self::getMigrationPath() == dirname($file) . '/') {
            return '/';
        }
        return str_replace(self::getMigrationPath() . '/', '', dirname($file));
    }

    public static function getMigrationFile($class = '')
    {
        $file = '';
        if ($class) {
            $files = FileHelper::findFiles(self::getMigrationPath(), ['only' => [$class . '.php']]);
            if ($files) $file = $files[0];
        }
        return $file;
    }

    private static function createMigration($class)
    {
        $file = self::getMigrationFile($class);
        require_once($file);
        return new $class();
    }

    private static function addMigrationHistory($version, $class)
    {
        $command = Yii::$app->db->createCommand();
        $command->insert(self::tableName(), [
            'version' => $version,
            'migration' => $class,
            'apply_time' => time(),
        ])->execute();
    }

    private static function deleteMigrationHistory($version, $class)
    {
        self::findOne(['version' => $version, 'migration' => $class])->delete();
    }

    private static function updateMigrationHistory($version, $class)
    {
        $command = Yii::$app->db->createCommand();
        $command->update(self::tableName(), [
            'apply_time' => time(),
        ], 'version = :version AND migration = :migration', [
            ':version' => $version,
            ':migration' => $class
        ])->execute();
    }

    public static function migrateUp($version, $class)
    {
        $migration = self::createMigration($class);
        $migration->safeUp();
        self::addMigrationHistory($version, $class);
    }

    public static function migrateDown($version, $class)
    {
        $migration = self::createMigration($class);
        $migration->down();
        if ($class != self::$default_class)
            self::deleteMigrationHistory($version, $class);
    }

    public static function migrateRetry($version, $class)
    {
        $migration = self::createMigration($class);
        $migration->down();
        $migration->up();
        self::deleteMigrationHistory($version, $class);
        self::addMigrationHistory($version, $class);
    }

    public static function migrateCancel($version, $class)
    {
        self::updateMigrationHistory($version, $class);
    }

    public static function checkMigrationTable()
    {
        if (!Yii::$app->db->getTableSchema(self::tableName())) {
            self::migrateUp(self::$default_version, self::$default_class);
        }
    }

}
