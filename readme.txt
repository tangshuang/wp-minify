=== WP MINIFY STATIC ===
Contributors: 否子戈
Donate link: http://www.tangshuang.net/wp-minify
Tags: 合并,压缩,静态资源,加速
Requires at least: 3.5.1
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

合并压缩网站内的静态资源，从而加速网站的打开速度。

== Description ==

将网站内的多个js、css合并为一个文件，从而减少http请求，加快网页打开速度。<br>
同时还提供CDN域名替换功能，方便将网页内的图片替换为CDN加速域名下的地址。<br>
开发与探讨：http://github.com/tangshuang/wp-minify ，你可以在GitHub里面给我提issue，或者pull request。

== Installation ==

1、在后台“安装插件”搜索"WP MINIFY STATIC"，点击安装；或者下载插件，上传到/wp-content/plugins/目录<br />
2、在后台插件列表中启用它<br />
3、在“设置-MINIFY”菜单中进行初始化配置
4、修改主题，不在主题中直接使用< link >标签和< script >标签输出css和js，而是使用插件提供的wp_minify_js，wp_minify_css函数来输出。具体看 http://github.com/tangshuang/wp-minify 页的说明

== Frequently Asked Questions ==


== Screenshots ==

== Changelog ==

= 1.0.5 =
修复Tail的bug。

= 1.0.4 =
增加一个更新文件版本tail的按钮。

= 1.0.3 =
把缓存文件移动到wp-content/cache，防止升级插件的时候缓存也被删除。

= 1.0.2 =
url尾巴功能。

= 1.0.0 =
成为正式的发布版本。

== Upgrade Notice ==

= 1.0.5 =
修复Tail的bug。

= 1.0.4 =
增加一个更新文件版本tail的按钮。

= 1.0.3 =
把缓存文件移动到wp-content/cache，防止升级插件的时候缓存也被删除。

= 1.0.2 =
url尾巴功能。

= 1.0.0 =
成为正式的发布版本。
