<?php

use yii\db\Schema;
use yii\db\Migration;

class m000000_0000_initDbConfigTable extends Migration
{
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        } else {
            $tableOptions = null;
        }
        $this->createTable('{{%config}}', [
            'name' => Schema::TYPE_STRING . "(64) NOT NULL COMMENT 'Config Name'",
            'value' => Schema::TYPE_TEXT . " NOT NULL COMMENT 'Config Values'",
            'PRIMARY KEY (name)'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%config}}');
    }
}
