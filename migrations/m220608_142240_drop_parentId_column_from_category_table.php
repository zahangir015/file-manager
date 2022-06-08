<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%category}}`.
 */
class m220608_142240_drop_parentId_column_from_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%category}}', 'parentId');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%category}}', 'parentId', $this->integer());
    }
}
