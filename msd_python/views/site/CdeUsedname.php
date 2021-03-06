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

                            if (obj.data[i].cde_usedname2 == '') {
                                if (obj.data[i].cde_usedname == '') {
                                    $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='cde_usedname2' contenteditable='false'>" + obj.data[i].cde_usedname2 + "</div>");
                                } else {
                                    $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='cde_usedname2' contenteditable='true'>" + obj.data[i].cde_usedname2 + "</div>");
                                }
                            } else {
                                $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='cde_usedname2' contenteditable='true'>" + obj.data[i].cde_usedname2 + "</div>");
                            }

                            if (obj.data[i].cde_usedname3 == '') {
                                if (obj.data[i].cde_usedname2 == '') {
                                    $(this).find('td').eq(3).html("<div style='width:100%;min-height:23px;' class='cde_usedname3' contenteditable='false'>" + obj.data[i].cde_usedname3 + "</div>");
                                } else {
                                    $(this).find('td').eq(3).html("<div style='width:100%;min-height:23px;' class='cde_usedname3' contenteditable='true'>" + obj.data[i].cde_usedname3 + "</div>");
                                }
                            } else {
                                $(this).find('td').eq(3).html("<div style='width:100%;min-height:23px;' class='cde_usedname3' contenteditable='true'>" + obj.data[i].cde_usedname3 + "</div>");
                            }

                            if (obj.data[i].cde_usedname4 == '') {
                                if (obj.data[i].cde_usedname3 == '') {
                                    $(this).find('td').eq(4).html("<div style='width:100%;min-height:23px;' class='cde_usedname4' contenteditable='false'>" + obj.data[i].cde_usedname4 + "</div>");
                                } else {
                                    $(this).find('td').eq(4).html("<div style='width:100%;min-height:23px;' class='cde_usedname4' contenteditable='true'>" + obj.data[i].cde_usedname4 + "</div>");
                                }
                            } else {
                                $(this).find('td').eq(4).html("<div style='width:100%;min-height:23px;' class='cde_usedname4' contenteditable='true'>" + obj.data[i].cde_usedname4 + "</div>");
                            }

                            if (obj.data[i].cde_usedname5 == '') {
                                if (obj.data[i].cde_usedname4 == '') {
                                    $(this).find('td').eq(5).html("<div style='width:100%;min-height:23px;' class='cde_usedname5' contenteditable='false'>" + obj.data[i].cde_usedname5 + "</div>");
                                } else {
                                    $(this).find('td').eq(5).html("<div style='width:100%;min-height:23px;' class='cde_usedname5' contenteditable='true'>" + obj.data[i].cde_usedname5 + "</div>");
                                }
                            } else {
                                $(this).find('td').eq(5).html("<div style='width:100%;min-height:23px;' class='cde_usedname5' contenteditable='true'>" + obj.data[i].cde_usedname5 + "</div>");
                            }

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
                        }, function (data) {
                            if (data.delete == true) {
                                location.reload();
                            }
                        })
                    }
                });

                $(document).on('focus', '.cde_usedname', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname', function () {
                    var langval = $(this).parents('tr').attr('lang');
                    var cde_name_value = $('tr[lang=' + langval + ']').find('td').eq(0).text();
                    var cde_usedname2_value = $('tr[lang=' + langval + ']').find('td').eq(2).text();
                    var cde_usedname3_value = $('tr[lang=' + langval + ']').find('td').eq(3).text();
                    var cde_usedname4_value = $('tr[lang=' + langval + ']').find('td').eq(4).text();
                    var cde_usedname5_value = $('tr[lang=' + langval + ']').find('td').eq(5).text();
                    if ($(this).text() != config_value) {
                        if (($(this).text() != '' && $(this).text() == cde_usedname2_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname3_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname4_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname5_value)) {
                            alert('药品别名有重复！');
                            $(this).empty();
                        } else {
                            $.post(host + 'cdeusedname/update', {
                                'id': langval,
                                'cde_name_value': cde_name_value,
                                'cde_usedname': 1,
                                'cde_usedname_value': $(this).text()
                            });
                        }
                    }
                    if ($(this).text() != '') {
                        console.log(langval);
                        $('tr[lang=' + langval + ']').find('td').eq(2).children('.cde_usedname2').attr('contenteditable', true);
                    }else{
                        $('tr[lang=' + langval + ']').find('td').eq(2).children('.cde_usedname2').attr('contenteditable', false);
                    }
                });

                $(document).on('focus', '.cde_usedname2', function () {
                    config_value = $(this).text();
                });

                $(document).on('blur', '.cde_usedname2', function () {
                    var langval = $(this).parents('tr').attr('lang');
                    var cde_name_value = $('tr[lang=' + langval + ']').find('td').eq(0).text();
                    var cde_usedname1_value = $('tr[lang=' + langval + ']').find('td').eq(1).text();
                    var cde_usedname3_value = $('tr[lang=' + langval + ']').find('td').eq(3).text();
                    var cde_usedname4_value = $('tr[lang=' + langval + ']').find('td').eq(4).text();
                    var cde_usedname5_value = $('tr[lang=' + langval + ']').find('td').eq(5).text();
                    if ($(this).text() != config_value) {
                        if (($(this).text() != '' && $(this).text() == cde_usedname1_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname3_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname4_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname5_value)) {
                            alert('药品别名有重复！');
                            $(this).empty();
                        } else {
                            $.post(host + 'cdeusedname/update', {
                                'id': langval,
                                'cde_name_value': cde_name_value,
                                'cde_usedname2': 1,
                                'cde_usedname2_value': $(this).text()
                            });
                        }
                    }
                    if ($(this).text() != '') {
                        console.log(langval);
                        $('tr[lang=' + langval + ']').find('td').eq(3).children('.cde_usedname3').attr('contenteditable', true);
                    }else{
                        $('tr[lang=' + langval + ']').find('td').eq(3).children('.cde_usedname3').attr('contenteditable', false);
                    }
                });

                $(document).on('focus', '.cde_usedname3', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname3', function () {
                    var langval = $(this).parents('tr').attr('lang');
                    var cde_name_value = $('tr[lang=' + langval + ']').find('td').eq(0).text();
                    var cde_usedname1_value = $('tr[lang=' + langval + ']').find('td').eq(1).text();
                    var cde_usedname2_value = $('tr[lang=' + langval + ']').find('td').eq(2).text();
                    var cde_usedname4_value = $('tr[lang=' + langval + ']').find('td').eq(4).text();
                    var cde_usedname5_value = $('tr[lang=' + langval + ']').find('td').eq(5).text();
                    if ($(this).text() != config_value) {
                        if (($(this).text() != '' && $(this).text() == cde_usedname1_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname2_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname4_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname5_value)) {
                            alert('药品别名有重复！');
                            $(this).empty();
                        } else {
                            $.post(host + 'cdeusedname/update', {
                                'id': langval,
                                'cde_name_value': cde_name_value,
                                'cde_usedname3': 1,
                                'cde_usedname3_value': $(this).text()
                            });
                        }
                    }
                    if ($(this).text() != '') {
                        console.log(langval);
                        $('tr[lang=' + langval + ']').find('td').eq(4).children('.cde_usedname4').attr('contenteditable', true);
                    }else{
                        $('tr[lang=' + langval + ']').find('td').eq(4).children('.cde_usedname4').attr('contenteditable', false);
                    }
                });

                $(document).on('focus', '.cde_usedname4', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname4', function () {
                    var langval = $(this).parents('tr').attr('lang');
                    var cde_name_value = $('tr[lang=' + langval + ']').find('td').eq(0).text();
                    var cde_usedname1_value = $('tr[lang=' + langval + ']').find('td').eq(1).text();
                    var cde_usedname2_value = $('tr[lang=' + langval + ']').find('td').eq(2).text();
                    var cde_usedname3_value = $('tr[lang=' + langval + ']').find('td').eq(3).text();
                    var cde_usedname5_value = $('tr[lang=' + langval + ']').find('td').eq(5).text();
                    if ($(this).text() != config_value) {
                        if (($(this).text() != '' && $(this).text() == cde_usedname1_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname2_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname3_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname5_value)) {
                            alert('药品别名有重复！');
                            $(this).empty();
                        } else {
                            $.post(host + 'cdeusedname/update', {
                                'id': langval,
                                'cde_name_value': cde_name_value,
                                'cde_usedname4': 1,
                                'cde_usedname4_value': $(this).text()
                            });
                        }
                    }
                    if ($(this).text() != '') {
                        console.log(langval);
                        $('tr[lang=' + langval + ']').find('td').eq(5).children('.cde_usedname5').attr('contenteditable', true);
                    }else{
                        $('tr[lang=' + langval + ']').find('td').eq(5).children('.cde_usedname5').attr('contenteditable', false);
                    }
                });

                $(document).on('focus', '.cde_usedname5', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname5', function () {
                    if ($(this).text() != config_value) {
                        var langval = $(this).parents('tr').attr('lang');
                        var cde_name_value = $('tr[lang=' + langval + ']').find('td').eq(0).text();
                        var cde_usedname1_value = $('tr[lang=' + langval + ']').find('td').eq(1).text();
                        var cde_usedname2_value = $('tr[lang=' + langval + ']').find('td').eq(2).text();
                        var cde_usedname3_value = $('tr[lang=' + langval + ']').find('td').eq(3).text();
                        var cde_usedname4_value = $('tr[lang=' + langval + ']').find('td').eq(4).text();
                        if (($(this).text() != '' && $(this).text() == cde_usedname1_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname2_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname3_value) ||
                                ($(this).text() != '' && $(this).text() == cde_usedname4_value)) {
                            alert('药品别名有重复！');
                            $(this).empty();
                        } else {
                            $.post(host + 'cdeusedname/update', {
                                'id': langval,
                                'cde_name_value': cde_name_value,
                                'cde_usedname5': 1,
                                'cde_usedname5_value': $(this).text()
                            });
                        }
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

                                if (obj.data[i].cde_usedname2 == '') {
                                    if (obj.data[i].cde_usedname == '') {
                                        $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='cde_usedname2' contenteditable='false'>" + obj.data[i].cde_usedname2 + "</div>");
                                    } else {
                                        $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='cde_usedname2' contenteditable='true'>" + obj.data[i].cde_usedname2 + "</div>");
                                    }
                                } else {
                                    $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='cde_usedname2' contenteditable='true'>" + obj.data[i].cde_usedname2 + "</div>");
                                }

                                if (obj.data[i].cde_usedname3 == '') {
                                    if (obj.data[i].cde_usedname2 == '') {
                                        $(this).find('td').eq(3).html("<div style='width:100%;min-height:23px;' class='cde_usedname3' contenteditable='false'>" + obj.data[i].cde_usedname3 + "</div>");
                                    } else {
                                        $(this).find('td').eq(3).html("<div style='width:100%;min-height:23px;' class='cde_usedname3' contenteditable='true'>" + obj.data[i].cde_usedname3 + "</div>");
                                    }
                                } else {
                                    $(this).find('td').eq(3).html("<div style='width:100%;min-height:23px;' class='cde_usedname3' contenteditable='true'>" + obj.data[i].cde_usedname3 + "</div>");
                                }

                                if (obj.data[i].cde_usedname4 == '') {
                                    if (obj.data[i].cde_usedname3 == '') {
                                        $(this).find('td').eq(4).html("<div style='width:100%;min-height:23px;' class='cde_usedname4' contenteditable='false'>" + obj.data[i].cde_usedname4 + "</div>");
                                    } else {
                                        $(this).find('td').eq(4).html("<div style='width:100%;min-height:23px;' class='cde_usedname4' contenteditable='true'>" + obj.data[i].cde_usedname4 + "</div>");
                                    }
                                } else {
                                    $(this).find('td').eq(4).html("<div style='width:100%;min-height:23px;' class='cde_usedname4' contenteditable='true'>" + obj.data[i].cde_usedname4 + "</div>");
                                }

                                if (obj.data[i].cde_usedname5 == '') {
                                    if (obj.data[i].cde_usedname4 == '') {
                                        $(this).find('td').eq(5).html("<div style='width:100%;min-height:23px;' class='cde_usedname5' contenteditable='false'>" + obj.data[i].cde_usedname5 + "</div>");
                                    } else {
                                        $(this).find('td').eq(5).html("<div style='width:100%;min-height:23px;' class='cde_usedname5' contenteditable='true'>" + obj.data[i].cde_usedname5 + "</div>");
                                    }
                                } else {
                                    $(this).find('td').eq(5).html("<div style='width:100%;min-height:23px;' class='cde_usedname5' contenteditable='true'>" + obj.data[i].cde_usedname5 + "</div>");
                                }

                                i++;
                            })
                        }
                    });
                }

                $(document).on('blur', 'input[name=cde_usedname]', function () {
                    if ($(this).val() != '') {
                        $('input[name=cde_usedname2]').removeAttr('disabled');
                    } else {
                        $('input[name=cde_usedname2]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname2]').val('');
                        $('input[name=cde_usedname3]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname3]').val('');
                        $('input[name=cde_usedname4]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname4]').val('');
                        $('input[name=cde_usedname5]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname5]').val('');
                    }
                });
                
                $(document).on('blur', 'input[name=cde_usedname2]', function () {
                    if ($(this).val() != '') {
                        if($(this).val() == $('input[name=cde_usedname]').val()){
                            alert('药品别名有重复！');
                            $(this).val('');
                        }else{
                            $('input[name=cde_usedname3]').removeAttr('disabled');
                        }
                    } else {
                        $('input[name=cde_usedname3]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname3]').val('');
                        $('input[name=cde_usedname4]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname4]').val('');
                        $('input[name=cde_usedname5]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname5]').val('');
                    }
                });
                
                $(document).on('blur', 'input[name=cde_usedname3]', function () {
                    if ($(this).val() != '') {
                        if($(this).val() == $('input[name=cde_usedname]').val() || $(this).val() == $('input[name=cde_usedname2]').val()){
                            alert('药品别名有重复！');
                            $(this).val('');
                        }else{
                            $('input[name=cde_usedname4]').removeAttr('disabled');
                        }
                    } else {
                        $('input[name=cde_usedname4]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname4]').val('');
                        $('input[name=cde_usedname5]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname5]').val('');
                    }
                });

                $(document).on('blur', 'input[name=cde_usedname4]', function () {
                    if ($(this).val() != '') {
                        if($(this).val() == $('input[name=cde_usedname]').val() || $(this).val() == $('input[name=cde_usedname2]').val() || $(this).val() == $('input[name=cde_usedname3]').val()){
                            alert('药品别名有重复！');
                            $(this).val('');
                        }else{
                            $('input[name=cde_usedname5]').removeAttr('disabled');
                        }
                    } else {
                        $('input[name=cde_usedname5]').attr('disabled', 'disabled');
                        $('input[name=cde_usedname5]').val('');
                    }
                });

                $(document).on('blur', 'input[name=cde_usedname5]',function(){
                    if($(this).val() != ''){
                        if($(this).val() == $('input[name=cde_usedname]').val() || $(this).val() == $('input[name=cde_usedname2]').val() || $(this).val() == $('input[name=cde_usedname3]').val() || $(this).val() == $('input[name=cde_usedname4]').val()){
                            alert('药品别名有重复！');
                            $(this).val('');
                        }
                    }
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
                            $("#tip").html("<span style='color:red'>添加失败</span>");
                            alert('添加失败');
                        }
                    },
                    error: function () {
                        alert('重复添加！');
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
        <div style="padding-top:15px;">
			<div style="float: right; margin-right: 3%;">
				搜索:
				<input id="input" type="text" placeholder="">
				<button id="search" class="btn btn-primary btn-sm">Search</button>&nbsp;&nbsp;&nbsp;
				<button class="btn btn-primary btn-sm" data-toggle="modal"  data-target="#addUserModal" >添加药品名称</button>
			</div>
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
            <form method="post" action="" class="form-horizontal" role="form" id="form_data" onkeydown="if(event.keyCode==13)return false;" onsubmit="return check_form()" style="margin: 20px;">
                <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4 class="modal-title" id="myModalLabel">
                                    添加新药品名
                                </h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label for="cde_name" class="col-sm-3 control-label">药品名称</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cde_name" value=""
                                                   placeholder="请输入药品名称">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname" class="col-sm-3 control-label">药品别名1</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cde_usedname" value=""
                                                   placeholder="请输入药品别名1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname2" class="col-sm-3 control-label">药品别名2</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" disabled="disabled" name="cde_usedname2" value=""
                                                   placeholder="请输入药品别名2">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname3" class="col-sm-3 control-label">药品别名3</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" disabled="disabled" name="cde_usedname3" value=""
                                                   placeholder="请输入药品别名3">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname4" class="col-sm-3 control-label">药品别名4</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" disabled="disabled" name="cde_usedname4" value=""
                                                   placeholder="请输入药品别名4">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cde_usedname5" class="col-sm-3 control-label">药品别名5</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" disabled="disabled" name="cde_usedname5" value=""
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