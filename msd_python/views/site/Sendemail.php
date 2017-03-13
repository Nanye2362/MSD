<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title></title>
        <link rel="stylesheet" href="../css/grid.paging.min.css" />
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bsgrid.all.min.css" />
        <script src="../js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/bsgrid.all.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/grid.zh-CN.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/setting.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="../js/grid.paging.min.js"></script>
        <script type="text/javascript">
            var gridObj;
            var typeObj;
            var remarkText;
            var p_remarkText;

            $(function () {
                $.post(host + 'python/gettype', function (data) {
                    typeObj = data;
                    var firstO = '';
                    var firsti = '';
                    for (var o in typeObj) {
                        if (firstO.length == 0) {
                            firstO = o;
                        }
                        $('#select1').append('<option>' + o + '</option>');
                    }
                    changeTypeSelect(firstO);

                    for (var i in typeObj[firstO]) {
                        if (firsti.length == 0) {
                            firsti = i;
                        }
                    }

                    changeDetail(firsti);
                    getList(true);
                })

                $('#select1').change(function () {
                    changeTypeSelect($(this).val());
                    getList();
                })

                $('#select2').change(function () {
                    changeDetail($(this).val());
                    getList();
                })

                $('#select3').change(function () {
                    getList();
                })


                function changeTypeSelect(type) {
                    $('#select2').html('');
                    var firstO = '';
                    for (var o in typeObj[type]) {
                        if (firstO.length == 0) {
                            firstO = o;
                        }
                        $('#select2').append('<option>' + o + '</option>');
                    }
                    changeDetail(firstO);
                }

                function changeDetail(subtype) {
                    $('#select3').html('');
                    var type = $("#select1").val();

                    for (var o in typeObj[type][subtype]) {
                        $('#select3').append('<option value=' + typeObj[type][subtype][o] + '>' + o + '</option>');
                    }
                }

                //	             gridObj.page(1);
                //	         	 $.fn.bsgrid.page = function (curPage, options) {
                //          		// do something to get totalRows
                //          		var totalRows = 10;
                //          		// this below must exist, to set paging values and css styles
                //        			 gridObj.setPagingValues(curPage, totalRows); // setPagingValues(curPage, totalRows)
                $("#search").click(function () {
                    getList();
                });

                $("#input").keydown(function () {
                    if (event.keyCode == "13") { //keyCode=13是回车键
                        getList();
                    }
                })
                //     		 	};
                $('#delete').click(function () {
                    var checkedbox = $('input[type=checkbox]:checked');
                    var checkedboxlength = $('input[type=checkbox]:checked').length;
                    var checkboxvalues = new Array;
                    for (var i = 0; i < checkedboxlength; i++) {
                        checkboxvalues[i] = checkedbox.eq(i).val();
                    }
                    
                    $.post(host + 'emaillist/delete', {cde_id: checkboxvalues}, function(data){
                        if(data.success == true){
                            alert('删除成功');
                            getList();
                        }
                    });
                });
            });
            
            function getList(showPage) {

                $('#searchTable_pt_outTab').remove();
                gridObj = $.fn.bsgrid.init('searchTable', {
                    //url: 'data/json.json',
                    url: host + '/emaillist/getemaillist',
                    //autoLoad: false,
                    pageSizeSelect: true,
                    pageSize: 20,
                    otherParames: {
                        'searchText': $("#input").val(),
                        'typeId': $('#select3').val()
                    },
                    complete: function (options, a, b) {
                        var obj = eval("(" + a.responseText + ")");
                        var i = 0;
                        $('#searchTable tbody tr').each(function () {
                            if (i >= obj.data.length) {
                                return false;
                            }
                            //添加箭头
                            //判断箭头
                            if (obj.data[i].rank_status <= 0) {
                                $(this).find('td').eq(1).html($(this).find('td').eq(1).html() + "<a style='display: inline-block;' href='/site/page2?code=" + obj.data[i].id + "'> &nbsp; <img src='../images/right.jpg'></a>")
                            } else {
                                $(this).find('td').eq(1).html($(this).find('td').eq(1).html() + "<a style='display: inline-block;' href='/site/page2?code=" + obj.data[i].id + "'> &nbsp; <img src='../images/up.jpg'></a>")
                            }

                            //受理号添加链接
                            $(this).find('td').eq(2).html("<a style='display: inline-block;text-decoration:underline;color:#000;' href='/site/page2?code=" + obj.data[i].id + "'>" + obj.data[i].code + "</a>")

                            //判断行颜色
                            if (obj.data[i].row_status == 1) {
                                $(this).find('td').css("background-color", "#FFD2D2")
                            } else if (obj.data[i].row_status == 2) {
                                $(this).find('td').css("background-color", "#ceffce")
                            } else if (obj.data[i].row_status == 3) {
                                $(this).find('td').css("background-color", "#ffffce")
                            }

                            //时间借点记录填充
                            if(obj.data[i].rankList[0].datetime != ''){
                                $(this).find('td').eq(6).html($(this).find('td').eq(6).html() + 'No.' + obj.data[i].rank + "  " + obj.data[i].rankList[0].datetime)
                            }
                            //在序号排名变化时间节点记录页新增div
                            $(this).find('td').eq(6).css('position', 'relative');

                            //循环排名变化表
                            var len = obj.data[i].rankList.length;
                            var ranklist = $("<div style='z-index: 99;position:absolute;left:-1px;width: 100%;background:#fff;border:1px solid #000;min-height: 100px; display: none;'></div>");
                            for (var o = 1; o < len; o++) {
                                ranklist.append("<p>" + 'No.' + obj.data[i].rankList[o].rank + " " + obj.data[i].rankList[o].datetime + "</p>");
                            }

                            $(this).find('td').eq(6).append(ranklist);
                            $(this).find('td').eq(6).mouseenter(function () {
                                $(this).children("div").css('top', $(this).height() + 6 + "px");
                                $(this).children("div").show()
                            });
                            $(this).find('td').eq(6).mouseleave(function () {
                                $(this).children("div").hide()
                            });

//                            //临床适应症修改,并添加管理员权限
//                            if (obj.role == 1) {
//                                $(this).find('td').eq(9).html("<div style='width:100%;min-height:23px;word-break: break-word;word-wrap: break-word;' class='clinical_indication' contenteditable='true'>" + obj.data[i].clinical_indication + "</div>");
//                            }
//                            
//                            //备注修改
//                            $(this).find('td').eq(10).html("<div style='width:100%;min-height:23px;word-break: break-word;word-wrap: break-word;' class='remark' contenteditable='true'>" + obj.data[i].custom_remark + "</div>");
//
//                            //备注1修改
//                            $(this).find('td').eq(11).html("<div style='width:100%;min-height:23px;word-break: break-word;word-wrap: break-word;' class='remark1' contenteditable='true'>" + obj.data[i].remark1 + "</div>");
                            
                            $(this).find('td').eq(0).find('input').eq(0).val(obj.data[i].id);
                            $(this).attr('lang', obj.data[i].id);
                            i++;
                        })
                    }

                });

                //个人备注
                $(document).on('focus', '.remark', function () {
                    remarkText = $(this).text();
                })

                //公开备注
                $(document).on('focus', 'remark1', function () {
                    p_remarkText = $(this).text();
                })


                $(document).on('blur', '.remark', function () {
                    if ($(this).text() != remarkText) {
                        $.post(host + 'python/updateremark', {'cdeId': $(this).parents('tr').attr('lang'), "remark": $(this).text()})
                    }
                })

                //公开备注 
                $(document).on('blur', '.remark1', function () {
                    if ($(this).text() != p_remarkText) {
                        $.post(host + 'python/updatepublicremark', {'cdeId': $(this).parents('tr').attr('lang'), "remark": $(this).text()})
                    }
                })

            }
        </script>
    </head>

    <body>
        <?php include "menu.php"; ?>
        <p style="margin: 10px;font-size: 20px;text-align: center;">发送邮件</p>
        <div id="bar" style="float: left; margin-left: 3%;">
            <select id="select1">

            </select>
            <select id="select2">

            </select>
            <select id="select3">

            </select>
        </div>
        
        <div id="bar" style="float: right; margin-right: 3%;">
            <input id="input" type="text" placeholder="">
            <button class="btn btn-primary btn-sm" id="search">Search</button>
            <button class="btn btn-primary btn-sm" id="delete">Delete</button>
        </div>

        <br />
        <br />
        <table id="searchTable" align="center">
            <tr>
                <th w_check="true" width="3%;" title="全选"></th>
                <th w_index="rank" width="7%;" class='rank'>序号</th>
                <th w_index="code" w_align="left" width="10%;">受理号</th>
                <th w_index="name" w_align="left" width="10%;">药品名称</th>
                <th w_index="company" width="14%;">企业名称</th>
                <th w_index="join_date" width="8%;">进入中心时间</th>
                <th w_index="MARK" width="13%;">序号排名变化时间节点记录</th>
                <th w_index="ephmra_atc_code" width="5%;">适应症大类</th>
                <th  w_index="sfda_status" width="5%;">临床实验</th>
                <th  w_index="clinical_indication" width="7%;">临床适应症</th>
                <th style="border-right:none;" w_index="remark" width="6%;"><div style="text-align:right !important;">备</div><div>个人</div></th>
                <th style="border-left:none;" w_index="remark1" width="6%;"><div style="text-align:left !important;">注</div><div>公开</div></th>
                <th  w_index="showremark" width="8%;">所有用户备注</th>
            </tr>
        </table>
    </body>

</html>