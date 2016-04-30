<?php

/*
Plugin Name: WP Minify
Plugin URI: http://www.tangshuang.net/wp-minify
Description: 为你的博客提供javascript和css压缩服务
Version: 10.0
Author: Tison
Author URI: http://www.tangshuang.net
Origin: https://github.com/tangshuang/wp-minify
*/

define('WP_MINIFY',__FILE__);

// 菜单
require 'minify-menu.php';
require 'minify-compress.php';
require 'minify-meta-head.php';
