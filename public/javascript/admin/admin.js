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

        var $ = jQuery,
            $list = $('#fileList'),
            // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

            // 缩略图大小
            thumbnailWidth = 100 * ratio,
            thumbnailHeight = 100 * ratio,

            // Web Uploader实例
            uploader;

        // 初始化Web Uploader
        uploader = WebUploader.create({

            // 自动上传。
            auto: true,

            // swf文件路径
            swf: Host + 'public/javascript/Uploader.swf',

            // 文件接收服务端。
            server: 'http://webuploader.duapp.com/server/fileupload.php',

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker',

            // 只允许选择文件，可选。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        // 当有文件添加进来的时候
        uploader.on( 'fileQueued', function( file ) {
            var $li = $(
                '<div id="' + file.id + '" class="file-item thumbnail">' +
                '<img>' +
                '<div class="info">' + file.name + '</div>' +
                '</div>'
                ),
                $img = $li.find('img');

            $list.append( $li );

            // 创建缩略图
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $img.attr( 'src', src );
            }, thumbnailWidth, thumbnailHeight );
        });

        // 文件上传过程中创建进度条实时显示。
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#'+file.id ),
                $percent = $li.find('.progress span');

            // 避免重复创建
            if ( !$percent.length ) {
                $percent = $('<p class="progress"><span></span></p>')
                    .appendTo( $li )
                    .find('span');
            }

            $percent.css( 'width', percentage * 100 + '%' );
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function( file ) {
            $( '#'+file.id ).addClass('upload-state-done');
        });

        // 文件上传失败，现实上传出错。
        uploader.on( 'uploadError', function( file ) {
            var $li = $( '#'+file.id ),
                $error = $li.find('div.error');

            // 避免重复创建
            if ( !$error.length ) {
                $error = $('<div class="error"></div>').appendTo( $li );
            }

            $error.text('上传失败');
        });

        // 完成上传完了，成功或者失败，先删除进度条。
        uploader.on( 'uploadComplete', function( file ) {
            $( '#'+file.id ).find('.progress').remove();
        });
    };

    var bind = function(){
        test();
    };

    var init = function () {
        bind();
    };

    return {
        init: init
    }
})();