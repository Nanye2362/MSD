<?php

namespace app\controllers;

use Yii;
use app\models\Sendemailpage;
use app\models\Emaillist;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\filter\UserFilter;
use app\models\Cde;

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
        $cde_ids1 = Yii::$app->request->post('cde_ids');
        $names = Yii::$app->request->post('names');
        $user_id = User::$currUser->id;
        
        $obj = new \stdClass();
        
        if(!empty($names)){
            $alldrugids = Emaillist::getAlldrugid($names);
            foreach ($alldrugids as $alldrugid){
                foreach($alldrugid as $v){
                    $cde_ids2[] = $v['id'];
                }
            }
            if(!empty($cde_ids2)){
                foreach ($cde_ids2 as $cde_id2) {
                    if(!empty($cde_id2) && !empty($user_id)){
                        $Emaillist = new Emaillist();
                        $Emaillist->user_id = $user_id;
                        $Emaillist->cde_id = $cde_id2;
                        $Emaillist->checkbox = 2;
                        $Emaillist->save();
                        $obj->success = true;
                    }else{
                        $obj->success = false;
                    }
                }
            }
            
        }
        
        if(!empty($cde_ids1)){
            foreach ($cde_ids1 as $cde_id){
                if (!empty($cde_id) && !empty($user_id)) {
                    $emaillist = new Emaillist();
                    $emaillist->user_id = $user_id;
                    $emaillist->cde_id = $cde_id;
                    $emaillist->checkbox = 1;
                    $emaillist->save();
                    $obj->success = true;
                } else {
                    $obj->success = false;
                }
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
