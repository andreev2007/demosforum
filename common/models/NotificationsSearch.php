<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Notifications;

/**
 * NotificationsSearch represents the model behind the search form of `common\models\Notifications`.
 */
class NotificationsSearch extends Notifications
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'post_id', 'is_read', 'created_at', 'created_by'], 'integer'],
            [['type'], 'safe'],
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
     * @return ActiveDataProviderSignup
     */
    public function search($params)
    {
        $query = Notifications::find()
            ->where(['user_id' => \Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC]);

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
            'id' => $this->id,
            'post_id' => $this->post_id,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
