<?php

namespace yii2mod\comments\models\search;

use yii\data\ActiveDataProvider;
use yii2mod\comments\models\CommentModel;
use yii\db\Expression;

/**
 * Class CommentSearch
 *
 * @package yii2mod\comments\models\search
 */
class CommentSearch extends CommentModel
{
    /**
     * @var int the default page size
     */
    public $pageSize = 10;

    public $authorName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['content', 'relatedTo'], 'safe'],
            [['authorName'], 'string']
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params)
    {
        $query = CommentModel::find()->andWhere(new Expression('SELECT *, CONCAT(name, up.firstname, up.lastname, up.middlename) as author_name from comment left join user on user.id = comment.createdBy left join user_profile up on user.id = up.user_id'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);
        $query->andFilterWhere(['like', 'author_name', $this->authorName]);
        $query->andFilterWhere(['like', 'relatedTo', $this->relatedTo]);

        return $dataProvider;
    }
}
