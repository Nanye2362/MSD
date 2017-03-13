<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>别名配置</title>
        <link rel="stylesheet" href="../css/grid.paging.min.css" />
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bsgrid.all.min.css" />
        <script src="../js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/bsgrid.all.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/grid.zh-CN.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="../js/grid.paging.min.js"></script>
        <script src="../js/setting.js" type="text/javascript" charset="utf-8"></script>
        <!--<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>-->
        <script type="text/javascript">
            var config_value;
            $(function () {
                $.fn.bsgrid.init('searchTable', {
                    //url: 'data/json.json',
                    url: host + 'cdeusedname/getlist',
                    //autoLoad: false,
                    pageSizeSelect: true,
                    pageSize: 20,
                    complete: function (options, a, b) {
                        var obj = eval("(" + a.responseText + ")");
                        var i = 0;
                        console.log(obj);
                        $('#searchTable tbody tr').each(function () {
                            if (i >= obj.data.length) {
                                return false;
                            }
                            $(this).attr('lang', obj.data[i].id);

                            $(this).find('td').eq(0).html("<div style='width:100%;min-height:23px;' class='cde_name' contenteditable='true'>" + obj.data[i].cde_name + "</div>");
                            $(this).find('td').eq(1).html("<div style='width:100%;min-height:23px;' class='cde_usedname' contenteditable='true'>" + obj.data[i].cde_usedname + "</div>");
                            $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='cde_usedname2' contenteditable='true'>" + obj.data[i].cde_usedname2 + "</div>");
                            $(this).find('td').eq(3).html("<div style='width:100%;min-height:23px;' class='cde_usedname3' contenteditable='true'>" + obj.data[i].cde_usedname3 + "</div>");
                            $(this).find('td').eq(4).html("<div style='width:100%;min-height:23px;' class='cde_usedname4' contenteditable='true'>" + obj.data[i].cde_usedname4 + "</div>");
                            $(this).find('td').eq(5).html("<div style='width:100%;min-height:23px;' class='cde_usedname5' contenteditable='true'>" + obj.data[i].cde_usedname5 + "</div>");

                            i++;
                        })
                    }

                });



                $(document).on('focus', '.cde_name', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_name', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'cdeusedname/update', {
                            'id': $(this).parents('tr').attr('lang'),
                            'cde_name': 1,
                            'cde_name_value': $(this).text()
                        })
                    }
                });

                $(document).on('focus', '.cde_usedname', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'cdeusedname/update', {
                            'id': $(this).parents('tr').attr('lang'),
                            'cde_usedname': 1,
                            'cde_usedname_value': $(this).text()
                        })
                    }
                });

                $(document).on('focus', '.cde_usedname2', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname2', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'cdeusedname/update', {
                            'id': $(this).parents('tr').attr('lang'),
                            'cde_usedname2': 1,
                            'cde_usedname2_value': $(this).text()
                        })
                    }
                });

                $(document).on('focus', '.cde_usedname3', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname3', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'cdeusedname/update', {
                            'id': $(this).parents('tr').attr('lang'),
                            'cde_usedname3': 1,
                            'cde_usedname3_value': $(this).text()
                        })
                    }
                });

                $(document).on('focus', '.cde_usedname4', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname4', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'cdeusedname/update', {
                            'id': $(this).parents('tr').attr('lang'),
                            'cde_usedname4': 1,
                            'cde_usedname4_value': $(this).text()
                        })
                    }
                });

                $(document).on('focus', '.cde_usedname5', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname5', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'cdeusedname/update', {
                            'id': $(this).parents('tr').attr('lang'),
                            'cde_usedname5': 1,
                            'cde_usedname5_value': $(this).text()
                        })
                    }
                });

                $("#search").click(function () {
                    search();
                });

                $("#input").keydown(function () {
                    if (event.keyCode == "13") { //keyCode=13是回车键
                        search();
                    }
                });

                function search() {
                    $('#searchTable_pt_outTab').remove();
                    gridObj = $.fn.bsgrid.init('searchTable', {
                        url: host + '/cdeusedname/search',
                        pageSizeSelect: true,
                        pageSize: 20,
                        otherParames: {
                            'searchText': $("#input").val()
                        },
                        complete: function (options, a, b) {
                            var obj = eval("(" + a.responseText + ")");
                            var i = 0;
                            $('#searchTable tbody tr').each(function () {
                                if (i >= obj.data.length) {
                                    return false;
                                }

                                $(this).attr('lang', obj.data[i].id);

                                $(this).find('td').eq(0).html("<div style='width:100%;min-height:23px;' class='cde_name' contenteditable='true'>" + obj.data[i].cde_name + "</div>");
                                $(this).find('td').eq(1).html("<div style='width:100%;min-height:23px;' class='cde_usedname' contenteditable='true'>" + obj.data[i].cde_usedname + "</div>");
                                $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='cde_usedname2' contenteditable='true'>" + obj.data[i].cde_usedname2 + "</div>");
                                $(this).find('td').eq(3).html("<div style='width:100%;min-height:23px;' class='cde_usedname3' contenteditable='true'>" + obj.data[i].cde_usedname3 + "</div>");
                                $(this).find('td').eq(4).html("<div style='width:100%;min-height:23px;' class='cde_usedname4' contenteditable='true'>" + obj.data[i].cde_usedname4 + "</div>");
                                $(this).find('td').eq(5).html("<div style='width:100%;min-height:23px;' class='cde_usedname5' contenteditable='true'>" + obj.data[i].cde_usedname5 + "</div>");

                                i++;
                            })
                        }
                    });
                }

                $('#addUserModal').on('hide.bs.modal', function () {
                    // 关闭时清空edit状态为add
                    $("#act").val("add");
//                    location.reload();
                });
            });

            // 添加
            function check_form() {
                var form_data = $('#form_data').serialize();
                console.log(form_data);

                $.ajax({
                    url: host + 'cdeusedname/addone',
                    data: {"form_data": form_data},
                    type: "post",
                    beforeSend: function ()
                    {
                        $("#tip").html("<span style='color:blue'>正在处理...</span>");
                        return true;
                    },
                    success: function (data) {
                        if (data.success == true) {
                            console.log(data);
                            var msg = "添加";
                            $("#tip").html("<span style='color:blueviolet'>恭喜，" + msg + "成功！</span>");
                            alert(msg + "成功！");
                            location.reload();
                        } else {
                            $("#tip").html("<span style='color:red'>失败，请重试</span>");
                            alert('添加失败');
                        }
                    },
                    error: function () {
                        alert('请求出错');
                    },
                    complete: function () {
                        $('#acting_tips').hide();
                    }
                });

                return false;
            }
        </script>
    </head>

    <body>
        <?php include "menu.php"; ?>
        <p style="margin: 10px;font-size: 20px;text-align: center;">别名配置</p>
        <div style="float: right; margin-right: 3%;">
            搜索:
            <input id="input" type="text" placeholder="">
            <button id="search" class="btn btn-primary btn-sm">Search</button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-primary btn-sm" data-toggle="modal"  data-target="#addUserModal" >添加</button>
        </div>
        <table id="searchTable" align="center">
            <tr>
                <th w_index="cde_name" w_align="center" width="16%;" class='rank'>药品名称</th>
                <th w_index="cde_usedname" w_align="center" width="16%;">药品别名1</th>
                <th w_index="cde_usedname2" w_align="center" width="16%;">药品别名2</th>
                <th w_index="cde_usedname3" w_align="center" width="16%;">药品别名3</th>
                <th w_index="cde_usedname4" w_align="center" width="16%;">药品别名4</th>
                <th w_index="cde_usedname4" w_align="center" width="16%;">药品别名5</th>
            </tr>
        </table>
        <div class="container">
            <form method="post" action="" class="form-horizontal" role="form" id="form_data" onsubmit="return check_form()" style="margin: 20px;">
                <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4 class="modal-title" id="myModalLabel">
                                    用户信息
                                </h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label for="cde_name" class="col-sm-3 control-label">药品名称</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="user_id" name="cde_name" value=""
                                                   placeholder="请输入药品名称">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname" class="col-sm-3 control-label">药品别名1</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cde_usedname" value="" id="user_name"
                                                   placeholder="请输入药品别名1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname2" class="col-sm-3 control-label">药品别名2</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cde_usedname2" value="" id="address"
                                                   placeholder="请输入药品别名2">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname3" class="col-sm-3 control-label">药品别名3</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control"  name="cde_usedname3" value="" id="remark"
                                                   placeholder="请输入药品别名3">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname4" class="col-sm-3 control-label">药品别名4</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control"  name="cde_usedname4" value="" id="remark"
                                                   placeholder="请输入药品别名4">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname5" class="col-sm-3 control-label">药品别名5</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control"  name="cde_usedname5" value="" id="remark"
                                                   placeholder="请输入药品别名5">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    确定添加
                                </button><span id="tip"> </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>

</html>