<?php

namespace ZxpLib;

/**
 * 数组操作
 * 
 * @author zxp <zhang.xian.peng@qq.com>
 * @date 2018-07-05
 */
class Arrays
{
    /**
     * 返回二维数组中指定列
     * 
     * 通常用于从数据库查询结果里提取数据，
     * 和 php 函数 array_column 类似，array_column 要求 php 版本 >= 5.5.0
     * 
     * @param array $array          二维数组
     * @param mixed $column_key     需要返回的列；string：单列，array：多列
     * @param string $index_key     用于做索引的列
     * @return array
     */
    public static function column($array, $column_key, $index_key = null)
    {
        $data = [];
        if ($index_key) {
            if (is_array($column_key)) {
                foreach ($array as $key => $item) {
                    foreach ($column_key as $columnKey) {
                        $data[$item[$index_key]][$columnKey] = $item[$columnKey];
                    }
                }
            } else {
                foreach ($array as $key => $item) {
                    $data[$item[$index_key]] = $item[$column_key];
                }
            }
            return $data;
        } else {
            if (is_array($column_key)) {
                foreach ($array as $key => $item) {
                    foreach ($column_key as $columnKey) {
                        $data[$columnKey][] = $item[$columnKey];
                    }
                }
                foreach ($data as $columnKey => $columnKeyData) {
                    $data[$columnKey] = array_unique($columnKeyData);
                }
            } else {
                foreach ($array as $key => $item) {
                    $data[] = $item[$column_key];
                }
                $data = array_unique($data);
                sort($data);
            }
            return $data;
        }
    }

    /**
     * 二维数组多字段排序
     * 
     * 使用方式：
     * Arrays::multisort($array, [
     *     'column1' => 'SORT_DESC',
     *     'column2' => 'SORT_ASC',  // 可选，支持最少一个字段排序
     *     ...
     * ]);
     *
     * @param array &$array             二维数组
     * @param array $columnSortOrders   排序字段
     * @return void
     */
    public static function multisort(&$array, $columnSortOrders)
    {
        if (!is_array($array)) {
            return;
        }
        $columnValues = [];
        foreach ($array as $key => $row) {
            foreach ($columnSortOrders as $column => $sortOrder) {
                $columnValues[$column][] = $row[$column];
            }
        }
        $code = 'array_multisort(';
        foreach ($columnSortOrders as $column => $sortOrder) {
            $code .= '$columnValues[\'' . $column . '\'], ' . $sortOrder . ', ';
        }
        $code .= '$array);';
        eval($code);
    }

    /**
     * 二维数据转映射表
     *
     * @date 2021-03-05
     * @param array $array  二维数组
     * @param string $key   索引键名
     * @return array
     */
    public static function toMap($array, $key)
    {
        $map = [];
        foreach ($array as $index => $row) {
            $map[$row[$key]] = $row;
        }
        return $map;
    }
}
