<?php

namespace app\index\controller;

class Index extends Base
{
    public function Index()
    {

        $name = 'lisi';
        $this->assign('name', $name);
        return $this->fetch();
    }


}
