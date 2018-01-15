/**
 * Created by monstar on 2017/11/14.
 */

'use strict';

// 系统JS需要参数，此部分参数不可更改
var Host = $('base').attr('href');
var Api_host = Host + 'admin/';
var Page = {};
var Public = {};

/* 自定义参数，可根据业务需要修改，均为全局变量 */
var imgSourcePath = Host + "public/images/"; // 图片资源公共网络路径
var allSuccessFiles = []; // 所有上传成功的文件，用作页面文件回显，每次使用前清空数据
var imgPublicPath = "/public/images/"; // 图片资源公共路径

// 系统JS访问路由设置
var Route = {
    user_list: 'user/list',
    user_opt: 'user/opt'
};



/* ========================================== 以下是页面JS方法部分 ========================================== */



/*
 * ajax统一请求方法(暂时未使用)
 * ajaxData object ajax请求需要的参数 格式：{url:"xxxxxxx", data:{"userId": 1, "userName": "张三"}}
 * */
Public.ajax = function (ajaxData) {
    var url = ajaxData["url"];
    var type = 'post';
    if(ajaxData.hasOwnProperty('type')){
        type = ajaxData["type"];
    }

    var data = ajaxData["data"];
    var returnData = {};
    $.ajax({
        url: url,
        type: type,
        data: data,
        async: false,
        success: function(res){
            console.info(res);
            if(res != ""){
                var res = JSON.parse(res);
                if(res.status = "200" && res.data != null){
                    returnData = res.data;
                }
            }
        },
        error: function(res){
            returnData = res;
        }
    });

    console.info(returnData);
    return returnData;
};

/*
 * ajax统一请求方法(暂时未使用)
 * ajaxData object ajax请求需要的参数 格式：{url:"xxxxxxx", data:{"userId": 1, "userName": "张三"}}
 * */
Public.ajax_t = function(ajaxData){
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

/*
 * 去除字符串空格
 * str string 需要进行去除空格的字符串
 * type string 空格去除类型
 * */
Public.trimStr = function(str, type){
    // 默认: 去除首尾空格
    var reg = /^\s+|\s+$/g;
    // all: 去除所有空格
    if(type == "all"){
        reg = /\s+/g;
    }
    // both: 去除两端的空格
    if(type == "both"){
        reg = /^\s+|\s+$/g;
    }
    // left: 去除最前面的空格
    if(type == "left"){
        reg = /^\s*/;
    }
    // right: 去除最后的空格
    if(type == "right"){
        reg = /(\s*$)/g;
    }
    return str.replace(reg, "");
};

/*
 * 界面跳转方法
 * url string 需要跳转的界面路径
 * type string 值为"get"或 "post" 请求跳转类型
 * parames object 页面跳转请求需要的参数
 * */
Public.redirect = function(url, type, parames){

}



/* ========================================== 以下是页面公共JS部分 ========================================== */



$(function(){
    var url = window.location.href;

    for(var key in Route){
        if (url.match(Route[key]) != null) {
            Page[key].init();
            break;
        }else{
            continue;
        }
    }
});



/* ========================================== 以下是页面JS部分 ========================================== */



Page.user_list = (function(){
    var test = function(){
        console.info('this is user list function !');
    };

    var get_user_list = function(){
        var ajaxData = {};
        ajaxData.url = "admin/user/list/data";
        ajaxData.data = {};

        var userList = Public.ajax(ajaxData);
        console.info(userList);
        $("#userList").html(bt("btUserList", {userList: userList}));
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

Page.user_opt = (function(){
    var test = function(){
        console.info('this is user opt function !');
    };

    // 获取用户信息数据，用于编辑和详情的回显
    var getUserInfo = function(){
        var userInfo = {};
        var userId = $("#userId").val();

        var ajaxData = {};
        ajaxData.url = Api_host + "user/info";
        ajaxData.data = {"userId": userId};

        userInfo = Public.ajax(ajaxData);

        return userInfo;
    };

    // 使用百度模版填充数据表单
    var setHtml = function(){
        var userInfo = getUserInfo();

        var html = bt("btUserDetail", {"data": userInfo});
        $("#userDetail").html(html);
    };

    // 暂时未使用
    var getFiles = function(){
        var ajaxData = {};
        ajaxData.url = "admin/files/get";
        ajaxData.data = {};

        var fileList = Public.ajax(ajaxData);
        console.info(fileList);
    };

    // 打开上传modal弹窗
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

    // 绑定modal弹窗的html
    var setModal = function(){
        $("#mmmmm").html("");
        var modalHtml = '<div class="modal fade"tabindex="-1"role="dialog"data-target=".bs-example-modal-lg"id="fileUploadModal"><div class="modal-dialog"role="document"><div class="modal-content"><div class="modal-header"><button type="button"class="close"data-dismiss="modal"aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">文件上传</h4></div><div class="modal-body"><div id="uploader"class="wu-example"><div class="queueList"><div id="dndArea"class="placeholder"><div id="filePicker"></div><p>或将照片拖到这里，单次最多可选300张</p></div></div><div class="statusBar"style="display:none;"><div class="progress"><span class="text">0%</span><span class="percentage"></span></div><div class="info"></div><div class="btns"><div id="filePicker2"></div><div class="uploadBtn">开始上传</div></div></div></div></div><div class="modal-footer"><button type="button"class="btn btn-default"data-dismiss="modal">Close</button></div></div></div></div>';
        $("#mmmmm").append(modalHtml);

        // 文件上传成功后,绑定页面回显事件
        showFiles();
    };

    // 动态延时加载upload.js文件
    var addJS = function(){
        var new_element=document.createElement("script");
        new_element.setAttribute("type","text/javascript");
        new_element.setAttribute("src","public/javascript/upload.js");
        document.body.appendChild(new_element);
    };

    // 上传完成后，页面的上传文件回显
    var showFiles = function(){
        $('#fileUploadModal').on("hide.bs.modal", function(){
            console.info(allSuccessFiles);
            var fileHtml = "";
            var filePath = "";
            var len = allSuccessFiles.length;
            if(len > 0){
                for(var m = 0; m < len; m++){
                    var fileName = allSuccessFiles[m]["file_name"];
                    var fileExt = allSuccessFiles[m]["file_ext"];
                    fileExt = fileExt.substring(1).toLowerCase();
                    var imageType = allSuccessFiles[m]["image_type"];

                    filePath = imgPublicPath + "test/" + fileName;

                    if(fileExt == ""){
                        fileHtml += "<img src='" + imgSourcePath + "fileType/ini.png' />";
                    }else if(imageType == "jpeg"){
                        fileHtml += "<img src='" + imgSourcePath + "test/" + fileName + "' />";
                    }else{
                        fileHtml += '<img src="'+imgSourcePath+'fileType/'+fileExt+'.png'+'" onerror="javascript:this.src='+imgSourcePath+'fileType/ini.png'+'" />';
                    }
                }
            }
            $("#fileList").html("");
            $("#fileList").append(fileHtml);
            $("#filePath").val(filePath);
        });
    };

    // 提交按钮方法
    var submit = function(){
        $("#btn_submit").click(function(){
            // 获取所有输入信息
            var userName = Public.trimStr($("#userName").val());
            var realName = Public.trimStr($("#realName").val());
            var headImage = Public.trimStr($("#filePath").val());
            var email = Public.trimStr($("#email").val());
            var phoneNumber = Public.trimStr($("#phoneNumber").val());
            var password = Public.trimStr($("#password").val());
            var passwordConf = Public.trimStr($("#passwordConf").val());
            var roleId = Public.trimStr($("#userRole").val());
            var status = Public.trimStr($('#userStatus input[name="optionsRadios"]:checked').val());

            // 验证输入信息
            if(userName == ""){
                alert("用户名不能为空！");
                return false;
            }
            if(realName == ""){
                alert("真实姓名不能为空！");
                return false;
            }
            if(email == ""){
                alert("邮箱不能为空！");
                return false;
            }
            if(password == ""){
                alert("密码不能为空！");
                return false;
            }
            if(password != passwordConf){
                alert("两次输入的密码不一致！");
                return false;
            }

            var data = {};
            data.userName = userName;
            data.realName = realName;
            data.headImage = headImage;
            data.email = email;
            data.phoneNumber = phoneNumber;
            data.password = password;
            data.roleId = roleId;
            data.status = status;

            console.info(data);

            var ajaxData = {};
            ajaxData.url = Api_host + "user/handle";
            ajaxData.data = data;

            var userInfo = Public.ajax(ajaxData);
        });
    };



    var bind = function(){
        test();
        setHtml();
        openUploadModal();
        submit();
    };

    var init = function () {
        bind();
    };

    return {
        init: init
    }
})();