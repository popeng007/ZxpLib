<?php

namespace ZxpLib\ThinkPHP5;

use think\Request;

/**
 * 上传
 * 
 * @author zxp <zhang.xian.peng@qq.com>
 * @date 2021-01-22
 */
class Upload
{
    /**
     * 单个文件
     *
     * @param string $inputName     input控件名称
     * @param string $directory     子目录
     * @param boolean $returnUrl    是否返回完整url。true：是，false：返回相对路径
     * @return string
     */
    public static function file($inputName = 'file', $directory = '', $returnUrl = false)
    {
        $saveName = '';
        $request = Request::instance();
        $file = $request->file($inputName);
        if ($file) {
            $info = $file->move(self::getPath($directory));
            if ($info) {
                $saveName = $info->getSaveName();
            }
        }
        // 完整url
        if ($saveName && $returnUrl) {
            $saveName = self::getUrl($directory, $saveName);
        }
        return $saveName;
    }

    /**
     * 多个文件
     *
     * @param string $inputName     input控件名称
     * @param string $directory     子目录
     * @param boolean $returnUrl    是否返回完整url。true：是，false：返回相对路径
     * @return array
     */
    public static function files($inputName = 'files', $directory = '', $returnUrl = false)
    {
        $saveNames = [];
        $request = Request::instance();
        $files = $request->file($inputName);
        if ($files) {
            $url = self::getUrl($directory);
            $path = self::getPath($directory);
            foreach ($files as $file) {
                $info = $file->move($path);
                if ($info) {
                    if ($returnUrl) {
                        $saveNames[] = $url . $info->getSaveName();
                    } else {
                        $saveNames[] = $info->getSaveName();
                    }
                }
            }
        }
        return $saveNames;
    }

    /**
     * 获取上传成功后的完整url
     *
     * @param string $directory
     * @param string $saveName
     * @return string
     */
    public static function getUrl($directory = '', $saveName = '')
    {
        if (!empty($directory)) {
            $directory .= '/';
        }
        return Request::instance()->domain() . '/' . self::$directoryRoot . $directory . $saveName;
    }

    /**
     * 获取文件存储路径
     *
     * @param string $directory
     * @return string
     */
    public static function getPath($directory = '')
    {
        return self::$directoryRoot . $directory;
    }

    /**
     * 上传目录（主目录），在 TP5 框架的 public 目录下
     *
     * @var string
     */
    private static $directoryRoot = 'uploads/';
}
