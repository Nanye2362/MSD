<?php

/* @var $this yii\web\View */
use \app\assets\PrivilegeAsset;
PrivilegeAsset::addCss($this,'@web/css/privilege/list.css');
$this->title = '特权中心';
?>
<div class="main_content">
        <div><span class="mc_title">活动管理中心</span><a class="mc_add" href="/aia_demo/web/privilege/create1">+创建活动链接</a> <a class="mc_add" href="/aia_demo/web/privilege/create">+创建活动</a><div class="clear"></div></div>
        <div><span class="t_left">活动列表</span><span class="t_right">最近修改时间</span><div class="clear"></div></div>
        <table class="table">
                <?php foreach($list as $row):?>
                <tr>
                        <td style="width:80px"><?=($row['status']==1)?"线上":"已下线";?></td>
                        <td><?=$row['id'];?></td>
                        <td><?=$row['title'];?></td>
                        <td><span class=" btn btn-link"><a href="/aia_demo/web/privilege/create?id=<?=$row['id'];?>">修改编辑</a></span></td>
                        <td><span class=" btn btn-link" lang="<?=$row['status']?>"><a href="/aia_demo/web/privilege/change?id=<?=$row['id'];?>&status=<?php echo $row['status'];?>"><?=($row['status']==1)?"暂停发布":"发布";?></a></span></td>
                        <td><span class=" btn btn-link">统计报表</span></td>
                        <td style="width:70px"><?=$row['edit_date'];?></td>
                </tr>
                <?php endforeach;?>
        </table>
</div>