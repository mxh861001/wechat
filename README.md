# wechat

> 基于最新的 overtrue/wechat 4.x

WeChat SDK for Yii2 , 基于 [overtrue/wechat](https://github.com/overtrue/wechat).     
这个扩展可以简单的用yii2的方式去调用EasyWechat:   `Yii::$app->wechat`.   

## 安装
```
composer require mxh861001/wechat
```

## 配置

添加 SDK 到Yii2的 `config/main.php` 的 `component`:

```php

'components' => [
	// ...
	'wechat' => [
		'class' => 'mxh861001\easywechat\Wechat',
		'rebinds' => [ // 自定义服务模块 
		    // 'cache' => 'common\components\Cache',
		]
	],
	// ...
]
```

设置基础配置信息和微信支付信息到 `config/params.php`:
```php
// 微信配置 具体可参考EasyWechat 
'wechatConfig' => [],

// 微信支付配置 具体可参考EasyWechat
'wechatPaymentConfig' => [],

// 微信小程序配置 具体可参考EasyWechat
'wechatMiniProgramConfig' => [],
```

配置文档

[微信配置说明文档.](https://www.easywechat.com/docs/master/official-account/configuration)  
[微信支付配置说明文档.](https://www.easywechat.com/docs/master/payment/jssdk)  
[微信小程序配置说明文档.](https://www.easywechat.com/docs/master/mini-program/index)  

## 使用例子

```php
```
获取微信SDK实例

```php
$app = Yii::$app->wechat->app;
```
获取微信支付SDK实例

```php
$payment = Yii::$app->wechat->payment;
```
获取微信小程序实例

```php
$miniProgram = Yii::$app->wechat->miniProgram;
```

微信支付(JsApi):

```php
// 支付参数
$orderData = [ 
    'openid' => '.. '
    // ... etc. 
];

// 生成支付配置
$payment = Yii::$app->wechat->payment;
$result = $payment->order->unify($orderData);
if ($result['return_code'] == 'SUCCESS') {
    $prepayId = $result['prepay_id'];
    $config = $payment->jssdk->sdkConfig($prepayId);
} else {
    throw new yii\base\ErrorException('微信支付异常, 请稍后再试');
}  

return $this->render('wxpay', [
    'jssdk' => $payment->jssdk, // $app通过上面的获取实例来获取
    'config' => $config
]);

```

JSSDK发起支付
```
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    //数组内为jssdk授权可用的方法，按需添加，详细查看微信jssdk的方法
    wx.config(<?php echo $jssdk->buildConfig(array('chooseWXPay'), true) ?>);
    // 发起支付
    wx.chooseWXPay({
        timestamp: <?= $config['timestamp'] ?>,
        nonceStr: '<?= $config['nonceStr'] ?>',
        package: '<?= $config['package'] ?>',
        signType: '<?= $config['signType'] ?>',
        paySign: '<?= $config['paySign'] ?>', // 支付签名
        success: function (res) {
            // 支付成功后的回调函数
        }
    });
</script>
```

### 智能提示

如果你需要编辑器（PhpStorm等）的智能提示来使用`Yii::$app->wechat`，可以在`yii\base\Application`中加入:
```
<?php

namespace yii\base;

use Yii;

/**
 *
 * @property \mxh861001\easywechat\Wechat $wechat 加入这一行即可实现编辑器智能提示.
 *
 */
abstract class Application extends Module
{

}
```

### 更多的文档

 [EasyWeChat Docs](https://www.easywechat.com/docs/master).
