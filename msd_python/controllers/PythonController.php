<?php

namespace app\controllers;

use app\models\Cde;
use app\models\CdeType;
use app\models\CdePublicremark;
use app\models\ChinaDrugTrials;
use app\models\CdeUsedname;
use yii\db\Expression;
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
        $cde_ids = Yii::$app->request->post('cde_id');
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');
        $sortName = Yii::$app->request->post('sortName');
        $sortOrder = Yii::$app->request->post('sortOrder');
        
        $uid = User::$currUser->id;
        $role = User::$currUser->role;

        $obj = new \stdClass();

        $cde_name = CdeUsedname::getCdename($searchText);
        
		$cde_usedname = CdeUsedname::getCdeusedname($searchText);
		
        if(!empty($cde_name)){
			//别名查询
            $searchText = array(strtoupper($cde_name['cde_name']), strtoupper($cde_name['cde_usedname']), strtoupper($cde_name['cde_usedname2']), strtoupper($cde_name['cde_usedname3']), strtoupper($cde_name['cde_usedname4']), strtoupper($cde_name['cde_usedname5']), strtoupper($searchText));
        }
		
		if(!empty($cde_usedname)){
			//原名查询
			$searchText = array(strtoupper($cde_usedname['cde_name']), strtoupper($cde_usedname['cde_usedname']), strtoupper($cde_usedname['cde_usedname2']), strtoupper($cde_usedname['cde_usedname3']), strtoupper($cde_usedname['cde_usedname4']), strtoupper($cde_usedname['cde_usedname5']), strtoupper($searchText));
		}
		
		if(empty($cde_name) && empty($cde_usedname)){
			$searchText = strtoupper($searchText);
		}
        
        if (!empty($curPage) && !empty($pageSize)) {
            $obj = Cde::getList($curPage, $pageSize, $typeId, $searchText, $uid, $role, $cde_ids, $sortName, $sortOrder);
            $obj->success = true;
        } else {
            $obj->success = false;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $obj;
    }

    public function actionGetlistbyephmra() {
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');
        $ephmra_atc_code = Yii::$app->request->post('ephmra_atc_code');
        $sortName = Yii::$app->request->post('sortName');
        $sortOrder = Yii::$app->request->post('sortOrder');
        
        $uid = User::$currUser->id;
        $role = User::$currUser->role;
        
        $cde_name = CdeUsedname::getCdename($searchText);
        
        if(!empty($cde_name)){
            $searchText = array(strtoupper($searchText), strtoupper($cde_name));
        }else{
			$searchText = strtoupper($searchText);
		}

        $obj = new \stdClass();

        if (!empty($curPage) && !empty($pageSize)) {
            $obj = Cde::getListbyephmra($curPage, $pageSize, $typeId, $searchText, $uid, $role, $ephmra_atc_code, $sortName, $sortOrder);
            $obj->success = true;
        } else {
            $obj->success = false;
        }
//        var_dump($obj);die;
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
            $cde = CdePublicremark::find()->where('"cde_id"=:cdeid and "user_id"=:user_id', [":cdeid" => $cdeId, ":user_id" => $userid])->one();
            if (empty($cde)) {
                $cde = new CdePublicremark();
            }

            $cde->remark = $remark;
            $cde->cde_id = $cdeId;
            $cde->user_id = $userid;
			$cde->create_date=new Expression("TO_DATE('".date('Y-m-d H:i:s')."','YYYY-MM-DD HH24:MI:SS')");
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
		$user_name = User::$currUser->name;

        $obj = new \stdClass();
        if (!empty($cdeId)) {
            $cde = CdePublicremark::find()->where('"cde_id"=:cdeid and "user_id"=:user_id', [":cdeid" => $cdeId, ":user_id" => $userid])->one();
            if (empty($cde)) {
                $cde = new CdePublicremark();
            }

            $cde->public_remark = $remark;
            $cde->cde_id = $cdeId;
            $cde->user_id = $userid;
            $cde->create_date = new Expression("TO_DATE('".date('Y-m-d H:i:s')."','YYYY-MM-DD HH24:MI:SS')");
            $cde->save(false);
            $obj->uid = $userid;
            $obj->user_name = $user_name;
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
            $cde = Cde::find()->where('"id"=:cdeid', [":cdeid" => $cdeId])->one();
            if (empty($cde)) {
                $cde = new Cde();
            }

            $cde->clinical_indication = $clinical_indication;
            $cde->id = $cdeId;
            $cde->create_date =  new Expression("TO_DATE('".date('Y-m-d H:i:s')."','YYYY-MM-DD HH24:MI:SS')");
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
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');        
        $obj = new \stdClass();
        if (!empty($cdeId)) {
            $obj = ChinaDrugTrials::getChinaDrugByCdeId($curPage, $pageSize, $cdeId);
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
        $export = Yii::$app->request->get('export'); 
        
        $curPage = Yii::$app->request->post('curPage');
        $pageSize = Yii::$app->request->post('pageSize');
        $typeId = Yii::$app->request->post('typeId');
        $searchText = Yii::$app->request->post('searchText');
        $uid = User::$currUser->id;
        $role = User::$currUser->role;
        
        $searchText = strtoupper($searchText);
        $data = Cde::getList($curPage, $pageSize, $typeId, $searchText, $uid, $role, $cde_ids, '', '', $export);
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
