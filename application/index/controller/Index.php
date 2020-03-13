<?php

namespace app\index\controller;
use think\Db;
class Index extends Base
{
    public function Index()
    {
        $posts=input('post.');
        $type=input('get.type');
     if (empty($type)) $type=1;
        $name = 'lisi';
        $this->assign('name', $name);
        $types=Db::query('select * from b_type where 1');
        foreach ($types as $key=>$vo){
            if ($vo['id']==$type) $types[$key]['active']='hui-segment-active';

        }
        $list = Db::name('list')->where('type_id',$type)->paginate(2,false,
            ['query'=>[
                'type'=>$type
            ]]);
        $this->assign('list', $list);
        $this->assign('type', $types);
        return $this->fetch();
    }
    public function Detail()
    {
        $list_id=input('get.id');
        $rst=Db::table('b_list')
            ->where('id','=',$list_id)
            ->find();
        $this->assign('list', $rst);
        return $this->fetch();
    }

}
