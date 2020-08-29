<?php

namespace app\models;

use common\models\behaviors\ShowValidationErrorsBehavior;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use common\models\Posts;

/**
 * PostsSearch represents the model behind the search form of `\common\models\posts\Posts`.
 */
class CommonSearch extends Model
{
    public $gender = null;
    public $query;
    public $categories = [];
    public $tags = [];
    public $user_ids;
    public $skill;
    public $price;
    public $sort_by;

    public function behaviors()
    {
        return [
            [
                'class' => ShowValidationErrorsBehavior::class
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['query', 'user_ids', 'sort_by'], 'safe'],

        ];
    }


    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
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
    public function search($params)
    {
        $query = Posts::find()->where(['posts.status' => 1])->from('posts');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_ids,
        ]);

        if ($this->categories) {
            $query->andWhere(['posts.category_id' => $this->categories]);
        }
        if ($this->tags) {
            $query->joinWith('tags');
            $query->andWhere(['tags.id' => $this->tags]);
        }

        if ($this->query) {
            $query->andWhere(['OR',
                ['ilike', 'posts.title', $this->query],
                ['ilike', 'posts.body', $this->query]
            ]);
        }

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'query' => 'Поиск',
            'tags' => 'Теги',
        ];
    }
}
