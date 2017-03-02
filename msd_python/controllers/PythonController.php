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
use scotthuangzl\export2excel\Export2ExcelBehavior;

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
            'export2excel' => [
                'class' => Export2ExcelBehavior::className()
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

    public function actionExport(){
        $test = Yii::$app->request->post('test');
        var_dump($test);die;
        $excel_data = Export2ExcelBehavior::excelDataFormat(Cde::find()->asArray()->all());
        
        var_dump($excel_data);die;
        $excel_title = $excel_data['excel_title'];
        $excel_ceils = $excel_data['excel_ceils'];
        $excel_content = array(
            array(
                'sheet_name' => 'EOPStatus',
                'sheet_title' => $excel_title,
                'ceils' => $excel_ceils,
                'freezePane' => 'B2',
                'headerColor' => Export2ExcelBehavior::getCssClass("header"),
                'headerColumnCssClass' => array(
                    'id' => Export2ExcelBehavior::getCssClass('blue'),
                    'Status_Description' => Export2ExcelBehavior::getCssClass('grey'),
                ), //define each column's cssClass for header line only.  You can set as blank.
                'oddCssClass' => Export2ExcelBehavior::getCssClass("odd"),
                'evenCssClass' => Export2ExcelBehavior::getCssClass("even"),
            ),
            array(
                'sheet_name' => 'Important Note',
                'sheet_title' => array("Important Note For Region Template"),
                'ceils' => array(
                    array("1.Column Platform,Part,Region must need update.")
                , array("2.Column Regional_Status only as Regional_Green,Regional_Yellow,Regional_Red,Regional_Ready.")
                , array("3.Column RTS_Date, Master_Desc, Functional_Desc, Commodity, Part_Status are only for your reference, will not be uploaded into NPI tracking system."))
            ),
        );
        $excel_file = "testYii2Save2Excel";
        $this->export2excel($excel_content, $excel_file);
    }
}
