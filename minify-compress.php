<?php

add_action('init','minify_compress');
function minify_compress() {

    $minify_css = function($css,$options = array()) {
        if(in_array('**',$options)) $css = preg_replace('#\/\*[^*]*\*+([^/][^*]*\*+)*\/#isU','',$css); // 去除注释
        if(in_array('++',$options)) $css = preg_replace("/\s+/"," ",$css); // 合并连续的空格
        if(in_array('nr',$options)) $css = str_replace(array("\r\n","\r","\n"),'',$css); // 去除换行

        if(in_array('s',$options)) { // 去除多余的元素
            $css = str_replace("\t",' ',$css);
            $css = preg_replace("/\s*(:|\{|\}|;)\s*/","$1",$css);
            $css = str_replace(';}','}',$css);
        }

        $css = trim($css);
        return $css;
    };

    $minify_js = function($js,$options = array()) {
        if(in_array('**',$options)) $js = preg_replace('#\/\*[^*]*\*+([^/][^*]*\*+)*\/#isU','',$js); // 去除块级注释
        if(in_array('nr',$options)) $js = preg_replace("/([\r]\n)+/","\r\n",$js); // 合并连续的换行
        if(in_array('++',$options)) $js = preg_replace("/\s+/"," ",$js); // 合并连续的空格

        if(in_array('//',$options)) { // 去除行级注释
            $js = preg_replace("/^\/\/[^\n]*\n/","",$js);
            $js = str_replace('http://','【:??】',$js);
            $js = str_replace('https://','【s:??】',$js);
            $js = preg_replace("/([\s|;|\(|\)|\{|\}])\/\/[^\n]*\n/","$1",$js);
            $js = str_replace('【:??】','http://',$js);
            $js = str_replace('【s:??】','https://',$js);
        }

        if(in_array('r',$options)) { // 去除多余的换行
            $js = str_replace(array("\r\n","\r","\n"),';',$js);
            $js = preg_replace("/;+/",";",$js);
        }

        if(in_array('s',$options)) { // 去除多余的空格
            $js = str_replace("\t",' ',$js);
            $js = preg_replace("/\s*(>|<|=|>=|<=|\?|:|==|===|\{|\}|;)\s*/","$1",$js);
        }

        $js = trim($js);
        return $js;
    };

    $uri = $_SERVER['REQUEST_URI'];
    if(strpos($uri,'/??') !== 0) return;

    $scripts = substr($uri,3);
    $scripts = explode(',',$scripts);

    $content = '';
    foreach($scripts as $script) {
        $file = ABSPATH.$script;
        if(file_exists($file)) $content .= file_get_contents($file);
    }
    $content = trim($content);

    $options = get_option('wp_minify_options');
    $ext = substr(strrchr($uri, '.'), 1);

	if($ext == 'css' && $options['css_replace'] && trim($options['css_replace']) != '') {
		$replaces = $options['css_replace'];
        $replaces = explode("\n",$replaces);
        foreach($replaces as $partten) {
            list($find,$replace) = explode('=>',$partten);
            $find = trim($find);
            $replace = trim($replace);
			$replace = str_replace(array('{SITE_URL}','{TEMPLATE}','{STYLESHEET}'),array(site_url(),get_template(),get_stylesheet()),$replace);
			$content = str_replace($find, $replace, $content);
        }
	}

    if($ext == 'css' && $options['css_switch'] == 1) {
        $opts = array();
        if($options['css_nl'] == 1) $opts[] = 'nr';
        if($options['css_cmm'] == 1) $opts[] = '**';
        if($options['css_ss'] == 1) $opts[] = '++';
        if($options['css_sg'] == 1) $opts[] = 's';
        $content = $minify_css($content,$opts);
    }
    elseif($ext == 'js' && $options['js_switch'] == 1) {
        $opts = array();
        if($options['js_nl'] == 1) $opts[] = 'nr';
        if($options['js_br'] == 1) $opts[] = 'r';
        if($options['js_cmms'] == 1) $opts[] = '**';
        if($options['js_cmm'] == 1) $opts[] = '//';
        if($options['js_ss'] == 1) $opts[] = '++';
        if($options['js_sg'] == 1) $opts[] = 's';
        $content = $minify_js($content,$opts);
    }

    file_put_contents(dirname(WP_MINIFY).'/cache/'.md5($uri).'.'.$ext,$content,LOCK_EX);

	if($ext == 'css') {
		header('Content-type: text/css; charset=utf-8');
	}
	elseif($ext == 'js') {
		header('Content-type: application/javascript; charset=utf-8');
	}
    echo $content;
    exit;
}