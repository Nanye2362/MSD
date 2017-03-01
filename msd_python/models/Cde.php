<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cde".
 *
 * @property string $id
 * @property string $tid
 * @property integer $rank
 * @property string $code
 * @property string $name
 * @property string $join_date
 * @property string $review_status
 * @property integer $pharmacology_status
 * @property integer $clinical_status
 * @property integer $pharmacy_status
 * @property string $remark
 * @property string $create_date
 * @property string $company
 * @property integer $row_status
 * @property string $custom_remark
 * @property integer $sfda_status
 * @property integer $rank_status
 * @property string $rank_status_date
 * @property string $row_status_date
 * @property string $is_out
 * @property integer $indication_id
 * @property string $clinical_indication
 */
class Cde extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'cde';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['tid', 'code', 'name', 'review_status', 'remark', 'custom_remark'], 'required'],
                [['tid', 'rank', 'pharmacology_status', 'clinical_status', 'pharmacy_status', 'row_status', 'sfda_status', 'rank_status', 'indication_id'], 'integer'],
                [['join_date', 'create_date', 'rank_status_date', 'row_status_date'], 'safe'],
                [['code'], 'string', 'max' => 50],
                [['name', 'remark', 'company', 'custom_remark', 'clinical_indication'], 'string', 'max' => 500],
                [['review_status'], 'string', 'max' => 100],
                [['is_out'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'tid' => 'Tid',
            'rank' => 'Rank',
            'code' => 'Code',
            'name' => 'Name',
            'join_date' => 'Join Date',
            'review_status' => 'Review Status',
            'pharmacology_status' => 'Pharmacology Status',
            'clinical_status' => 'Clinical Status',
            'pharmacy_status' => 'Pharmacy Status',
            'remark' => 'Remark',
            'create_date' => 'Create Date',
            'company' => 'Company',
            'row_status' => 'Row Status',
            'custom_remark' => 'Custom Remark',
            'sfda_status' => 'Sfda Status',
            'rank_status' => 'Rank Status',
            'rank_status_date' => 'Rank Status Date',
            'row_status_date' => 'Row Status Date',
            'is_out' => 'Is Out',
            'indication_id' => 'Indication ID',
            'clinical_indication' => 'Clinical Indication',
        ];
    }

    static function getDetailByCdeId($cdeId) {
        $cdeObj = Cde::find()->select("cde.*,cde_sfda_status.name sfda_name,cde_light.*,cde.id cdeId")->where('cde.id=:id', [':id' => $cdeId])->leftJoin('cde_light', 'cde_light.cde_id=cde.id')->leftJoin('cde_sfda_status', 'cde_sfda_status.id=cde.sfda_status')->asArray()->all();
        $return = array();

        foreach ($cdeObj as $light) {
            $return['id'] = $light['cdeId'];
            $return['code'] = $light['code'];
            $return['name'] = $light['name'];
            $return['company'] = $light['company'];
            $return['status'] = $light['sfda_name'];
            $return['status_id'] = $light['sfda_status'];
            $return['lightList'][$light['type'] - 1][$light['sub_type'] - 1][] = $light['change_date'];
        }

        return $return;
    }

    static function getTimelineByCdeId($cdeId) {
        $cdeObj = Cde::find()->select("*")->where('cde.id=:id', [':id' => $cdeId])->leftJoin('cde_timeline', 'cde_timeline.cde_id=cde.id')->orderBy("cde_timeline.start_date asc")->asArray()->all();
        $return = array();

        foreach ($cdeObj as $timeline) {
            $return['code'] = $timeline['code'];
            if ($timeline['end_date'] == '0000-00-00') {
                $timeline['end_date'] = date('Y-m-d');
            }
            $days = (strtotime($timeline['end_date']) - strtotime($timeline['start_date'])) / 86400 + 1; //至少一天
            $week = ceil($days / 7 * 100) / 100; //转换周

            $return['timeline'][$timeline['status'] - 1] = $week;
            $return['end_date'][$timeline['status'] - 1] = date("Y/m/d", strtotime($timeline['end_date']));
        }

        return $return;
    }

    static function getList($curPage, $pageSize, $typeId, $serachText, $uid) {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = Cde::find()->leftJoin('indications_types', 'indications_types.id=cde.indication_id')->with('rankList')->with('publicremark');

        if (!empty($typeId)) {
            $cdeObj->andWhere('tid=:tid', [':tid' => $typeId]);
        }

        if (!empty($serachText)) {
            $cdeObj->andWhere("code like :serachText or name like :serachText or company like :serachText", [':serachText' => '%' . $serachText . '%']);
        }

        $num = $cdeObj->count();

        $cde = $cdeObj->select('cde.id,code,company,join_date,name,rank,rank_status,row_status,sfda_status,indications_types.ephmra_atc_code,clinical_indication')->orderBy('`row_status`!=0 DESC, row_status')->limit($pageSize)->offset($start)->asArray()->all();


        foreach ($cde as &$one) {
            $showRemark = '';
            $one['remark1'] = '';
            $one['custom_remark'] = '';
            foreach ($one['publicremark'] as $premark) {
                if (!empty($premark['public_remark'])) {
                    $showRemark .= $premark['uid'] . ':' . $premark['public_remark'] . "<br/>";
                }
                if ($uid == $premark['uid']) {
                    $one['remark1'] = $premark['public_remark'];
                    $one['custom_remark'] = $premark['remark'];
                }
            }
            $one['showremark'] = $showRemark;
            if (empty($one['ephmra_atc_code'])) {
                $one['ephmra_atc_code'] = '';
            }
            if (empty($one['clinical_indication'])) {
                $one['clinical_indication'] = '';
            }
            if ($one['sfda_status'] == 8) {
                $one['sfda_status'] = "<a href='site/page3?code=" . $one['id'] . "'>制证完毕－已发批件<img style='width:14px;height:14px;margin-left:5px;line-height:12px;vertical-align:middle;' src='/images/Diploma.png'></a>";
            } else {
                $one['sfda_status'] = '';
            }


            unset($one['publicremark']);
        }

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $cde;

        return $obj;
    }

    /**
     * @return \yii\db\ActiveQuery
     *  获取cde关联的cdeType数据
     */
    public function getRankList() {
        return $this->hasMany(CdeRankList::className(), ['cde_id' => 'id'])->orderBy('rank asc');
    }

    /**
     * @return \yii\db\ActiveQuery
     *  获取cde关联的cdeType数据
     */
    public function getPublicremark() {
        return $this->hasMany(CdePublicremark::className(), ['cde_id' => 'id'])->orderBy('create_date desc');
    }

}
