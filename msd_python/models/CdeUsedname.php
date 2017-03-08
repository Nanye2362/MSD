<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cde_usedname".
 *
 * @property string $id
 * @property integer $cde_name
 * @property integer $cde_usedname
 */
class CdeUsedname extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'cde_usedname';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['cde_name', 'cde_usedname'], 'required'],
                [['cde_name', 'cde_usedname'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cde_name' => 'Cde Name',
            'cde_usedname' => 'Cde Usedname',
        ];
    }

    static function getList($curPage, $pageSize) {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = CdeUsedname::find();

        $num = $cdeObj->count();

        $cde = $cdeObj->orderBy('`id` desc')->limit($pageSize)->offset($start)->asArray()->all();

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $cde;

        return $obj;
    }

}
