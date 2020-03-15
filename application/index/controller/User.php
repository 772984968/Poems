<?php

namespace app\index\controller;

use think\Db;

class User extends Base
{
    public function Index()
    {
        $user=[];
        if (session('uid')){
            $user=db('user')->where('id',session('uid'))->find();
        }
        $this->assign('user',$user);
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
                    'code' => 400, 'success' => false, 'msg' => '用户名或密码不正确']);
            }
            session('username', $rs['username']);
            session('uid', $rs['id']);
            return json(['code' => 200, 'success' => true, 'msg' => '登录成功！']);
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
            $sex = $data['sex'];
            if ($sex==1)$head='/img/head/'.random_int(1,9).'.jpg';
            if ($sex==0)$head='/img/head/10.jpg';


            $rst = db('user')->where('username', $register_user)->find();
            if (!empty($rst)) return json(['code' => 400, 'success' => false, 'msg' => '用户已存在']);
            $password = md5($register_password);
            $rst = db('user')->insertGetId([
                'username' => $register_user,
                'password' => $password,
                'status' => 1,
                'sex' => $sex,
                'head' => $head,
                'introduction' => $data['introduction'],
            ]);
            if (!empty($rst)) {
                session('username', $register_user);
                session('uid', $rst);
                return json(['code' => 200, 'success' => true, 'msg' => '注册成功！']);
            }
            return json(['code' => 500, 'success' => true, 'msg' => '注册失败！']);
        }
        return $this->fetch();
    }

    //  用户退出
    public function logout()
    {
        if ($this->request->isGet()) {
            $data = $this->request->get();
            $uid = $data['uid'];
            if (!empty($uid)) {
                session(null);
                return json(['code' => 200, 'success' => true, 'msg' => '退出成功！']);
            }
            return json(['code' => 400, 'success' => true, 'msg' => '退出失败！']);
        }
    }

    public function collect()
    {
        if ($this->request->isPost()) {
            $rst = db('attention')->where([
                'uid' => session('uid'),
                'list_id' => input('list_id'),
                'type' => 1,
            ])->find();
            if (!empty($rst)) return json(['code' => 200, 'success' => true, 'msg' => '已经收藏过了！']);
            $rst = db('attention')->insertGetId([
                'uid' => session('uid'),
                'type' => 1,
                'list_id' => input('list_id'),
            ]);
            if (!empty($rst)) {
                return json(['code' => 200, 'success' => true, 'msg' => '收藏成功！']);
            }
            return json(['code' => 500, 'success' => true, 'msg' => '收藏失败！']);
        }
    }

    public function like()
    {
        if ($this->request->isPost()) {
            $rst = db('attention')->where([
                'uid' => session('uid'),
                'list_id' => input('list_id'),
                'type' => 2,
            ])->find();
            if (!empty($rst)) return json(['code' => 200, 'success' => true, 'msg' => '已经点赞过了！']);
            $rst = db('attention')->insertGetId([
                'uid' => session('uid'),
                'type' => 2,
                'list_id' => input('list_id'),
            ]);
            if (!empty($rst)) {
                return json(['code' => 200, 'success' => true, 'msg' => '点赞成功！']);
            }
            return json(['code' => 500, 'success' => true, 'msg' => '点赞失败！']);
        }
    }

    public function mycollect()
    {
        if ($this->request->isPost()) {
            $rst = db('attention')->where([
                'uid' => session('uid'),
                'list_id' => input('list_id'),
                'type' => 1,
            ])->find();
            if (!empty($rst)) return json(['code' => 200, 'success' => true, 'msg' => '已经收藏过了！']);
            $rst = db('attention')->insertGetId([
                'uid' => session('uid'),
                'type' => 1,
                'list_id' => input('list_id'),
            ]);
            if (!empty($rst)) {
                return json(['code' => 200, 'success' => true, 'msg' => '收藏成功！']);
            }
            return json(['code' => 500, 'success' => true, 'msg' => '收藏失败！']);
        }
        $rst = Db::query("select b_attention.id as a_id,b_list.* from b_list LEFT JOIN b_attention ON b_attention.list_id=b_list.id  WHERE b_attention.type=1 and  b_attention.uid=" . session('uid'));
        $this->assign('list', $rst);
        return $this->fetch();
    }

    public function delcollect()
    {
        if ($this->request->isPost()) {
            $rst = db('attention')->where([
                'id' => input('id'),
            ])->delete();
            if (!empty($rst)) return json(['code' => 200, 'success' => true, 'msg' => '取消成功！']);
            return json(['code' => 500, 'success' => true, 'msg' => '取消失败！']);
        }
    }

    public function mylike()
    {
        if ($this->request->isPost()) {
            $rst = db('attention')->where([
                'uid' => session('uid'),
                'list_id' => input('list_id'),
                'type' => 2,
            ])->find();
            if (!empty($rst)) return json(['code' => 200, 'success' => true, 'msg' => '已经点赞过了！']);
            $rst = db('attention')->insertGetId([
                'uid' => session('uid'),
                'type' => 1,
                'list_id' => input('list_id'),
            ]);
            if (!empty($rst)) {
                return json(['code' => 200, 'success' => true, 'msg' => '点赞成功！']);
            }
            return json(['code' => 500, 'success' => true, 'msg' => '点赞失败！']);
        }
        $rst = Db::query("select b_attention.id as a_id,b_list.* from b_list LEFT JOIN b_attention ON b_attention.list_id=b_list.id  WHERE b_attention.type=2 and  b_attention.uid=" . session('uid'));
        $this->assign('list', $rst);
        return $this->fetch();
    }

    public function dellike()
    {
        if ($this->request->isPost()) {
            $rst = db('attention')->where([
                'id' => input('id'),
            ])->delete();
            if (!empty($rst)) return json(['code' => 200, 'success' => true, 'msg' => '取消成功！']);
            return json(['code' => 500, 'success' => true, 'msg' => '取消失败！']);
        }
    }

    public function comment()
    {
        if ($this->request->isPost()) {
            $rst = db('comments')->insertGetId([
                'uid' => session('uid'),
                'comments' => input('comments'),
                'date' => date('Y-m-d H:i:s'),
                'status' => 0,
                'list_id' => input('list_id'),
            ]);
            if (!empty($rst)) {
                return json(['code' => 200, 'success' => true, 'msg' => '评论成功，请耐心等待管理员审核！']);
            }
            return json(['code' => 500, 'success' => true, 'msg' => '品论失败失败！']);
        }
    }

    public function comments()
    {
        if ($this->request->isPost()) {
            $rst = db('comments')->insertGetId([
                'uid' => session('uid'),
                'comments' => input('comments'),
                'date' => date('Y-m-d H:i:s'),
                'status' => 0,
                'list_id' => input('list_id'),
            ]);
            if (!empty($rst)) {
                return json(['code' => 200, 'success' => true, 'msg' => '评论成功，请耐心等待管理员审核！']);
            }
            return json(['code' => 500, 'success' => true, 'msg' => '品论失败失败！']);
        }
        $uid = session('uid');

        //我评论的
        $mycom = Db::query("SELECT
	c.*, l.biaoti,u.username
FROM
	b_comments AS c
LEFT JOIN b_list AS l ON c.list_id = l.id
LEFT JOIN b_user AS u ON c.uid = u.id
WHERE
	c.uid =" . $uid);
        //评论我的
        $commy = Db::query("SELECT 
	c.*,
	l.biaoti,
	u.username
FROM
	b_comments AS c
LEFT JOIN b_list AS l ON l.id = c.list_id
LEFT JOIN b_user AS u ON u.id = l.uid
WHERE
	l.uid =" . session('uid'));

        $this->assign('mycom', $mycom);
        $this->assign('commy', $commy);
        return $this->fetch();
    }

    //我评论的
    public function infocomments()
    {
        if ($this->request->isPost()) {
            $rst = db('comments')->where([
                'id' => input('id'),
            ])->delete();
            if (!empty($rst)) return json(['code' => 200, 'success' => true, 'msg' => '删除成功！']);
            return json(['code' => 500, 'success' => true, 'msg' => '删除失败！']);
        }
        $id = input('id');
        //我评论的
        $list = Db::query("SELECT	c.id,l.biaoti,c.comments,c.status, u.username FROM 	b_list AS l LEFT JOIN b_comments AS c ON l.id = c.list_id LEFT JOIN b_user AS u ON u.id = l.uid WHERE c.id=" . $id);

        $this->assign('list', $list);
        return $this->fetch();
    }
}
