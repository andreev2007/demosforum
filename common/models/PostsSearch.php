<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Posts;

/**
 * PostsSearch represents the model behind the search form of `common\models\Posts`.
 */
class PostsSearch extends Posts
{

    public function formName()
    {
        return '';
    }

    public $period;
    public $search;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content', 'image'], 'safe'],
            [['search'], 'string'],
            [['period'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
          'period' => \Yii::t('app', 'Period'),
        ];
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
        $query = Posts::find()->orderBy(['created_at' => SORT_DESC])->andWhere(['status' => 10]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 8
            ]
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if ($this->period) {
            \Yii::error($this->period);
            $query->andFilterWhere([
                '>=',
                'created_at',
                time() - (int)$this->period
            ]);
        }


        if ($this->search) {
            $query->orFilterWhere(['like', 'content', $this->search])->orderBy(['created_at' => SORT_DESC])->all();
        }


        return $dataProvider;
    }
}
