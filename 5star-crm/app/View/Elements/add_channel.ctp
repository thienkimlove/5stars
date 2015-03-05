<div class="content-box" ng-show="workingPart == 'addChannel'">
    <div class="box-header clear">
        <h2>Thêm hoac chinh sua kenh :</h2>
    </div>  

    <div class="box-body clear">
        <div>
            <form class="form" novalidate name="channelCreateForm">
                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Tài khoản: <span class="required">*</span></label>
                    <input type="text" name="username" ng-model="c.channelCreate.User.username" class="text fl"  required />
                </div><!-- /.form-field -->

                <div class="form-field clear">
                    <label for="password" class="form-label fl-space2">Mật khẩu:</label>
                    <input type="password" name="password" ng-model="c.channelCreate.User.password" class="text fl" />
                </div><!-- /.form-field --> 

                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Tên: <span class="required">*</span></label>
                    <input type="text" name="fullname" ng-model="c.channelCreate.User.fullname" class="text fl"  required />
                </div><!-- /.form-field -->  

                <div class="form-field clear">
                    <label for="textfield" class="form-label fl-space2">Email: <span class="required">*</span></label>
                    <input type="text" name="email" ng-model="c.channelCreate.User.email" class="text fl"  required />
                </div><!-- /.form-field --> 


                <div class="form-field clear">
                    <label for="select" class="form-label fl-space2">Chọn status</label>
                    <select class="fl" name="status" ng-model="c.channelCreate.Channel.status" ng-options="s.value as s.name for s in [{ value : 'active', name : 'Active' }, { value : 'inactive', name : 'Inactive' }]"></select>
                </div><!-- /.form-field -->     

                <div class="clear">
                    <div class="half fl">
                        <div class="form-field clear">
                            <h4>Kết nối thành viên:</h4>
                            <div class="form-radio-item clear">
                                <input type="radio" ng-model="c.channelCreate.Channel.login_connection" name="login_connection" id="radio1" value="1" class="radio fl-space" /> <label for="radio1" class="fl">Có</label>
                            </div>

                            <div class="form-radio-item clear">
                                <input type="radio" ng-model="c.channelCreate.Channel.login_connection" name="login_connection" id="radio2" value="0" class="radio fl-space" /> <label for="radio2" class="fl">Không</label>
                            </div>                                


                        </div><!-- /.form-field-->
                    </div>

                    <div class="half fr">
                        <div class="form-field clear">
                            <h4>Kết nối payment:</h4>
                            <div class="form-radio-item clear fl-space2">
                                <input type="radio" ng-model="c.channelCreate.Channel.payment_connection" name="radio2" id="radio4" class="radio fl-space" value="1" /> <label for="radio4" class="fl">Có</label>
                            </div>

                            <div class="form-radio-item clear fl-space2">
                                <input type="radio" ng-model="c.channelCreate.Channel.payment_connection" name="radio2" id="radio5" class="radio fl-space" value="0" /> <label for="radio5" class="fl">Không</label>
                            </div>                                


                        </div><!-- /.form-field-->
                    </div>
                </div>                         



                <div class="form-field clear">
                    
                
                    <input ng-model="c.channelCreate.submit" type="submit" id="submitCreateChannelButton" ng-click="c.addChannel($event)" class="submit fr" value="Submit" />
                </div><!-- /.form-field --> 
                <div class="form-field clear">
                   <input ng-model="c.channelCreate.cancel" type="submit"  ng-click="c.cancelChannel($event)" class="submit fr" value="Cancel" />
                </div>                                                                                               
            </form>
        </div>
    </div>

 </div>