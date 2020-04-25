<?php

namespace mxh861001\easywechat;

use Yii;
use yii\base\Component;
use EasyWeChat\Factory;

/**
 * Class Wechat
 *
 * @package mxh861001\easywechat
 *
 * @property \EasyWeChat\OfficialAccount\Application $app 微信SDK实例
 * @property \EasyWeChat\Payment\Application $payment 微信支付SDK实例
 * @property \EasyWeChat\MiniProgram\Application $miniProgram 微信小程序实例
 */
class Wechat extends Component
{
    /**
     * @var array
     */
    public $rebinds = [];

    /**
     * 微信SDK
     *
     * @var Factory
     */
    private static $_app;

    /**
     * 支付 SKD
     *
     * @var Factory
     */
    private static $_payment;

    /**
     * 小程序 SKD
     *
     * @var Factory
     */
    private static $_miniProgram;

    /**
     * 获取 EasyWeChat 微信实例
     *
     * @return Factory|\EasyWeChat\OfficialAccount\Application
     */
    public function getApp()
    {
        if (!self::$_app instanceof \EasyWeChat\OfficialAccount\Application) {
            self::$_app = Factory::officialAccount(Yii::$app->params['wechatConfig']);
            !empty($this->rebinds) && self::$_app = $this->rebind(self::$_app);
        }

        return self::$_app;
    }

    /**
     * 获取 EasyWeChat 微信支付实例
     *
     * @return Factory|\EasyWeChat\Payment\Application
     */
    public function getPayment()
    {
        if (!self::$_payment instanceof \EasyWeChat\Payment\Application) {
            self::$_payment = Factory::payment(Yii::$app->params['wechatPaymentConfig']);
            !empty($this->rebinds) && self::$_payment = $this->rebind(self::$_payment);
        }

        return self::$_payment;
    }

    /**
     * 获取 EasyWeChat 微信小程序实例
     *
     * @return Factory|\EasyWeChat\MiniProgram\Application
     */
    public function getMiniProgram()
    {
        if (!self::$_miniProgram instanceof \EasyWeChat\MiniProgram\Application) {
            self::$_miniProgram = Factory::miniProgram(Yii::$app->params['wechatMiniProgramConfig']);
            !empty($this->rebinds) && self::$_miniProgram = $this->rebind(self::$_miniProgram);
        }

        return self::$_miniProgram;
    }

    /**
     * $app
     *
     * @param $app
     * @return mixed
     */
    public function rebind($app)
    {
        foreach ($this->rebinds as $key => $class) {
            $app->rebind($key, new $class());
        }

        return $app;
    }

    /**
     * overwrite the getter in order to be compatible with this component
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\Exception $e) {
            throw $e->getPrevious();
        }
    }
}
