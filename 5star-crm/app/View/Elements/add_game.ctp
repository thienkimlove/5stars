<div class="content-box" ng-show="workingPart == 'addGame'">
    <div class="box-header clear">
        <h2>Thêm game mới :</h2>
    </div>  

    <div class="box-body clear">
        <div>
            <form class="form" novalidate name="gameCreateForm">
                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Tài khoản: <span class="required">*</span></label>
                    <input type="text" name="username" ng-model="g.gameCreate.User.username" class="text fl"  required />
                </div><!-- /.form-field -->

                <div class="form-field clear">
                    <label for="password" class="form-label fl-space2">Mật khẩu:</label>
                    <input type="password" name="password" ng-model="g.gameCreate.User.password" class="text fl" />
                </div><!-- /.form-field --> 

                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Tên: <span class="required">*</span></label>
                    <input type="text" name="fullname" ng-model="g.gameCreate.User.fullname" class="text fl"  required />
                </div><!-- /.form-field -->  

                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Email: <span class="required">*</span></label>
                    <input type="text" name="email" ng-model="g.gameCreate.User.email" class="text fl"  required />
                </div><!-- /.form-field --> 


                <div class="form-field clear">
                    <label for="select" class="form-label fl-space2">Chọn status</label>
                    <select class="fl" name="status" ng-model="g.gameCreate.Game.status" ng-options="s.value as s.name for s in [{ value : 'active', name : 'Active' }, { value : 'inactive', name : 'Inactive' }]"></select>
                </div><!-- /.form-field -->     

                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Security Key: <span class="required">*</span></label>
                    <input type="text" name="security_key" ng-model="g.gameCreate.Game.security_key" class="text fl"  required />
                </div><!-- /.form-field --> 
                
                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Billing URL: </label>
                    <input type="text" name="billing_url" ng-model="g.gameCreate.Game.billing_url" class="text fl"   />
                </div><!-- /.form-field --> 
                
                
                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Wakeup Syntax: </label>
                    <input type="text" name="wakeup_syntax" ng-model="g.gameCreate.Game.wakeup_syntax" class="text fl"   />
                </div><!-- /.form-field -->                          



                <div class="form-field clear">
                    <input ng-model="g.gameCreate.submit" type="submit" id="submitCreateGameButton" ng-click="g.addGame($event)" class="submit fr" value="Submit" />
                </div><!-- /.form-field --> 
                <div class="form-field clear">
                    <input ng-model="g.gameCreate.cancel" type="submit" ng-click="g.cancelGame($event)" class="submit fr" value="Cancel" />
                </div><!-- /.form-field -->                                                                                               
            </form>
        </div>
    </div>

 </div>