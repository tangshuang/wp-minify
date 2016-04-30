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
<?php minfiy_js_url(array('/wp-content/themes/yourtheme/js/base.js','/wp-content/themes/yourtheme/js/module.js','/wp-content/themes/yourtheme/js/others.js')); ?>
```

输出css的方法是一样的，只不过要使用另外一个函数``minify_css_url()``。函数的参数是一个数组，数组内部的元素是脚本文件的路径，以WordPress安装目录为根目录，写入完整的相对路径。