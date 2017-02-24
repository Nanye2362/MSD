<?php

namespace app\models;

use Yii;
use yii\base\Object;
use yii\db\mysql;

/**
 * This is the model class for table "cde_type".
 *
 * @property string $id
 * @property string $title
 * @property string $subtitle
 * @property string $task
 */
class CdeType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cde_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'subtitle', 'task'], 'required'],
            [['title', 'subtitle', 'task'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'task' => 'Task',
        ];
    }

    static function getList(){
        $list=CdeType::find()->asArray()->all();
        $return['所有']['所有']['所有']=0;
        foreach($list as $array){
            if($array['subtitle']==" "){
                $array['subtitle'] = $array['title'];
            }
            $return[$array['title']][$array['subtitle']][$array['task']]=$array['id'];
        }

        return $return;
    }


}
