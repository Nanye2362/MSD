<?php

namespace app\controllers;

use Yii;
use app\models\CdeUsedname;
use app\models\CdeUsednameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use app\models\User;
use app\filter\UserFilter;
use yii\helpers\ArrayHelper;

/**
 * Description of UsednameController
 *
 * @author ctsuser
 */
class CdeusednameController extends Controller {

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

    public function actionGetlist() {
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');

        $obj = new \stdClass();
        if (!empty($curPage) && !empty($pageSize)) {
            $obj = CdeUsedname::getList($curPage, $pageSize);
            $obj->success = true;
        } else {
            $obj->success = false;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionUpdate() {
        $id = Yii::$app->request->post('id');
        $cde_name = Yii::$app->request->post('cde_name');
        $cde_usedname = Yii::$app->request->post('cde_usedname');

        $obj = new \stdClass();
        if (!empty($id)) {
            $cde = CdeUsedname::find()->where("id=:id", [":id" => $id])->one();
            $cde->cde_name = $cde_name;
            $cde->cde_usedname = $cde_usedname;
            $cde->save(false);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionSearch() {
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $searchText = Yii::$app->request->post('searchText');

        $obj = new \stdClass();
        if (!empty($curPage) && !empty($pageSize)) {
            $obj = CdeUsednameSearch::search($curPage, $pageSize, $searchText);
            $obj->success = true;
        } else {
            $obj->success = false;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

}
