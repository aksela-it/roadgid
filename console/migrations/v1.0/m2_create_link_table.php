<?php

use backend\models\Migration;
use common\models\Link;

class m2_create_link_table extends \yii\db\Migration {

    public function safeUp()
    {
        $this->createTable(Link::tableName(), [
            'id' => $this->primaryKey(),
            'link' => $this->string()->notNull(),
            'short_link' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(Link::tableName());
    }
}
