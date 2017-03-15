<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>适应症分类</title>
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
                function GetQueryString(name) {
                    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
                    var r = window.location.search.substr(1).match(reg);
                    if (r != null)
                        return  unescape(r[2]);
                    return null;
                }
                //post ephmra_atc_code
                ephmra_atc_code = GetQueryString('ephmra_atc_code');



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
                    getListbyephmra(true);
                })

                $('#select1').change(function () {
                    changeTypeSelect($(this).val());
                    getListbyephmra();
                })

                $('#select2').change(function () {
                    changeDetail($(this).val());
                    getListbyephmra();
                })

                $('#select3').change(function () {
                    getListbyephmra();
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
                    getListbyephmra();
                });

                $("#input").keydown(function () {
                    if (event.keyCode == "13") { //keyCode=13是回车键
                        getListbyephmra();
                    }
                })
                //     		 	};

                //导出excel
                $('#export').click(function () {
                    var checkedbox = $('input[type=checkbox]:checked');
                    var checkedboxlength = $('input[type=checkbox]:checked').length;
                    var checkboxvalues = new Array;
                    for (var i = 0; i < checkedboxlength; i++) {
                        checkboxvalues[i] = checkedbox.eq(i).val();
                    }
                    var cde_id = checkboxvalues;
                    window.open(host + 'python/export?export=1&cde_id=' + cde_id);
                });
            });

            function getListbyephmra(showPage) {
                $('.sort').remove();
                $('#searchTable_pt_outTab').remove();
                gridObj = $.fn.bsgrid.init('searchTable', {
                    //url: 'data/json.json',
                    url: host + '/python/getlistbyephmra',
                    //autoLoad: false,
                    pageSizeSelect: true,
                    pageSize: 20,
                    otherParames: {
                        'searchText': $("#input").val(),
                        'typeId': $('#select3').val(),
                        'ephmra_atc_code': ephmra_atc_code
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
                            if (typeof(obj.data[i].rankList[0]) != 'undefined') {
                                $(this).find('td').eq(6).html("<p style='position:relative'>" + $(this).find('td').eq(6).html() + 'No.' + obj.data[i].rank + "  " + obj.data[i].rankList[0].datetime + "</p>")
                            }
                            //在序号排名变化时间节点记录页新增div
                            // $(this).find('td').eq(6).css('position', 'relative');

                            //循环排名变化表
                            var len = obj.data[i].rankList.length;
                            var ranklist = $("<div class='panel_div' style='z-index: 99;position:absolute;left:-1px;width: 100%;background:#fff;border:1px solid #000;min-height: 100px; display: none;'></div>");
                            for (var o = 1; o < len; o++) {
                                ranklist.append("<p>" + 'No.' + obj.data[i].rankList[o].rank + " " + obj.data[i].rankList[o].datetime + "</p>");
                            }

                            $(this).find('td').eq(6).find("p").append(ranklist);
                            $(this).find('td').eq(6).mouseenter(function () {
                                $(this).find(".panel_div").css('top', $(this).height() - 8 + "px");
                                $(this).find(".panel_div").show()
                            });
                            $(this).find('td').eq(6).mouseleave(function () {
                                $(this).find(".panel_div").hide()
                            });

                            $(this).find('td').eq(0).find('input').eq(0).val(obj.data[i].id);
                            $(this).attr('lang', obj.data[i].id);
                            i++;
                        })

                        $('#searchTable').wrap('<div id="printableArea"></div>');
                    }

                });

            }
        </script>
    </head>

    <body>
        <?php include "menu.php"; ?>
        <p style="margin: 10px;font-size: 20px;text-align: center;">适应症分类</p>
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
        </div>

        <br />
        <br />

        <table id="searchTable" align="center">
            <tr id="trhead">
                <th  w_check="true" width="3%;" title="全选"></th>
                <th  w_index="rank" w_sort="rank" width="7%;" class='rank'>序号</th>
                <th  w_index="code" w_sort="code" w_align="left" width="8%;">受理号</th>
                <th  w_index="name" w_align="left" width="11%;">药品名称</th>
                <th  w_index="company" width="14%;">企业名称</th>
                <th  w_index="join_date" w_sort="join_date" width="8%;">进入中心时间</th>
                <th  w_index="MARK" width="13%;">序号排名变化时间节点记录</th>
                <th  w_index="sfda_status" width="5%;">状态</th>
                <th  w_index="clinical_test_link" width="5%;">临床实验链接</th>
                <th  w_index="end_date" w_sort="end_date" width="13%;">完成时间</th>
                <th  w_index="total_days" w_sort="total_days" width="13%;">共计天数</th>
            </tr>
        </table>

    </body>

</html>