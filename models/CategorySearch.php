<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Category;
use yii\helpers\ArrayHelper;

/**
 * CategorySearch represents the model behind the search form of `app\models\Category`.
 */
class CategorySearch extends Category
{
    public $creator;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'parentId', 'status', 'updatedBy', 'createdAt'], 'integer'],
            [['name', 'createdBy', 'updatedAt', 'creator'], 'safe'],
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
        $query = Category::find();
        $query->joinWith(['creator']);
        // add conditions that should always apply here
        $folders = CategoryPermission::find()->select(['refId', 'id'])->where(['userId' => \Yii::$app->user->id])->all();
        $query->andFilterWhere(['category.id' => ArrayHelper::map($folders, 'id', 'refId')])->andFilterWhere(['category.status' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
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
            'category.id' => $this->id,
            'category.parentId' => $this->parentId,
            'category.status' => $this->status,
            'category.createdBy' => $this->createdBy,
            'category.updatedBy' => $this->updatedBy,
            'category.createdAt' => $this->createdAt,
            'category.updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'category.name', $this->name])
            ->andFilterWhere(['like', 'user.email', $this->creator]);

        return $dataProvider;
    }
}
