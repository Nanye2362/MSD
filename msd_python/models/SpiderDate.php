<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spider_date".
 *
 * @property string $spider_time
 * @property string $http_status
 */
class SpiderDate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spider_date';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spider_time'], 'required'],
            [['spider_time'], 'safe'],
            [['http_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spider_time' => 'Spider Time',
            'http_status' => 'Http Status',
        ];
    }
    
    public static function getSpiderdate()
    {
        $return = SpiderDate::find()->select("to_char(\"spider_time\",'yyyy-mm-dd HH24:MI:SS') \"spider_time\", \"http_status\"")->orderBy('spider_time desc')->limit(1)->asArray()->one();
        return $return;
    }
}
