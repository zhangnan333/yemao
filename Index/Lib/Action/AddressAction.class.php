<?php

//Output
//Parameter
//（集合）
//code
//
//string
//0.异常  1.成功
//message
//
//string
//提示信息 成功与否都返回
//college_id
//messages(JSON数组)
//string
//大学id
//college_area
//messages
//string
//大学所在区
//college_city
//messages
//string
//大学所在城市
//
//college_name
//messages
//string
//大学名称
// 注册


class AddressAction extends Action {

    public function index()
    {
        $re = M('address')->select();
        if($re){
            $data ['code'] = 1;
        }else{
            $data ['code'] = 0;
        }
        for($i = 0;$i<count($re);$i++){
            $data ['messages'][$i]['college_id'] = $re[$i]['id'];//大学id
            $data ['messages'][$i]['college_area'] = $re[$i]['area'];//大学所在区
            $data ['messages'][$i]['college_city'] = $re[$i]['city'];//大学所在城市
            $data ['messages'][$i]['college_name'] = $re[$i]['school'];//大学名称
        }
        echo($data);
    }

}