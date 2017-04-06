<?php

class IndexAction extends Action {
    public function index(){
	    $where['name']=$_REQUEST['username'];
        $where['password']=$_REQUEST['password'];

       // $where['']=$_REQUEST['college_id'];
        $verify='ddd';
            //$_REQUEST['verify_code'];
        $re = '';
        $se = M('member')->where($where)->select();
        if($se){
            $data['code']= 0;
            $data['message']='已注册请登录';
            if($verify = 'ddd'&&!$se){
                $where['type']=1;
                $where['create_time'] = date("Ymd");
                $re = M('member')->add($where);
                if($re){
                    $data['code']= 1;
                    $data['message']='注册成功';
                }
                else{
                    $data['code']= 0;
                    $data['message']='连接服务器失败';
                }
            }
        }

        echo($data);

    }
    public function login(){
        $where['username'] = $_REQUEST['username'];
        $where['password'] = $_REQUEST['password'];
        if($where){
            $re = M('member')->where($where)->find();
            if($re){
//                p($re);
                $data['message'] = '成功';
                $data['code']= 1;
                $data ['messages']['user_id'] = $re['id'];
                $data ['messages']['user_type'] = $re['type'];
                $data ['messages']['user_head_url'] = $re['file_path'];
                $data ['messages']['user_nick'] = $re['nickname'];

                $where2['member_id'] = $re['id'];
                $re2 = M('member_account')->where($where2)->find();
                $data ['messages']['user_account'] = $re2['money'];

            }else{
                $data['message'] = '失败';
                $data['code']= 0;
            }

        }
        echo($data);
    }
    public function goods_lists(){
        $page = $_REQUEST['pageIndex'];
        $data = '';
        $m = M('goods');
        for($i=0;$i<10;$i++){
            $id = $i+($page-1)*10;
            $where['goods_id'] = $id;
            $re = $m->where($where)->find();

            if($re){
                $data['messages'][$i]['goods_id'] = $re['goods_id'];
                $data['messages'][$i]['goods_logo'] = $re['goods_logo'];
                $data['messages'][$i]['goods_prices'] = $re['goods_prices'];
                $data['messages'][$i]['goods_start_time'] = $re['goods_start_time'];
                $data['messages'][$i]['goods_name'] = $re['goods_name'];
                $data['messages'][$i]['goods_total_num'] = $re['goods_total_num'];
                $data['messages'][$i]['goods_buy_num'] = $re['goods_buy_num'];
                $data['messages'][$i]['goods_is_start'] = $re['goods_is_start'];

            }
        }
        if($data){
            $data['message'] = '成功';
            $data['code']= 1;

        }else{
            $data['message'] = '失败';
            $data['code']= 0;
        }
        echo($data);

//        p($data);

}
    public function category(){
        $re = M('goods_type')->select();
        $data = '';
        if($re){
            $data['message'] = '成功';
            $data['code']= 1;

        }else{
            $data['message'] = '失败';
            $data['code']= 0;
        }
        for($i=0;$i<count($re);$i++){
            $data ['messages'][$i]['category_id'] = $re[$i]['category_id'];
            $data ['messages'][$i]['category_logo'] = $re[$i]['category_logo'];
            $data ['messages'][$i]['category_name'] = $re[$i]['category_name'];
        }
        echo($data);
    }
    public function goods_updown(){
        $where['shop_id'] = $_REQUEST['user_id'];
        $where['goods_id'] = $_REQUEST['goods_id'];

        $tag = $_REQUEST['tag'];

        $datas['goods_is_up'] = $tag;
        $data = '';
        $re = M('goods')->where($where)->save($datas);
        if($re){
            $data['code']=1;
            $data['message']='成功';

        }
        else{
            $data['code']=0;
            $data['message']='失败';
        }

    }
    public function mutil_delete(){
        $where['shop_id'] = $_REQUEST['user_id'];
//        $goods_id = $_REQUEST['goods_id'];
        $goods_id = '1,2,3,4';
        $goods_id_arr = explode(',',$goods_id);
        $re = '';
        for($i=0;$i<count($goods_id_arr);$i++){
           $where['goods_id'] = $goods_id_arr[$i];
           $re = M('goods')->where($where)->delete();
        }
        if($re){
            $data['code']=1;
            $data['message']='成功';

        }
        else{
            $data['code']=0;
            $data['message']='失败';
        }
// array_chunk(arr,10)
    }
    public function algent_goods(){
        $where['shop_id'] = $_REQUEST['user_id'];

        $tag = $_REQUEST['tag'];

        $data = '';
        $re = M('goods')->where($where)->select();
        if($re){
            $data['code']=1;
            $data['message']='成功';

        }
        else{
            $data['code']=0;
            $data['message']='失败';
        }
        if($tag==1){

            foreach ($re as $key => $row)
            {
                $volume[$key]  = $row['goods_sales'];

            }

            array_multisort($volume, SORT_DESC,  $re);
        }elseif($tag==2){
            foreach ($re as $key => $row)
            {
                $volume[$key]  = $row['goods_prices'];

            }

            array_multisort($volume, SORT_DESC,  $re);
        }
       $re= array_chunk($re,10);
        $pageIndex = $_REQUEST['pageIndex'];
        $pageIndex = 1;
        $data['pageIndex'] = $pageIndex;
        $re = $re[$pageIndex-1];
        for($i=0;$i<count($re);$i++){
            $data ['messages'][$i]['goods_id'] = $re[$i]['goods_id'];
            $data ['messages'][$i]['goods_logo'] = $re[$i]['goods_logo'];
            $data ['messages'][$i]['goods_name'] = $re[$i]['goods_name'];
            $data ['messages'][$i]['goods_desc'] = $re[$i]['goods_desc'];
            $data ['messages'][$i]['goods_oldprice'] = $re[$i]['goods_prices'];
            $data ['messages'][$i]['goods_salingnum'] = $re[$i]['goods_salingnum'];//
            $data ['messages'][$i]['goods_balance'] = $re[$i]['goods_balance'];
            $data ['messages'][$i]['goods_countprice'] = $re[$i]['goods_countprice'];//在销人数？
            $data ['messages'][$i]['goods_is_up'] = $re[$i]['goods_is_up'];

        }

        echo($data);

    }
    public function canclealgent_goods(){
        $where['shop_id'] = $_REQUEST['user_id'];
        $where['goods_id'] = $_REQUEST['goods_id'];
        $datas['shop_id'] = null;
        $re = M('goods')->where($where)->save($datas);
        if($re){
            $data['code']=1;
            $data['message']='成功';

        }
        else{
            $data['code']=0;
            $data['message']='失败或无此代理';
        }

        echo($data);
    }

    public function orderlist_manager(){
        $where['member_id'] = $_REQUEST['user_id'];
        $handlestatus = $_REQUEST['handlestatus'];
        $pageIndex = $_REQUEST['pageIndex'];
        $datas['handlestatus'] = $handlestatus;
        $re_handlestatus = M('t_order')->where($where)->save($datas);//更新状态
        $re = M('t_order')->where($where)->select();
        $re= array_chunk($re,10);
        $re = $re[$pageIndex-1];

        if($re){
            $data['code']=1;
            $data['message']='成功';
            for($i=0;$i<count($re);$i++){
                $data ['messages'][$i]['goods_id'] = $re[$i]['goods_id'];
                $data ['messages'][$i]['goods_ordercount'] = $re[$i]['goods_ordercount'];
                $data ['messages'][$i]['goods_ordertime'] = $re[$i]['goods_ordertime'];
                $data ['messages'][$i]['handlestatus'] = $re[$i]['handlestatus'];

                $where_goods['goods_id']= $re[$i]['goods_id'];
                $re_goods = M('goods')->where($where_goods)->find();

                $data ['messages'][$i]['goods_logo'] = $re_goods['goods_logo'];
                $data ['messages'][$i]['goods_name'] = $re_goods['goods_name'];
                $data ['messages'][$i]['goods_desc'] = $re_goods['goods_desc'];



            }
        }
        else{
            $data['code']=0;
            $data['message']='失败';
        }
        echo($data);

    }
    public function delivery_goods(){
        $where['user_id'] = $_REQUEST['user_id'];
        $where['goods_id'] = $_REQUEST['goods_id'];
        $datas['handlestatus'] = 3;
        $re = M('t_order')->where($where)->save($datas);
        if($re){
            $data['code']=1;
            $data['message']='成功';

        }
        else{
            $data['code']=0;
            $data['message']='失败';
        }
        echo($data);
    }
    public function commit_feedback(){
        $datas['suggest_id'] = $_REQUEST['user_id']=9;
        $datas['title'] = $_REQUEST['title']='sss';
        $datas['content'] = $_REQUEST['content']='ssss';
        $datas['create_time'] = date("Y-m-d");

        $re = M('suggest')->add($datas);
        p($re);
        if($re){
            $data['code']=1;
            $data['message']='成功';

        }
        else{
            $data['code']=0;
            $data['message']='失败';
        }
        echo($data);
    }





}