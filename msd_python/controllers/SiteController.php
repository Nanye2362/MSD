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
use app\models\SpiderDate;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;

class SiteController extends Controller {

    //不使用布局文件
    public $layout = false;

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
    public function actions() {
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

    public function actionGetdate() {
        echo date("Y-m-d H:i:s");
    }

    public function actionLogin() {
        //格式  mail=hong.chen@cognizant.com&name=hong.chen&isrole=true&date=2017-02-02 12:33:00
        $url_str = rsa::decodeing(Yii::$app->request->get('token'));
        if (empty($url_str)) {
            $this->redirect('http://teamspace.merck.com');
            //echo 'token不正确';
            //exit;
        }
        parse_str($url_str);

        if (time() - strtotime($date) > 300) {
            $this->redirect('http://teamspace.merck.com');
            //echo 'token过期';
            //exit;
        }


        if (!empty($mail)) {
            $user = user::findOne(['email' => $mail]);
            if (empty($user)) {
                $user = new user();
                $user->created_at = time();
                $user->status = 1;
                $user->email = $mail;
            }

            $user->role = empty($isrole) | $isrole == 'false' ? 0 : 1;
            $user->name = $name;
            $user->updated_at = time();
            $user->save();

            $session = Yii::$app->session;
            $session['user_id'] = $user->id;
            $session['user_name'] = $user->name;

            $user_favorite = user::getUserfavorite($mail);

            if ($user_favorite > 0) {
                return $this->redirect(['site/myfavorite']);
            } else {
                return $this->redirect(['site/index']);
            }
        } else {
            $this->redirect('http://teamspace.merck.com');
            //echo '权限不够，以后跳转到teamspace.merck.com';
            //exit;
        }
    }

    public function actionIndex() {
        //return $this->redirect(['site/index']);
        $spider_time = SpiderDate::getSpiderdate();
        $data['title'] = 'index';
        $data['spider_time'] = $spider_time['spider_time'];
        if($spider_time['http_status'] == 1){
            $data['http_status'] = '';
        }else{
            $data['http_status'] = 'CFDA(或CDE)网站数据源位置发生变化，需重新配置，更新完成的时间待定';
        }
        return $this->render('index', $data);
    }

    public function actionPage2() {
        $data['title'] = 'page2';
        return $this->render('page2', $data);
    }

    public function actionPage3() {
        $data['title'] = 'page3';
        return $this->render('page3', $data);
    }

    public function actionPage4() {
        $data['title'] = 'page4';
        return $this->render('page4', $data);
    }

    public function actionIndex4() {
        $data['title'] = 'index4';
        return $this->render('index4', $data);
    }

    public function actionConfig() {
        $data['title'] = 'config';
        return $this->render('config', $data);
    }

    public function actionMailconfig() {
        $data['title'] = 'mailconfig';
        return $this->render('mailconfig', $data);
    }

    public function actionUsednameconfig() {
        $data['title'] = 'usednameconfig';
        return $this->render('CdeUsedname', $data);
    }

    public function actionIndicationstypes() {
        $data['title'] = 'indicationstypes';
        return $this->render('IndicationsTypes', $data);
    }

    public function actionMyfavorite() {
        $data['title'] = 'myfavorite';
        return $this->render('Myfavorite', $data);
    }

    public function actionSendemail() {
        $data['title'] = 'sendemail';
        return $this->render('Sendemail', $data);
    }

}
