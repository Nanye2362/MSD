<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Session;
use app\filter\UserFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

class SiteController extends Controller {

    //不使用布局文件
    public $layout = false;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'user' => [
                'class' => UserFilter::className(),
                'admin_actions' => ['config', 'mailconfig', 'IndicationsTypes'],
                'user_actions' => ['index', 'page2', 'page3', 'index4']
            ],
        ];
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

    public function actionLogin() {
        $mail = Yii::$app->request->get('mail');
        $role = Yii::$app->request->get('isrole');
        if (!empty($mail)) {
            $user = user::findOne(['email' => $mail]);

            if (empty($user)) {
                $user = new user();
                $user->created_at = time();
                $user->status = 1;
                $user->email = $mail;
            }

            $user->role = empty($role) ? 0 : 1;
            $user->updated_at = time();
            $user->save();

            $session = Yii::$app->session;
            $session['user_id'] = $user->id;

            $user_favorite = user::getUserfavorite($mail);

            if ($user_favorite > 0) {
                return $this->redirect(['site/myfavorite']);
            } else {
                return $this->redirect(['site/index']);
            }
        } else {
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
        return $this->render('usedname');
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
