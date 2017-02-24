<?php

/* @var $this yii\web\View */
use yii\jui\DatePicker;
use app\assets\PrivilegeAsset;
use yii\widgets\ActiveForm;
use app\assets\KindeditorAsset;
use yii\helpers\Html;

PrivilegeAsset::addCss($this,'@web/css/privilege/detail.css');
PrivilegeAsset::addScript($this,'@web/js/privilege/detail.js');
KindeditorAsset::register($this);
$this->title = '特权中心';
?>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="main_content">
    <input type="hidden" name="id" value="<?=$activity->id;?>"/>
    <div><span class="mc_title">主页内容编辑</span></div>
    <div><span class="label_c">活动标题:</span><span class="input"> <input  type="text" name="title" value="<?=$activity->title;?>"/></span></div>
    <div><span class="label_c">超链接:</span><span class="input"><input  type="text" value="<?=$activity->url;?>" name="url"/></span></div>
    <div><span class="label_c">封面图片:</span><span class="input"><input type="file"  accept = "image/*" name="img" /></span></div>
    <div><span class="label_c">正文:</span><span class="input"><textarea class="textarea" name="content1"><?=$activity->content;?></textarea></span></div>

    <div><span class="mc_title">发布设置</span></div>
    <div>
        <span class="span_left"><span class="label_c">发布位置:</span><span class="input"><select name="where"><option>我的特权广告位</option></select></span></span>
        <span class="span_left">
            <span class="label_c">发布时间设置:</span><span class="input">
                <div class="input_div"><span>开始时间 </span><?= DatePicker::widget(['name' => 'start']) ?></div>
                <div class="input_div"><span>结束时间 </span><?= DatePicker::widget(['name' => 'end']) ?></div>
            </span>
        </span>
    </div>

    <div><span class="mc_title">接收用户设置</span></div>

    <div>
        <span class="span_left"><span class="label_c">发布对象:</span>
            <span class="input">
                <select name="where">
                    <option>对象选择</option>
                </select>
            </span>
        </span>
    </div>
    <div>
        <span class="label_c">对象选择:</span>
        <span class="panel_table">
        <table class="table_c">
            <tr width="100%">
                <th width="20%"><input type="checkbox" lang="1"> VIP</th>
                <th width="20%"><input type="checkbox" lang="2"> AIAS卡</th>
                <th width="20%"><input type="checkbox" lang="3"> 渠道</th>
                <th width="20%"><input type="checkbox" lang="4"> 分公司</th>
                <th width="20%"><input type="checkbox" lang="5"> 家庭结构</th>
            </tr>
            <tr>
                <td class="td1 td">
                    <div><input type="checkbox" name="td1[]" value="金卡"> 金卡</div>
                    <div><input type="checkbox" name="td1[]" value="白金"> 白金</div>
                    <div><input type="checkbox" name="td1[]" value="钻石"> 钻石</div>
                    <input class="json" type="hidden" value="<?php echo htmlspecialchars(json_encode($arr['vip'])); ?>"/>
                </td>
                <td class="td2 td">
                    <div><input type="checkbox" name="td2[]" value="旧卡"> 旧卡</div>
                    <div><input type="checkbox" name="td2[]" value="新卡"> 新卡</div>
                    <input class="json" type="hidden" value="<?php echo htmlspecialchars(json_encode($arr['aias'])); ?>"/>
                </td>
                <td class="td3 td">
                    <div><input type="checkbox" name="td3[]" value="AGY"> AGY</div>
                    <div><input type="checkbox" name="td3[]" value="BANC非花旗"> BANC非花旗</div>
                    <div><input type="checkbox" name="td3[]" value="BANC花旗"> BANC花旗</div>
                    <div><input type="checkbox" name="td3[]" value="DM"> DM</div>
                    <div><input type="checkbox" name="td3[]" value="E-biz"> E-biz</div>
                    <input class="json" type="hidden" value="<?php echo htmlspecialchars(json_encode($arr['qudao'])); ?>"/>
                </td>
                <td class="td4 td">
                    <div><input type="checkbox" name="td4[]" value="上海"> 上海</div>
                    <div><input type="checkbox" name="td4[]" value="北京"> 北京</div>
                    <div><input type="checkbox" name="td4[]" value="江苏"> 江苏</div>
                    <div><input type="checkbox" name="td4[]" value="广东"> 广东</div>
                    <input class="json" type="hidden" value="<?php echo htmlspecialchars(json_encode($arr['fengongsi'])); ?>"/>
                </td>
                <td class="td5 td">
                    <div><input type="checkbox" name="td5[]" value="单身"> 单身</div>
                    <div><input type="checkbox" name="td5[]" value="已婚已育"> 已婚已育</div>
                    <div><input type="checkbox" name="td5[]" value="已婚未育"> 已婚未育</div>
                    <input class="json" type="hidden" value="<?php echo htmlspecialchars(json_encode($arr['jiatingjiegou'])); ?>"/>
                </td>
            </tr>
        </table>
        </span>
    </div>

    <input type="submit" name="button" value="保存" />
</div>


<?php ActiveForm::end(); ?>