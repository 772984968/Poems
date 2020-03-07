<?php

namespace app\index\controller;

class User extends Base
{
    public function Index()
    {

        $name = 'lisi';
        $this->assign('name', $name);
        return $this->fetch('account');
    }
    //    用户登录
    public function Login()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $username = $data['user_name'];
            $password = md5($data['password']);
            $rs = db('user')->where(['username' => $username, 'password' => $password])->find();
            if (!$rs) {
                return json([
                    'code' => 400,'success'=>false, 'msg' => '用户名或密码不正确']);
            }
            session('username', $rs['username']);
            session('uid', $rs['id']);
            return json(['code' => 200,'success'=>true, 'msg' => '登录成功！']);
        }

        return $this->fetch();
    }

    //  用户注册
    public function Register()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $register_user = $data['register_user'];
            $register_password = $data['register_password'];
            $register_two_password = $data['register_two_password'];

            $rst=db('user')->where('username',$register_user)->find();
            if (!empty($rst)) return json(  ['code' => 400,'success'=>false, 'msg' => '用户已存在']);
            $password = md5($register_password);
            $rst=db('user')->insertGetId([
                'username'=>$register_user,
                'password'=>$password,
                'status'=>1,
                'head'=>'',
            ]);
            if (!empty($rst)&&empty($id)){
                session('username', $register_user);
                session('uid', $rst);
                return json(['code' => 200,'success'=>true, 'msg' => '注册成功！']);
            }
            return json(['code' => 500,'success'=>true, 'msg' => '注册失败！']);
        }
        return $this->fetch();
    }
    //  用户退出
    public function logout()
    {
        if ($this->request->isGet()) {
            $data = $this->request->get();
            $uid = $data['uid'];
            if (!empty($uid)){
                session(null);
                return json(['code' => 200,'success'=>true, 'msg' => '退出成功！']);
            }
            return json(['code' => 400,'success'=>true, 'msg' => '退出失败！']);
        }
    }

}
