angular.module('boardApp.services', ['star.services']).
factory('Channel', function(fdbModel) {
    return fdbModel('Channel');
}).
factory('Game', function(fdbModel) {
    return fdbModel('Game');
}).
factory('User', function(fdbModel) {
    return fdbModel('User');
}).
factory('Payment', function(fdbModel) {
    return fdbModel('Payment');
}).
factory('History', function(fdbModel) {
    return fdbModel('History');
});
angular.module('boardApp.directives', []);
angular.module('boardApp.filters', []).
filter('hideEmail', function() {        
    return function(input) {
        var response = '';
        var stop = false;
        var count = 0;
        for (var i = 0 ; i < input.length ; i++) {
            if (input[i] == '@' || count == 5) {
                stop = true;
            }
            if (stop == false) {
                response += '*';
                count++;
            } else {
                response += input[i];
            }
        }  
        return response;
    };
});
angular.module('boardApp', ['boardApp.services','boardApp.directives', 'boardApp.filters']).
controller('boardCtrl', function($scope, Channel, User, Game, Payment, History, $http, $filter, $window, $timeout) {    
    $scope.workingPart = 'userList';

    $scope.u = new userClass(User);
    $scope.u.renderUser();
    
    $scope.permission = Config.currentUser.role;

    Game.query().then(function(response){       
        $scope.g = new gameClass(response, Game, $scope, $timeout);
    });
    Channel.query().then(function(response){       
        $scope.c = new channelClass(response, Channel, $scope, $timeout);
    });

    $scope.p = new paymentClass(Payment);

    $scope.p.renderPayment();
});

//userClass.
function userClass(userService) {
    this.currentUsers  = null;
    this.userCreate = null;

    this.showEditUserForm = false;
    this.userCount = 0;    
    this.editUser = function(user) {       
        this.showEditUserForm = true;
        this.userCreate = angular.copy(user);       
    };

    this.filter = {
        limit : 20,
        time : null,
        start_date :  null,
        end_date : null,      
        textFilter : null,
        channel : null,
        game : null
    };
    this.switchTime =  function(period) {   
        if (this.filter.time == period) {
            this.filter.time = null;
        } else {
            this.filter.time = period;
        }
        this.renderUser();
    };

    this.endSearch = function(){
        this.filter.textFilter = null;        
        this.renderUser();
    }
    this.endCustom = function(){
        this.filter.start_date = null;
        this.filter.end_date = null;
        this.renderUser();
    }

    this.renderUser = function() {
        var conditions = {};
        conditions['limit'] = this.filter.limit;
        if (this.filter.start_date != null) {
            conditions['start_date'] = moment(this.filter.start_date, 'YYYY-MM-DD').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');   
        }
        if (this.filter.end_date != null) {
            conditions['end_date'] = moment(this.filter.end_date, 'YYYY-MM-DD').hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');            
        } 
        if (this.filter.textFilter != null) {
            conditions['search'] = this.filter.textFilter;
        }

        if (this.filter.time != null) {
            if (this.filter.time == 'today') {
                conditions['start_date'] = moment().hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');
                conditions['end_date']  =  moment().hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');               
            }
            if (this.filter.time == 'thisweek') {
                conditions['start_date'] = moment().startOf('week').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');
                conditions['end_date']  =  moment().endOf('week').hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');

            }
            if (this.filter.time == 'thismonth') {
                conditions['start_date'] = moment().startOf('month').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');
                conditions['end_date']  =  moment().endOf('month').hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');              
            } 
        }
        if (this.filter.channel != null) {
            conditions['channel_id'] = this.filter.channel;
        }
         if (this.filter.game != null) {
            conditions['game_id'] = this.filter.game;
        }

        var represent =  this;
        userService.query(conditions).then(function(response){
            represent.currentUsers = response.users;
            represent.userCount = response.summary;            
        });        
    };
    this.updateUser = function(event) {
        event.preventDefault();       
        var represent = this;
        userService.edit(this.userCreate.User.id, angular.toJson(this.userCreate.User)).then(function(response){            
            represent.userCreate = null;
            represent.showEditUserForm = false;
            represent.renderUser();
        });
    };

};


//channelClass.
function gameClass(config, gameService, scope, timeout) {
    this.games = config.games;
    this.gameCreate = null;
    this.gameLimit = 100;    
    this.gameCount = config.gameCount;
    this.prepareAddGame = function(){
        this.gameCreate = null;
        scope.workingPart = 'addGame';
    };
    this.addGame = function(event) {        
        event.preventDefault();
        if (scope.gameCreateForm.$invalid) {            
            timeout(function(){
                $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                $('#msgcontent').text('Thông tin nhập vào có lỗi hoặc thiếu dữ liệu yêu cầu');                
                }, 0);
        } else {
            timeout(function(){
                $('#message').hide();
                $('#submitCreateGameButton').val('Đang xử lí...').attr('disabled', true); 
                }, 0);  
            var represent = this;
            if (this.gameCreate.Game.id != undefined) {
                gameService.edit(this.gameCreate.Game.id, angular.toJson(this.gameCreate)).then(function(response){ 
                    timeout(function(){
                        $('#submitCreateGameButton').val('Submit').attr('disabled', false);
                        }, 0);               
                    if (response.Game != undefined) {
                        var index;
                        for (var i = 0 ; i < represent.games.length ; i ++) {
                            if (represent.games[i].Game.id == represent.gameCreate.Game.id) {
                                index= i;
                            }
                        }
                        represent.games[index] = response;
                        represent.gameCreate = null;
                        scope.workingPart = 'gameList';
                    } else {
                        timeout(function(){
                            $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                            $('#msgcontent').text('Lỗi xảy ra khi tạo thêm kênh trong hệ thống, xin thử lại..');

                            }, 0);
                    }

                    }, function(response){
                        timeout(function(){
                            $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                            $('#msgcontent').text(response.data.name);
                            $('#submitCreateGameButton').val('Submit').attr('disabled', false);
                            }, 0);
                });
            } else {
                gameService.add(this.gameCreate).then(function(response){ 
                    timeout(function(){
                        $('#submitCreateGameButton').val('Submit').attr('disabled', false);
                        }, 0);                
                    if (response.Game != undefined) {
                        represent.games.push(response);
                        represent.gameCreate = null;
                        scope.workingPart = 'gameList';
                    } else {
                        timeout(function(){
                            $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                            $('#msgcontent').text('Lỗi xảy ra khi tạo thêm kênh trong hệ thống, xin thử lại..');                           
                            }, 0);
                    }

                    }, function(response) {
                        timeout(function(){
                            $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                            $('#msgcontent').text(response.data.name);
                            $('#submitCreateGameButton').val('Submit').attr('disabled', false);
                            }, 0);
                });
            }

        }
    };
    this.editGame = function(game) {
        this.gameCreate = angular.copy(game);
        this.gameCreate.User.password = '';        
        scope.workingPart = 'addGame';
    };

    this.cancelGame = function(event){
        event.preventDefault();
        scope.workingPart = 'gameList';
    }

};

//channelClass.
function channelClass(config, channelService, scope, timeout) {
    this.channels = config.channels;
    this.channelCreate = null;
    this.channelLimit = 100;	
    this.channelCount = config.channelCount;
    this.prepareAddChannel = function(){
        this.channelCreate = null;
        scope.workingPart = 'addChannel';
    };
    this.addChannel = function(event) {        
        event.preventDefault();
        if (scope.channelCreateForm.$invalid) {            
            timeout(function(){
                $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                $('#msgcontent').text('Thông tin nhập vào có lỗi hoặc thiếu dữ liệu yêu cầu');                
                }, 0);
        } else {
            timeout(function(){
                $('#message').hide();
                $('#submitCreateChannelButton').val('Đang xử lí...').attr('disabled', true); 
                }, 0);  
            var represent = this;
            if (this.channelCreate.Channel.id != undefined) {
                channelService.edit(this.channelCreate.Channel.id, angular.toJson(this.channelCreate)).then(function(response){ 
                    timeout(function(){
                        $('#submitCreateChannelButton').val('Submit').attr('disabled', false);
                        }, 0);               
                    if (response.Channel != undefined) {
                        var index;
                        for (var i = 0 ; i < represent.channels.length ; i ++) {
                            if (represent.channels[i].Channel.id == represent.channelCreate.Channel.id) {
                                index= i;
                            }
                        }
                        represent.channels[index] = response;
                        represent.channelCreate = null;
                        scope.workingPart = 'channelList';
                    } else {
                        timeout(function(){
                            $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                            $('#msgcontent').text('Lỗi xảy ra khi tạo thêm kênh trong hệ thống, xin thử lại..');

                            }, 0);
                    }

                    }, function(response){
                        timeout(function(){
                            $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                            $('#msgcontent').text(response.data.name);
                            $('#submitCreateChannelButton').val('Submit').attr('disabled', false);
                            }, 0);
                });
            } else {
                channelService.add(this.channelCreate).then(function(response){ 
                    timeout(function(){
                        $('#submitCreateChannelButton').val('Submit').attr('disabled', false);
                        }, 0);                
                    if (response.Channel != undefined) {
                        represent.channels.push(response);
                        represent.channelCreate = null;
                        scope.workingPart = 'channelList';
                    } else {
                        timeout(function(){
                            $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                            $('#msgcontent').text('Lỗi xảy ra khi tạo thêm kênh trong hệ thống, xin thử lại..');                           
                            }, 0);
                    }

                    }, function(response) {
                        timeout(function(){
                            $('#message').removeClass('note-error').removeClass('note-attention').removeClass('note-success').removeClass('note-info').addClass('note-error').show();
                            $('#msgcontent').text(response.data.name);
                            $('#submitCreateChannelButton').val('Submit').attr('disabled', false);
                            }, 0);
                });
            }

        }
    };
    this.editChannel = function(channel) {
        this.channelCreate = angular.copy(channel);
        this.channelCreate.User.password = '';        
        scope.workingPart = 'addChannel';
    };

    this.cancelChannel = function(event){
        event.preventDefault();
        scope.workingPart = 'channelList';
    }

};

function historyClass(config) {
    this.currentChart = [];
    this.countRegister = 0;
    this.countLogin = 0;
    this.countRelogin = 0;
    this.histories = config.histories;
    this.filter = {
        channel : null,
        game : null,
        start_date : null,
        end_date : null 
    };
    this.renderChart = function() {
        this.currentChart = [];
        this.countRegister = 0;
        this.countLogin = 0;
        this.countRelogin = 0;

        if (this.filter.start_date != null) {
            var momentStart = moment(this.filter.start_date, 'YYYY-MM-DD').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');   
        }
        if (this.filter.end_date != null) {
            var momentEnd = moment(this.filter.end_date, 'YYYY-MM-DD').hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');            
        } 

        for (var i = 0 ; i < this.histories.length; i ++) {
            var inList = true;
            if (this.filter.channel != null && this.histories[i].History.channel_id !=  this.filter.channel) {
                inList = false;
            }
            if (this.filter.game != null && this.histories[i].History.game_id !=  this.filter.game) {
                inList = false;
            }

            if ((this.filter.start_date != null) && (this.histories[i].History.time < momentStart)) {     
                inList = false;
            }
            if ((this.filter.end_date != null) && (this.histories[i].History.time > momentEnd)) { 
                inList = false;
            }
            if (inList) {
                this.currentChart.push(this.histories[i]);
            }
        }
    }
}


//paymentControl Class.
function paymentClass(paymentService) {
    this.currentPayments = [];
    this.paymentCount = 0;
    this.paymentAmount = 0;    
    this.showEditPaymentForm =  false;
    this.paymentEdit = null;
    this.paymentSearchButton = false;
    this.paymentCustomButton = false;

    this.filter =  {    
        limit  : 20,        
        send_game_status : null,
        payment_status : null,       
        textFilter : null,
        sort_by : 'created',
        time : null,
        giftcode : null,
        start_date : null,
        end_date : null,
        game : null,
        channel : null
    };


    this.endSearch = function(){
        this.filter.textFilter = null;
        this.renderPayment();
        this.paymentSearchButton = false;
    };

    this.startSearch = function(){
        this.renderPayment(); 
        this.paymentSearchButton = true;
    };

    this.endCustom = function(){
        this.filter.start_date = null;
        this.filter.end_date = null;
        this.renderPayment();		
    };


    this.renderPayment =  function() {          

        var conditions = {};

        conditions['limit'] = this.filter.limit;
        if (this.filter.start_date != null) {
            conditions['start_date'] = moment(this.filter.start_date, 'YYYY-MM-DD').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');   
        }
        if (this.filter.end_date != null) {
            conditions['end_date'] = moment(this.filter.end_date, 'YYYY-MM-DD').hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');			
        } 
        if  (this.filter.send_game_status != null) {
            conditions['send_game_status'] = this.filter.send_game_status;
        }

        if (this.filter.textFilter != null) {
            conditions['search'] = this.filter.textFilter;
        }

        if (this.filter.time != null) {
            if (this.filter.time == 'today') {
                conditions['start_date'] = moment().hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');
                conditions['end_date']  =  moment().hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');               
            }
            if (this.filter.time == 'thisweek') {
                conditions['start_date'] = moment().startOf('week').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');
                conditions['end_date']  =  moment().endOf('week').hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');

            }
            if (this.filter.time == 'thismonth') {
                conditions['start_date'] = moment().startOf('month').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm:ss');
                conditions['end_date']  =  moment().endOf('month').hour(23).minute(59).second(59).format('YYYY-MM-DD HH:mm:ss');              
            } 
        }
        if (this.filter.giftcode != null) {
            conditions['amount'] = 66800;
        }

        if (this.filter.channel != null) {
            conditions['channel_id'] = this.filter.channel;
        }
        if (this.filter.game != null) {
            conditions['game_id'] = this.filter.game;
        }
        var represent =  this;
        paymentService.query(conditions).then(function(response){
            represent.currentPayments = response.payments;
            represent.paymentCount = response.summary[0].Payment.count;
            represent.paymentAmount = response.summary[0].Payment.count_amount;
        });
    };

    this.switchSendGameStatus =  function(num) {            
        if (this.filter.send_game_status == num) {
            this.filter.send_game_status = null;
        } else {
            this.filter.send_game_status = num;
        }
        this.renderPayment();
    };
    this.switchTime =  function(period) {    

        if (this.filter.time == period) {
            this.filter.time = null;
        } else {
            this.filter.time = period;
        }
        this.renderPayment();
    };

    this.switchGiftcode = function(){
        if (this.filter.giftcode == null) {
            this.filter.giftcode = 1;
        } else {
            this.filter.giftcode = null;
        }
        this.renderPayment();
    }

    this.editPayment =  function(payment) {
        this.showEditPaymentForm = true;
        this.paymentEdit = angular.copy(payment);
        $('#formPaymentEdit').find('input:first').focus();
    };
    this.cancelPayment = function(event) {
        event.preventDefault();
        this.showEditPaymentForm = false;
    }
    this.updatePayment = function(event) {
        event.preventDefault();       
        var represent = this;
        paymentService.edit(this.paymentEdit.Payment.id, angular.toJson(this.paymentEdit.Payment)).then(function(response){           
            represent.paymentEdit = null;
            represent.showEditPaymentForm = false;
            represent.renderPayment();
        });
    };
};