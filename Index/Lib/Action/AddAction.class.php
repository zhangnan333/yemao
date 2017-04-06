<?php
// 本类由系统自动生成，仅供测试用途
class AddAction extends Action {
    public function index(){
	$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }

    public function interface_sendCode() //短信接口
    {
    	echo "string";
    }


    public function interface_address()//学校列表
    {
        
    }

    public function interface_register() //注册
    {
    	
    }

    public function interface_login()//登录
    {
    	
    }

    public function interface_goods_lists()//商品列表
    {
    	
    }

    public function interface_category()//商品分类
    {
    	
    }

     public function interface_category_goods()//分类下的商品列表
     {
         $category_id = I('category_id');
         $user_id = I('user_id');
         $pageIndex = I('pageindex');
         $tag = I('tag');


         if ($tag == 0) { //用户查询
             if ($user_id = 0) {//查询所有

                 $goodshop = M('goods');
                 $good_array = $goodshop->where("goods_type = $category_id")->select();
                 $this->returnArray($good_array, $pageIndex);

             } else {

                 $array = $this->shoplistcategory($user_id,$category_id);
                 $this->returnArray($array, $pageIndex);


             }
         }else{ //商户查询 查询所有
                 $goodshop = M('goods');
                 $good_array = $goodshop->where("goods_type = $category_id")->select();
                 $this->returnArray($good_array, $pageIndex);
             }

     }
      function shoplistcategory($user_id,$category_id)  //商品列表
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
                     $result = $goods->where("goods_id = $shop_goods_id")->where("goods_type = $category_id")->find();
                      $id = $value['member_id'];
                      $name = $user->where("id = $id")->getField('name');
                      $result['shop_name']=$name;
                      $result['shop_id']=$id;
                    array_push($arr,$result); 
                 }  
        }

        return $arr;  
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

   function returnArray($array,$pageIndex)
   {
       if(is_array($array)){
           $ar = array_chunk($array,10);

           if (count($ar) <= $pageIndex) {
               $this ->ajaxReturn(array('code'=>'1','message'=>$ar[$pageIndex]));

           }else{
               $this ->ajaxReturn(array('code'=>'1','message'=>$array[count($ar)-1]));
           }
       }else{
           $this ->ajaxReturn(array('code'=>'1','message'=>'无数据'));
       }

   }

     public function interface_series_goods()//查询商品列表
     {
     	$series = I('series_code');
        $user_id = I('user_id');
        $tag = I('tag');
        $pageIndex = I('pageIndex');

         $array = $this->shoplist($user_id);

        if ( $tag == 0) { //用户查询
             if ($user_id = 0) {//查询所有

                $goodshop = M('goods');
                $good_array = $goodshop->select();
                $this -> returnArray($good_array,$pageIndex);

               
             }else{
                if ($series == 1) {//默认查询

                $this ->ajaxReturn(array('code'=>'1','message'=>$array));

                }elseif($series == 2){//销量排序

                 usort($array, function($a, $b) {   
                            $al = $a['goods_sales'];
                            $bl = $b['goods_sales'];
                            if ($al == $bl)
                               return 0;
                            return ($al > $bl) ? 1 : -1;
                    });


                $this -> returnArray($array,$pageIndex);

                }else{ //价格排序
                    
                     usort($array, function($a, $b) {   //按价格排序
                            $al = $a['goods_prices'];
                            $bl = $b['goods_prices'];
                            if ($al == $bl)
                               return 0;
                            return ($al > $bl) ? 1 : -1;
                    });

                   $this -> returnArray($array,$pageIndex);
                }

             }
           
        }else{ //商户查询 查询所有
                $goodshop = M('goods');
                $good_array = $goodshop->select();
                $this -> returnArray($good_array,$pageIndex);
        }
     }

     public function interface_add_buycar()//加入购物车
     {
     	$goodid = I('goods_id');
        $num = I('goods_num');
        $standard = I('standard_id');
        $shopid = I('shop_id');
        $shop = M('goods');
        $userId = M('user_id');
       $price = $shop ->where("goods_id = $goodid")->getField('goods_prices');
        $date =date('Y-m-d H:i:s',time());
        $car = M('car');
        $data = array(
          'goods_id' =>$goodid,
          'goods_num' => $num,
          'goods_prices' =>$price *$num,
          'shop_id' =>$shopid,
          'member_id'=>$userId,
          'create_time'=>$date,
          'standard_id' => $standard
            );
       
        $result =$car ->data($data)->add();
        if ($result) {
           $this ->ajaxReturn(array('code'=>'1','message'=>'添加成功'));
        }else{
           $this ->ajaxReturn(array('code'=>'0','message'=>'添加失败')); 
        }

     }

     public function interface_buycar_lists()//购物车列表
     {
        $userId = I('user_id');
        $car = M('car');
        $shop = M('goods');
        $result = $car->where("member_id = $userId")->select();
        $arr = array();
        if (is_array($result)) {
            
            foreach ($result as $key => $value){
                $shopid = $value['goods_id'] ;
                $result1 = $shop->where("goods_id = $shopid")->find();
                $result1['goods_num'] = $value['goods_ordercount'];
                array_push($arr,$result1);  
            }
        }
       
        if (count($arr) > 0) {
            $this->ajaxReturn(array('code'=>'1','message'=>$arr));
        }else{
            $this->ajaxReturn(array('code'=>'0','message'=>'暂无商品'));
        }

     }

     public function interface_buycar_delete()//购物车删除
     {
     	 $userId = I('user_id');
         $shopid = I('goods_id');
         $car = M('car');
         $num = $car->where("member_id = $userId")->where("goods_id = $shopid")->getField('goods_ordercount');
         $num = $num - 1;  
         if ($num >= 1) {

          $result= $car->where("member_id = $userId")->where("goods_id = $shopid")->setField('goods_ordercount',$num);

         }else{
          
           $result = $car->where("member_id = $userId")->where("goods_id = $shopid")->delete();

         }

         if ($result) {
                 $this->ajaxReturn(array('code'=>'1','message'=>'删除成功'));

          }else{
                 $this->ajaxReturn(array('code'=>'0','message'=>'删除失败'));

          }
        
     }

     public function interface_receipt_address()//配送地址列表
     {
        $userId = I('user_id');
        $address =M('address_detail');
        $result =$address->where("member_id = $userId")->select();
        if (is_array($result)) {
                 $this->ajaxReturn(array('code'=>'1','message'=>$result));
 
        }else{
                 $this->ajaxReturn(array('code'=>'0','message'=>'暂无地址'));

        }
     	
     }

     public function interface_delete_receipt_address()//删除配送地址
     {
     	
        $userId = I('address_id');
        $address =M('address_detail');
        $result = $address ->where("address_id = $userId")->delete();

        if ($result) {
               $this->ajaxReturn(array('code'=>'1','message'=>'删除成功'));
 
        }else{
              $this->ajaxReturn(array('code'=>'0','message'=>'删除失败'));
 
        }

     }

     public function interface_add_receipt_address()//添加配送地址
     {
     	$userId = I('user_id');
        $address_de =I('receipt_address');
        $phone =I('receipt_phone');

        $address = M('address_detail');
        $data =array(
          'address_detail'=>$address_de,
          'address_phone' =>$phone,
          'member_id' =>$userId
            );
       $result = $address->data($data)->add();
       
       if ($result) {
          $this->ajaxReturn(array('code'=>'1','message'=>'添加成功'));
       }else{
          $this->ajaxReturn(array('code'=>'0','message'=>'添加失败'));
       }
     }

     public function interface_about_us()//关于我们
     {
     	//直接连过去
     }

     public function interface_update_nickname()//修改昵称
     {
     	$userid = I('user_id');
        $nickname =I('nickname');
        $user = M('member');
       $result = $user->where("id = $userid")->setField('nickname',$nickname);   
       if ($result) {
             $this->ajaxReturn(array('code'=>'1','message'=>'修改成功'));
         }else{
            $this->ajaxReturn(array('code'=>'0','message'=>'修改失败'));
         }

     }

     public function interface_find_pass() //找回密码
     {
        $username = I('username');
        $password = I('password');

         
     }

     public function interface_my_message()//我的消息
     {
         $userId = I('user_id');
         $pageIndex = I('pageIndex');
         $message = M('message');
        $result = $message ->where("member_id = $userId")->select();
        
        if (is_array($result)) {
            $arr = array_chunk($result,10);
            if (count($arr) <= $pageIndex) {
                $this->ajaxReturn(array('code'=>'1','message'=>$arr[$pageIndex]));
            }else{
              $this->ajaxReturn(array('code'=>'1','message'=>$arr[count($arr)-1]));
            }

        }else{
            $this->ajaxReturn(array('code'=>'0','message'=>'暂无记录'));
        }

     }

     public function interface_send_readed()//发送一度标记
     { 
        $msgId = I('msg_id');
        $message = M('message');
        $result = $message -> where("msg_id = $msgId")->setField('msg_is_read','1');
        if ($result) {
           $this->ajaxReturn(array('code'=>'1','message'=>'标记成功'));
        }else{
            $this->ajaxReturn(array('code'=>'0','message'=>'标记失败'));
        }
     }

     public function interface_self_goods()//自营商品
     {    
         $userId = I('user_id');
         $pageIndex = I('pageIndex');
         $shop_agent = M('goods_agent');
         $shopId = $shop_agent ->where("id = $userId")->getField('goods_id');
         $shop = M('shop');
         $data['id'] = array('egt',$pageIndex);
         $result = $shop->where($data)->select();
         if (is_array($result)) {
            $this->ajaxReturn(array('code'=>'1','message'=>$result)); 
         }else{
           $this->ajaxReturn(array('code'=>'0','message'=>'查询失败')); 

         }
         

     }

     public function interface_update_pass()//修改密码
     {
        $userId = I('user_id');
        $oldpass = I('oldpass');
        $newpass = I('newpass');

        $user = M('member');

        $result = $user->where("id = $userId")->find();
        if ($result['password'] == $oldpass) {
           $user ->where("id =$userId")->setField('password',$newpass);

            $this->ajaxReturn(array('code' => '1','message'=>'修改密码成功'));

        }else{
            $this->ajaxReturn(array('code' => '0','message'=>'修改密码失败'));
        }
     }
} 