<?php

namespace app\controllers;

use Yii;
use app\models\Sendemailpage;
use app\models\Emaillist;
//use app\models\IndicationstypesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\filter\UserFilter;

/**
 * Description of SendemailController
 *
 * @author ctsuser
 */
class EmaillistController extends Controller {

    public function init() {
        $this->enableCsrfValidation = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'user' => [
                'class' => UserFilter::className()
            ],
        ];
    }

    public function actionInsert() {
        $curPage = 1;
        $pageSize = Yii::$app->request->post('pageSize');
        $cde_ids = Yii::$app->request->post('cde_ids');
        $user_id = User::$currUser->id;
        
        $obj = new \stdClass();
        foreach ($cde_ids as $cde_id){
            if (!empty($cde_id) && !empty($user_id)) {
                $cde = new Emaillist();
                $cde->user_id = $user_id;
                $cde->cde_id = $cde_id;
                $cde->save();
                if ($cde->save()) {
                    $obj->success = true;
                } else {
                    $obj->success = false;
                }
            } else {
                $obj->success = false;
            }
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionGetemaillist() {
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');
        
        $uid = User::$currUser->id;

        $obj = new \stdClass();
        if (!empty($curPage) && !empty($pageSize)) {
            $obj = Emaillist::getList($curPage, $pageSize, $typeId, $searchText, $uid);
            $obj->success = true;
        } else {
            $obj->success = false;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

//    public function actionGetemailpage(){
//        $pagedata = Yii::$app->request->post();
//    }

    protected function findModel($id) {
        if (($model = Indicationstypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
