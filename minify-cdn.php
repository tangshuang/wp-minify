<?php

add_action('wp_loaded','minify_cdn_ob_start');

function minify_cdn_ob_start() {
    ob_start('minify_cdn_replace_url');
}

function minify_cdn_replace_url($content) {
    $options = get_option('wp_minify_options');
    if($options['cdn_switch'] != 1) {
        return $content;
    }

    $local_host = $options['cdn_find']; // 本地根路径
    $cdn_host = $options['cdn_replace']; // CDN根路径
    $cdn_exts   = $options['cdn_exts']; // 扩展名（使用|分隔）
    $cdn_dirs   = $options['cdn_dirs']; // 目录（使用|分隔）
    $cdn_dirs   = str_replace('-', '\-', $cdn_dirs);

    if($cdn_dirs) {
        $regex	=  '/' . str_replace('/', '\/', $local_host) . '\/((' . $cdn_dirs . ')\/[^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))/';
        $content =  preg_replace($regex, $cdn_host . '/$1', $content);
    }
    else {
        $regex	= '/' . str_replace('/', '\/', $local_host) . '\/([^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))/';
        $content =  preg_replace($regex, $cdn_host . '/$1', $content);
    }

    return $content;
}