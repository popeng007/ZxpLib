<?php

namespace ZxpLib;

/**
 * HTTP 请求
 * 
 * @author zxp <zhang.xian.peng@qq.com>
 * @date 2020-12-19
 */
class Http
{
    /**
     * 异步 post 方式
     *
     * @param string $url
     * @param array $data
     * @return void
     */
    public static function asyncPost($url, $data)
    {
        $data = http_build_query($data);
        $len = strlen($data);
        $parseUrl = parse_url($url);
        $host = empty($parseUrl['host']) ? $_SERVER['SERVER_NAME'] : $parseUrl['host'];
        $port = empty($parseUrl['port']) ? $_SERVER['SERVER_PORT'] : $parseUrl['port'];
        $path = $parseUrl['path'];

        if ($port == 443) {
            $path = 'https://' . $host . $path;
            $host = 'ssl://' . $host;
        }

        $fp = fsockopen($host, $port, $errno, $errstr, 30);
        if ($fp) {
            stream_set_blocking($fp, true); //开启了手册上说的非阻塞模式
            stream_set_timeout($fp, 120);   //设置超时
            $out = "POST $path HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: $len\r\n";
            $out .= "Connection: close\r\n";
            $out .= "\r\n";
            $out .= $data . "\r\n";
            fwrite($fp, $out);
            fclose($fp);
        }
    }
}
