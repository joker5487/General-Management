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
var paginationNum = 20; // 分页参数，每页展示的数据记录数，应该和后台分页查询的limit值保持一致

// 系统JS访问路由设置
var Route = {
    user_list: 'user/list',
    user_opt: 'user/opt',
    school_list: 'school/list'
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
            //console.info(res);
            if(res != ""){
                var res = JSON.parse(res);
                if(res.status == "200" && res.data != null){
                    returnData = res;
                }
            }
        },
        error: function(res){
            res.status = "-1";
            returnData = res;
        }
    });

    //console.info(returnData);
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
 * 页面跳转方法
 * path string 需要跳转的页面路径
 * target string 标识 跳转式样（新开标签或者本页面跳转）
 * */
Public.jump = function(path, target){
    target = target ? "open" : "";
    path = path ? path : "";
    if(path) {
        if (target) {
            window.open(path);
        } else {
            window.location.href = path;
        }
    }
};

/*
 * 加载Modal弹窗的方法
 * dialogId string 标识 modal弹窗的Id
 * */
Public.addUploadDialog = function(dialogId, folderPath, type, data){
    var modalId = 'myModal' + dialogId;

    folderPath = folderPath ? folderPath : "";
    folderPath = Public.Chain("path").formatUrl(folderPath).getPath();

    var showText = "";
    if(type == 'image'){
        showText = "或将照片拖到这里，单次最多可选" + data.fileNumLimit + "张";
    }
    if(type == "file"){
        showText = "或将文件拖到这里，单次最多可选" + data.fileNumLimit + "个";
    }


    // 绑定modal弹窗的html
    $("#mmmmm").html("");
    var modalHtml = '<div class="modal fade"tabindex="-1"role="dialog"data-target=".bs-example-modal-lg"id="'+modalId+'"><input type="hidden" id="folderPath" value="'+folderPath+'" /><div class="modal-dialog"role="document"><div class="modal-content"><div class="modal-header"><button type="button"class="close"data-dismiss="modal"aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">文件上传</h4></div><div class="modal-body"><div id="uploader"class="wu-example"><div class="queueList"><div id="dndArea"class="placeholder"><div id="filePicker"></div><p>' + showText + '</p></div></div><div class="statusBar"style="display:none;"><div class="progress"><span class="text">0%</span><span class="percentage"></span></div><div class="info"></div><div class="btns"><div id="filePicker2"></div><div class="uploadBtn">开始上传</div></div></div></div></div><div class="modal-footer"><button type="button"class="btn btn-default"data-dismiss="modal">Close</button></div></div></div></div>';
    $("#mmmmm").append(modalHtml);

    $('#' + modalId).modal({
        "show": true,
        "backdrop": "static",
        "keyboard": false
    });
    $('#' + modalId).on("shown.bs.modal", function(){
        var jsUrl = "public/javascript/upload.js";
        Public.addJS(jsUrl, data);
    });
};

/*
 * 动态延时加载upload.js文件
 * jsUrl string 需要加载的js文件历经
 * data array 需要动态传入的参数,其中key需要和js文件中的取值方式完全对应 格式： ["key1": "val1", "key2": "val2"]
 * */
Public.addJS = function(jsUrl, data){
    var new_element=document.createElement("script");
    new_element.setAttribute("type","text/javascript");
    new_element.setAttribute("src", jsUrl);

    // 向引用的js文件传递相应的参数
    if(data){
        new_element.setAttribute("id", "jsParamScript");

        for(var itemKey in data){
            var itemValue = data[itemKey];
            new_element.setAttribute(itemKey, itemValue);
        }

        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(new_element, s);
    }

    document.body.appendChild(new_element);
};

/*
 * 上传完成后，页面的上传文件回显
 * dialogId string 标识 modal弹窗的Id
 * folderPath string 用做回显的文件夹路径
 * */
Public.showFiles = function(dialogId, folderPath){
    var id = "myModal" + dialogId;
    $('#' + id).on("hide.bs.modal", function(){
        //console.info(allSuccessFiles);
        var fileHtml = "";
        var filePath = "";
        var newFilePath = filePath;
        var len = allSuccessFiles.length;
        if(len > 0){
            for(var m = 0; m < len; m++){
                var fileName = allSuccessFiles[m]["file_name"];
                var fileExt = allSuccessFiles[m]["file_ext"];
                fileExt = fileExt.substring(1).toLowerCase();
                var imageType = allSuccessFiles[m]["image_type"];

                filePath = folderPath + fileName;

                if(fileExt == ""){
                    fileHtml += "<img src='" + imgSourcePath + "fileType/ini.png' />";
                }else if(imageType == "jpeg"){
                    fileHtml += "<img src='" + imgSourcePath + "test/" + fileName + "' />";
                }else{
                    fileHtml += '<img src="' + imgSourcePath + 'fileType/' + fileExt + '.png' + '" onerror="javascript:this.src=' + imgSourcePath + 'fileType/ini.png' + '" />';
                }
            }

            $("#fileList").html("");
            $("#fileList").append(fileHtml);
            $("#filePath").val(filePath);
        }
    });
};


/* ========================================== 以下是页面JS链式方法部分 ========================================== */



/*
 * 路径链式方法 此方法提供给Api接口路径的格式化链式操作
 * setUrl fn 初始化Api路径的方法。如果没有路径传入，路径为初始路径；如果有路径传入，则拼接成新的路径。
 * setParam fn 格式化参数的方法。当一个路径中需要有动态参数的时候使用，路径中参数格式【{0},{1}】，方法中传入的参数是路径中对应参数个数的一维数组。
 * getPath 返回新路径的方法。一般用在链式操作最后。返回链式操作的最终路径
 * */
function path () {};
path.prototype = {//扩展它的prototype
    formatUrl: function(path){
        var newPath = path.toLocaleLowerCase();
        if(newPath.indexOf("http://") === -1 && newPath.indexOf("https://") === -1){
            this.path = path.replace(/\/+/g, '/');
        }else{
            var pathArr = path.split("://");
            this.path = pathArr[0] + "://" + pathArr[1].replace(/\/\//g, '/');
        }

        return this;
    },
    setUrl: function (path) {
        var apiHost = Public.trimStr(Api_host);
        var newPath = apiHost;

        var ending = apiHost.substr(-1, 1);
        if(ending !== "/"){
            apiHost += "/";
        }
        if(path){
            var starting = path.substr(0, 1);
            if(starting === "/"){
                newPath = apiHost + path.substr(1);
            }else{
                newPath = apiHost + path;
            }
        }
        console.info("set path");
        this.path = newPath;
        return this;
    },
    setParam: function(paramArr){
        var newPath = this.path;
        if(paramArr){
            var len = paramArr.length;
            for (var i = 0; i < len; i++) {
                var regexp = new RegExp('\\{' + i + '\\}', 'gi');
                newPath = newPath.replace(regexp, paramArr[i])
            }
        }
        console.info("set param");
        this.path = newPath;
        return this;
    },
    getPath:function (){
        return this.path;
    }
};


// 此方法为测试用
function test () {};
test.prototype = {
    setNum: function (num) {
        this.num = num;

        return  this;
    },
    getNum: function () {
        return this.num;
    }
};

//工厂函数
Public.Chain = function(chain) {
    chain = chain.toLowerCase();
    //return new chain;
    return new window[chain];
};


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

    // 获取用户列表
    var get_user_list = function(){
        var pageNum = Public.trimStr($("#currentPage").val());
        pageNum = pageNum ? pageNum : 1;

        var ajaxData = {};
        ajaxData.url = Public.Chain("path").setUrl("user/list/data").getPath();
        ajaxData.data = {"pageNum": pageNum};

        var userList = Public.ajax(ajaxData);
        return userList;
    };

    // 渲染页面数据
    var set_html = function(){
        var data = get_user_list();
        var userList = data.data.userList;
        var nextFlg = data.data.nextFlg;
        $("#userList").html(bt("btUserList", {userList: userList}));

        // 获取当前页码
        var pageNum = Public.trimStr($("#currentPage").val());
        if(pageNum < 1){
            pageNum = 1;
        }

        // 根据返回数据 控制 分页按钮层 是否显示
        if(!nextFlg && userList.length <= paginationNum && pageNum == 1){
            $("#pagination").css({"display": "none"});
        }

        // 控制 上一页 按钮的点击状态
        if(pageNum == 1){
            $("#btn_prev").attr("disabled", true);
        }else{
            $("#btn_prev").attr("disabled", false);
        }

        // 根据返回数据 控制 下一页 按钮的点击状态
        if(nextFlg){
            $("#btn_next").attr("disabled", false);
        }else{
            $("#btn_next").attr("disabled", true);
        }

        // 用户列表项点击事件
        item_click();
    };

    // 上一页按钮事件
    var btn_prev = function(){
        $("#btn_prev").click(function(){
            var pageNum = parseInt(Public.trimStr($("#currentPage").val())) - 1;
            if(pageNum < 1){
                pageNum = 1;
            }
            $("#currentPage").val(pageNum);

            set_html();
        });
    };

    // 下一页按钮事件
    var btn_next = function(){
        $("#btn_next").click(function(){
            var pageNum = parseInt(Public.trimStr($("#currentPage").val())) + 1;
            if(pageNum < 1){
                pageNum = 1;
            }
            $("#currentPage").val(pageNum);

            set_html();
        });
    };

    // 用户列表项点击事件
    var item_click = function(){
        $("#userList tr").click(function(){
            var userId = Public.trimStr($(this).attr("data-userId"));
            var paramArr = [userId];
            var url = Public.Chain("path").setUrl("user/opt/{0}").setParam(paramArr).getPath();
            Public.jump(url);
        });
    };

    // 添加用户按钮事件
    var btn_user_add = function(){
        $("#btn_user_add").click(function(){
            var url = Public.Chain("path").setUrl("user/opt").getPath();
            Public.jump(url);
        });
    };

    // 导出按钮事件
    var user_export = function(){
        $("#btn_user_export").click(function(){
            var url = Public.Chain("path").setUrl("excel/export").getPath();
            location.href = url;
        });
    };

    // 导入按钮事件，打开上传modal弹窗
    var openUploadModal = function(){
        $("#btn_user_import").click(function(){
            var dir = "listTest";
            var data = {
                "fileNumLimit": 1,
                "fileSizeLimit": 5,
                "fileSingleSizeLimit": 1
            };
            var type = "file";
            Public.addUploadDialog('userImport', dir, type, data);
        });
    };

    var bind = function(){
        test();
        set_html();
        btn_user_add();
        btn_prev();
        btn_next();
        user_export();
        openUploadModal();
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
        //var str = fnString.path();
        var path = Public.Chain("path").setUrl("/qweq/qweqw/eqw/e/qwe/qwe/qw/").getPath();
        var num = Public.Chain("TesT").setNum(12345).getNum();
        console.log(num);
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

        var html = bt("btUserDetail", {"data": userInfo.data});
        $("#userDetail").html(html);
    };

    // 暂时未使用
    var getFiles = function(){
        var ajaxData = {};
        ajaxData.url = "admin/files/get";
        ajaxData.data = {};

        var fileList = Public.ajax(ajaxData);
    };

    // 打开上传modal弹窗
    var openUploadModal = function(){
        $("#fileUpload").click(function(){
            var dir = "test/";
            var data = {
                "fileNumLimit": 1,
                "fileSizeLimit": 5,
                "fileSingleSizeLimit": 1
            };
            var type = "image";
            Public.addUploadDialog('userHeadImg', dir, type, data);

            // 文件上传成功后,绑定页面回显事件
            Public.showFiles("userHeadImg", imgPublicPath + dir);
        });
    };

    // 提交按钮方法
    var submit = function(){
        $("#btn_submit").click(function(){
            // 获取所有输入信息
            var userId = Public.trimStr($("#userId").val());
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
            data.userId = userId;
            data.userName = userName;
            data.realName = realName;
            data.headImage = headImage;
            data.email = email;
            data.phoneNumber = phoneNumber;
            data.password = password;
            data.roleId = roleId;
            data.status = status;

            //console.info(data);

            var ajaxData = {};
            ajaxData.url = Public.Chain("path").setUrl("user/handle").getPath(); // Api_host + "user/handle";
            ajaxData.data = data;

            var userInfo = Public.ajax(ajaxData);

            var msg = "用户添加失败";
            if(userId){
                var msg = "用户修改失败";
            }
            if(userInfo["status"] == "-1"){
                alert("errorCode: -1 " + msg);
            }else if(userInfo["status"] != "200"){
                alert(msg);
            }else{
                var url = Public.Chain("path").setUrl("user/list").getPath();
                Public.jump(url);
            }
        });
    };

    // 返回按钮方法
    var cancel = function(){
        $("#btn_cancel").click(function(){
            window.history.back(-1);
        });
    };

    var bind = function(){
        test();
        setHtml();
        openUploadModal();
        submit();
        cancel();
    };

    var init = function () {
        bind();
    };

    return {
        init: init
    }
})();





Page.school_list = (function(){
    var test = function(){
        console.log(123)
    };

    var getSelectValue = function(selectId){
        var selectVal = $("#" + selectId + " option:selected") .val();
        return selectVal;
    };

    var btnAdd = function(){
        $("#btnAdd").click(function(){
            var only = checkOnly();
            if(!only){
                console.log('!only');
                return false;
            }

            var schoolArea = getSelectValue("schoolArea");
            var schoolDep = getSelectValue("schoolDep");
            var schoolPost = getSelectValue("schoolPost");
            var schoolScore = $("#schoolScore").val();
            var userId = $("#userId").val();

            var addHtml = '<tr class="school-item" data-id="" data-userId="'+ userId +'"><td name="item-schoolArea">'+ schoolArea +'</td><td name="item-schoolDep">' + schoolDep + '</td><td name="item-schoolPost">' + schoolPost + '</td><td name="item-schoolScore">' + schoolScore + '</td><td><button type="button">删除</button></td></tr>';
            console.log(schoolArea, schoolDep, schoolPost, schoolScore, addHtml);

            $("#schoolList").append(addHtml);
            btnDel();
        });
    };

    var delIds = [];
    var btnDel = function(){
        console.log('fn_btndel')
        $("#schoolList button").click(function(){
            console.log('btndel_click');
            var trDom = $(this).parent().parent();
            var oldId = $(trDom).attr("data-id");
            if(oldId != ""){
                delIds.push(oldId);
            }
            $(trDom).remove();
        });
    };

    var btnSubmit = function(){
        $("#btnSubmit").click(function(){
            console.log("btnsubmit_click");
            var data = {};

            var allItem = [];

            var schoolList = $("#schoolList tr");
            schoolList.each(function(){
                var oldId = $(this).attr("data-id");
                if(oldId){
                    return true;
                }
                var schoolArea = $(this).find("[name='item-schoolArea']").text();
                var schoolDep = $(this).find("[name='item-schoolDep']").text();
                var schoolPost = $(this).find("[name='item-schoolPost']").text();
                var schoolScore = $(this).find("[name='item-schoolScore']").text();
                console.log(schoolArea, schoolDep, schoolPost, schoolScore);

                var schoolItem = {};
                schoolItem.userId = $("#userId").val();
                schoolItem.schoolArea = schoolArea;
                schoolItem.schoolDep = schoolDep;
                schoolItem.schoolPost = schoolPost;
                schoolItem.schoolScore = schoolScore;

                allItem.push(schoolItem);
            });

            data.schoolList = allItem;
            data.delIds = delIds;

            console.log(data);

            $.ajax({
                url: Api_host + "school/add",
                type: "post",
                data: data,
                success: function(res){
                    var res = JSON.parse(res);
                    console.log(res);
                    window.location.reload();
                },
                error: function(err){
                    console.log(err);
                }
            });
        });
    };

    var getData = function(){
        var schoolList = [];
        $.ajax({
            url: Api_host + "school/get",
            type: "post",
            data: {},
            async: false,
            success: function(res){
                var res = JSON.parse(res);
                schoolList = res.data;
                console.log(res);
            },
            error: function(err){
                console.log(err);
            }
        });

        return schoolList;
    };

    var initSchoolList = function(){
        var schoolList = getData();
        console.log("init", schoolList);
        var len = schoolList.length;

        var initHtml = "";
        for(var m = 0; m < len; m++){
            var item = schoolList[m];
            initHtml += '<tr class="school-item" data-id="' + item["id"] + '" data-userId="'+ item["userId"] +'"><td name="item-schoolArea">'+ item["schoolArea"] +'</td><td name="item-schoolDep">' + item["schoolDep"] + '</td><td name="item-schoolPost">' + item["schoolPost"] + '</td><td name="item-schoolScore">' + item["schoolScore"] + '</td><td><button type="button">删除</button></td></tr>';
        }

        $("#schoolList").append(initHtml);
        btnDel();
    };

    var checkOnly = function(){
        console.log("checkonly");
        var userId = $("#userId").val();
        if(userId == ""){
            alert("userId is can not be empty!");
            return false;
        }
        var schoolArea = $("#schoolArea option:selected") .val();

        var schoolList = $("#schoolList tr");
        schoolList.each(function(){
            var oldUserId = $(this).attr("data-userId");
            var oldSchoolArea = $(this).find("[name='item-schoolArea']").text();
            if(userId == oldUserId && oldSchoolArea == schoolArea){
                alert("is not only!");
                return false;
            }
        });

        return true;
    };

    var bind = function(){
        test();
        initSchoolList();
        btnAdd();
        btnSubmit();
    };

    var init = function () {
        bind();
    };

    return {
        init: init
    }
})();