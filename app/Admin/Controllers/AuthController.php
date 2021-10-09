<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Controllers\AuthController as BaseAuthController;
use Dcat\Admin\Layout\Content;

class AuthController extends BaseAuthController
{
    protected $view = 'admin.login';

//    public function getLogin(Content $content)
//    {
//        return $content->full()->body(view($this->view));
//    }
}
