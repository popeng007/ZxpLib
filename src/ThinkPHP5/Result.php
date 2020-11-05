<?php
namespace ZxpLib\ThinkPHP5;

/**
 * 返回结果类
 * 
 * @author zxp <zhang.xian.peng@qq.com>
 * @date 2018-05-23
 */
class Result
{
    /**
     * 返回成功
     *
     * @param string $msg       消息提示
     * @param mixed $data       返回数据
     * @param boolean $json     是否返回 json；false：返回数组
     * @return \think\response\Json
     */
    public static function success($msg = '', $data = null, $json = true)
    {
        $result = [
            'status' => 'success',
            'code' => 0,
        ];
        if (isset($data['dataList']) && empty($data['dataList'])) {
            $msg = '暂无内容';
        }
        if ($data !== null && empty($data)) {
            $msg = '暂无内容';
        }
        $result['msg'] = $msg;
        if ($data !== null) {
            $result['data'] = $data;
        }
        if ($json) {
            return json($result);
        } else {
            return $result;
        }
    }
    
    /**
     * 返回失败
     *
     * @param mixed $msg        消息提示
     * @param integer $code     错误码；可自定义
     * @param boolean $json     是否返回 json；false：返回数组
     * @return \think\response\Json
     */
    public static function failure($msg, $code = 1, $json = true)
    {
        $result = [
            'status' => 'failure',
            'code' => $code,
            'msg' => $msg
        ];
        if ($json) {
            return json($result);
        } else {
            return $result;
        }
    }

    /**
     * 输出失败，并终止程序执行
     *
     * @param integer $code     错误码；可自定义
     * @param mixed $info       消息提示
     * @return void
     */
    public static function stop($code = 1, $info)
    {
        $result = [
            'status' => 'failure',
            'code' => $code,
            'msg' => $info
        ];
        header('Content-type:text/json');
        echo json_encode($result);
        die;
    }

    /**
     * 将数据结果集转换成分页数据格式
     *
     * @param integer $total    总记录数
     * @param integer $page     第几页
     * @param integer $size     每页显示多少条记录
     * @param array $dataList   数据结果集
     * @return array
     */
    public static function pageData($total, $page, $size, $dataList)
    {
        $data = [
            'pageInfo' => [
                'total' => $total,
                'cur_page' => (int) $page,
                'size' => (int) $size
            ],
            'dataList' => $dataList
        ];
        return $data;
    }

    /**
     * 将 ThinkPHP5 框架 Model::paginate 方法查询的结果转换成本类使用的分页数据格式
     * 注意在 paginate 方法之后接 toArray()
     *
     * @param array $data
     * @return array
     */
    public static function paginateFormat($data)
    {
        $data = [
            'pageInfo' => [
                'total' => $data['total'],
                'cur_page' => (int) $data['current_page'],
                'size' => (int) $data['per_page']
            ],
            'dataList' => $data['data']
        ];
        return $data;
    }
}
