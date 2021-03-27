<?php

namespace ZxpLib\ThinkPHP5\Model;

use think\Model;
use ZxpLib\ThinkPHP5\Result;

/**
 * 模型基类
 * 封装一些对数据的常用操作方法
 * 
 * @author zxp <zhang.xian.peng@qq.com>
 * @date 2020-09-15
 */
class Base extends Model
{
    /**
     * 获取数据列表
     *
     * @param array $fields
     * @param array $where
     * @param int $size
     * @param mixed $order
     * @return array
     */
    public static function getList($fields, $where, $size, $order = 'id desc')
    {
        $data = self::field($fields)->where($where)->order($order)->paginate($size)->toArray();
        return Result::paginateFormat($data);
    }

    /**
     * 关联模型获取数据列表
     *
     * @param mixed $with
     * @param mixed $fields
     * @param mixed $where
     * @param int $size
     * @param mixed $order
     * @return array
     */
    public static function getListWith($with, $fields, $where, $size, $order = 'id desc')
    {
        $data = self::with($with)->field($fields)->where($where)->order($order)->paginate($size)->toArray();
        return Result::paginateFormat($data);
    }

    /**
     * 关联模型联合获取数据列表
     *
     * @date 2021-03-02
     * @param mixed $with
     * @param array $joins
     * @param string $alias
     * @param mixed $fields
     * @param mixed $where
     * @param int $size
     * @param mixed $order
     * @return array
     */
    public static function getListWithJoin($with, $joins, $alias, $fields, $where, $size, $order = 'id desc')
    {
        $data = self::with($with)->join($joins)->alias($alias)->field($fields)->where($where)->order($order)->paginate($size)->toArray();
        return Result::paginateFormat($data);
    }

    /**
     * 获取全部数据
     *
     * @param array $fields
     * @param array $where
     * @param mixed $order
     * @return array
     */
    public static function getAll($fields, $where, $order = 'id desc')
    {
        return self::field($fields)->where($where)->order($order)->select();
    }

    /**
     * 关联模型获取全部数据
     *
     * @date 2021-02-26
     * @param mixed $with
     * @param mixed $fields
     * @param mixed $where
     * @param mixed $order
     * @return array
     */
    public static function getAllWith($with, $fields, $where, $order = 'id desc')
    {
        return self::with($with)->field($fields)->where($where)->order($order)->select();
    }
}
