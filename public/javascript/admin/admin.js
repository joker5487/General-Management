/**
 * Created by monstar on 2017/11/14.
 */

'use strict';

// 系统JS需要参数，此部分参数不可更改
var Host = $('base').attr('href');
var Api_host = Host + 'admin';
var Page = {};
var Public = {};

/* 自定义参数，可根据业务需要修改，均为全局变量 */
var imgSourcePath = Host + "public/images/"; // 图片资源公共网络路径
var allSuccessFiles = []; // 所有上传成功的文件，用作页面文件回显，每次使用前清空数据

// 系统JS访问路由设置
var Route = {
    user_list: 'user/list',
    user_add: 'user/opt/add'
};

// ajax统一请求方法
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
            setModal();
            $('#fileUploadModal').modal({
                "show": true,
                "backdrop": "static",
                "keyboard": false
            });
            $('#fileUploadModal').on("shown.bs.modal", function(){
                addJS();
            });

        });
    };

    var setModal = function(){
        $("#mmmmm").html("");
        var modalHtml = '<div class="modal fade"tabindex="-1"role="dialog"data-target=".bs-example-modal-lg"id="fileUploadModal"><div class="modal-dialog"role="document"><div class="modal-content"><div class="modal-header"><button type="button"class="close"data-dismiss="modal"aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">文件上传</h4></div><div class="modal-body"><div id="uploader"class="wu-example"><div class="queueList"><div id="dndArea"class="placeholder"><div id="filePicker"></div><p>或将照片拖到这里，单次最多可选300张</p></div></div><div class="statusBar"style="display:none;"><div class="progress"><span class="text">0%</span><span class="percentage"></span></div><div class="info"></div><div class="btns"><div id="filePicker2"></div><div class="uploadBtn">开始上传</div></div></div></div></div><div class="modal-footer"><button type="button"class="btn btn-default"data-dismiss="modal">Close</button></div></div></div></div>';
        $("#mmmmm").append(modalHtml);

        // 文件上传成功后页面回显
        showFiles();
    };

    var addJS = function(){
        var new_element=document.createElement("script");
        new_element.setAttribute("type","text/javascript");
        new_element.setAttribute("src","public/javascript/upload.js");
        document.body.appendChild(new_element);
    };

    var showFiles = function(){
        $('#fileUploadModal').on("hide.bs.modal", function(){
            console.info(allSuccessFiles);
            var imgHtml = "";
            var len = allSuccessFiles.length;
            for(var m = 0; m < len; m++){
                imgHtml += "<img src='" + imgSourcePath + "test/" + allSuccessFiles[m]["file_name"] + "' />";
            }
            $("#imgList").append(imgHtml)
        });
    };

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