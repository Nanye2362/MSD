<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="../css/index4.css"/>
</head>
<body>
<?php include "menu.php";?>
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
        <ul>
            <li class="nth_1">The field of ‘Certificate</li>
            <li class="nth_2">Thield of ‘Certificate</li>
            <li class="nth_3">The field o</li>
            <li class="nth_4">Thf ‘Certificate</li>
            <li class="nth_5">The fieldertificate</li>
        </ul>
    </div><div class="cont_b hk">
        <ul class="ul_one">
            <li>
                <!--                    	<div class="date date_a">2017-01-01</div>
                                        <div class="xuxian xian_a"></div>-->
                <ul class="ul_1 nth_1">

                </ul>
            </li>
            <li>
                <!--                    	<div class="date date_b">2017-01-01</div>
                                        <div class="xuxian xian_b"></div>-->
                <ul class="ul_1 nth_2">

                </ul>
            </li>
            <li>
                <!--                    	<div class="date date_c">2017-01-01</div>
                                        <div class="xuxian xian_c"></div>-->
                <ul class="ul_1 nth_3">

                </ul>
            </li>
            <li>
                <!--                    	<div class="date date_d">2017-01-01</div>
                                        <div class="xuxian xian_d"></div>-->
                <ul class="ul_1 nth_4">

                </ul>
            </li>
            <li>
                <!--                    	<div class="date date_e">2017-01-01</div>
                                        <div class="xuxian xian_e"></div>-->
                <ul class="ul_1 nth_5">

                </ul>
            </li>
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
                        var totalWeek = 0;//总周数
                        var yuweek = 0;
                        console.log(data.data.timeline);
                        console.log(data.data.end_date);

                        for (var i in data.data.timeline) {
                            console.log(i);
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
                                var divwidth = parseInt(totalWeek) * 38;
                            } else {
                                var divwidth = parseInt(totalWeek) * 38 + 5 + (totalWeek - parseInt(totalWeek)) * 28;
                            }

                            var enddate = data.data.end_date[i];
                            if (i == 0) {
                                $('.ul_one').append("<div class='fxuxian' style='position: absolute;top: 0;left: 0;height: 100%;border-right: 1px dotted #ccc;'></div>");
                                $('.ul_one').append("<div class='fdate' style='position: absolute;top: -1px;width: 50px;padding-top: 3px;'>" + enddate + "</div>");
                                $('.fxuxian').css('width', divwidth);
                                $('.fdate').css('left', divwidth);
                            } else if (i == 1) {
                                $('.ul_one').append("<div class='sxuxian' style='position: absolute;top: 0;left: 0;height: 100%;border-right: 1px dotted #ccc;'></div>");
                                $('.ul_one').append("<div class='sdate' style='position: absolute;top: -1px;width: 50px;padding-top: 3px;'>" + enddate + "</div>");
                                $('.sxuxian').css('width', divwidth);
                                $('.sdate').css('left', divwidth);
                            } else if (i == 2) {
                                $('.ul_one').append("<div class='txuxian' style='position: absolute;top: 0;left: 0;height: 100%;border-right: 1px dotted #ccc;'></div>");
                                $('.ul_one').append("<div class='tdate' style='position: absolute;top: -1px;width: 50px;padding-top: 3px;'>" + enddate + "</div>");
                                $('.txuxian').css('width', divwidth);
                                $('.tdate').css('left', divwidth);
                            } else if (i == 3) {
                                $('.ul_one').append("<div class='foxuxian' style='position: absolute;top: 0;left: 0;height: 100%;border-right: 1px dotted #ccc;'></div>");
                                $('.ul_one').append("<div class='fodate' style='position: absolute;top: -1px;width: 50px;padding-top: 3px;'>" + enddate + "</div>");
                                $('.foxuxian').css('width', divwidth);
                                $('.fodate').css('left', divwidth);
                            } else if (i == 4) {
                                $('.ul_one').append("<div class='fixuxian' style='position: absolute;top: 0;left: 0;height: 100%;border-right: 1px dotted #ccc;'></div>");
                                $('.ul_one').append("<div class='fidate' style='position: absolute;top: -1px;width: 50px;padding-top: 3px;'>" + enddate + "</div>");
                                $('.fixuxian').css('width', divwidth);
                                $('.fidate').css('left', divwidth);
                            }

                        }


                        totalWeek = parseInt(totalWeek + 0.99);

                        for (var i = 1; i <= totalWeek; i++) {
                            $('.ul_c').append("<li><span>" + i + "</span>周</li>");
                        }

                        $('.ul_1,.ul_c').width(totalWeek * 38);

                    })
                }
            })
</script>
</body>
</html>