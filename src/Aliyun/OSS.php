<?php

namespace ZxpLib\Aliyun;

use config\zxplib\aliyun\OSSConfig;
use OSS\OssClient;
use OSS\Core\OssException;

/**
 * 阿里云对象存储 OSS SDK 的再次封装
 * 支持 OSS\OssClient 全部方法
 * 
 * 无需实例化，直接用静态方法调用
 * 示例 OSS::pubObject($bucket, $object, $content)
 * 
 * 注意：
 * 请先在项目根路径创建 /config/zxplib/aliyun/OSSConfig.php 配置文件，配置 access key；
 * 可复制示例文件后修改。
 * 
 * @author zxp <zhang.xian.peng@qq.com>
 * @date 2021-03-12
 */
class OSS
{
    /**
     * 获取签名
     * 用于前端“服务端签名后直传”
     * 基于阿里云官方示例的封装
     *
     * @param int $expire           设置该 policy/signature 的超时时间，即这个 policy/signature 过了这个有效时间，将不能访问。
     * @param string $dir           用户上传文件时指定的前缀。
     * @param string $host          格式为 bucketname.endpoint，请替换为您的真实信息。示例：http://bucket-name.oss-cn-hangzhou.aliyuncs.com
     * @param string $callbackUrl   上传回调服务器的 URL，请将 IP 和 Port 配置为您自己的真实 URL 信息。示例：http://88.88.88.88:8888/aliyun-oss-appserver-php/php/callback.php
     * @return array
     */
    public static function getSignature($expire = 10, $dir = '', $host = '', $callbackUrl = '')
    {
        $id = OSSConfig::OSS_ACCESS_KEY_ID;                   // 请填写您的 AccessKeyId。
        $key = OSSConfig::OSS_ACCESS_KEY_SECRET;              // 请填写您的 AccessKeySecret。
        $host = empty($host) ? self::$scheme . '://' . OSSConfig::OSS_BUCKET : $host;

        $now = time();
        $end = $now + $expire;

        list($base64Policy, $signature) = self::getPolicy($key, $end, $dir);
        $base64CallbackBody = self::getBase64CallbackBody($callbackUrl);

        $response = [];
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64Policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        if (!empty($base64CallbackBody)) {
            $response['callback'] = $base64CallbackBody;
        }
        if (!empty($dir)) {
            $response['dir'] = $dir;
        }

        return $response;
    }

    /**
     * 获取 policy 和 signature
     *
     * @param string $key   AccessKeySecret
     * @param int $end      时间戳，有效期至
     * @param string $dir   上传文件指定前级
     * @return array        [$base64Policy, $signature]
     */
    private static function getPolicy($key, $end, $dir)
    {
        // 时间容错，加 5 分钟
        $tolerant = 300;

        $expiration = self::gmtIso8601($end + $tolerant);

        //最大文件大小.用户可以自己设置
        $condition = [0 => 'content-length-range', 1 => 0, 2 => 1048576000];
        $conditions[] = $condition;

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        $start = [0 => 'starts-with', 1 => '$key', 2 => $dir];
        $conditions[] = $start;

        $arr = ['expiration' => $expiration, 'conditions' => $conditions];
        $policy = json_encode($arr);
        $base64Policy = base64_encode($policy);
        $string_to_sign = $base64Policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        return [$base64Policy, $signature];
    }

    /**
     * 获取 base64 格式的 callbackbody
     *
     * @param string $callbackUrl   回调服务器 URL
     * @return string
     */
    private static function getBase64CallbackBody($callbackUrl)
    {
        if (empty($callbackUrl)) {
            return '';
        }
        $callbackParam = [
            'callbackUrl' => $callbackUrl,
            'callbackBody' => 'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}',
            'callbackBodyType' => "application/x-www-form-urlencoded"
        ];
        $callbackString = json_encode($callbackParam);
        $base64CallbackBody = base64_encode($callbackString);
        return $base64CallbackBody;
    }

    /**
     * 格林威治 ISO8601 时间
     *
     * @param int $time     时间戳
     * @return string       格式：2021-03-12T09:09:58Z
     */
    private static function gmtIso8601($time)
    {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }

    /**
     * 魔术方法，调用阿里云官方 SDK 的 OSS\OssClient 对应方法
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $ossClient = self::getInstance();

        if (!method_exists($ossClient, $name)) {
            echo 'not found this method in OSS\OssClient.';
            die;
        }

        $result = '';
        try {
            $result = call_user_func_array([$ossClient, $name], $arguments);
        } catch (OssException $e) {
            echo $e->getMessage();
            die;
        }

        return $result;
    }

    /**
     * 单例模式，获取实例
     *
     * @return object
     */
    private static function getInstance()
    {
        $accessKeyId = OSSConfig::OSS_ACCESS_KEY_ID;
        $accessKeySecret = OSSConfig::OSS_ACCESS_KEY_SECRET;
        $endpoint = self::$scheme . '://' . OSSConfig::OSS_ENDPOINT;

        if (!self::$ossClient) {
            try {
                self::$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            } catch (OssException $e) {
                echo $e->getMessage();
                die;
            }
        }
        return self::$ossClient;
    }

    /**
     * http/https
     *
     * @var string
     */
    private static $scheme = 'http';

    /**
     * 单例模式，对象变量，阿里云 OSS 客户端
     *
     * @var object
     */
    private static $ossClient;
}
