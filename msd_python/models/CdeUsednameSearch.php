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
                [['cde_name', 'cde_usedname'], 'safe'],
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
            $configObj->andWhere("cde_name like :searchText or cde_usedname like :searchText", [':searchText' => '%' . $searchText . '%']);
        }

        $num = $configObj->count();

        $configValues = $configObj->orderBy('`id` desc')->limit($pageSize)->offset($start)->asArray()->all();

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $configValues;

        return $obj;
    }

}
