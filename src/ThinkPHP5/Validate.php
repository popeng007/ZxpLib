<?php
namespace ZxpLib\ThinkPHP5;

use think\Loader;

/**
 * 基于 ThinkPHP5.0 验证器的封装
 * 
 * @author zxp <zhang.xian.peng@qq.com>
 * @date 2020-06-10
 */
class Validate
{
    /**
     * 验证参数
     *
     * @param string $validater     验证器名称
     * @param array $data           需要验证的数据
     * @param string $scene         验证场景
     * @return mixed
     */
    public static function check($validater, $data, $scene = '')
    {
        $validate = Loader::validate($validater);
        if (!empty($scene)) {
            $result = $validate->scene($scene)->check($data);
        } else {
            $result = $validate->check($data);
        }
        if (!$result) {
            return $validate->getError();
        }
        return true;
    }
}
