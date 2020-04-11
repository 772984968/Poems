<?php

namespace app\index\controller;

use think\Db;

class Index extends Base
{
    public function Index()
    {

        $type = input('get.type');
        $search = input('get.search');
        if (empty($type)) $type = 1;
        $name = 'lisi';
        $this->assign('name', $name);
        $types = Db::query("select * from b_type where 1  ");
        foreach ($types as $key => $vo) {
            if ($vo['id'] == $type) $types[$key]['active'] = 'hui-segment-active';

        }
        if (!empty($search)) {
            $where = " status=1 and author like '%{$search}%' or biaoti like '%{$search}%' or content like '%{$search}%'";
            $sql = 'select * from b_list where  ' . $where;
            $list = Db::query($sql);

        } else {
            $search = '';
            $list = Db::name('list')->where([
                'type_id' => $type,
                'status' => 1
            ])->paginate(10, false,
                ['query' => [
                    'type' => $type
                ]]);
        }

        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('type', $types);
        return $this->fetch();
    }

    public function Detail()
    {
        $list_id = input('get.id');
        $rst = Db::table('b_list')
            ->where('id', '=', $list_id)
            ->find();
        $user = db('user')->where([
            'id' => $rst['uid']
        ])->find();

        $comments = Db::query("SELECT
	b_comments.*,b_user.head,b_user.username,b_list.uid as author_id
FROM
	b_comments
LEFT JOIN b_user on b_user.id=b_comments.uid
LEFT JOIN b_list on b_comments.list_id=b_list.id
WHERE
	list_id =" . $list_id." ORDER BY b_comments.id desc");
        $this->assign('list', $rst);
        foreach ($comments as $key => $comment) {
            $recomment = Db::query("SELECT
	*
FROM
	b_recomments
LEFT JOIN b_user ON b_user.id=b_recomments.reply_id
WHERE
	 comment_id = " . $comment['id'] . "  ORDER BY b_recomments.id desc");

            foreach ($recomment as $k=>$value){
                if ($value['reply_id']==$comment['author_id'])
                $recomment[$k]['username']='<span style="color: red">作者</span>';
            }
            if ($comment['uid']==$comment['author_id']){
                $comments[$key]['username']='<span style="color: red">作者</span>';
            }
            $comments[$key]['recomments'] = $recomment;
        }
        $this->assign('comments', $comments);
        $this->assign('user', $user);
        return $this->fetch();
    }

    public function Create()
    {
        if ($this->request->isPost()) {
            $post = input('post.');
            $types = [
                '1' => '唐朝',
                '2' => '宋朝',
                '3' => '其他',
            ];
            $src = '/img/img/' . random_int(1, 10) . '.jpg';
            $user = db('user')->where('id', session('uid'))->find();
            $status = !empty($user['is_admin']) ? 1 : 0;
            $rst = db('list')->insertGetId([
                'type_id' => $post['type'],
                'biaoti' => $post['biaoti'],
                'author' => $post['author'],
                'content' => $post['content'],
                'dynasty' => $types[$post['type']],
                'comment' => !empty($post['comment']) ? $post['comment'] : '',
                'introduction' => !empty($post['introduction']) ? $post['introduction'] : '',
                'date' => date('Y-m-d H:i:s'),
                'src' => $src,
                'status' => $status,
                'uid' => session('uid'),
            ]);
            if (!empty($rst)) {
                return json(['code' => 200, 'success' => true, 'msg' => '发布成功，请耐心等待管理审核！']);
            }
            return json(['code' => 400, 'success' => false, 'msg' => '发布失败，请稍后再试！']);
        }
        return $this->fetch();
    }

    public function hot(){
        return $this->fetch();
    }

}
