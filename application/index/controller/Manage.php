<?php

namespace app\index\controller;

use think\Db;

class Manage extends Base
{
    public function Index()
    {

        $search=input('search');
        $where='1';
        switch ($search){
            case '待审核':
                $where=' status=0';
                break;
            case '已审核':
                $where=' status=1';
                break;
            case '唐朝':
                $where=' type_id=1';
                break;
            case '宋朝':
                $where=' type_id=2';
                break;
            default:
                $where=" author like '%{$search}%' or biaoti like '%{$search}%' or content like '%{$search}%'";
                
        }
        $sql='select * from b_list where  '.$where;
        $rst=Db::query($sql);
        $this->assign('list', $rst);
        $this->assign('search', $search);
        return $this->fetch();
    }

    public function update(){
        if ($this->request->isPost()){
            $post=input('post.');
            $types=[
                '1'=>'唐朝',
                '2'=>'宋朝',
                '3'=>'其他',
            ];
            $rst=db('list')->update([
                'id'=>$post['id'],
                'type_id'=>$post['type'],
                'biaoti'=>$post['biaoti'],
                'author'=>$post['author'],
                'content'=>$post['content'],
                'dynasty'=>$types[$post['type']],
                'comment'=>!empty($post['comment'])?$post['comment']:'',
                'introduction'=>!empty($post['introduction'])?$post['introduction']:'',
                'status'=>'1',
            ]);
            if (!empty($rst)){
                return json(['code' => 200,'success'=>true, 'msg' => '审核成功']);
            }
            return json(['code' => 400,'success'=>false, 'msg' => '审核失败，请稍后重试！']);
        }
        if ($this->request->isGet()){
            $rst=db('list')->where('id',input('list_id'))->find();
            $this->assign('list',$rst);
        }
        return $this->fetch();
    }
    public function delete(){
        if ($this->request->isPost()){
            $rst=db('list')->where('id',input('id'))->delete();
            if (!empty($rst)){
                return json(['code' => 200,'success'=>true, 'msg' => '成功删除！']);
            }
            return json(['code' => 400,'success'=>false, 'msg' => '删除失败，请稍后再试！']);
        }
        return $this->fetch();
    }
    public function comments(){
        //我评论的
        $mycom = Db::query("SELECT
	c.*, l.biaoti,u.username,l.id as lid
FROM
	b_comments AS c
LEFT JOIN b_list AS l ON c.list_id = l.id
LEFT JOIN b_user AS u ON c.uid = u.id
WHERE	1 ORDER BY date desc ");

        $this->assign('mycom', $mycom);
        return $this->fetch();


    }
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
        $list = Db::query("SELECT	c.id,c.list_id,l.biaoti,c.comments,c.status, u.username FROM 	b_list AS l LEFT JOIN b_comments AS c ON l.id = c.list_id LEFT JOIN b_user AS u ON u.id = l.uid WHERE c.id=" . $id);

        $this->assign('list', $list);
        return $this->fetch();
    }




}
