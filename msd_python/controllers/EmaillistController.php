<?php

namespace app\controllers;

use Yii;
use app\models\Sendemailpage;
//use app\models\Indicationstypes;
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
    public function behaviors()
    {
        return [
            'user' => [
                'class' => UserFilter::className()
            ],
        ];
    }

    public function actionGetemaillist() {
        $curPage = 1;
        $pageSize = Yii::$app->request->post('pageSize');
//        $typeId = Yii::$app->request->post('typeId');
//        $searchText = Yii::$app->request->post('searchText');
        $uid = User::$currUser->id;
        $checkboxvalue = Yii::$app->request->post('checkboxvalue');
        
//        var_dump($curPage);
//        var_dump($pageSize);
//        var_dump($uid);
//        var_dump($checkboxvalue);
//        die;
        $obj = new \stdClass();
        if (!empty($curPage) && !empty($pageSize)) {
            $obj = Sendemailpage::getList($curPage, $pageSize, $uid, $checkboxvalue);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
//        $obj = json_encode($obj);
//        var_dump($obj);die;
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    protected function findModel($id) {
        if (($model = Indicationstypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
