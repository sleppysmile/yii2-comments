<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;
use yii2mod\moderation\enums\Status;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \yii2mod\comments\models\search\CommentSearch */
/* @var $commentModel \yii2mod\comments\models\CommentModel */

$this->title = Yii::t('yii2mod.comments', 'Comments Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <h1><?php echo Html::encode($this->title); ?></h1>
    <?php Pjax::begin(['timeout' => 10000]); ?>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'max-width: 50px;'],
            ],
            [
                'attribute' => 'content',
                'contentOptions' => ['style' => 'max-width: 350px;'],
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->content, $model->view_url);
                },
            ],
            [
                'attribute' => 'authorName',
                'value' => function ($model) {
                if (!empty($model->createdBy)) {
                    return $model->author->userProfile->fullName;
                }
                    return $model->name;
                },
                'filterInputOptions' => ['prompt' => Yii::t('yii2mod.comments', 'Select Author'), 'class' => 'form-control'],
                'header' => 'Кем создан'
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Yii::t('yii2mod.comments', Status::getLabel($model->status));
                },
                'filter' => false,
            ],
            [
                'attribute' => 'createdAt',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->createdAt);
                },
                'filter' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $title = Yii::t('yii2mod.comments', 'View');
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                            'target' => '_blank',
                        ];
                        $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']);
                        $url = $model->getViewUrl();

                        if (!empty($url)) {
                            return Html::a($icon, $url, $options);
                        }

                        return null;
                    },
                ],
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
