<?php

/*
Plugin Name: WP MINIFY STATIC
Plugin URI: http://www.tangshuang.net/wp-minify
Description: 为你的博客提供javascript和css压缩服务
Version: 1.0.5
Author: 否子戈
Author URI: http://www.tangshuang.net
Origin: https://github.com/tangshuang/wp-minify
*/

define('WP_MINIFY',__FILE__);
define('WP_MINIFY_DIR',dirname(WP_MINIFY));
define('WP_MINIFY_CACHE_DIR','cache');


require(WP_MINIFY_DIR.'/minify-cdn.php');
require(WP_MINIFY_DIR.'/minify-compress.php');
require(WP_MINIFY_DIR.'/minify-meta-head.php');

require(WP_MINIFY_DIR.'/minify-menu.php');