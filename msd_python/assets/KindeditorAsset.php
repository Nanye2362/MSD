<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class KindeditorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'pro/kindeditor/themes/default/default.css',
        'pro/kindeditor/plugins/code/prettify.css',
    ];
    public $js = [
        'pro/kindeditor/kindeditor.js',
        'pro/kindeditor/lang/zh_CN.js',
        'pro/plugins/code/prettify.js'
    ];
    public $depends = [
    ];

    //定义按需加载JS方法，注意加载顺序在最后
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [AppAsset::className()]);
    }

    //定义按需加载JS方法，注意加载顺序在最后
    public static function addCss($view, $cssfile) {
        $view->registerCssFile($cssfile, [AppAsset::className()]);
    }

}
