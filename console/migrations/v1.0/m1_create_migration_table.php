<?php

use backend\models\Migration;

class m1_create_migration_table extends \yii\db\Migration {

    public function safeUp()
    {
        $this->createTable(Migration::tableName(), [
            'id' => $this->primaryKey(),
            'migration' => $this->string()->notNull(),
            'version' => $this->string()->notNull(),
            'apply_time' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(Migration::tableName());
    }
}
