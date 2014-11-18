<script type="text/javascript">
$(document).ready(function(){
	$(".list li p").hide();
	$(".list > li").on('click',function(){
		$('.active').removeClass('active');
		$(this).addClass('active');
		$('.list li p:visible').stop().slideUp('500');
	    $('.active p').stop().slideDown('500');
		return false;
	});
	$(".list li p a").on('click',function(){
		window.location.href = $(this).attr('href');
	});
});
</script>
<form class="content">
<h2 class="heading">FAQS - Các câu hỏi thường gặp</h2>
<ul class="list">
		<li><a href="#">1. Tài khoản 5Stars là gì?</a>
		<p>Đây là tài khoản dùng để truy cập vào tất cả các sản phẩm do 5Stars cung cấp.</p></li>
		<li><a href="#">2. Làm sao để có được tài khoản 5Stars?</a>
		<p>Truy cập vào địa chỉ sau: <a href="http://wap.5stars.vn/">http://wap.5stars.vn/</a><br/>
			- Chọn đăng ký tài khoản 5Stars.<br/>
			- Điền đầy đủ và chính xác các thông tin trên trang đăng ký 5Stars vì đó là cơ sở để chúng tôi hỗ trợ bạn khi có vấn đề phát sinh.
		</p></li>
		<li><a href="#">3. Cách tốt nhất để bảo vệ tài khoản 5Stars?</a>
		<p>Để bảo vệ tài khoản của bạn tốt nhất, bạn cần phải khai báo đầy đủ và thật chính xác các thông tin cho tài khoản khi đăng ký như:<br/>
- Địa chỉ Email: đây là thông tin để chúng tôi liên lạc với bạn và giúp bạn khôi phục mật khẩu hay các thông tin về chương trình khuyến mãi.
		</p></li>
		<li><a href="#">4. Làm sao để khôi phục mật khẩu bị đánh mất hay quên?</a>
		<p>Chúng tôi cung cấp cho bạn 2 cách lấy mật khẩu như sau: <br/>
Cách 1: Tạo lại mật khẩu thông qua Email.<br/>
- Truy cập vào địa chỉ sau: http:// <br/>
 - Nhập vào địa chỉ Email đã dùng để đăng ký tài khoản trước đây.<br/>
- Lúc này hệ thống sẽ gửi link liên kết tạo lại mật khẩu mới thông qua địa chỉ Email mà bạn đã khai báo khi đăng ký trước đây.<br/>
Cách 2: Tạo lại mật khẩu qua Số điện thoại di động.<br/>
- Truy cập vào địa chỉ sau: http:// <br/>
- Nhập vào số điện thoại di động đã cập nhật trong thông tin cá nhân.<br/>
- Lúc này hệ thống sẽ gửi về điện thoại của bạn SMS với nội dung là link liên kết tạo lại mật khẩu mới.<br/>
		</p></li>
		<li><a href="#">5. Làm sao có thể chơi game của 5Stars ?</a>
		<p>Chỉ cần có tài khoản 5Stars bạn có thể chơi được Game của 5Stars.</p></li>
		<li><a href="#">6. Dịch vụ 5Stars Pay cung cấp những hình thức nạp thẻ nào?</a>
		<p>Hiện tại dịch vụ 5Stars Pay cung cấp 2 phương thức nạp thẻ vào game:<br/>
- Các loại thẻ cào điện thoại của Viettel, Mobi, Vina với mệnh giá 10.000, 20.000, 50.000, 100.000, 200.000, 500.000 VNĐ.<br/>
- Nạp qua SMS
		</p></li>

	</ul>
</form>