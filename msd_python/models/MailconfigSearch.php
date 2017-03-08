<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Indicationstypes;

/**
 * Description of MailconfigSearch
 *
 * @author ctsuser
 */
class MailconfigSearch extends Mailconfig{
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id'], 'integer'],
                [['name', 'value', 'note'], 'safe'],
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

        $MailconfigObj = Mailconfig::find();
        
        if (!empty($searchText)) {
            $MailconfigObj->andWhere("name like :searchText or value like :searchText or note like :searchText", [':searchText' => '%' . $searchText . '%']);
        }

        $num = $MailconfigObj->count();
        
        $MailconfigValues = $MailconfigObj->orderBy('`id` desc')->limit($pageSize)->offset($start)->asArray()->all();        

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $MailconfigValues;

        return $obj;
    }
}
