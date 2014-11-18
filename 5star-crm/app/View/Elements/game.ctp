<div class="content-box" ng-show="workingPart == 'gameList'">
    <div class="box-header clear">
      <h2>Danh sách game hiện tại của 5Stars</h2> 
    </div>            


    <div class="box-body clear">
        <div id="tableGame">
            
            <table>
                <thead>
                    <tr>             
                        <th>ID</th>                               
                        <th>Tên</th>
                        <th>Tài khoản CRM</th>                                            
                        <th>Status</th>
                        <th>Security Key</th>
                        <th>Billing URL</th>
                        <th>Wakeup Syntax</th>
                        <th>Thiết lập</th>
                    </tr>
                </thead>

                <tbody>
                    <tr ng-repeat="game in g.games | limitTo:g.gameLimit"> 
                        <td ng-bind="game.Game.id"></td>                                          
                        <td ng-bind="game.User.fullname"></td>
                        <td ng-bind="game.User.username"></td>
                        <td ng-bind="game.Game.status"></td>
                        <td ng-bind="game.Game.security_key"></td>
                        <td ng-bind="game.Game.billing_url"></td>
                        <td ng-bind="game.Game.wakeup_syntax"></td>
                        <td>
                            <a style="cursor: pointer;" ng-click="g.editGame(game)"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" class="icon16 fl-space2" alt="" title="edit" /></a>
                            <a href="#" style="cursor: pointer;display:none"><img src="<?php echo $this->Html->imageUrl('ico_delete_16.png') ?>" class="icon16 fl-space2" alt="" title="delete" /></a>
                            <a href="#" style="cursor: pointer;display:none"><img src="<?php echo $this->Html->imageUrl('ico_settings_16.png') ?>" class="icon16 fl-space2" alt="" title="settings" /></a>
                        </td>
                    </tr>

                </tbody>
            </table>

            <div class="tab-footer clear" ng-show="g.gameCount >= g.gameLimit">
                <div class="fl">                                       
                    <button class="submit fl-space" ng-click="g.gameLimit = g.gameLimit + 10">Load More</button>
                </div>                                    
            </div>

        </div>
    </div> <!-- end of box-body -->


 </div>