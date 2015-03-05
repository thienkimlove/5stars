<div class="control-group">

<a href="<?php echo $this->Html->url(array('controller' => 'auth','action' => 'register')) ?>" class="btn btn-block btn-success">Tạo tài khoản 5Stars bằng thông tin của Facebook</a></p>

<a href="<?php echo $this->Session->read('Auth.CurrentUrl') ?>" class="btn btn-block btn-primary">Quay lại trang đăng nhập</a>
</div>