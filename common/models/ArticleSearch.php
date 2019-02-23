<?php

namespace common\models;

use kartik\daterange\DateRangeBehavior;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleSearch represents the model behind the search form of `common\models\Article`.
 */
class ArticleSearch extends Article
{

    public $user;
    public $date_range;
    public $date_from;
    public $date_to;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'date_range',
                'dateStartAttribute' => 'date_from',
                'dateEndAttribute' => 'date_to',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['user'], 'string'],
            [['date_range'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
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
        $query = Article::find()->joinWith(['user']);

        // add conditions that should always apply here
        $query->where(['<>', 'article.status', Article::STATUS_DELETED]);
        // Show any statuses articles for admin and author (his own)
        if (!\Yii::$app->user->can('admin')) {
            $query->andWhere(
                ['or',
                    ['article.status' => Article::STATUS_PUBLIC],
                    ['user_id' => \Yii::$app->user->id]
                ]
            );
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'user' => [
                        'asc' => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                    ],
                    'created_at'
                ]
            ],
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', User::tableName() . '.username', $this->user]);
        $query->andFilterWhere(['>=', 'article.created_at', $this->date_from])
            ->andFilterWhere(['<=', 'article.created_at', $this->date_to]);


        return $dataProvider;
    }
}
