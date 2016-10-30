<?php

function wp_minify_js($files = array()) {
    $options = get_option('wp_minify_options');
    $version = '?version='.get_option('wp_minify_version');
    if($options['switch'] != 1) {
        $home_url = home_url();
        foreach($files as $file) {
            echo '<script src="'.$home_url.$file.($options['tail'] == 1 ? $version : '').'"></script>'."\r\n";
        }
        return;
    }

    $uri = '/??'.implode(',',$files);
    $file = dirname(WP_MINIFY).'/cache/'.md5($uri).'.js';
    if(file_exists($file)) $url = plugins_url('/cache/'.md5($uri).'.js',WP_MINIFY);
    else $url = home_url($uri);
    echo '<script src="'.$url.($options['tail'] == 1 ? $version : '').'"></script>';
}

function wp_minify_css($files = array()) {
    $options = get_option('wp_minify_options');
    $version = '?version='.get_option('wp_minify_version');
    if($options['switch'] != 1) {
        $home_url = home_url();
        foreach($files as $file) {
            echo '<link rel="stylesheet" href="'.$home_url.$file.($options['tail'] == 1 ? $version : '').'">'."\r\n";
        }
        return;
    }

    $uri = '/??'.implode(',',$files);
    $file = dirname(WP_MINIFY).'/cache/'.md5($uri).'.css';
    if(file_exists($file)) $url = plugins_url('/cache/'.md5($uri).'.css',WP_MINIFY);
    else $url = home_url($uri);
    echo '<link rel="stylesheet" href="'.$url.($options['tail'] == 1 ? $version : '').'">';
}

// Minify加载
class wp_minify_css_action {
	private $files;
	function __construct($files = array(),$hook = 'wp_head') {
		$this->files = $files;
		if($hook) add_action($hook,array($this,'action'));
	}
	function action() {
		$files = $this->files;
        wp_minify_css($files);
	}
	function add($file) {
	    $this->files[] = $file;
    }
}

class wp_minify_js_action {
	private $files;
	function __construct($files = array(),$hook = 'wp_footer') {
		$this->files = $files;
		if($hook) add_action($hook,array($this,'action'));
	}
	function action() {
		$files = $this->files;
        wp_minify_js($files);
	}
    function add($file) {
        $this->files[] = $file;
    }
}

/**
 * 两个action类并没有直接被使用，而是需要你在主题开发时实例化它们才会被调用。以wp_minify_js_action为例，可以在主题的wp_head()之前执行如下：
 * $wp_minify_js = new wp_minify_js_action(array('/wp-content/themes/yourtheme/js/jquery-2.1.4.min.js','/wp-content/themes/yourtheme/js/base.js'),'wp_head');
 * 在这个基础上，还可以调用$wp_minify_js->add('/wp-content/themes/yourtheme/js/another.js');继续追加脚本，实例化的时候可以hook参数为false，再在需要的时候调用$wp_js->action();
 * 这样就会直接在wp_head处输出对应的<script>标签，你可以在自己的主题functions.php中使用这个实现更加丰富的调用方式，这看你自己的创造力
 */