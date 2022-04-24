<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m220424_110708_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'parentId' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'status' => $this->boolean()->notNull(),
            'createdBy' => $this->dateTime()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->dateTime()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
