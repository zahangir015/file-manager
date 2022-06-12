<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CategoryPermission;

/**
 * CategoryPermissionSearch represents the model behind the search form of `app\models\CategoryPermission`.
 */
class CategoryPermissionSearch extends CategoryPermission
{
    public $creator;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'userId', 'refId', 'createdBy', 'updatedBy'], 'integer'],
            [['refModel', 'createdAt', 'updatedAt', 'creator'], 'safe'],
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
        $query = CategoryPermission::find();
        $query->joinWith(['creator']);

        // add conditions that should always apply here
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
            'category_permission.id' => $this->id,
            'category_permission.userId' => $this->userId,
            'category_permission.refId' => $this->refId,
            'category_permission.createdBy' => $this->createdBy,
            'category_permission.updatedBy' => $this->updatedBy,
            'category_permission.createdAt' => $this->createdAt,
            'category_permission.updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'category_permission.refModel', $this->refModel])
            ->andFilterWhere(['like', 'user.email', $this->creator]);


        return $dataProvider;
    }
}
