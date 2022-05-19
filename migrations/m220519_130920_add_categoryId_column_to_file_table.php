<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%file}}`.
 */
class m220519_130920_add_categoryId_column_to_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%file}}', 'categoryId', $this->integer()->notNull());

        // creates index for column `categoryId`
        $this->createIndex(
            'idx-file-categoryId',
            'file',
            'categoryId'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-file-categoryId',
            'file',
            'categoryId',
            'category',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `post`
        $this->dropForeignKey(
            'fk-file-categoryId',
            'file'
        );

        // drops index for column `categoryId`
        $this->dropIndex(
            'idx-file-categoryId',
            'file'
        );

        $this->dropColumn('{{%file}}', 'categoryId');
    }
}
