<?php

function minify_js_url($files = array()) {
    $options = get_option('wp_minify_options');
    if($options['js_switch'] != 1) {
        $home_url = home_url();
        foreach($files as $file) {
            echo '<script src="'.$home_url.$file.'"></script>'."\r\n";
        }
        return;
    }

    $uri = '/??'.implode(',',$files);
    $file = dirname(WP_MINIFY).'/cache/'.md5($uri).'.js';
    if(file_exists($file)) $url = plugins_url(WP_MINIFY,'/cache/'.md5($uri).'.js');
    else $url = home_url($uri);
    echo '<script src="'.$url.'"></script>';
}

function minify_css_url($files = array()) {
    $options = get_option('wp_minify_options');
    if($options['css_switch'] != 1) {
        $home_url = home_url();
        foreach($files as $file) {
            echo '<link rel="stylesheet" href="'.$home_url.$file.'">'."\r\n";
        }
        return;
    }

    $uri = '/??'.implode(',',$files);
    $file = dirname(WP_MINIFY).'/cache/'.md5($uri).'.css';
    if(file_exists($file)) $url = plugins_url('/cache/'.md5($uri).'.css',WP_MINIFY);
    else $url = home_url($uri);
    echo '<link rel="stylesheet" href="'.$url.'">';
}
