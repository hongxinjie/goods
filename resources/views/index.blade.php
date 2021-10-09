<!doctype html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>zzz~~~~</title>
    <meta name="description" content="zzz~~~~" >
    <meta name="keywords" content="zzz~~~~" >
    <link href="static/css/index.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="banner">
        <div class="contact-form">
            <div class="form-item">
                <div class="form-input ">
                    <input class="name" style="width: 325px;" type="text" placeholder="请输入您的姓名" maxlength="20" />
                </div>
                <div class="form-err form-err-name">
                    请输入您的姓名
                </div>
            </div>
            <div class="form-item">
                <div class="form-input">
                    <input class="mobile" type="text" placeholder="请输入您的手机号" maxlength="11" />
                </div>
            </div>
            <div class="form-button">
                <span class="btn">加入我们</span>
            </div>
            <div class="form-button" style="margin-left: 20px">
                <a class="btn" href="{{ route('admin') }}">前往登录</a>
            </div>
        </div>
    </div>
</header>
<section class="service">
    <div class="container">
        <div class="title" style="margin-top: -60px; margin-bottom: 60px;">
            <h1>我们的优势</h1>
            <div>合规、专业、安全的保障体系</div>
        </div>
        <div class="content">
            <div class="col-xs-6 col-md-3">
                <div class="item"> <i class="icon1"></i>
                    <h2>节约成本</h2>
                    <p>降低内部管理及服务人员， </p>
                    <p>显著降低管理成本</p>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="item"> <i class="icon4"></i>
                    <h2>方便快捷</h2>
                    <p>自主研发结算系统，自由职业者</p>
                    <p>订单可实时处理，7×24小时极速发货</p>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="item"> <i class="icon5"></i>
                    <h2>提升体验</h2>
                    <p>无需担心快递不方便，自主选择；</p>
                    <p>订单状态随时查看</p>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="item"> <i class="icon6"></i>
                    <h2>隐私保障</h2>
                    <p>数据加密、分布式存储，</p>
                    <p>全力护航数据隐私</p>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="static/js/jquery1.9.0.min.js" type="text/javascript"></script>
<script src="Bootstrap/js/bootstrap.js"></script>
</body>
</html>
