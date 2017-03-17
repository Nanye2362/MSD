<?php use app\models\user;?>
<div>
<ul id="nav">
        <li><a href="/">首页</a></li>
        <?php if(user::$currUser->role==1):?>
		    <li><a href="/site/config">爬虫配置</a></li>
		    <li><a href="/site/mailconfig">邮箱配置</a></li>
		    <li><a href="/site/usednameconfig">别名配置</a></li>
		    <li><a href="/site/indicationstypes">适应症类配置</a></li>
        <?php endif;?>
                    <li><a href="/site/myfavorite">个人收藏</a></li>
		    <li><a href="/site/sendemail">发送邮件</a></li>
</ul>
</div>
    <style type="text/css">
		#nav{
			height:50px;
			background-color:#428bca;
		}
		
		#nav li{
			display:inline-block;
			width:150px;
			color:#fff;
			font-weight:bold;
			text-align:center;
			font-size:14px;
			line-height:50px;
		}
		
		#nav li a{
			color:#fff;
			text-decoration:none;
		}
    </style>
