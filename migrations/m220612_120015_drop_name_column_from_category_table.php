<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%category}}`.
 */
class m220612_120015_drop_name_column_from_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%category}}', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%category}}', 'name', $this->string());
    }
}
