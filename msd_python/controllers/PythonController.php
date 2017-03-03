<?php

namespace app\controllers;

use app\models\Cde;
use app\models\CdeType;
use app\models\CdePublicremark;
use app\models\ChinaDrugTrials;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\filter\UserFilter;
use Yii;
use moonland\phpexcel\Excel;

class PythonController extends \yii\web\Controller {

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

    //获取cde列表
    public function actionGetlist() {
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');

        $uid = User::$currUser->id;
        $role = User::$currUser->role;

        $obj = new \stdClass();

        if (!empty($curPage) && !empty($pageSize)) {
            $obj = Cde::getList($curPage, $pageSize, $typeId, $searchText, $uid, $role);
            $obj->success = true;
        } else {
            $obj->success = false;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionGettype() {
        $obj = CdeType::getList();
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionGetdetail() {

        $cdeId = Yii::$app->request->post('cdeId');
        $obj = new \stdClass();
        if (!empty($cdeId)) {
            $obj->data = cde::getDetailByCdeId($cdeId);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionGettimeline() {

        $cdeId = Yii::$app->request->post('cdeId');
        $obj = new \stdClass();
        if (!empty($cdeId)) {
            $obj->data = cde::getTimelineByCdeId($cdeId);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionUpdateremark() {
        $cdeId = Yii::$app->request->post('cdeId');
        $remark = Yii::$app->request->post('remark');
        //后期修改 对接  临时用表单传递
        $userid = User::$currUser->id;

        $obj = new \stdClass();
        if (!empty($cdeId)) {
            $cde = CdePublicremark::find()->where("cde_id=:cdeid and uid=:uid", [":cdeid" => $cdeId, ":uid" => $userid])->one();
            if (empty($cde)) {
                $cde = new CdePublicremark();
            }

            $cde->remark = $remark;
            $cde->cde_id = $cdeId;
            $cde->uid = $userid;
            $cde->create_date = date('Y-m-d H:i:s');
            $cde->save(false);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionUpdatepublicremark() {
        $cdeId = Yii::$app->request->post('cdeId');
        $remark = Yii::$app->request->post('remark');
        //后期修改 对接  临时用表单传递
        $userid = User::$currUser->id;


        $obj = new \stdClass();
        if (!empty($cdeId)) {
            $cde = CdePublicremark::find()->where("cde_id=:cdeid and uid=:uid", [":cdeid" => $cdeId, ":uid" => $userid])->one();
            if (empty($cde)) {
                $cde = new CdePublicremark();
            }

            $cde->public_remark = $remark;
            $cde->cde_id = $cdeId;
            $cde->uid = $userid;
            $cde->create_date = date('Y-m-d H:i:s');
            $cde->save(false);
            $obj->uid = $userid;
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionUpdateclinicalindication() {
        $cdeId = Yii::$app->request->post('cdeId');
        $clinical_indication = Yii::$app->request->post('clinical_indication');
        //后期修改 对接  临时用表单传递
        $userid = User::$currUser->id;

        $obj = new \stdClass();
        if (!empty($cdeId)) {
            $cde = Cde::find()->where("id=:cdeid", [":cdeid" => $cdeId])->one();
            if (empty($cde)) {
                $cde = new Cde();
            }

            $cde->clinical_indication = $clinical_indication;
            $cde->id = $cdeId;
            $cde->create_date = date('Y-m-d H:i:s');
            $cde->save(false);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionGetchinadrug() {
        $cdeId = Yii::$app->request->post('cdeId');
        $obj = new \stdClass();
        if (!empty($cdeId)) {
            $obj->data = ChinaDrugTrials::getChinaDrugByCdeId($cdeId);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionExport() {
        $cde_ids = Yii::$app->request->get('cde_id'); 
        
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');
        $uid = User::$currUser->id;
        $role = User::$currUser->role;
        
        
        $data = Cde::getList($curPage, $pageSize, $typeId, $searchText, $uid, $role, $cde_ids);
        $excel_data = $data->data;

        Excel::export([
            'models' => $excel_data,
            'columns' => ['rank', 'code', 'name', 'company', 'join_date', 'rl', 'ephmra_atc_code',
                'sfda_status', 'clinical_indication', 'remark', 'remark1', 'showremark'],
            'headers' => [
                'rank' => '序号',
                'code' => '受理号',
                'name' => '药品名称',
                'company' => '企业名称',
                'join_date' => '进入中心时间',
                'rl' => '序号排名变化时间节点记录',
                'ephmra_atc_code' => '适应症大类',
                'sfda_status' => '临床实验',
                'clinical_indication' => '临床适应症',
                'remark' => '个人备注',
                'remark1' => '公开备注',
                'showremark' => '所有用户备注'
            ]
        ]);
    }

}
