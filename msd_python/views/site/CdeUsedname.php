<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>别名配置</title>
        <link rel="stylesheet" href="../css/grid.paging.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bsgrid.all.min.css" />
        <script src="../js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/bsgrid.all.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/grid.zh-CN.min.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="../js/grid.paging.min.js"></script>
        <script src="../js/setting.js" type="text/javascript" charset="utf-8"></script>
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
                        $('#searchTable tbody tr').each(function () {
                            if (i >= obj.data.length) {
                                return false;
                            }
                            $(this).attr('lang', obj.data[i].id);

                            $(this).find('td').eq(0).html("<div style='width:100%;min-height:23px;' class='cde_name' contenteditable='true'>" + obj.data[i].cde_name + "</div>");
                            $(this).find('td').eq(1).html("<div style='width:100%;min-height:23px;' class='cde_usedname' contenteditable='true'>" + obj.data[i].cde_usedname + "</div>");

                            i++;
                        })
                    }

                });



                $(document).on('focus', '.cde_name', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_name', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'cdeusedname/update', {'id': $(this).parents('tr').attr('lang'), 'cde_name': $(this).text(), 'cde_usedname': $('.cde_usedname').text()})
                    }
                });

                $(document).on('focus', '.cde_usedname', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.cde_usedname', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'cdeusedname/update', {'id': $(this).parents('tr').attr('lang'), 'cde_name': $('.cde_name').text(), 'cde_usedname': $(this).text()})
                    }
                });

                $("#search").click(function () {
                    console.log('ahduwia');
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

                                i++;
                            })
                        }
                    });
                }
            });
        </script>
    </head>

    <body>
        <?php include "menu.php"; ?>
        <p style="margin: 10px;font-size: 20px;text-align: center;">别名配置</p>
        <div style="float: left; margin-right: 3%;">
            搜索:
            <input id="input" type="text" placeholder="">
            <button id="search" style="background-color:skyblue;FONT-SIZE:1.3rem;COLOR: white; ">Search</button>
        </div>
        <table id="searchTable" align="center">
            <tr>
                <th w_index="" w_align="center" width="50%;" class='rank'>药品名称</th>
                <th w_index="" w_align="center" width="50%;">药品别名</th>
            </tr>
        </table>
    </body>

</html>