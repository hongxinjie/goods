<?php

namespace App\Admin\Controllers;

use App\Admin\Metrics\Examples;
use App\Admin\Metrics\Order;
use App\Admin\Metrics\Users;
use App\Http\Controllers\Controller;
use Dcat\Admin\Controllers\Dashboard;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('近期变动')
            ->body(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $column->row(new Order());
                });
                $row->column(6, function (Column $column) {
                    $column->row(new Users());
                });
            });
    }
}
