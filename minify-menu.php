<?php

// 保存Minify设置的内容
add_action('admin_init','minify_add_admin_options_submenu_save');
function minify_add_admin_options_submenu_save(){
    if(isset($_GET['page']) && $_GET['page'] == 'minify' && isset($_REQUEST['action']) && strpos($_REQUEST['action'],'minify-') === 0){
        check_admin_referer();
        $action = $_REQUEST['action'];
        if($action == 'minify-update') {
            $options = $_POST['minify'];
            update_option('wp_minify_options',$options) || add_option('wp_minify_options',$options);
        }
        elseif($action == 'minify-clean') {
            $dir = WP_CONTENT_DIR.DIRECTORY_SEPARATOR.WP_MINIFY_CACHE_DIR;
            if(is_dir($dir)) {
                $op = dir($dir);
                while(false != ($item = $op->read())) {
                    if($item == '.' || $item == '..') continue;
                    $file = $dir.DIRECTORY_SEPARATOR.$item;
                    if(is_file($file)) unlink($file);
                }
            }
        }
        elseif($action == 'minify-version') {
            update_option('wp_minify_version',date('YmdHis'));
        }
        wp_redirect(admin_url('options-general.php?page=minify&saved=true&time='.time()));
        exit;
    }
}

// 创建后台设置页面
add_action('admin_menu','minify_add_admin_options_submenu');
function minify_add_admin_options_submenu(){
    add_options_page('Minify设置','Minify','edit_theme_options','minify','minify_add_admin_options_submenu_view');
}
function minify_add_admin_options_submenu_view(){
    if(@$_GET['saved'] == 'true')echo '<div id="message" class="updated fade"><p><strong>更新成功！</strong></p></div>';
    $options = get_option('wp_minify_options');
    ?>
    <style>
        .postbox h3,.postbox .inside {border-bottom: #f1f1f1 solid 1px;}
        .postbox > .inside:last-child {border-bottom: 0;}
    </style>
    <div class="wrap" id="minify-admin">
        <h2>Minify设置</h2>
        <div class="metabox-holder">
            <form method="post">
                <div class="postbox">
                    <h3>全局设置</h3>
                    <div class="inside">
                        <h4>开关</h4>
                        <p><select name="minify[switch]"><option value="0" <?php selected($options['switch'],0); ?>>关闭</option><option value="1" <?php selected($options['switch'],1); ?>>开启</option></select>Minify功能</p>
                        <p><small>关闭的时候，使用单个的文件一个一个链入，开启的情况下，合并为一个文件。</small></p>
                    </div>
                    <div class="inside">
						<h4>缓存</h4>
						<p><select name="minify[cache]"><option value="0" <?php selected($options['cache'],0); ?>>关闭</option><option value="1" <?php selected($options['cache'],1); ?>>开启</option></select>HTTP缓存功能</p>
						<p><select name="minify[file]"><option value="0" <?php selected($options['file'],0); ?>>关闭</option><option value="1" <?php selected($options['file'],1); ?>>开启</option></select>文件缓存功能，请确保你的wp-content目录有可写权限，缓存文件将放在wp-content/cache目录中。 <a href="<?php echo add_query_arg(array('action' => 'minify-clean','_wpnoce' => wp_create_nonce())); ?>" class="button">清空文件缓存</a></p>
                        <p><small>可以通过Ctrl+F5强制刷新页面来更新HTTP缓存。当你修改了脚本之后，也可以自己去删除对应的文件，如果你知道是哪一个的话。</small></p>
                    </div>
                    <div class="inside">
                        <h4>尾巴</h4>
                        <p><select name="minify[tail]"><option value="0" <?php selected($options['tail'],0); ?>>关闭</option><option value="1" <?php selected($options['tail'],1); ?>>开启</option></select>尾巴功能 <a href="<?php echo add_query_arg(array('action' => 'minify-version','_wpnoce' => wp_create_nonce())); ?>" class="button">更新版本</a></p>
                        <p><small>开启尾巴会在输出的css/js文件的url后面跟上一个<code>?version=<?php echo date('YmdHis'); ?></code>参数，方便更新和调试。生成缓存文件的时候会自动更新版本。</small></p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>Javascript规则</h3>
                    <div class="inside">
                        <h4>压缩</h4>
                        <p><select name="minify[js_switch]"><option value="0" <?php selected($options['js_switch'],0); ?>>关闭</option><option value="1" <?php selected($options['js_switch'],1); ?>>开启</option></select>Javascript压缩功能</p>
                        <p><small>仅在上面开启Minify功能时生效。关闭的情况下，仅连接文件内容，不进行压缩，原始代码将按原本的情况输出。</small></p>
                    </div>
                    <div class="inside">
                        <h4>压缩规则</h4>
                        <p><input type="checkbox" name="minify[js_nl]" value="1" <?php checked($options['js_nl'],1); ?>> 将多个换行合并为一个换行</p>
                        <p><input type="checkbox" name="minify[js_br]" value="1" <?php checked($options['js_br'],1); ?>> 去除多余换行，<small>注意<code>//</code>注释一定要去除，每行要以;结尾</small></p>
                        <p><input type="checkbox" name="minify[js_cmms]" value="1" <?php checked($options['js_cmms'],1); ?>> 去除块注释<code>/* ... */</code></p>
                        <p><input type="checkbox" name="minify[js_cmm]" value="1" <?php checked($options['js_cmm'],1); ?>> 去除行注释<code>// ...</code>，行注释和正则表达式中的<code>/\//g</code>有冲突，请确保你的脚本中不存在类似情况</p>
                        <p><input type="checkbox" name="minify[js_ss]" value="1" <?php checked($options['js_ss'],1); ?>> 将多个空格合并为一个空格</p>
                        <p><input type="checkbox" name="minify[js_sg]" value="1" <?php checked($options['js_sg'],1); ?>> 去除多余空格，比如<code> = </code>，把=前后的空格去除</p>
                        <p><small>仅在上面的压缩功能生效的时候有效，注意程序中避免与正则表达式冲突。</small></p>
                    </div>
                </div>
                <div class="postbox">
                    <h3>CSS规则</h3>
					<div class="inside">
                        <h4>路径替换</h4>
                        <p><textarea name="minify[css_replace]" class="large-text" rows="8"><?php echo stripcslashes($options['css_replace']); ?></textarea></p>
                        <p><small>如果你的css中引用了图片之类的，就要进行路径替换，否则css无法引用到正确的图片位置。比如我们可以这样进行设置：
<pre>../img/ => http://yourdomain.com/wp-content/themes/yourtheme/img/
../font/ => http://yourdomain.com/wp-content/themes/yourtheme/font/</pre>
                                类似的，注意，用 => 表示替换为
                            </small></p>
						<p><small>{SITE_URL}代表site_url(),{TEMPLATE}代表主题目录名称,{STYLESHEET}代表子主题目录名称</small></p>
                    </div>
                    <div class="inside">
                        <h4>压缩</h4>
                        <p><select name="minify[css_switch]"><option value="0" <?php selected($options['css_switch'],0); ?>>关闭</option><option value="1" <?php selected($options['css_switch'],1); ?>>开启</option></select>CSS压缩功能</p>
                        <p><small>仅在上面开启Minify功能时生效。关闭的情况下，仅连接文件内容，不进行压缩，原始代码将按原本的情况输出。</small></p>
                    </div>
                    <div class="inside">
                        <h4>压缩规则</h4>
                        <p><input type="checkbox" name="minify[css_nl]" value="1" <?php checked($options['css_nl'],1); ?>> 去除换行</p>
                        <p><input type="checkbox" name="minify[css_cmm]" value="1" <?php checked($options['css_cmm'],1); ?>> 去除注释<code>/* ... */</code></p>
                        <p><input type="checkbox" name="minify[css_ss]" value="1" <?php checked($options['css_ss'],1); ?>> 将多个空格合并为一个空格</p>
                        <p><input type="checkbox" name="minify[css_sg]" value="1" <?php checked($options['css_sg'],1); ?>> 去除多余元素，比如<code> : </code>，把:前后的空格去除，比如<code>;}</code>把}前面的;去掉</p>
                        <p><small>仅在上面的压缩功能生效的时候有效。</small></p>
                    </div>
                </div>
				<div class="postbox">
					<h3>其他规则</h3>
					<div class="inside">
						<h4>CDN</h4>
                        <p><small>注意：开启CDN功能不仅会替换生成的minify css/js文件中的url，而且连网页中的url也会被一并替换。另外，开启CDN功能，即使关闭minify主开关，网页中的替换功能也会生效。</small></p>
						<p><select name="minify[cdn_switch]"><option value="0" <?php selected($options['cdn_switch'],0); ?>>关闭</option><option value="1" <?php selected($options['cdn_switch'],1); ?>>开启</option></select>CDN镜像替换功能</p>
						<p>要替换的URL头：<input type="text" name="minify[cdn_find]" class="regular-text" value="<?php echo $options['cdn_find']; ?>" placeholder="<?php echo home_url(); ?>"></p>
						<p>URL头替换为：<input type="text" name="minify[cdn_replace]" class="regular-text" value="<?php echo $options['cdn_replace']; ?>" placeholder="http://xxx.cdn.qiniu.com"></p>
						<p>要替换的目录：<input type="text" name="minify[cdn_dirs]" class="regular-text" value="<?php echo $options['cdn_dirs']; ?>" placeholder="wp-content|wp-includes"></p>
						<p>要替换的文件后缀：<input type="text" name="minify[cdn_exts]" class="regular-text" value="<?php echo $options['cdn_exts']; ?>" placeholder="png|jpg|jpeg|gif|PNG|JPG|JPEG|GIF"></p>
					</div>
				</div>
                <div class="postbox">
                    <h3>说明</h3>
                    <div class="inside">
                        <ul>
                            <li>什么是Minify？通俗的说，就是把你的CSS和Javascript合并后输出，把原本多个js和css文件实现只用一个文件输出。</li>
                            <li>Minify有什么用？一般而言，主要可以实现减少http请求，减少服务器压力，加快网页加载，有利于SEO。</li>
                            <li>所有Minify后的文件，会在插件目录下的cache目录下。暂不支持修改。</li>
                        </ul>
                    </div>
                </div>
                <p class="submit">
                    <button type="submit" class="button-primary">提交</button>
                </p>
                <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
                <input type="hidden" name="action" value="minify-update" />
                <?php wp_nonce_field(); ?>
            </form>
        </div>
    </div>
<?php
}
