window.fbAsyncInit = function () {
    FB.init({appId: "709557629115632", channelUrl: "//wap.5stars.vn/channel.php", status: !0, cookie: !0, xfbml: !0, version: "v2.0"})
};
(function (a, b, c) {
    var d = a.getElementsByTagName(b)[0];
    a.getElementById(c) || (a = a.createElement(b), a.id = c, a.src = "//connect.facebook.net/en_US/sdk.js", d.parentNode.insertBefore(a, d))
})(document, "script", "facebook-jssdk");
function showLoading() {
    $("#spinner").show();
    $(".spinner").show()
}
function hideLoading() {
    $("#spinner-layout").hide();
    $(".spinner").hide()
}
function showPopup(a) {
    $("#popup-layout").show();
    $("#popup").fadeIn();
    $("#popup-message").html(a)
}
function closePopup() {
    $("#popup-layout").hide();
    $("#popup").fadeOut()
}
$("#popup-close,#popup-layout,#spinner-layout").on("click", function () {
    closePopup();
    hideLoading()
});
$("#btn-code").on("click", function () {
    login()
});
function login() {
    s = $("#server").val();
    (s>0) ? FB.login(function (a) {
        a.authResponse ? getCode(a, s) : showPopup("B\u1ea1n vui l\u00f2ng cho \u1ee9ng d\u1ee5ng xin quy\u1ec1n truy c\u1eadp")
    }, {scope: 'email,publish_actions,user_likes',return_scopes: true}) : showPopup("B\u1ea1n vui l\u00f2ng ch\u1ecdn server")
}
function getCode(a, b) {
    $.ajax({url: "//wap.5stars.vn/mhv/newbie_fetch/" + a.authResponse.accessToken + "/" + b+"/", type: "GET", dataType: "json", success: function (a) {
        a.error ? showPopup(a.error) : ($("#get").hide(), $("#giftcode").html(a.code), $("#code").fadeIn("slow"))
    }, error: function (a) {
        showPopup("C\u00f3 l\u1ed7i x\u1ea3y ra vui l\u00f2ng th\u1eed l\u1ea1i trong gi\u00e2y l\u00e1t")
    }, beforeSend: function () {
        showLoading()
    }, complete: function () {
        hideLoading()
    }})
};