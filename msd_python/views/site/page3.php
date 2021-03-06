<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>相关临床实验</title>
        <link rel="stylesheet" href="../css/grid.paging.min.css"/>
        <link rel="stylesheet" type="text/css" href="../css/bsgrid.all.min.css"/>
        <script src="../js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/bsgrid.all.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="../js/grid.zh-CN.min.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="../js/grid.paging.min.js"></script>
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


            var gridObj;
            $(function () {
                getList(true);

                $(document).on('click', '.drop', function () {
                    // 创建Form  
                    var form = $('<form></form>').hide();
                    // 设置属性  
                    form.attr('action', "http://www.chinadrugtrials.org.cn/eap/clinicaltrials.searchlistdetail");
                    form.attr('method', 'post');
                    // form的target属性决定form在哪个页面提交  
                    // _self -> 当前页面 _blank -> 新页面  
                    form.attr('target', '_blank');
                    // 创建Input  
                    var my_input = $('<input type="text" name="ckm_index" />');
                    my_input.attr('value', 1);
                    // 附加到Form  
                    form.append(my_input);

                    var my_input = $('<input type="text" name="ckm_id" />');
                    my_input.attr('value', $(this).attr('lang'));
                    // 附加到Form  
                    form.append(my_input);

                    var my_input = $('<input type="text" name="rule" />');
                    my_input.attr('value', 'CTR');
                    // 附加到Form  
                    form.append(my_input);

                    var my_input = $('<input type="text" name="currentpage" />');
                    my_input.attr('value', 1);
                    // 附加到Form  
                    form.append(my_input);
                    $('body').append(form);

                    // 提交表单  
                    form.submit();
                    form.remove();
                    return false;
                })
            });

            function getList(showPage) {
                var code = GetQueryString('code');
                $('#searchTable_pt_outTab').remove();
                gridObj = $.fn.bsgrid.init('searchTable', {
                    url: host + 'python/getchinadrug',
                    //url:'http://172.20.15.1/msd_python/web/python/getlist',
                    //autoLoad: false,
                    pageSizeSelect: true,
                    pageSize: 20,
                    otherParames: {'cdeId': code},
                    complete: function (options, a, b) {
                        var obj = eval("(" + a.responseText + ")");
                        var i = 0;
                        $('#searchTable tbody tr').each(function () {
                            if (i >= obj.data.length) {
                                return false;
                            }
                            $(this).find('td').eq(0).html(i+1);
                            $(this).find('td').eq(1).html('<a class="drop" href="#" style="text-decoration:underline;" lang="' + obj.data[i].ckm_id + '">' + obj.data[i].code + '</a>');
                            $(this).find('td').eq(2).html(obj.data[i].first_date);
                            $(this).find('td').eq(3).html(obj.data[i].status);
                            $(this).find('td').eq(4).html(obj.data[i].drug_name);
                            $(this).find('td').eq(5).html(obj.data[i].company);
                            $(this).find('td').eq(6).html(obj.data[i].indications);
                            $(this).find('td').eq(7).html(obj.data[i].popular_topic);
                            //$(this).find('td').eq(1).html($(this).find('td').eq(1).html()+"<a href='page2.html?code="+obj.data[i].code+"'><span lang='div1'></span></a>")
                            i++;
                        })
                    }

                });




            }
            //function operate(record, rowIndex, colIndex, options) {
            //return '<a href="#" onclick="alert(\'ID=' + gridObj.getRecordIndexValue(record, 'ID') + '\');">Operate</a>';
            //}



        </script>
    </head>
    <body>
        <?php include "menu.php"; ?>
        <div style="padding-top:15px;"></div>
        <table id="searchTable" align="center" >
            <tr>
                    <!--<th w_check="true" width="3%;" title="全选"></th>-->       	
                <th width="4%;" class='rank'>序号</th>
                <th w_align="left" width="8%;">登记号</th>
                <th w_align="left" width="7%;">首次公示信息日期</th>
                <th width="10%;">试验状态</th>
                <th width="12%;">药品名称</th>
                <th width="18%;">企业名称</th>
                <th width="12%;">适应症</th>
                <th width="27%;">试验通俗题目</th>
            </tr>
        </table>
    </body>
</html>
