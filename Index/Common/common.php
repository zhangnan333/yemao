<?php
/**
 * Created by PhpStorm.
 * User: zhangnan
 * Date: 2017/2/20
 * Time: 下午4:25
 */
 function p($arr){//格式化打印数组
     dump($arr,1,'<pre>',0);

 }
 function code ()//生成验证码方法自动写入
{
    import('ORG.Util.Image');//引入Image类
    Image::buildImageVerify(4,1,png,155,30,verify);//调用buildImageVerify()方法
}
function shoplist($user_id)  //商品列表
{
    $user =M('member');
    $shop = M('goods_agent');
    $goods = M('goods');
    $addre = M('address');
    $address_id = $user->where("id = $user_id")->getField('address_id');
    $school = $addre ->where("id = $address_id")->getField('school'); //用户所在学校
//
    $mem_array = $addre->where("school = '$school'")->field('id')->select(); //查询该学校的所有用户的address_id

    $arr = array();
    if (is_array($mem_array)) {

        foreach ($mem_array as $key => $value) {
            //查询商户
            $user_address_id = $value['id'];
            $user_array = $user->where("address_id = $user_address_id")->where("type = 0")->field('id')->select();

            foreach ($user_array as $key1 => $value1) {
                $mem_id = $value1['id'];
                $goods_array= $shop->where("member_id = $mem_id")->where("goods_is_up = 1")->field('goods_id,member_id')->select();
                //查询商户代理商品
            }
        }

        //查询商品信息
        foreach ($goods_array as $key => $value){
            $shop_goods_id = $value['goods_id'];
            $result = $goods->where("goods_id = $shop_goods_id")->find();
            $id = $value['member_id'];
            $name = $user->where("id = $id")->getField('name');
            $result['shop_name']=$name;
            $result['shop_id']=$id;
            array_push($arr,$result);
        }
    }

    return $arr;
}