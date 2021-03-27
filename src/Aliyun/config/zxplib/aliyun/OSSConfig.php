<?php

namespace config\zxplib\aliyun;

/**
 * 阿里云对象存储 OSS 配置文件
 * 
 * ### 安装步骤 ###
 * 1. 复制此文件到项目根路径的 config/zxplib/aliyun/ 目录下，并更新为您的真实信息；
 * 2. 修改项目根路径的 composer.json：
 *    (1) 在 psr-4 中添加自动加载目录 "config\\zxplib\\": "config/zxplib"；
 *    (2) 执行 composer dump-autoload；
 * 
 * @author zxp <zhang.xian.peng@qq.com>
 * @date 2021-03-16
 */
final class OSSConfig
{
    // accessKeyId
    const OSS_ACCESS_KEY_ID = 'update me';
    // accessKeySecret
    const OSS_ACCESS_KEY_SECRET = 'update me';
    // endpoint
    const OSS_ENDPOINT = 'update me';       // 示例：oss-cn-hangzhou.aliyuncs.com
    // bucket
    const OSS_BUCKET = 'update me';         // 示例：test-bucket1.oss-cn-hangzhou.aliyuncs.com
}
