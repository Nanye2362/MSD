<?php

namespace app\controllers;

use Yii;
use app\models\Configuration;
use app\models\ConfigurationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;

/**
 * ConfigurationController implements the CRUD actions for Configuration model.
 */
class ConfigurationController extends Controller {

    public function init() {
        $this->enableCsrfValidation = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return ArrayHelper::merge([
                        [
                        'class' => Cors::className(),
                        'cors' => [
                            'Origin' => ['Origin' => '*'],
                            'Access-Control-Request-Method' => ['GET', 'POST', 'HEAD', 'OPTIONS']
                        ],
                    ],
                        ], parent::behaviors());
    }

    /**
     * Updates an existing Configuration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate() {
        $id = Yii::$app->request->post('id');
        $value = Yii::$app->request->post('value');
        $obj = new \stdClass();
        if (!empty($id)) {
            $cde = Configuration::find()->where("id=:id", [":id" => $id])->one();
            $cde->value = $value;
            $cde->save(false);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionGetlist() {
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');
        $uid = Yii::$app->request->post('uid');
        $uid = empty($uid) ? 0 : $uid;

        $obj = new \stdClass();
        if (!empty($curPage) && !empty($pageSize)) {
            $obj = Configuration::getList($curPage, $pageSize);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
        
    }
    
//    public function actionTest() { 
//        return $this->render('/site/config.php', ['test' => 5]);
//    }

    public function actionSearch() {
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
//        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');
        $uid = Yii::$app->request->post('uid');
        $uid = empty($uid) ? 0 : $uid;
        
        $obj = new \stdClass();
        if (!empty($curPage) && !empty($pageSize)) {
            $obj = ConfigurationSearch::search($curPage, $pageSize, $uid, $searchText);
            $obj->success = true;
        } else {
            $obj->success = false;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }
    
    /**
     * Finds the Configuration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Configuration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Configuration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
