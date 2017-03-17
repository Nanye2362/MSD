<?php

namespace app\controllers;


use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\components\rsa;

use app\filter\UserFilter;

use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;

class SiteController extends Controller
{
    //不使用布局文件
    public $layout = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['Origin'=>'*'],
                    'Access-Control-Request-Method' => ['GET','POST', 'HEAD', 'OPTIONS']
                ],
            ],
            'user' => [
                'class' => UserFilter::className(),
                'admin_actions' => ['config', 'mailconfig', 'IndicationsTypes'],
                'user_actions' => ['index', 'page2', 'page3', 'index4']
            ]
        ], parent::behaviors());
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionGetdate(){
        echo date("Y-m-d H:i:s");
        exit;
    }

    public function actionLogin(){
        //格式  mail=hong.chen@cognizant.com&name=hong.chen&isrole=true&date=2017-02-02 12:33:00
        $url_str=rsa::decodeing(Yii::$app->request->get('token'));
        if(empty($url_str)){
            echo 'token不正确';
            exit;
        }
        parse_str($url_str);

        if(time()-strtotime($date)>300){
            echo 'token过期';
            exit;
        }


        if(!empty($mail)){
            $user=user::findOne(['email'=>$mail]);
            if(empty($user)){
                $user=new user();
                $user->created_at=time();
                $user->status=1;
                $user->email = $mail;
            }

            $user->role=empty($isrole)|$isrole=='false'?0:1;
            $user->name=$name;
            $user->updated_at=time();
            $user->save();

            $session = Yii::$app->session;
            $session['user_id'] = $user->id;
            return $this->redirect(['site/index']);
        }else{
            echo '权限不够，以后跳转到teamspace.merck.com';
            exit;
        }
    }

    public function actionIndex() {
        //return $this->redirect(['site/index']);

        return $this->render('index');
    }

    public function actionPage2() {
        return $this->render('page2');
    }

    public function actionPage3() {
        return $this->render('page3');
    }

    public function actionPage4() {
        return $this->render('page4');
    }

    public function actionIndex4() {
        return $this->render('index4');
    }

    public function actionConfig() {
        return $this->render('config');
    }

    public function actionMailconfig() {
        return $this->render('mailconfig');
    }

    public function actionUsednameconfig() {
        return $this->render('CdeUsedname');
    }

    public function actionIndicationstypes() {
        return $this->render('IndicationsTypes');
    }

    public function actionMyfavorite() {
        return $this->render('Myfavorite');
    }

    public function actionSendemail() {
        return $this->render('Sendemail');
    }
}
