<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property int $id
 * @property string $title
 * @property string $path
 * @property int $categoryId
 * @property int $status
 * @property string $createdBy
 * @property int|null $updatedBy
 * @property string $createdAt
 * @property string|null $updatedAt
 */
class File extends \yii\db\ActiveRecord
{
    public $files;
    /**
     * {@inheritdoc}
     */
    public $pdfFile;

    public static function tableName(): string
    {
        return '{{%file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['categoryId', 'title', 'path', 'status', 'createdBy', 'createdAt'], 'required'],
            [['status', 'updatedBy', 'createdBy', 'categoryId'], 'integer'],
            [['createdAt', 'updatedAt', 'pdfFile'], 'safe'],
            [['title'], 'unique'],
            [['title', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'categoryId' => Yii::t('app', 'Folder'),
            'path' => Yii::t('app', 'Path'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::className(), ['id' => 'categoryId']);
    }

    public function getCreator(): \yii\db\ActiveQuery
    {
        return $this->hasOne(\mdm\admin\models\User::className(), ['id' => 'createdBy']);
    }
}
