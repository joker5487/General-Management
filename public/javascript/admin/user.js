/**
 * Created by monstar on 2017/11/27.
 */
$(function(){
    var baseUrl = $('base').attr('href');

    get_user_list();

    function ajax(url, data){
        var returnData = {};
        $.ajax({
            url: url,
            type: "post",
            data: data,
            async: false,
            success: function(res){
                if(res != "" && res != null){
                    returnData = JSON.parse(res);
                }
            },
            error: function(res){
                returnData = res;
            }
        });

        return returnData;
    }

    function get_user_list(){
        var data = {};
        var url = "admin/user/list/data";

        var userList = ajax(url, data);

        $("#userList").html(bt("btUserList", {userList: userList.data}));
    }
});