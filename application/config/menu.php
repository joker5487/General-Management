<?php
/**
 * Created by PhpStorm.
 * User: monstar
 * Date: 2017/11/13
 * Time: 下午4:48
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$config['asideMenu'] = array(
    array(
        'menu' => 'User',
        'icon' => 'dashboard',
        'path' => 'admin/user/list',
        'subMenu' => array()
    ),
    array(
        'menu' => 'System Logs',
        'icon' => 'dashboard',
        'path' => 'admin/logs/show',
        'subMenu' => array()
    ),
    array(
        'menu' => 'Menu2',
        'icon' => 'dashboard',
        'path' => '',
        'subMenu' => array(
            array(
                'menu' => 'Menu2-1',
                'icon' => '',
                'path' => '',
            ),
            array(
                'menu' => 'Menu2-2',
                'icon' => '',
                'path' => '',
            )
        )
    ),
    array(
        'menu' => 'Menu3',
        'icon' => 'dashboard',
        'path' => '',
        'subMenu' => array(
            array(
                'menu' => 'Menu3-1',
                'icon' => '',
                'path' => '',
            ),
            array(
                'menu' => 'Menu3-2',
                'icon' => '',
                'path' => '',
            )
        )
    )
);