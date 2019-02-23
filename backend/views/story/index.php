<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\StorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '故事管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="story-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'user_id',
            [
                    'attribute'=>'Author',
                'value'=>'createdBy.nickName',
//                'filter'=>\common\models\User::find()
//                    ->select(['nickName','id'])
//                    ->indexBy('id')
//                    ->column(),
            ],
            'entity',
[
        'attribute' => 'entity',
    'value'=> function($data){
        return Html::a($data->entity,[
                'story/play'
        ],['entity' => 'entity']);}
],
//            'during',
[
        'attribute' => 'during',
    'value' => 'secToTime',
],
//            'type',
            [
                'attribute'=>'type',
                'value'=>'cateStr',
                'filter'=>\common\models\Story::$cateStrArray,
            ],
             'status',
            // 'created_at',
            [
                'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
