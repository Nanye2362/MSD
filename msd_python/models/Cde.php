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
		$return = array();
		$is_old_value = Cde::find()->select("to_char(\"sfda_date\",'yyyy-mm-dd HH24:MI:SS') \"sfda_date\", \"old_value\"")->where('"cde"."id"=:id', [':id' => $cdeId])->asArray()->one();
		if($is_old_value['old_value'] == 1){
			$old_data_cfda = Cde::find()->select('"olddata_cfda"."sfda_status", "olddata_cfda"."finishtime"')->where('"cde"."id"=:id', [':id' => $cdeId])->leftJoin('"olddata_cfda"', '"olddata_cfda"."code"="cde"."code"')->asArray()->one();
			$return['olddata_status'] = !empty($old_data_cfda['sfda_status']) ? $old_data_cfda['sfda_status'] : '';
			$return['sfda_date'] = !empty($old_data_cfda['finishtime']) ? $old_data_cfda['finishtime'] : '';
		} elseif ($is_old_value['old_value'] == 0) {
			$return['sfda_date'] = !empty($is_old_value['sfda_date'])?$is_old_value['sfda_date']:'';
		}
		
        $cdeObj = Cde::find()->select("cde.*,cde_sfda_status.name sfda_name,cde_light.*,to_char(\"change_date\",'yyyy-mm-dd') \"change_date\",cde.id cdeId")->where('"cde"."id"=:id', [':id' => $cdeId])->leftJoin('cde_light', '"cde_light"."cde_id"="cde"."id"')->leftJoin('cde_sfda_status', '"cde_sfda_status"."id"="cde"."sfda_status"')->asArray()->all();
        		
        foreach ($cdeObj as $light) {
            $return['id'] = $light['cdeId'];
            $return['code'] = $light['code'];
            $return['name'] = $light['name'];
            $return['company'] = $light['company'];
            $return['status'] = !empty($light['sfda_name']) ? $light['sfda_name'] : '';
			$return['olddata_status'] = !empty($return['olddata_status']) ? $return['olddata_status'] : '';
            $return['status_id'] = $light['sfda_status'];
            $return['lightList'][$light['type']][$light['sub_type']][] = $light['change_date'];
        }
		
        return $return;
    }

    static function getTimelineByCdeId($cdeId) {
        $cdeObj = Cde::find()->select("*")->where('"cde"."id"=:id', [':id' => $cdeId])->leftJoin('cde_timeline', '"cde_timeline"."cde_id"="cde"."id"')->orderBy('"cde_timeline"."start_date" asc')->asArray()->all();
        $return = array();


        foreach ($cdeObj as $timeline) {
            $return['code'] = $timeline['code'];
            $return['name'] = $timeline['name'];
            $return['company'] = $timeline['company'];

            if (empty($timeline['end_date'])) {
                $timeline['end_date'] = date('Y-m-d');
            }
            $days = (strtotime($timeline['end_date']) - strtotime($timeline['start_date'])) / 86400 + 1; //至少一天
            $week = ceil($days / 7 * 100) / 100; //转换周

            $return['timeline'][$timeline['status'] - 1] = $week;
            $return['end_date'][$timeline['status'] - 1] = date("Y/m/d", strtotime($timeline['end_date']));
        }

        return $return;
    }

    static function getList($curPage = 1, $pageSize = 20, $typeId = '', $searchText = '', $uid, $role, $cde_ids = '', $sortName = '', $sortOrder = '', $export = '') {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = Cde::find()->leftJoin('indications_types', '"indications_types"."id"="cde"."indication_id"')->with('rankList')->with('publicremark');



        if (!empty($typeId)) {
            $cdeObj->andWhere('"tid"=:tid', [':tid' => $typeId]);
        }

        if (!empty($cde_ids)) {
			$cdeObj->andWhere('"cde"."id" in('. $cde_ids .')');
        }

        if (!empty($searchText)) {
            if (is_array($searchText)) {
                $cde_name = $searchText[0];
				$cde_used_name = !empty($searchText[1]) ? $searchText[1] : $cde_name;
				$cde_used_name2 = !empty($searchText[2]) ? $searchText[2] : $cde_name;
				$cde_used_name3 = !empty($searchText[3]) ? $searchText[3] : $cde_name;
				$cde_used_name4 = !empty($searchText[4]) ? $searchText[4] : $cde_name;
				$cde_used_name5 = !empty($searchText[5]) ? $searchText[5] : $cde_name;
                $cdeObj->andWhere('"code" like :searchText or UPPER("name") like :cde_name or UPPER("name") like :cde_used_name or UPPER("name") like :cde_used_name2 or UPPER("name") like :cde_used_name3 or UPPER("name") like :cde_used_name4 or UPPER("name") like :cde_used_name5 or UPPER("company") like :searchText or UPPER("clinical_indication") like :searchText', [':cde_name' => '%' . $cde_name . '%', ':cde_used_name' => '%' . $cde_used_name . '%', ':cde_used_name2' => '%' . $cde_used_name2 . '%', ':cde_used_name3' => '%' . $cde_used_name3 . '%', ':cde_used_name4' => '%' . $cde_used_name4 . '%', ':cde_used_name5' => '%' . $cde_used_name5 . '%', ':searchText' => '%' . $searchText[6] . '%']);
            }else{
				$cdeObj->andWhere('"code" like :searchText or UPPER("name") like :searchText or UPPER("company") like :searchText or UPPER("clinical_indication") like :searchText', [':searchText' => '%' . $searchText . '%']);
			}
			
        }

        $num = $cdeObj->count();

        if (!empty($sortName) && !empty($sortOrder)) {
            $cdeObj->orderBy($sortName . ' ' . $sortOrder);
        } else {
            $cdeObj->orderBy('"flag","row_status"');
        }

        $cde = $cdeObj->select(['decode("row_status", 0 , 1 , 0) "flag","cde"."id","code","company",to_char("join_date",\'yyyy-mm-dd\') "join_date","name","rank","rank_status","row_status","sfda_status","indications_types"."chinese_name","indications_types"."ephmra_atc_code","clinical_indication"'])->limit($pageSize)->offset($start)->asArray()->all();

        foreach ($cde as &$one) {
            $clinical_test_links = Cde::find()->select('count(DISTINCT("cde_china_drug_trials"."china_drug_trials_id")) as "clinical_test_link"')->innerJoin('cde_china_drug_trials', '"cde_china_drug_trials"."cde_id" = "cde"."id"')->andWhere('"cde"."id" = :cde_id', [':cde_id' => $one['id']])->asArray()->one();
            $one['clinical_test_link'] = "<a href='/site/page3?code=" . $one['id'] . "'>相关实验链接(" . $clinical_test_links['clinical_test_link'] . ")</a>";

            $showRemark = '';
            $one['remark1'] = '';
            $one['custom_remark'] = '';
            foreach ($one['publicremark'] as $premark) {
                if (!empty($premark['public_remark'])) {
                    $showRemark .= "<p id='refresh_remark_" . $premark['user_id'] . "_" . $one['id'] . "' style='margin-top: 0px;margin-bottom: 0px;word-break: break-word;word-wrap: break-word;'>" . $premark['name'] . ':' . $premark['public_remark'] . "</p>";
                }
                if ($uid == $premark['user_id']) {
                    $one['remark1'] = empty($premark['public_remark']) ? '' : $premark['public_remark'];
                    $one['custom_remark'] = empty($premark['remark']) ? '' : $premark['remark'];
                }

                if (!empty($cde_ids) && !empty($export)) {
                    $one['showremark'] = strip_tags($showRemark);
                } else {
                    $one['showremark'] = $showRemark;
                }
            }

            if (!empty($cde_ids) && !empty($export)) {
				if(!empty($one['rankList'])){
					$rv = $one['rankList'][0];
					$one['rl'] = 'No.' . $rv['rank'] . ' ' . $rv['datetime'];
				}else{
					$one['rl'] = '';
				}
            }

            if (empty($one['ephmra_atc_code'])) {
                $one['ephmra_atc_code'] = '';
            }
            if (empty($one['clinical_indication'])) {
                $one['clinical_indication'] = '';
            }
            if ($one['sfda_status'] == 8) {
                $one['sfda_status'] = "制证完毕－已发批件";
            } else {
                $one['sfda_status'] = '';
            }
            
            $ephmra_atc_codes = explode(',', $one['ephmra_atc_code']);
            foreach ($ephmra_atc_codes as $k => $v) {
                $ephmra_atc_codes[$k] = "<a style='display: inline-block;text-decoration:underline;color:#000;' href='/site/page4?ephmra_atc_code=" . $v . "'>" . $v . "</a>";
                $ephmra_atc_code = implode('<br>', $ephmra_atc_codes);
            }
            if ($export != '1') {
                $one['ephmra_atc_code'] = $ephmra_atc_code;
            }

            unset($one['publicremark']);
        }

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->role = $role;
        $obj->data = $cde;

        return $obj;
    }

    static function getListbyephmra($curPage = 1, $pageSize = 20, $typeId = '', $searchText = '', $uid, $role, $ephmra_atc_code = '', $sortName = '', $sortOrder = '') {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = Cde::find()->leftJoin('indications_types', '"indications_types"."id"="cde"."indication_id"')->with('rankList')->with('publicremark');

        if (!empty($typeId)) {
            $cdeObj->andWhere('"tid"=:tid', [':tid' => $typeId]);
        }

        if (!empty($ephmra_atc_code)) {
            $cdeObj->andWhere('"indications_types"."ephmra_atc_code" like :ephmra_atc_code', [':ephmra_atc_code' => '%' . $ephmra_atc_code . '%']);
        }

        if (!empty($searchText)) {
            if (is_array($searchText)) {
                $cde_name = $searchText[1];
                $cdeObj->andWhere('"code" like :searchText or UPPER("name") like :searchText or UPPER("name") like :cde_name or UPPER("company") like :searchText or UPPER("clinical_indication") like :searchText', [':searchText' => '%' . $searchText[0] . '%', ':cde_name' => '%' . $cde_name . '%']);
            } else {
                $cdeObj->andWhere('"code" like :searchText or UPPER("name") like :searchText or UPPER("company") like :searchText or UPPER("clinical_indication") like :searchText', [':searchText' => '%' . $searchText . '%']);
            }
        }

        $num = $cdeObj->count();

        if (!empty($sortName) && ($sortName != 'end_date' && $sortName != 'total_days')) {
            $cdeObj->orderBy($sortName . ' ' . $sortOrder);
        } elseif (empty($sortName)) {
            $cdeObj->orderBy('"flag","row_status"');
        }

        $cde = $cdeObj->select(['decode("row_status", 0 , 1 , 0) "flag","cde"."id","code","company",to_char("join_date",\'yyyy-mm-dd\') "join_date","name","rank","rank_status","row_status","sfda_status","indications_types"."chinese_name","indications_types"."ephmra_atc_code","clinical_indication"'])->limit($pageSize)->offset($start)->asArray()->all();

        foreach ($cde as &$one) {
            $datas = Cde::find();
            if (!empty($sortName) && ($sortName == 'end_date' || $sortName == 'total_days')) {
                $datas->orderBy($sortName . ' ' . $sortOrder);
            }

            $end_date = $datas->select('"cde"."join_date", "cde_timeline"."end_date", ROUND(TO_NUMBER("cde_timeline"."end_date" - "cde"."join_date")) as "total_days" ')->innerJoin('cde_timeline', '"cde"."id" = "cde_timeline"."cde_id"')->andWhere('"cde"."code" = :cde_code and "cde"."sfda_status" = 8 and "cde_timeline"."status" = 5', [':cde_code' => $one['code']])->asArray()->one();

            $one['end_date'] = $end_date['end_date'];
            if (!empty($one['end_date'])) {
                $one['total_days'] = $end_date['total_days'];
            } else {
                $one['total_days'] = '';
            }

            $clinical_test_links = Cde::find()->select('count(DISTINCT("cde_china_drug_trials"."china_drug_trials_id")) as "clinical_test_link"')->innerJoin('cde_china_drug_trials', '"cde_china_drug_trials"."cde_id" = "cde"."id"')->andWhere('"cde"."id" = :cde_id', [':cde_id' => $one['id']])->asArray()->one();
            $one['clinical_test_link'] = "<a href='/site/page3?code=" . $one['id'] . "'>相关实验链接(" . $clinical_test_links['clinical_test_link'] . ")</a>";

            $showRemark = '';
            $one['remark1'] = '';
            $one['custom_remark'] = '';
            foreach ($one['publicremark'] as $premark) {
                if (!empty($premark['public_remark'])) {
                    $showRemark .= "<p id='refresh_remark_" . $premark['user_id'] . "_" . $one['id'] . "' style='margin-top: 0px;margin-bottom: 0px;word-break: break-word;word-wrap: break-word;'>" . $premark['name'] . ':' . $premark['public_remark'] . "</p>";
                }
                if ($uid == $premark['user_id']) {
                    $one['remark1'] = empty($premark['public_remark']) ? '' : $premark['public_remark'];
                    $one['custom_remark'] = empty($premark['remark']) ? '' : $premark['remark'];
                }

                if (!empty($cde_ids)) {
                    $one['showremark'] = strip_tags($showRemark);
                } else {
                    $one['showremark'] = $showRemark;
                }
            }

            if (empty($one['ephmra_atc_code'])) {
                $one['ephmra_atc_code'] = '';
            }
            if (empty($one['clinical_indication'])) {
                $one['clinical_indication'] = '';
            }
            if ($one['sfda_status'] == 8) {
                $one['sfda_status'] = "制证完毕－已发批件";
            } else {
                $one['sfda_status'] = '';
            }

            unset($one['publicremark']);
        }

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->role = $role;
        $obj->data = $cde;

        return $obj;
    }

    /**
     * @return \yii\db\ActiveQuery
     *  获取cde关联的cdeType数据
     */
    public function getRankList() {
        return $this->hasMany(CdeRankList::className(), ['cde_id' => 'id'])->select(['"id","cde_id","rank",to_char("datetime",\'yyyy-mm-dd\') "datetime"'])->orderBy('rank asc');
    }

    /**
     * @return \yii\db\ActiveQuery
     *  获取cde关联的cdeType数据
     */
    public function getPublicremark() {
        return $this->hasMany(CdePublicremark::className(), ['cde_id' => 'id'])->select("cde_publicremark.*,user.name")->leftJoin("user", '"user"."id"="cde_publicremark"."user_id"')->orderBy('create_date desc');
    }

}
