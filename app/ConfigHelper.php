<?php
namespace App;

use Illuminate\Support\Facades\File;

trait ConfigHelper
{
    protected $config = [
        'accessKey' => '',
        'secret' => '',
        'url' => 'https://alidns.aliyuncs.com',
        'format' => 'json',
        'version' => '2015-01-09',
        'signatureMethod' => 'HMAC-SHA1',
        'signatureVersion' => '1.0',
    ];

    /**
     * 设置配置
     * @param $key
     * @param $value
     */
    protected function setConfig($key, $value)
    {
        if (array_has($this->config, $key)) {
            $this->config[$key] = $value;
            return ;
        }

        throw new \InvalidArgumentException('配置项不合法：' . $key);
    }

    /**
     * 刷新配置文件
     */
    protected function writeConfig()
    {
        $path = config('app.configFilePath');
        if (! is_dir(File::dirname($path))) {
            mkdir(File::dirname($path), 0777, true);
        }
        file_put_contents($path, json_encode($this->config));
    }

    /**
     * 读取配置文件
     */
    protected function readConfig()
    {
        $path = config('app.configFilePath');
        if (file_exists($path)) {
            $this->config = json_decode(file_get_contents($path), true);
            return ;
        }

        throw new \InvalidArgumentException('配置文件不存在：' . $path);
    }
}
