<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
    <title>支付宝扫码</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="renderer" content="webkit">
    <link type="text/css" href="/plugins/css/ali_qr.css" rel="stylesheet">
    <script type="text/javascript" src="//ossweb-img.qq.com/images/js/jquery/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/plugins/js/qrcode.min.js"></script>
    <script type="text/javascript" src="/plugins/js/steal_alipay.js?v=1.1"></script>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text">支付宝扫码</span>
    </h1>
    <div class="mod-ct">
        <div class="order"></div>
        <div class="amount">￥{{ sprintf('%0.2f',$amount/100) }}</div>
        <?php if(is_numeric($qrcode)&&strlen($qrcode)==16){?>
        <div class="qr-image" id="qrcode"></div>
        <?php }else{?>
        <div class="qr-image" id="qrcode"><img src="/zfb.jpg" width="230" height="230" style="" /></div>
        <div><span style="color:red;font-weight:bold;display: block;margin-top: 24px">付款时请输入付款备注：{{$_GET["title"]}}</span></div>
        <?php }?>

        <div id="open-app-container">
            <span style="display: block;margin-top: 24px" id="open-app-tip">请截屏此界面或保存二维码，打开支付宝扫码，选择相册图片</span>
            <span style="display: block;color: red;margin-top: 8px">请支付成功后直接返回，不要重复支付！</span>
            <a style="padding:6px 34px;border:1px solid #e5e5e5;display: inline-block;margin-top: 8px" id="open-app">点击打开支付宝</a>
        </div>
        <div class="detail" id="orderDetail">
            <dl class="detail-ct" style="display: none;">
                <dt>商品</dt>
                <dd id="storeName">{{ $name }}</dd>
                <!--dt>说明</dt>
                <dd id="productName">用户充值</dd-->
                <dt>订单号</dt>
                <dd id="billId">{{ $id }}</dd>
                <dt>时间</dt>
                <dd id="createTime"><?php echo date('Y-m-d H:i:s')?></dd>
            </dl>
            <a href="javascript:void(0)" class="arrow"><i class="ico-arrow"></i></a>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan"></div>
            <div class="tip-text">
                <p>请使用支付宝扫一扫</p>
                <p>扫描二维码完成支付</p>
            </div>
        </div>
        <div class="tip-text">
        </div>
    </div>
    <div class="foot">
        <div class="inner">
            <p><?php echo SYS_NAME ?>, 有疑问请联系客服</p>
        </div>
    </div>
</div>

<script>
        <?php if(is_numeric($qrcode)&&strlen($qrcode)==16){?>
    var code_url = 'alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data={"s": "money","u": "{{$qrcode}}","a": "{{ sprintf('%0.2f',$amount/100) }}","m":"{{$_GET["title"]}}"}';

    var qrcode = new QRCode("qrcode", {
        text: code_url,
        width: 230,
        height: 230,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H,
        title: '请使用支付宝扫一扫'
    });
    <?}?>
    // 订单详情
    var orderDetail = $('#orderDetail');
    orderDetail.find('.arrow').click(function (event) {
        if (orderDetail.hasClass('detail-open')) {
            orderDetail.find('.detail-ct').slideUp(500, function () {
                orderDetail.removeClass('detail-open');
            });
        } else {
            orderDetail.find('.detail-ct').slideDown(500, function () {
                orderDetail.addClass('detail-open');
            });
        }
    });

    $(document).ready(function () {
        var time = 4000, interval;
        function getData() {
            $.post('/api/qrcode/query/{!! $pay_id !!}', {
                    id: '{!! $id !!}',
                    t: Math.random()
                },
                function (r) {
                    clearInterval(interval);
                    $('.qr-image').remove();
                    $('.tip').html('<p style="font-size:24px">已支付，正在处理...</p>');
                    window.location = r.data;
                }, 'json');
        }
        (function run() {
            interval = setInterval(getData, time);
        })();
    });

    // call app
    if (navigator.userAgent.match(/(iPhone|iPod|Android|ios|SymbianOS)/i) !== null) {
        var app_package = 'com.eg.android.AlipayGphone';
        var app_url = 'alipays://platformapi/startapp?saId=10000007&clientVersion=3.7.0.0718&qrcode=' + encodeURIComponent(code_url);
        $('#open-app').on('click', function () {
            goPage(app_url, app_package);
        });
        setTimeout(function () {
            goPage(app_url, app_package);
        }, 100);
    } else {
        $('#open-app-tip').hide();
        $('#open-app').hide();
    }

    setTimeout(function () {
        alert('请支付成功后直接返回，不要重复支付！');
    },100);

</script>
</body>
</html>