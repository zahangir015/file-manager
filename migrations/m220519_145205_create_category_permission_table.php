<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_permission}}`.
 */
class m220519_145205_create_category_permission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_permission}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string()->notNull(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category_permission}}');
    }
}
