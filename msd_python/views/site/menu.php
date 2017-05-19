<?php

use app\models\user; ?>
<link rel="stylesheet" type="text/css" href="../css/search.css">
<link rel="stylesheet" type="text/css" href="../css/corev4.css">

<div id="s4-ribbonrow" class="s4-pr s4-ribbonrowhidetitle" style="height: 44px;">
    <div id="s4-ribboncont">
        <div id="RibbonContainer">
            <div class="ms-cui-ribbon" id="Ribbon" unselectable="on" aria-describedby="ribboninstructions" oncontextmenu="return false" role="toolbar">
                <div class="ms-cui-ribbonTopBars" unselectable="on">
                    <div class="ms-cui-topBar2" unselectable="on"><div class="ms-cui-jewel-container" id="jewelcontainer" unselectable="on" style="display:none"></div><div class="ms-cui-TabRowRight s4-trc-container s4-notdlg" id="RibbonContainer-TabRowRight" unselectable="on" style="display: block;">
                            <a href="http://teamspace.merck.com/sites/china_kdkm/default.aspx#" tabindex="-1" style="display:none"></a><a href="http://teamspace.merck.com/sites/china_kdkm/default.aspx#" tabindex="-1" style="display:none"></a>
                            <div class="s4-trc-container-menu">
                                <div>
                                    <span id="zz16_Menu_t" class="ms-SPLink ms-SpLinkButtonInActive ms-welcomeMenu" title="Open Menu"  style="white-space:nowrap"><span style="color:#fff;"><?php echo Yii::$app->session['user_name']; ?></span></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</div>

<div id="s4-workspace">
    <div class="s4-title s4-lp">
        <div class="s4-title-inner">
            <table class="s4-titletable" cellspacing="0">
                <tbody>
                    <tr>
                        <td class="s4-titletext"></td>
                        <td>
                            <input type="image" name="ctl00$ctl38$ctl00" title="Sensitive and confidential content cannot be stored on this site" src="/images/english_sensitivity2.jpg" style="border-width:0px;">
                        </td>
                        <td class="s4-socialdata-notif"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="s4-topheader2" class="s4-pr s4-notdlg">
        <a name="startNavigation"></a>
        
        <div class="s4-rp s4-app">
        </div>
        <div class="s4-lp s4-toplinks">
            <h2 class="ms-hidden">Top Link Bar</h2>
            <div id="zz17_TopNavigationMenuV4" class="s4-tn">
                <div class="menu horizontal menu-horizontal">
                    <ul class="root static">
                        <li class="static">
                            <a class="static <?php if ($title == 'index') echo "selected"; ?>  menu-item" href="/" accesskey="1">	
                                <span class="additional-background">
                                    <span class="menu-item-text">首页</span>
                                </span>
                            </a>
                        </li>
                        <?php if (!empty(user::$currUser) && user::$currUser->role == 1): ?>
                            <li class="static"><a class="static <?php if ($title == 'config') echo "selected"; ?> menu-item" href="/site/config"><span class="additional-background"><span class="menu-item-text">爬虫配置</span></span></a></li>
                            <li class="static"><a class="static <?php if ($title == 'mailconfig') echo "selected"; ?> menu-item" href="/site/mailconfig"><span class="additional-background"><span class="menu-item-text">邮箱配置</span></span></a></li>
                            <li class="static"><a class="static <?php if ($title == 'usednameconfig') echo "selected"; ?> menu-item" href="/site/usednameconfig"><span class="additional-background"><span class="menu-item-text">别名配置</span></span></a></li>
                            <li class="static"><a class="static <?php if ($title == 'indicationstypes') echo "selected"; ?> menu-item" href="/site/indicationstypes"><span class="additional-background"><span class="menu-item-text">适应症类配置</span></span></a></li>
                        <?php endif; ?>
                        <li class="static"><a class="static <?php if ($title == 'myfavorite') echo "selected"; ?> menu-item" href="/site/myfavorite"><span class="additional-background"><span class="menu-item-text">个人收藏</span></span></a></li>
                        <li class="static"><a class="static <?php if ($title == 'sendemail') echo "selected"; ?> menu-item" href="/site/sendemail"><span class="additional-background"><span class="menu-item-text">发送邮件</span></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>