/**
 * Created by monstar on 2017/11/14.
 */

'use strict';

var Host = $('base').attr('href');
var Api_host = Host + 'admin';
var Page = {};
var Public = {};

var Route = {
    user_list: 'user/list',
    user_add: 'user/opt/add'
};

Public.ajax = function(ajaxData){
    var url = ajaxData["url"];
    var type = 'post';
    if(ajaxData.hasOwnProperty('type')){
        type = ajaxData["type"];
    }
    var data = ajaxData["data"];

    var deferred = $.Deferred();
    var opt = {
        url: url,
        type: type,
        data: data,
        timeout: 10000,
        success: deferred.resolve,
        error: deferred.reject
    };
    $.ajax(opt);
    return deferred.promise();
};

Public.ajax_t = function (ajaxData) {
    var url = ajaxData["url"];
    var type = 'post';
    if(ajaxData.hasOwnProperty('type')){
        type = ajaxData["type"];
    }

    console.info("===" + type);
    var data = ajaxData["data"];
    var returnData = {};
    $.ajax({
        url: url,
        type: type,
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
};

$(function(){
    var url = window.location.href;

    for(var key in Route){
        console.info(key);
        if (url.match(Route[key]) != null) {
            Page[key].init();
            break;
        }else{
            continue;
        }
    }
});

Page.user_list = (function(){
    var test = function(){
        console.info('this is user list function !');
    };

    var get_user_list = function(){
        var ajaxData = {};
        ajaxData.url = "admin/user/list/data";
        ajaxData.data = {};

        Public.ajax(ajaxData).done(function(res){
            var userList = JSON.parse(res);
            console.info(userList);
            $("#userList").html(bt("btUserList", {userList: userList.data}));
        }).fail(function(res){
            console.info(res);
        });
    }

    var bind = function(){
        test();
        get_user_list();
    };

    var init = function () {
        bind();
    };

    return {
        init: init
    }
})();

Page.user_add = (function(){
    var test = function(){
        console.info('this is user add function !');
    };

    var upload = function(){
        $(".uploadBtn").click(function(){
            var ajaxData = {};
            ajaxData.url = "admin/upload";
            ajaxData.data = new FormData($("#formInfo")[0]);

            console.info(ajaxData);
            //return false;

            $.ajax({
                url: ajaxData.url,
                type: "post",
                processData: false,
                contentType: false,
                data: ajaxData.data,
                success:function(res){
                    var res = JSON.parse(res);
                    console.info(res);
                },
                error:function(){
                    alert("ERROR");
                }
            });
        });
    }

    var openUploadModal = function(){
        $("#fileUpload").click(function(){
            $('#fileUploadModal').modal()
        });
    }

    var bind = function(){
        test();
        //upload();
        openUploadModal();
    };

    var init = function () {
        bind();
    };

    return {
        init: init
    }
})();