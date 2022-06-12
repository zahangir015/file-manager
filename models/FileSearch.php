<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\File;
use yii\helpers\ArrayHelper;

/**
 * FileSearch represents the model behind the search form of `app\models\File`.
 */
class FileSearch extends File
{
    public $creator;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status', 'updatedBy', 'createdAt', 'categoryId'], 'integer'],
            [['title', 'path', 'createdBy', 'updatedAt', 'creator'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = File::find();
        $query->joinWith(['creator']);

        // add conditions that should always apply here
        /*$folders = CategoryPermission::find()->select(['refId', 'id'])->where(['userId' => \Yii::$app->user->id])->all();
        $query->andFilterWhere(['categoryId' => ArrayHelper::map($folders, 'id', 'refId')])->andFilterWhere(['status' => 1]);*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['creator'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['user.email' => SORT_ASC],
            'desc' => ['user.email' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'file.id' => $this->id,
            'file.status' => $this->status,
            'file.categoryId' => $this->categoryId,
            'file.createdBy' => $this->createdBy,
            'file.updatedBy' => $this->updatedBy,
            'file.createdAt' => $this->createdAt,
            'file.updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'file.title', $this->title])
            ->andFilterWhere(['like', 'file.path', $this->path])
            ->andFilterWhere(['like', 'user.email', $this->creator]);

        return $dataProvider;
    }
}
