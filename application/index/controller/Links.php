<?php
namespace app\index\controller;
use think\Controller;
class Links extends Controller{
    public function index()
    {
        return $this->view->fetch();
    }
}