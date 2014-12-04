<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>  <?php echo $title_for_layout; ?> </title>
    <?php
    echo $this->Html->meta('icon', $this->webroot .'app/webroot/favicon.ico');
    echo $this->Html->css('mhv/fanpage');
    ?>
</head>

<body>
<div id="fb-root"></div>

<div id="app">

    <div id="link">
        <a href="http://myhauvuong.5stars.vn/" alt="Mỹ Hầu Vương" target="_blank">Trang Chủ</a>
        <a href="http://diendan.5stars.vn/" alt="Diễn Đàn Mỹ Hầu Vương" target="_blank">Diễn Đàn</a>
    </div>
    <div id="content">
        <div id="get">
            <p>Vui lòng chọn server</p>
            <p>B1: Like Fanpage</p>
            <div class="fb-like" data-href="https://www.facebook.com/myhauvuongmobile" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
            <p>B2:</p>
            <select id="server">
                <?php foreach($servers as $key=>$val):?>
                <option value="<?php  echo $key;?>"><?php echo $val;?></option>
                <?php endforeach;?>
            </select>
            <p>B3:</p>
            <a id="btn-code">get code</a>
        </div>
        <div id="code">
            <img src="/img/mhv/congratulations.png" alt="Chúc mừng bạn"/>
            <p>Giftcode của bạn:</p>
            <p id="giftcode"></p>
        </div>
    </div>
    <div id="popup-layout"></div>
    <div id="popup">
        <div id="popup-close">×</div>
        <div id="popup-message"></div>
    </div>
    <div class="spinner">
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
</div>
<?php
echo $this->Html->script ('lib/jquery-1.11.1.min');
echo $this->Html->script ('mhv/fanpage');
?>
</body>
</html>
