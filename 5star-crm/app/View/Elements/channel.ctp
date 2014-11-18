<div class="content-box" ng-show="workingPart == 'channelList'">
    <div class="box-header clear">
      <h2>Danh sách kênh hiện tại của 5Stars</h2> 
    </div>            



    <div class="box-body clear">
        <div id="tableChannel">
            
            <table>
                <thead>
                    <tr>             
                        <th>ID</th>                               
                        <th>Tên</th>
                        <th>Tài khoản CRM</th>                                            
                        <th>Status</th>
                        <th>Kết nối Thành viên</th>
                        <th>Kết nối Thanh toán</th>
                        <th>Thiết lập</th>
                    </tr>
                </thead>

                <tbody>
                    <tr ng-repeat="channel in c.channels | limitTo:c.channelLimit"> 
                        <td ng-bind="channel.Channel.id"></td>                                          
                        <td ng-bind="channel.User.fullname"></td>
                        <td ng-bind="channel.User.username"></td>
                        <td ng-bind="channel.Channel.status"></td>
                        <td ng-bind="channel.Channel.login_connection"></td>
                        <td ng-bind="channel.Channel.payment_connection"></td>
                        <td>
                            <a style="cursor: pointer;" ng-click="c.editChannel(channel)"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" class="icon16 fl-space2" alt="" title="edit" /></a>
                            <a href="#" style="cursor: pointer;display:none"><img src="<?php echo $this->Html->imageUrl('ico_delete_16.png') ?>" class="icon16 fl-space2" alt="" title="delete" /></a>
                            <a href="#" style="cursor: pointer;display:none"><img src="<?php echo $this->Html->imageUrl('ico_settings_16.png') ?>" class="icon16 fl-space2" alt="" title="settings" /></a>
                        </td>
                    </tr>

                </tbody>
            </table>

            <div class="tab-footer clear" ng-show="c.channelCount >= c.channelLimit">
                <div class="fl">                                       
                    <button class="submit fl-space" ng-click="c.channelLimit = c.channelLimit + 10">Load More</button>
                </div>                                    
            </div>

        </div>
    </div> <!-- end of box-body -->


 </div>