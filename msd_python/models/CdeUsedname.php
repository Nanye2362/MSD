<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cde_usedname".
 *
 * @property string $id
 * @property string $cde_name
 * @property string $cde_usedname
 * @property string $cde_usedname2
 * @property string $cde_usedname3
 * @property string $cde_usedname4
 * @property string $cde_usedname5
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
                [['cde_name', 'cde_usedname', 'cde_usedname2', 'cde_usedname3', 'cde_usedname4', 'cde_usedname5'], 'required'],
                [['cde_name', 'cde_usedname', 'cde_usedname2', 'cde_usedname3', 'cde_usedname4', 'cde_usedname5'], 'string', 'max' => 50],
                [['cde_name'], 'unique'],
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
            'cde_usedname2' => 'Cde Usedname2',
            'cde_usedname3' => 'Cde Usedname3',
            'cde_usedname4' => 'Cde Usedname4',
            'cde_usedname5' => 'Cde Usedname5',
        ];
    }

    static function getList($curPage, $pageSize) {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = CdeUsedname::find();

        $num = $cdeObj->count();

        $cde = $cdeObj->orderBy('"id" desc')->limit($pageSize)->offset($start)->asArray()->all();
		
		foreach($cde as &$o){
			$o['cde_usedname']=empty($o['cde_usedname'])?"":$o['cde_usedname'];
			$o['cde_usedname2']=empty($o['cde_usedname2'])?"":$o['cde_usedname2'];
			$o['cde_usedname3']=empty($o['cde_usedname3'])?"":$o['cde_usedname3'];
			$o['cde_usedname4']=empty($o['cde_usedname4'])?"":$o['cde_usedname4'];
			$o['cde_usedname5']=empty($o['cde_usedname5'])?"":$o['cde_usedname5'];
		}

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $cde;

        return $obj;
    }

    static function getCdename($searchText = '') {
        if (!empty($searchText)) {
			$searchText = strtoupper($searchText);
            $cde_name = CdeUsedname::find()->andWhere('UPPER("cde_usedname") like :searchText or UPPER("cde_usedname2") like :searchText or UPPER("cde_usedname3") like :searchText or UPPER("cde_usedname4") like :searchText or UPPER("cde_usedname5") like :searchText', [':searchText' => '%' . $searchText . '%'])->asArray()->one();
            return $cde_name;
        } else {
            return '';
        }
    }

	static function getCdeusedname($searchText = '') {
        if (!empty($searchText)) {
			$searchText = strtoupper($searchText);
            $cde_usedname = CdeUsedname::find()->andWhere('UPPER("cde_name") like :searchText', [':searchText' => '%' . $searchText . '%'])->asArray()->one();
            return $cde_usedname;
        } else {
            return '';
        }
    }
	
}
