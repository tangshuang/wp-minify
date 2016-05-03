<?php

function wp_minify_js($files = array()) {
    $options = get_option('wp_minify_options');
    if($options['switch'] != 1) {
        $home_url = home_url();
        foreach($files as $file) {
            echo '<script src="'.$home_url.$file.'"></script>'."\r\n";
        }
        return;
    }

    $uri = '/??'.implode(',',$files);
    $file = dirname(WP_MINIFY).'/cache/'.md5($uri).'.js';
    if(file_exists($file)) $url = plugins_url('/cache/'.md5($uri).'.js',WP_MINIFY);
    else $url = home_url($uri);
    echo '<script src="'.$url.'"></script>';
}

function wp_minify_css($files = array()) {
    $options = get_option('wp_minify_options');
    if($options['switch'] != 1) {
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

// Minify加载
class wp_minify_css_action {
	private $files;
	function __construct($files) {
		$this->files = $files;
		add_action('wp_head',array($this,'css_action'));
	}
	function css_action() {
		$files = $this->files;
		if(function_exists('wp_minify_css')) {
			wp_minify_css($files);
		}
		else {
			$site_url = site_url();
			foreach($files as $file) {
				echo '<link rel="stylesheet" href="'.$site_url.$file.'">';
			}
		}
	}
}

class wp_minify_js_action {
	private $files;
	function __construct($files) {
		$this->files = $files;
		add_action('wp_footer',array($this,'js_action'));
	}
	function js_action() {
		$files = $this->files;
		if(function_exists('wp_minify_js')) {
			wp_minify_js($files);
		}
		else {
			$site_url = site_url();
			foreach($files as $file) {
				echo '<script src="'.$site_url.$file.'"></script>';
			}
		}
	}
}