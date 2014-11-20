<div class="content-box" ng-show="workingPart == 'userList'">
    <div class="box-header clear">
       <ul class="tabs clear">
            <li style="cursor: pointer;"><a ng-click="u.switchTime('today')"  ng-class="{'selected' : u.filter.time == 'today'}">Today</a></li>
            <li style="cursor: pointer;"><a ng-click="u.switchTime('thisweek')"  ng-class="{'selected' : u.filter.time == 'thisweek'}">This week</a></li>
            <li style="cursor: pointer;"><a ng-click="u.switchTime('thismonth')"  ng-class="{'selected' : u.filter.time == 'thismonth'}">This month</a></li>
            
        </ul>
      <h2>Danh sách thành viên hiện tại của 5Stars (Total : <span ng-bind="u.userCount"></span>)</h2> 
    </div>            


    <div class="box-body clear">
        <div id="editUserForm" ng-show="u.showEditUserForm">
            <form name="userEditForm"  class="form" novalidate>
                <div class="form-field clear">
                    <label for="select" class="form-label fl-space2">Chọn status</label>
                    <select class="fl" ng-model="u.userCreate.User.status" ng-options="s.value as s.name for s in [{ value : 'active', name : 'Active' }, { value : 'inactive', name : 'Inactive' }]"></select>
                </div><!-- /.form-field -->     

                

                <div class="form-field clear">
                    <input ng-model="u.userCreate.submit" ng-click="u.updateUser($event)" type="submit" class="submit fr" value="Submit" />
                </div><!-- /.form-field -->                                                                                                
            </form>
        </div>
        
        <div id="userList">
            <div class="dataTables_wrapper">   
            
                <div class="dataTables_length"> Chọn Kênh <select ng-change="u.renderUser();" ng-model="u.filter.channel" ng-options="s.Channel.id as s.Channel.name for s in c.channels"><option value="">-- All --</option></select>
                </div>
                &nbsp;
                <div class="dataTables_length"> Chọn Game <select ng-change="u.renderUser();" ng-model="u.filter.game" ng-options="s.Game.id as s.Game.name for s in g.games"><option value="">-- All --</option></select>
                </div>         
                
                <br/>   
                <br/>
                <div class="dataTables_length">Custom Date: <input placeholder="ngày bắt đầu" datepicker ng-model="u.filter.start_date" type="text"> &nbsp; <input placeholder="ngày kết thúc" datepicker ng-model="u.filter.end_date" type="text"> &nbsp; <button ng-click="u.renderUser()">Tìm</button>&nbsp;&nbsp;<button ng-click="u.endCustom()">Bỏ Custom</button></div>
                <br/>
                <br/>
                <div class="dataTables_filter">Tìm theo Username: <input ng-model="u.filter.textFilter" type="text"> &nbsp; <button  ng-click="u.renderUser()">Tìm</button><button ng-click="u.endSearch()">Bỏ tìm</button></div>
                
                
                
                <table class="datatable" style="width: 984px;">

                    <thead>
                    <tr>             
                        <th>ID</th>                               
                        <th>Tên</th>
                        <th>Tài khoản</th>                                            
                        <th>Email</th>
                        <th>Thành viên từ</th>
                        <th>Status</th>                        
                        <th>Thiết lập</th>
                    </tr>
                </thead>

                    <tbody>
                    <tr ng-repeat="user in u.currentUsers | limitTo:u.filter.limit"> 
                        <td ng-bind="user.User.id"></td>                                          
                        <td ng-bind="user.User.fullname"></td>
                        <td ng-bind="user.User.username"></td>
                        <td ng-bind="user.User.email"></td>
                        <td ng-bind="user.User.created | timeAgo"></td>
                        <td ng-bind="user.User.status"></td>
                        <td>
                            <a style="cursor: pointer;" ng-show="permission == 'admin' || permission == 'user'" ng-click="u.editUser(user)"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" class="icon16 fl-space2" alt="" title="edit" /></a>
                            <a href="#" style="cursor: pointer;display:none"><img src="<?php echo $this->Html->imageUrl('ico_delete_16.png') ?>" class="icon16 fl-space2" alt="" title="delete" /></a>
                            <a href="#" style="cursor: pointer;display:none"><img src="<?php echo $this->Html->imageUrl('ico_settings_16.png') ?>" class="icon16 fl-space2" alt="" title="settings" /></a>
                        </td>
                    </tr>

                </tbody>
                </table>




                <div class="tab-footer clear fl" ng-show="u.userCount >= u.filter.limit">
                    <div class="fl">                                
                        <input type="submit" ng-model="user.loadmore" class="submit fl-space" ng-click="u.filter.limit = u.filter.limit + 20; u.renderUser();" value="Load More">
                    </div>
                </div>

            </div>
        
        </div>
        
    </div>

 </div>