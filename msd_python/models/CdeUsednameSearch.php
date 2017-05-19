<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CdeUsedname;

/**
 * Description of CdeUsednameSearch
 *
 * @author ctsuser
 */
class CdeUsednameSearch extends CdeUsedname {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id'], 'integer'],
                [['cde_name', 'cde_usedname', 'cde_usedname2', 'cde_usedname3'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    static function search($curPage, $pageSize, $searchText) {
        $start = ($curPage - 1) * $pageSize;

        $configObj = CdeUsedname::find();

        if (!empty($searchText)) {
            $configObj->andWhere('"cde_name" like :searchText or "cde_usedname" like :searchText or "cde_usedname2" like :searchText or "cde_usedname3" like :searchText or "cde_usedname4" like :searchText or "cde_usedname5" like :searchText', [':searchText' => '%' . $searchText . '%']);
        }

        $num = $configObj->count();

        $configValues = $configObj->orderBy('"id" desc')->limit($pageSize)->offset($start)->asArray()->all();
		
		foreach($configValues as &$configValue){
			$configValue['cde_usedname2'] = !empty($configValue['cde_usedname2'])?$configValue['cde_usedname2']:'';
			$configValue['cde_usedname3'] = !empty($configValue['cde_usedname3'])?$configValue['cde_usedname3']:'';
			$configValue['cde_usedname4'] = !empty($configValue['cde_usedname4'])?$configValue['cde_usedname4']:'';
			$configValue['cde_usedname5'] = !empty($configValue['cde_usedname5'])?$configValue['cde_usedname5']:'';
		}
		
        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $configValues;

        return $obj;
    }

}
