<?php
namespace app\filter;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\web\Session;
use app\models\User;


class UserFilter extends ActionFilter{

    public $admin_actions =[];
    public $user_actions=[];

    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $uid=$session['user_id'];
        $user=User::findOne(['id'=>$uid]);
        User::$currUser=$user;
    }


    public function beforeAction($action) {
        //admin权限
        if(in_array($action->id,$this->admin_actions)){
             if(User::$currUser->role==1){
                 return True;
             }else{
                 Yii::$app->getResponse()->redirect('/site/login');
                 return false;
             }
        }

        //普通用户权限
        if(in_array($action->id,$this->user_actions)) {
            if (!empty(User::$currUser)) {
                return true;
            }else{
                Yii::$app->getResponse()->redirect('/site/login');
                return false;
            }
        }

        return TRUE;//如果返回值为false,则action不会运行
    }
}