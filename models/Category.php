<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property int $parentId
 * @property string $name
 * @property int $status
 * @property string $createdBy
 * @property int|null $updatedBy
 * @property int $createdAt
 * @property string|null $updatedAt
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'status', 'createdBy', 'createdAt'], 'required'],
            [['parentId', 'status', 'updatedBy', 'createdBy'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parentId' => Yii::t('app', 'Parent Folder'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getCreator(): \yii\db\ActiveQuery
    {
        return $this->hasOne(\mdm\admin\models\User::className(), ['id' => 'createdBy']);
    }
}
