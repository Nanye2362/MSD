<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>注册进度</title>
        <style type="text/css">
            table {
                width: 98%;
                margin-right: 5px;
                border-collapse: collapse;
                padding: 3px;
                border: solid 1px #ccc;
                text-align: center;
                color: #333;
                line-height: 1.8em;
                font-size: 13px;
                font-family: '微软雅黑', Verdana, sans-serif, '宋体', serif;
            }

            img{
                vertical-align:middle;
            }
            .datetime{
                vertical-align:middle;
                display:inline-block;
                padding-left:5px;
            }
        </style>

    </head>

    <body>
        <?php include "menu.php"; ?>
		<div style="padding-top:15px;">
			<table style="display:none" border="1px">
				<tr style="background-color:lavender;">
					<th width="10%">受理号</th>
					<th width="15%">药品名称</th>
					<th width="15%">企业名称</th>
					<th width="15%">药理毒理</th>
					<th width="15%">临床</th>
					<th width="15%">药学</th>
					<th width="15%">证书批准状态</th>
				</tr>
				<tr class="lie">
					<td class="tg-yw4l" rowspan="3">
						<span class="code"></span><br/><br/>
						<input class="btn" type="button" value="注册进度时间轴" style="font-size: 1.0rem; background-color: powderblue;height: 2.1rem;">
					</td>
					<td rowspan="3"><span class="name"></span></td>
					<td rowspan="3"><span class="company"></span></td>
					<td class="row0"></td>
					<td class="row1"></td>
					<td class="row2"></td>
					<td rowspan="3" class="status"></td>
				</tr>
				<tr class="lie">
					<td class="row0"><img src="../images/green.jpg"></td>
					<td class="row1"><img src="../images/green.jpg"></td>
					<td class="row2"><img src="../images/green.jpg"></td>
				</tr>
				<tr class="lie">
					<td class="row0"><img src="../images/yellow.jpg"></td>
					<td class="row1"><img src="../images/yellow.jpg"></td>
					<td class="row2"><img src="../images/yellow.jpg"></td>
				</tr>
			</table>
		</div>
        <br><br>
        <div class="note" style="margin: auto;text-align: center;">
            <img src="../images/green.jpg">本专业正在审评&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img src="../images/yellow.jpg">本专业排队待审评&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img src="../images/gray.jpg">本专业已完成审评
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
                    $.post(host + 'python/getdetail', {'cdeId': code}, function (data) {
                        $('.code').html(data.data.code);
                        $('.name').html(data.data.name);
                        $('.company').html(data.data.company);
						console.log(data);
                        if (data.data.status_id == 8) {
                            $('.status').html('<span style="vertical-align:middle">' + data.data.status + '</span>' + '<a href="page3?code=' + data.data.id + '"><img style="width:14px;height:14px;margin-left:5px;line-height:12px;vertical-align:middle;" src="<?php echo Yii::$app->request->baseUrl; ?>/images/Diploma.png"/></a>' + '<br>' + data.data.sfda_date);
                        } else {
							console.log(data.data.status);
							console.log(data.data.olddata_status);
							console.log(data.data.sfda_date);
							if(data.data.status != ''){
								$('.status').html(data.data.status + '<br>' + data.data.sfda_date);
							}else if(data.data.olddata_status != ''){
								$('.status').html(data.data.olddata_status + '<br>' + data.data.sfda_date);
							}
                            
                        }
						console.log(data.data.lightList);
                        for (var i in data.data.lightList) {
							console.log('i:'+i);
                            for (var j in data.data.lightList[i]) {
								console.log('j:'+j);
                                /*
								if (j == 0) {
                                    var k = parseInt(j + 1);
									console.log(k);
                                    var t = k - 2;
                                    if (t < 0) {
                                        t = t * -1;
                                    }
									console.log('j==0,t:'+t);
                                } else {
                                    var t = j - 2;
                                    if (t < 0) {
                                        t = t * -1;
                                    }
									console.log('j!=0,t:'+t);
                                }
								*/
								
                                var date = '';
                                for (var z in data.data.lightList[i][j]) {
									console.log(data.data.lightList[i][j]);
									console.log('zzzzzz:'+z);
                                    date += data.data.lightList[i][j][z] + "<br/>";
                                }
								
								if(j!=4){
									if(j==3){//灰灯
										console.log('.row' + parseInt(i-1));
										$('.row' + parseInt(i-1)).eq(0).append("<img src='../images/gray.jpg'><span class='datetime'>" + date + "</span>");
									}else{//黄绿
										console.log('.row' + parseInt(i-1));
										$('.row' + parseInt(i-1)).eq(j).append("<span class='datetime'>" + date + "</span>");
									}
								}
								
                            }
                        }

                        $('.btn').click(function () {
                            window.location.href = "index4?code=" + code;
                        })

                        $('table').show();
                    })
                }
            })
        </script>

    </body>

</html>