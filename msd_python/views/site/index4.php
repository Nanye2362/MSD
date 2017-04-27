<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>注册进度时间轴</title>
        <link rel="stylesheet" type="text/css" href="../css/reset.css"/>
        <link rel="stylesheet" type="text/css" href="../css/index4.css"/>
    </head>
    <body>
        <?php include "menu.php"; ?>
        <div id="head">
            <div class="clearfloat head">
                <div class="fl">
                    <span>注射用区拖住单抗</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>fieldof‘Certificat</span>
                </div>
                <div class="fr" style="font-size:14px ;">
                    上海罗氏制药有限公司
                </div>
            </div>
        </div>
        <div id="cont">
            <div class="cont_a hk">
                <ul><li class="nth_0"></li></ul>
                <ul id="tagposition">
                    <li id="cont_a_nth_1" class="nth_1">进入CDE中心</li>
                    <li id="cont_a_nth_2" class="nth_2">CDE开始审评</li>
                    <li id="cont_a_nth_3" class="nth_3">CDE审评完成</li>
                    <li id="cont_a_nth_4" class="nth_4">CFDA审批</li>
                    <li id="cont_a_nth_5" class="nth_5">临床试验批证</li>
                </ul>
            </div><div class="cont_b hk">
                <ul class="ul_one">
                    <li>
                        <ul class="ul_0 nth_0"></ul>
                    </li>
                    <div id="timelineposition">
                        <li id="timelineposition_1"><ul class="ul_1 nth_1"></ul></li>
                        <li id="timelineposition_2"><ul class="ul_1 nth_2"></ul></li>
                        <li id="timelineposition_3"><ul class="ul_1 nth_3"></ul></li>
                        <li id="timelineposition_4"><ul class="ul_1 nth_4"></ul></li>
                        <li id="timelineposition_5"><ul class="ul_1 nth_5"></ul></li>                        
                    </div>
                    <li>
                        <ul class="ul_c">

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <script src="../js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/setting.js" type="text/javascript" charset="utf-8"></script>


        <script type="text/javascript">
            function GetQueryString(name)
            {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if (r != null)
                    return  unescape(r[2]);
                return null;
            }


            $(function () {
                var code = GetQueryString('code');
                if (code.length > 0) {
                    $.post(host + 'python/gettimeline', {'cdeId': code}, function (data) {
                        if (data.data.timeline[-1] == undefined) {
                            var totalWeek = 0;//总周数
                            var yuweek = 0;
                            console.log(data.data.timeline);

                            var li_num = new Array;

                            for (var i in data.data.timeline) {
                                li_num.push(i);
                                //填充空白展位
                                for (var j = 0; j < parseInt(totalWeek); j++) {
                                    $('.ul_1').eq(i).append("<li></li>");
                                }

                                //填充前部分余数
                                var curWeek = data.data.timeline[i];
                                if (yuweek != 0) {
                                    var li_span = $("<li class='show'></li>");
                                    var span_start = $("<span class='span_start'></span>");
                                    span_start.width(yuweek + "%");
                                    li_span.append(span_start);
                                    $('.ul_1').eq(i).append(li_span);
                                    curWeek = curWeek - (1 - yuweek / 100);
                                }
                                console.log(curWeek);

                                //填充整数
                                var manweek = parseInt(curWeek);
                                console.log(manweek);
                                for (var k = 0; k < manweek; k++) {
                                    $('.ul_1').eq(i).append("<li class='show'></li>");
                                }


                                //填充后部分余数
                                yuweek = curWeek * 100 % 100;
                                console.log(yuweek);
                                if (yuweek != 0) {
                                    var span_width = 0;
                                    if (yuweek > 0) {
                                        var li_span = $("<li class='show'></li>");
                                        span_width = 100 - yuweek; //只有尾情况
                                    } else {
                                        span_width = yuweek * -1; //有头和尾情况
                                        yuweek = 100 - (yuweek * -1);
                                    }
                                    var span_end = $("<span class='span_end'></span>");
                                    span_end.width(span_width + "%");
                                    li_span.append(span_end);
                                    $('.ul_1').eq(i).append(li_span);
                                }

                                totalWeek += data.data.timeline[i];

                                //添加虚线和日期
                                if ((totalWeek - parseInt(totalWeek)) == 0) {
                                    var divwidth = parseInt(totalWeek) * 75;
                                } else {
                                    var divwidth = parseInt(totalWeek) * 75 + 5 + (totalWeek - parseInt(totalWeek)) * 65;
                                }

                                var enddate = data.data.end_date[i];
                                if (i == 0) {
                                    $('.ul_one').append("<div class='xuxian1' style='position: absolute;top: 52px;left: 0;border-right: 1px dotted rgb(138, 131, 131);'></div>");
                                    $('.ul_one').append("<div class='fdate' style='position: absolute;top: 40px;width: 56px;'>" + enddate + "</div>");
                                    $('.xuxian1').css('width', divwidth);
                                    $('.fdate').css('left', divwidth - 29);
                                } else if (i == 1) {
                                    $('.ul_one').append("<div class='xuxian2' style='position: absolute;top: 52px;left: 0;border-right: 1px dotted rgb(138, 131, 131);'></div>");
                                    $('.ul_one').append("<div class='sdate' style='position: absolute;top: 30px;width: 56px;'>" + enddate + "</div>");
                                    $('.xuxian2').css('width', divwidth);
                                    $('.sdate').css('left', divwidth - 29);
                                } else if (i == 2) {
                                    $('.ul_one').append("<div class='xuxian3' style='position: absolute;top: 52px;left: 0;border-right: 1px dotted rgb(138, 131, 131);'></div>");
                                    $('.ul_one').append("<div class='tdate' style='position: absolute;top: 20px;width: 56px;'>" + enddate + "</div>");
                                    $('.xuxian3').css('width', divwidth);
                                    $('.tdate').css('left', divwidth - 29);
                                } else if (i == 3) {
                                    $('.ul_one').append("<div class='xuxian4' style='position: absolute;top: 52px;left: 0;border-right: 1px dotted rgb(138, 131, 131);'></div>");
                                    $('.ul_one').append("<div class='fodate' style='position: absolute;top: 10px;width: 56px;'>" + enddate + "</div>");
                                    $('.xuxian4').css('width', divwidth);
                                    $('.fodate').css('left', divwidth - 29);
                                } else if (i == 4) {
                                    $('.ul_one').append("<div class='xuxian5' style='position: absolute;top: 52px;left: 0;border-right: 1px dotted rgb(138, 131, 131);'></div>");
                                    $('.ul_one').append("<div class='fidate' style='position: absolute;top: 0px;width: 56px;'>" + enddate + "</div>");
                                    $('.xuxian5').css('width', divwidth);
                                    $('.fidate').css('left', divwidth - 29);
                                }
//                            for (var l = 0; l < $('.span_start').length; l++) {
//                                $('.span_start').parent('li').eq(l).css('margin-left', 15 + 10 * l);
//                            }

                            }
                            console.log(divwidth);
                            var all_li = ['0', '1', '2', '3', '4'];
                            Array.prototype.diff = function(a) {
                                return this.filter(function(i) {return a.indexOf(i) < 0;});
                            };
                            var diff = all_li.diff(li_num);
                            console.log(diff);
                            //var diff = li_num.concat(all_li).filter(v => !li_num.includes(v) || !all_li.includes(v));//取差集
                            for (var dv = 0; dv < diff.length; dv++) {
                                var num = parseInt(diff[dv]) + 1;
                                var id1 = '#cont_a_nth_' + num;
                                var id2 = '#timelineposition_' + num;
                                $(id1).remove();
                                $(id2).remove();
                            }

                            for (var k in li_num) {
                                var xuxianclass = '.xuxian' + (parseInt(li_num[k]) + 1);
                                console.log(li_num);
                                var height = 67 * li_num.length + 15;//li_num.length行数 67行高 15xuxian div top
                                $(xuxianclass).css('height', height);
                            }

                            totalWeek = parseInt(totalWeek + 0.99);

                            for (var i = 1; i <= totalWeek; i++) {
                                $('.ul_c').append("<li><span>" + i + "</span>周</li>");
                            }

                            $('.ul_1,.ul_c').width(totalWeek * 75 + 50);
                        }


                    })
                }
            })
        </script>
    </body>
</html>
