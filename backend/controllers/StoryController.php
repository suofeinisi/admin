<?php

namespace backend\controllers;

use Yii;
use common\models\Story;
use common\models\StorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StoryController implements the CRUD actions for Story model.
 */
class StoryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Story models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Story model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Story model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Story();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionPlay()
    {
        try {
            $entity = \Yii::$app->request->get('entity', '');
            $entity = \Yii::$app->params['AAC_PATH_PRE'] . '/'. $entity;
            self::GetMp4File($entity);
        } catch (\Exception $ex) {
            echo $ex->getMessage();die;
        }
    }

    public static function GetMp4File($file) {
        $size = filesize($file);
        header("Content-type: video/mp4");
        header("Accept-Ranges: bytes");
        if(isset($_SERVER['HTTP_RANGE'])){
            header("HTTP/1.1 206 Partial Content");
            list($name, $range) = explode("=", $_SERVER['HTTP_RANGE']);
            list($begin, $end) =explode("-", $range);
            if($end == 0){
                $end = $size - 1;
            }
        }else {
            $begin = 0; $end = $size - 1;
        }
        header("Content-Length: " . ($end - $begin + 1));
        header("Content-Disposition: filename=".basename($file));
        header("Content-Range: bytes ".$begin."-".$end."/".$size);
        $fp = fopen($file, 'rb');
        fseek($fp, $begin);
        while(!feof($fp)) {
            $p = min(1024, $end - $begin + 1);
            $begin += $p;
            echo fread($fp, $p);
        }
        fclose($fp);
    }

    /**
     * Updates an existing Story model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Story model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Story model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Story the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Story::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
