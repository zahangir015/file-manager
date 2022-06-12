<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%category_permission}}".
 *
 * @property int $id
 * @property int $userId
 * @property int $refId
 * @property string $refModel
 * @property int $createdBy
 * @property int|null $updatedBy
 * @property string $createdAt
 * @property string|null $updatedAt
 */
class CategoryPermission extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category_permission}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId', 'refId', 'refModel', 'createdBy', 'createdAt'], 'required'],
            [['userId', 'createdBy', 'updatedBy'], 'integer'],
            [['createdAt', 'updatedAt', 'refId'], 'safe'],
            [['refModel'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User'),
            'refId' => Yii::t('app', 'Folder'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(\mdm\admin\models\User::className(), ['id' => 'userId']);
    }

    public function getCreator(): \yii\db\ActiveQuery
    {
        return $this->hasOne(\mdm\admin\models\User::className(), ['id' => 'createdBy']);
    }

}
