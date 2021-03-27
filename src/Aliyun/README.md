# ZxpLib\Aliyun\OSS

## 安装

1. 安装阿里云 OSS 官方 SDK
```
composer require aliyuncs/oss-sdk-php
```
2. 安装 ZxpLib
```
composer require zxplib/zxplib
```

## 使用

1. 在项目根路径下创建配置文件：  
/config/zxplib/aliyun/OSSConfig.php，修改配置信息；
> 配置文件请见示例，可复制过去进行修改。
2. 修改项目根路径的 composer.json：
  - (1) 在 psr-4 中添加自动加载目录 "config\\zxplib\\": "config/zxplib"；
  - (2) 执行 composer dump-autoload；
3. 调用 *（支持阿里云官方 SDK OSS\OssClient 的全部方法）*

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use ZxpLib\Aliyun\OSS;

// 请自己准备图片进行测试
$result = OSS::putObject('test-bucket1', 'test/image.png', file_get_contents('images/image.png'));

var_dump($result);
```

