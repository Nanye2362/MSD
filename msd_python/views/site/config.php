<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>配置中心</title>
        <link rel="stylesheet" href="../css/grid.paging.min.css" />
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bsgrid.all.min.css" />
        <script src="../js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/bsgrid.all.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/grid.zh-CN.min.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="../js/grid.paging.min.js"></script>
        <script src="../js/setting.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
            var config_value;
            var gridObj;
            $(function () {
                $.fn.bsgrid.init('searchTable', {
                    //url: 'data/json.json',
                    url: host + 'configuration/getlist',
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

                            //标识建
                            $(this).attr('lang', obj.data[i].id);

                            $(this).find('td').eq(1).html("<div style='width:100%;min-height:23px;' class='config_value' contenteditable='true'>" + obj.data[i].value + "</div>");

                            i++;
                        })
                    }

                });


                //个人备注
                $(document).on('focus', '.config_value', function () {
                    config_value = $(this).text();
                })

                $(document).on('blur', '.config_value', function () {
                    if ($(this).text() != config_value) {
                        $.post(host + 'configuration/update', {'id': $(this).parents('tr').attr('lang'), "value": $(this).text()})
                    }
                })

                $("#search").click(function () {
                    search();
                });

                $("#input").keydown(function () {
                    if (event.keyCode == "13") { //keyCode=13是回车键
                        search();
                    }
                })

            });

            function search() {
                $('#searchTable_pt_outTab').remove();
                gridObj = $.fn.bsgrid.init('searchTable', {
                    url: host + 'configuration/search',
                    //autoLoad: false,
                    pageSizeSelect: true,
                    pageSize: 20,
                    otherParames: {
                        'searchText': $("#input").val(),
                        //'typeId': $('#select3').val(),
                        "uid": $('#inputuid').val()
                    },
                    complete: function (options, a, b) {
                        var obj = eval("(" + a.responseText + ")");
                        var i = 0;
                        $('#searchTable tbody tr').each(function () {
                            if (i >= obj.data.length) {
                                return false;
                            }

                            //标识建
                            $(this).attr('lang', obj.data[i].id);

                            $(this).find('td').eq(1).html("<div style='width:100%;min-height:23px;' class='config_value' contenteditable='true'>" + obj.data[i].value + "</div>");

                            i++;
                        })
                    }
                });
            }
        </script>
    </head>

    <body>
        <?php include "menu.php"; ?>
        <div style="padding-top:15px;">
            <div style="float: right; margin-right: 3%;">
                搜索:
                <input id="input" type="text" placeholder="">
                <button class="btn btn-primary btn-sm" id="search">Search</button>
            </div>
        </div>
        <table id="searchTable" align="center">
            <tr>
                <th w_index="name" width="33%;" class='rank'>标识健</th>
                <th w_index="value" w_align="left" width="33%;">配置值</th>
                <th w_index="note" w_align="left" width="33%;">配置说明</th>
            </tr>
        </table>
    </body>

</html>