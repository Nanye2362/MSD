<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indications_types".
 *
 * @property integer $id
 * @property string $english_name
 * @property string $chinese_name
 * @property string $ephmra_atc_code
 */
class Indicationstypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'indications_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['english_name'], 'string', 'max' => 145],
            [['chinese_name'], 'string', 'max' => 45],
            [['ephmra_atc_code'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'english_name' => 'English_name',
            'chinese_name' => 'Chinese_name',
            'ephmra_atc_code' => 'Ephmra_atc_code',
        ];
    }


    static function getList($curPage,$pageSize){
        $start=($curPage-1)*$pageSize;

        $cdeObj=Indicationstypes::find();

        $num=$cdeObj->count();

        $cde=$cdeObj->orderBy('"id" desc')->limit($pageSize)->offset($start)->asArray()->all();

        $obj=new \stdClass();
        $obj->totalRows=$num;
        $obj->curPage=$curPage;
        $obj->data=$cde;

        return $obj;
    }
}
