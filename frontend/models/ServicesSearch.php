<?php

namespace app\models;

use common\models\references\PostCategories;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\services\Services;

/**
 * ServicesSearch represents the model behind the search form of `\common\models\services\Services`.
 */
class ServicesSearch extends CommonSearch
{
    public function formName()
    {
        return '';
    }

    public static function sortBy()
    {
        return [
            'price' => 'цене',
            'rating' => 'рейтингу',
            'created_at' => 'дате добавления',
            'surname' => 'фамилии'
        ];
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
        $query = Services::find()
            ->where(['services.status' => 1])
            ->from('services')
            ->joinWith('user as user')
            ->joinWith('specialist as specialist');

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

        if ($this->query) {
            $query->andWhere(['OR',
                ['ilike', 'services.name', $this->query],
                ['ilike', 'services.description', $this->query],
                ['ilike', 'specialist.description', $this->query],
                ['ilike', 'user.name', $this->query],
                ['ilike', 'user.surname', $this->query],
            ]);
        }

        if ($this->gender) {
            $query->andWhere(['user.gender' => $this->gender]);
        }

        if ($this->categories) {
            $SubCategories = PostCategories::find()
                ->from(['categories' => PostCategories::tableName()])
                ->where(['categories.id' => $this->categories])
                ->andWhere(['IS', 'categories.parent_id', null])
                ->joinWith('children as children')
                ->select('children.id')->column();
            $categoriesId = array_merge($this->categories, $SubCategories);
            $query->andWhere(['services.category_id' => $categoriesId]);
        }

        if ($this->tags) {
            $query->joinWith('specialist.tagsAssignment as sptags');
            $query->andWhere(['sptags.tag_id' => $this->tags]);
            $query->andHaving('COUNT(sptags.tag_id)=' . count($this->tags));
        }
        if ($this->skill) {
            if ($this->skill == 1) $query->andWhere(['<=', 'specialist.started_at', (int)date('Y') - 5]);
            if ($this->skill == 2) $query->andWhere(['>=', 'specialist.started_at', (int)date('Y') - 5]);
        }
        if ($this->price) {
            $query->andWhere(['<=', 'services.price', $this->price]);
        }

       if ($this->sort_by == 'price') $query->orderBy('services.price');
       if ($this->sort_by == 'date') $query->orderBy('services.created_at');
       if ($this->sort_by == 'surname') $query->orderBy('user.surname');
       if ($this->sort_by == 'rating') $query->orderBy(['specialist.rating' => SORT_DESC]);
        $query->groupBy(['specialist.id','services.id','user.surname']);

        return $dataProvider;
    }
}
