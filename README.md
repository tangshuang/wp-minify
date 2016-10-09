# wp-minify
WordPress静态化文件合并插件，把你的多个css和js文件合并为一个

## 安装
把插件上传到你的wordpress插件目录，到后台启用该插件。

## 使用
在后台菜单“设置”下面多了一个“Minify”的子菜单，进入该菜单，对Minify进行设置。

## 主题修改
使用wp-minify必须修改主题，因为你输出css和js的方式不一样了。
在你的主题中，删除原来的CSS和JS输出，使用如下的方法输出JS：

```
<?php wp_minfiy_js(array('/wp-content/themes/yourtheme/js/base.js','/wp-content/themes/yourtheme/js/module.js','/wp-content/themes/yourtheme/js/others.js')); ?>
```

输出css的方法是一样的，只不过要使用另外一个函数``wp_minify_css()``。函数的参数是一个数组，数组内部的元素是脚本文件的路径，以WordPress安装目录为根目录，写入完整的相对路径。

此外提供另外一种逐一添加的形式：

```
<?php
// 方法1
$wp_js = new wp_minify_js_action(array('/wp-content/themes/yourtheme/js/base.js','/wp-content/themes/yourtheme/js/module.js','/wp-content/themes/yourtheme/js/others.js'),'wp_head');

// 方法2
$wp_js = new wp_minify_js_action(array(),false);
$wp_js->add('/wp-content/themes/yourtheme/js/base.js');
$wp_js->add('/wp-content/themes/yourtheme/js/1.js');
$wp_js->add('/wp-content/themes/yourtheme/js/2.js');
...
$wp_js->action(); // 输出结果
```

甚至，你可以利用这些方法，在自己的主题中进行更加灵活的开发。