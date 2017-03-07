<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>适应症类型</title>
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
                    url: host + 'indicationstypes/getlist',
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

                            $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='config_value' contenteditable='true'>" + obj.data[i].ephmra_atc_code + "</div>");
                            
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
                        $.post(host + 'indicationstypes/update', {'id': $(this).parents('tr').attr('lang'), 'ephmra_atc_code': $(this).text()})
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
                
                function search(){
                    $('#searchTable_pt_outTab').remove();
                    gridObj = $.fn.bsgrid.init('searchTable',{
                        url: host + '/indicationstypes/search',
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

                                $(this).attr('lang', obj.data[i].id);

                                $(this).find('td').eq(2).html("<div style='width:100%;min-height:23px;' class='config_value' contenteditable='true'>" + obj.data[i].ephmra_atc_code + "</div>");

                                i++;
                            })
                        }
                    });
                }
            });
        </script>
    </head>

    <body>
    <?php include "menu.php";?>
        <p style="margin: 10px;font-size: 20px;text-align: center;">适应症类配置</p>
        <div style="float: left; margin-right: 3%;">
            搜索:
            <input id="input" type="text" placeholder="">
            <button id="search" style="background-color:skyblue;FONT-SIZE:1.3rem;COLOR: white; ">Search</button>
        </div>
        <table id="searchTable" align="center">
            <tr>
                <th w_index="english_name" width="33%;" class='rank'>英文名</th>
                <th w_index="chinese_name" w_align="left" width="33%;">中文名</th>
                <th w_index="ephmra_atc_code" w_align="left" width="33%;">ephmra_atc_code</th>
            </tr>
        </table>
    </body>

</html>