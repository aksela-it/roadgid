<?php

use common\models\Redirect;

class m3_create_redirect_table extends \yii\db\Migration {

    public function safeUp()
    {
        $this->createTable(Redirect::tableName(), [
            'id' => $this->primaryKey(),
            'link_id' => $this->string()->notNull(),
            'ip' => $this->string()->notNull(),
            'created_at' => $this->string()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(Redirect::tableName());
    }
}
