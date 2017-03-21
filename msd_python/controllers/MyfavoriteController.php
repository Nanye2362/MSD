<?php

namespace app\controllers;

use Yii;
use app\models\Sendemailpage;
use app\models\Myfavoritelist;
use app\models\CdeUsedname;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\filter\UserFilter;

/**
 * Description of FavoriteController
 *
 * @author ctsuser
 */
class MyfavoriteController extends Controller {

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
        $cde_ids = Yii::$app->request->post('cde_ids');
        $user_id = User::$currUser->id;

        $obj = new \stdClass();
        if (!empty($cde_ids)) {
            foreach ($cde_ids as $cde_id) {
                if (!empty($cde_id) && !empty($user_id)) {
                    $cde = new Myfavoritelist();
                    $cde->user_id = $user_id;
                    $cde->cde_id = $cde_id;
                    $cde->save();
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

    public function actionGetmyfavoritelist() {
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');

        $uid = User::$currUser->id;

        $cde_name = CdeUsedname::getCdename($searchText);

        if (!empty($cde_name)) {
            $searchText = array($searchText, $cde_name);
        }

        $obj = new \stdClass();
        if (!empty($curPage) && !empty($pageSize)) {
            $obj = Myfavoritelist::getList($curPage, $pageSize, $typeId, $searchText, $uid);
            $obj->success = true;
        } else {
            $obj->success = false;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionDelete() {
        $cde_ids = Yii::$app->request->post();
        $user_id = User::$currUser->id;

        $obj = new \stdClass();
        if (!empty($cde_ids)) {
            foreach ($cde_ids as $v) {
                foreach ($v as $cde_id) {
                    $emaillist = new Myfavoritelist();
                    $delete_status = $emaillist->deleteAll('user_id = :user_id and cde_id = :cde_id', [':user_id' => $user_id, ':cde_id' => $cde_id]);
                    if ($delete_status == 1) {
                        $obj->success = true;
                    } else {
                        $obj->success = false;
                    }
                }
            }
        }
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
