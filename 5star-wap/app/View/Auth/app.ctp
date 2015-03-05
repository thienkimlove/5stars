<!doctype html>
<html>
<head>
    <?php
    echo $this->Html->css('bootstrap3.min');
    echo $this->Html->css('gift_v2');
    ?>
    <title>Bá Khí Giang Hồ</title>
</head>
<body>
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=165774193618638";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<div class="container" style="max-width: 750px">
    <div id="body-content">
        <div id="content">
            <div id="message"></div>
            Bước 1: Like fan page <br>

            <div class="fb-like" data-href="https://www.facebook.com/bakhigiangho" data-width="202"
                 data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"
                 id="like"></div>
            <br>
            Bước 2:
            <select name="server" id="server">
                <option value="0">-- Chọn server --</option>
                <option value="2">Nhất Dương Chỉ</option>
                <option value="3">Hấp Tinh Đại Pháp</option>
                <option value="4">Ngọc Nữ Tâm Kinh</option>
                <option value="5">Dịch Cân Kinh</option>
                <option value="6">Cửu Âm Chân Kinh</option>
                <option value="7">Cửu Dương Chân Kinh</option>
                <option value="8">Độc Cô Cửu Kiếm</option>
                <option value="9">Lục Mạch Thần Kiếm</option>
                <option value="10">Bắc Minh Thần Công</option>
                <option value="11">Lăng Ba Vi Bộ</option>
            </select>

            <br>
            Bước 3: Nhận code <br>
            <button id="get-code"></button>

        </div>
        <div id="result"><p id="code"></p></div>
        <div id="button-top">
            <a href="http://bakhi.5stars.vn" target="_blank">Trang Chủ</a> |
            <a href="http://diendan.5stars.vn/#ba-khi-giang-h.17" target="_blank"> Diễn Đàn</a>
        </div>
        <div id="button-bottom">
            <a href="//play.google.com/store/apps/details?id=com.fivestarsmobilegame.bakhi" target="_blank"><img
                    src="../img/fb/btn-02.png"></a>
            <a href="//itunes.apple.com/WebObjects/MZStore.woa/wa/viewSoftware?id=767373717&mt=8" target="_blank"><img
                    src="../img/fb/btn-01.png"></a>
        </div>
    </div>

</div>

<script
    src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: "165774193618638",
            channelUrl: "https://wap.5stars.vn/channel.php",
            status: true,
            cookie: true,
            xfbml: true
        })
    };

    function login(s) {
        FB.login(function (e) {
            if (e.authResponse) {
                getCode(e, s)
            } else {
                var t = "<p class='warning'>Bạn vui lòng cho ứng dụng xin quyền truy cập</p>";
                $("#message").html(t)
            }
        }, {
            scope: "email,publish_stream,user_likes"
        })
    }

    function getCode(e, s) {
        $.ajax({
            url: "//wap.5stars.vn/auth/fetch/" + e.authResponse.accessToken + "/" + s,
            type: "GET",
            dataType: "json",
            success: function (e) {
                if (!e.error) {
                    $("#content").hide();
                    $("#code").html(e.code);
                    $("#result").fadeIn("slow")
                } else {
                    $("#message").html('<p class="warning">' + e.error + "</p>")
                }
            },
            error: function (e) {
                var t = "<p class='warning'>Có lỗi xảy ra vui lòng thử lại trong giây lát</p>";
                $("#message").html(t)
            },
            beforeSend: function () {
                $("#message").html('<p class="text-info">Đang xử lý dữ liệu vui lòng chờ ...</p>')
            }
        })
    }
    $(document).ready(function () {
        $("#get-code").on("click", function () {
            var s = $("#server").val();
            if (s == 2 || s == 3 || s == 4 || s == 5 || s == 6 || s == 7 || s == 8 || s == 9 || s == 10 || s == 11) {
                login(s)
            } else {
                var t = "<p class='warning'>Vui lòng chọn server</p>";
                $("#message").html(t);
            }

        })
    })
</script>

</body>

</html>