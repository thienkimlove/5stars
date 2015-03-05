<html>
<head>
    <?php
    echo $this->Html->css('bootstrap3.min');
    echo $this->Html->css('gift-new-year');
    ?>
    <title></title>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1455188264710110";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<div class="container" style="max-width: 750px">
    <div id="body-content">
        <div id="content">
            <div id="message"></div>
            Bước 1: Like fan page <br>
            <div class="fb-like" data-href="https://www.facebook.com/bakhigiangho" data-width="202" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" id="like"></div>
            <br>
            Bước 2: Chọn server
            <select name="server" id="select">
                <option value="0">Chọn Server</option>
                <option value="1">Quỳ Hoa Bảo Điển</option>
                <option value="2">Nhất Dương Chỉ</option>
            </select>

            <br>
            Bước 3: Nhận code <br>
            <button id="get-code"></button>

        </div>
        <div id="result"><p id="code"></p></div>
    </div>

</div>
<script
    src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: "1455188264710110",
            channelUrl: "https://wap.5stars.vn/channel.php",
            status: true,
            cookie: true,
            xfbml: true
        })
    };

    function login() {
        s = $("#select").val();
        if (s == 1 || s == 2) {

            FB.login(function (e) {

                if (e.authResponse) {
                    getCode(e, s);
                } else {
                    var t = "<p class='warning'>Bạn vui lòng cho ứng dụng xin quyền truy cập</p>";
                    $("#message").html(t);
                }
            }, {
                scope: "email,publish_stream,user_likes"
            })
        } else {
            var e = "<p class='warning'>Bạn vui lòng chọn server</p>";
            $("#message").html(e);
        }
    }

    function getCode(e, t) {
        $.ajax({
            url: "https://wap.5stars.vn/fb/fetch/" + e.authResponse.accessToken + "/" + t,
            type: "GET",
            dataType: "json",
            success: function (e) {
                if (!e.error) {
                    $("#content").hide();
                    console.log(e);
                    $("#code").html(e.code);
                    $("#result").fadeIn("slow");
                } else {
                    $("#message").html('<p class="warning">' + e.error + "</p>");
                }
            },
            error: function (e) {
                var t = "<p class='warning'>Có lỗi xảy ra vui lòng thử lại trong giây lát</p>";
                $("#message").html(t);
            },
            beforeSend: function () {
                $("#message").html('<p class="text-info">Đang xử lý dữ liệu vui lòng chờ ...</p>');
            }
        })
    }
    $(document).ready(function () {
        $("#get-code").on("click", function () {
            login();
        })
    })
</script>
</body>

</html>