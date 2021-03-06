<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>首页</title>
        <link rel="stylesheet" href="../css/grid.paging.min.css" media="all"/>
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bsgrid.all.min.css" media="all" />
        <script src="../js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/bsgrid.all.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/grid.zh-CN.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/setting.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/jquery-ui-1.10.4.custom.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/jquery.PrintArea.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/clipboard.min.js" type="text/javascript" charset="utf-8"></script>

        <script type="text/javascript" src="../js/grid.paging.min.js"></script>
        <script type="text/javascript">
            var gridObj;
            var typeObj;
            var remarkText;
            var p_remarkText;

            function formatTable(obj) {
                obj.find('td .bsgrid_editgrid_check').each(function () {
                    if (!$(this).prop("checked")) {
                        $(this).parents('tr').remove();
                    }

                })

                obj.find('tr').each(function () {
                    $(this).find('td .panel_div').remove();
                    $(this).find('td,th').eq(0).remove();
                })


            }

            $(function () {
                $('#Print').click(function () {
                    var obj = $("#printableArea").clone();
                    formatTable(obj)

                    obj.printArea({mode: "iframe", keepAttr: ["id", "class", "style"], extraHead: '<meta charset="utf-8" />,<meta http-equiv="X-UA-Compatible" content="IE=edge"/>'});
                })


                var clipboard = new Clipboard('#copy', {
                    text: function () {
                        var obj = $("#printableArea").clone();
                        formatTable(obj)

                        var text = "";
                        obj.find("tr").each(function () {
                            if ($(this).find('th').length > 0) {
                                return true;
                            }
                            $(this).find("td").each(function () {
                                text += $(this).text() + "\t";
                            })
                            text += "\r\n";
                        })
                        return text;
                    }
                });



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

                //导出excel
                $('#export').click(function () {
                    var checkedbox = $('.bsgrid_editgrid_check:checked');
                    var checkboxvalues = new Array;
                    if(checkedbox.length == 0){
                        alert('请选择需要导出的信息');
                    }else{
                        for (var i = 0; i < checkedbox.length; i++) {
                            checkboxvalues[i] = checkedbox.eq(i).val();
                        }
                        var cde_id = checkboxvalues;
                        window.open(host + 'python/export?export=1&cde_id=' + cde_id);
                    }
                });
            });


            function getEmaillist(checkboxvalues, drugname_checkboxvalues) {
                $.ajax({
                    type: "post",
                    url: host + '/emaillist/insert',
                    data: {pageSize: 20, cde_ids: checkboxvalues, names: drugname_checkboxvalues},
                    success: function (data) {
                        if (data.success == true) {
                            alert('选择成功');
                        }
                    }
                });
            }

            function getMyfavoritelist(checkboxvalues) {
                $.ajax({
                    type: "post",
                    url: host + '/myfavorite/insert',
                    data: {pageSize: 20, cde_ids: checkboxvalues},
                    success: function (data) {
                        if (data.success == true) {
                            alert('选择成功');
                        }
                    }
                });
            }

            function getcheckboxvalue() {
                $('#sendemail').unbind('click').click(function () {
                    var checkedbox = $('.bsgrid_editgrid_check:checked');
                    var checkboxvalues = new Array;
                    for (var i = 0; i < checkedbox.length; i++) {
                        checkboxvalues[i] = checkedbox.eq(i).val();
                    }
                    console.log(checkboxvalues);

                    var drugname_checkbox = $('#drugnameID:checked');
                    var drugname_checkboxvalues = new Array;
                    for (var i = 0; i < drugname_checkbox.length; i++) {
                        drugname_checkboxvalues[i] = drugname_checkbox.eq(i).val();
                    }
                    console.log(drugname_checkboxvalues);

                    getEmaillist(checkboxvalues, drugname_checkboxvalues);

                });
                $('#addtofavorite').unbind('click').click(function () {
                    var checkedbox = $('.bsgrid_editgrid_check:checked');
                    var checkboxvalues = new Array;
                    for (var i = 0; i < checkedbox.length; i++) {
                        checkboxvalues[i] = checkedbox.eq(i).val();
                    }
                    console.log(checkboxvalues);
                    getMyfavoritelist(checkboxvalues);
                });
            }

            function getDrugname() {
                var drugname_checkbox = $('#drugnameID:checked');
                var drugname_checkboxvalues = new Array;
                for (var i = 0; i < drugname_checkbox.length; i++) {
                    drugname_checkboxvalues[i] = drugname_checkbox.eq(i).val();
                }
                console.log(drugname_checkboxvalues);
            }

            function getList(showPage) {
                $('.sort').remove();
                $('#searchTable_pt_outTab').remove();
                gridObj = $.fn.bsgrid.init('searchTable', {
                    //url: 'data/json.json',
                    url: host + '/python/getlist',
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

                            //药品名checkbox
                            $(this).find('td').eq(3).html("<input id='drugnameID' type='checkbox' value=" + obj.data[i].name + ">");

                            //判断行颜色
                            if (obj.data[i].row_status == 1) {
                                $(this).find('td').css("background-color", "#FFD2D2")
                            } else if (obj.data[i].row_status == 2) {
                                $(this).find('td').css("background-color", "#ceffce")
                            } else if (obj.data[i].row_status == 3) {
                                $(this).find('td').css("background-color", "#ffffce")
                            }

                            //时间借点记录填充
                            if(typeof(obj.data[i].rankList[0]) != 'undefined'){
                                $(this).find('td').eq(7).html("<p style='position:relative'>" + $(this).find('td').eq(7).html() + 'No.' + obj.data[i].rank + "  " + obj.data[i].rankList[0].datetime + "</p>");
                            }

                            //在序号排名变化时间节点记录页新增div
                            // $(this).find('td').eq(6).css('position', 'relative');

                            //循环排名变化表
                            var len = obj.data[i].rankList.length;
                            var ranklist = $("<div class='panel_div' style='overflow:auto;height:200px;z-index: 99;position:absolute;left:-1px;width: 100%;background:#fff;border:1px solid #000;min-height: 100px; display: none;'></div>");
                            for (var o = 1; o < len; o++) {
                                ranklist.append("<p>" + 'No.' + obj.data[i].rankList[o].rank + " " + obj.data[i].rankList[o].datetime + "</p>");
                            }

                            $(this).find('td').eq(7).find("p").append(ranklist);
                            $(this).find('td').eq(7).mouseenter(function () {
                                $(this).find(".panel_div").css('top', $(this).height() - 8 + "px");
                                $(this).find(".panel_div").show()
                            });
                            $(this).find('td').eq(7).mouseleave(function () {
                                $(this).find(".panel_div").hide()
                            });

                            //临床适应症修改,并添加管理员权限
                            if (obj.role == 1) {
                                $(this).find('td').eq(11).html("<div style='width:100%;min-height:23px;word-break: break-word;word-wrap: break-word;' class='clinical_indication' contenteditable='true'>" + obj.data[i].clinical_indication + "</div>");
                            }

                            //备注修改
                            $(this).find('td').eq(12).html("<div style='width:100%;min-height:23px;word-break: break-word;word-wrap: break-word;' class='remark' contenteditable='true'>" + obj.data[i].custom_remark + "</div>");

                            //备注1修改
                            $(this).find('td').eq(13).html("<div style='width:100%;min-height:23px;word-break: break-word;word-wrap: break-word;' class='remark1' contenteditable='true'>" + obj.data[i].remark1 + "</div>");
                            $(this).find('td').eq(0).find('input').eq(0).val(obj.data[i].id);
                            $(this).attr('lang', obj.data[i].id);
                            i++;
                        })
                        getcheckboxvalue();
                        $('#searchTable').wrap('<div id="printableArea"></div>');
                    }

                });

                //个人备注
                $(document).on('focus', '.remark', function () {
                    remarkText = $(this).text();
                })

                $(document).on('blur', '.remark', function () {
                    if ($(this).text() != remarkText) {
                        $.post(host + 'python/updateremark', {'cdeId': $(this).parents('tr').attr('lang'), "remark": $(this).text()})
                    }
                })

                //公开备注
                $(document).on('focus', '.remark1', function () {
                    p_remarkText = $(this).text();
                })

                $(document).on('blur', '.remark1', function () {
                    cde_id = $(this).parent().parent('tr').attr('lang');
                    new_remarkText = $(this).text();
                    if (new_remarkText != p_remarkText) {
                        $.post(
                                host + 'python/updatepublicremark',
                                {'cdeId': $(this).parents('tr').attr('lang'), "remark": $(this).text()},
                                function (data) {
                                    if (data.success == true) {
                                        //实时更新所有用户备注
                                        var refresh_remark = $('#refresh_remark_' + data.uid + '_' + cde_id);
                                        if (refresh_remark.length == 0) {
                                            $('tr[lang=' + cde_id + ']').find('td').eq(14).html("<p id='refresh_remark_" + data.uid + '_' + cde_id + "' style='margin-top: 0px;margin-bottom: 0px;word-break: break-word;word-wrap: break-word;'>" + data.user_name + ':' + new_remarkText + "</p>");
                                        } else {
                                            refresh_remark.html(data.user_name + ':' + new_remarkText);
                                        }
                                        console.log('修改成功');
                                    }
                                }
                        );
                    }
                })

                //临床适应症编辑
                $(document).on('focus', '.clinical_indication', function () {
                    c_remarkText = $(this).text();
                })

                $(document).on('blur', '.clinical_indication', function () {
                    if ($(this).text() != c_remarkText) {
                        $.post(host + 'python/updateclinicalindication', {'cdeId': $(this).parents('tr').attr('lang'), "clinical_indication": $(this).text()});
                    }
                })

            }
            //function operate(record, rowIndex, colIndex, options) {
            //return '<a href="#" onclick="alert(\'ID=' + gridObj.getRecordIndexValue(record, 'ID') + '\');">Operate</a>';
            //}
        </script>
    </head>

    <body>
        <?php include "menu.php"; ?>
        <div style="padding-top:15px;">
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
				<button class="btn btn-primary btn-sm" id="search">Search</button>&nbsp;&nbsp;&nbsp;
				<button class="btn btn-primary btn-sm" id="copy">Copy</button>
				<button class="btn btn-primary btn-sm" id="export">Export</button>
				<button class="btn btn-primary btn-sm" id="Print">Print</button>
				<button class="btn btn-primary btn-sm" id="addtofavorite">Add to favorite</button>
				<button class="btn btn-primary btn-sm" id="sendemail">Send Email</button>
			</div>
		</div>
        <br />
        <br />
        
        <p style="margin-left: 3%;">注：2017年1月9日前的排名数据为99999</p>
		<p style="margin-left: 3%;">统计至<?php echo $spider_time.'&nbsp;'.$http_status;?></p>

        <table id="searchTable" align="center">
            <tr id="trhead">
                <th  w_check="true" width="3%;" title="全选"></th>
                <th  w_index="rank" w_sort="rank" width="7%;" class='rank'>序号</th>
                <th  w_index="code" w_sort="code" w_align="left" width="8%;">受理号</th>
                <th  w_index="" w_align="center" width="3%;">药名邮件通知</th>
                <th  w_index="name" w_align="left" width="8%;">药品名称</th>
                <th  w_index="company" width="12%;">企业名称</th>
                <th  w_index="join_date" w_sort="join_date" width="8%;">进入中心时间</th>
                <th  w_index="MARK" width="11%;">序号排名变化时间节点记录</th>
                <th  w_index="ephmra_atc_code" width="7%;">适应症大类</th>
                <th  w_index="sfda_status" width="5%;">状态</th>
                <th  w_index="clinical_test_link" width="5%;">临床实验链接</th>                
                <th  w_index="clinical_indication" width="7%;">临床适应症</th>
                <th style="border-right:none;" w_index="remark" width="6%;"><div style="text-align:right !important;">备</div><div>个人</div></th>
                <th style="border-left:none;" w_index="remark1" width="6%;"><div style="text-align:left !important;">注</div><div>公开</div></th>
                <th  w_index="showremark" width="8%;">所有用户备注</th>
            </tr>
        </table>

    </body>

</html>