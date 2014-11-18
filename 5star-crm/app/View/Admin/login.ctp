<div class="login-box" ng-controller="userLoginCtrl" ng-app="userLoginApp" ng-cloak>
    <div class="login-border">
        <div class="login-style">
            <div class="login-header">
                <div class="logo clear">
                    <img src="images/logo_earth.png" alt="" class="picture" />
                    <span class="title" style="font-size: 14px; width: 550px;">
                        <?php 
                        echo $this->Session->flash();
                        echo $this->Session->flash('error');   
                        echo $this->Session->flash('success');
                        echo $this->Session->flash('attention'); 
                        echo $this->Session->flash('info');        
                        ?>

                    </span>			
                </div>
            </div>
            <form id="userLoginForm" name="userLoginForm" novalidate method="post" action="<?php echo $this->Html->url(array('controller' => 'admin', 'action' => 'login')) ?>">

                <div class="login-inside">
                    <div class="login-data">
                        <div class="row clear">
                            <label for="user">Tài khoản:</label>
                            <input type="text" ng-model="user.login" required name="login" value="login" size="25" class="text" id="user" /><br/>
                            <span style="color: red;" ng-show="userLoginForm.login.$dirty && userLoginForm.login.$error.required">Xin nhập vào tài khoản</span>                            
                        </div>
                        <div class="row clear">
                            <label for="password">Mật khẩu:</label>
                            <input type="password" ng-model="user.password" name="password" size="25" class="text" id="password" required /> <br/>
                            <span style="color: red;" ng-show="userLoginForm.password.$dirty && userLoginForm.password.$error.required">Xin nhập vào mật khẩu</span>  
                        </div>
                        <input type="submit" class="submit" ng-click="doLogin($event)" ng-model="user.submit" value="Đăng nhập" />
                    </div>
                    <p>Đăng nhập vào hệ thống CRM 5stars.vn</p>
                </div>

            </form>

        </div>
    </div>
</div>