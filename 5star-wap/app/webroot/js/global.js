/**
 * internationalisation helper
 */
__ = function(toTranslate) {
	if(I18n == undefined) {
		I18n = {
			translations: {}
		};
	}
	if(!I18n.hasOwnProperty('translations')) {
		I18n.translations = {};
	}
	if(I18n.translations.hasOwnProperty(toTranslate)) {
		return I18n.translations[toTranslate];
	} else {
		return toTranslate;
	}
};

/**
 * some string helper
 */
String.prototype.toUnderscore = function(){
	return this.replace(/(?!^.?)([A-Z])/g, function($1){return "_" + $1;}).toLowerCase();
};

String.prototype.toDate = function(){
	var t = this.split(/[- :]/);
	return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
};

/**
 * some date helper
 */
Date.prototype.getWeek = function() { 
	var determinedate = new Date(); 
	determinedate.setFullYear(this.getFullYear(), this.getMonth(), this.getDate()); 
	var D = determinedate.getDay(); 
	if(D == 0) D = 7; 
	determinedate.setDate(determinedate.getDate() + (4 - D)); 
	var YN = determinedate.getFullYear(); 
	var ZBDoCY = Math.floor((determinedate.getTime() - new Date(YN, 0, 1, -6)) / 86400000); 
	var WN = 1 + Math.floor(ZBDoCY / 7); 
	return WN; 
};

// TODO: this i build in into monentjs so we could remove that
Date.prototype.toTimeAgo = function() {
	var now = new Date();
	var timeDiffMinutes = (now - this) / 1000 / 60;
	if(timeDiffMinutes < 60) {
		return Math.floor(timeDiffMinutes) + ' ' + __('minutes ago');
	} else if(timeDiffMinutes < 1440) { // 60 * 24 = 1 day
		return Math.floor(timeDiffMinutes / 60) + ' ' + __('hours ago');
	} else if(timeDiffMinutes < 10080) { // 60 * 24 * 7 = 1 week
		return Math.floor(timeDiffMinutes / 60 / 24) + ' ' + __('days ago');
	} else if(timeDiffMinutes < 44640) { // 60 * 24 * 31 = 1 month
		return Math.floor(timeDiffMinutes / 60 / 24 / 7) + ' ' + __('weeks ago');
	} else if(timeDiffMinutes < 525600) { // 60 * 24 * 256 = 1 year
		return Math.floor(timeDiffMinutes / 60 / 24 / 31) + ' ' + __('months ago');
	} else {
		return Math.floor(timeDiffMinutes / 60 / 24 / 356) + ' ' + __('years ago');
	}
};

createPanelOverlay = function(options) {
	options = options || {};
	var overlay = $('<div class="panel-overlay"></div>');
	$('.panel:first').append(overlay);
	return overlay;
};

$(function() {

	/**
	 * Textarea autogrow
	 */
	$('textarea[autosize]').autosize();
	/**
    * Fix for browser form autocomplete and angularjs validation
    */
    setTimeout(function(){
        //This code breaks stuff sometimes
        $('input[ng-model]:not([type="radio"]):not([type="checkbox"]):not([breakout="true"]), select[ng-model]:not([breakout="true"])').each(function(){            
            if($(this).val() != '') {
                var controller = angular.element($(this)).controller('ngModel');
                if(controller) {
                    controller.$setViewValue($(this).val());
                }
            }
        });
    },250);      
    $('input[ng-model]:not([type="radio"]):not([type="checkbox"]), select[ng-model]').on('change blur',function(){        
        var e = $(this);
        setTimeout(function() {
            e.closest('form').find('input[ng-model]:not([type="radio"]):not([type="checkbox"])').each(function(){
                if($(this).val() != '') {
                    var controller = angular.element($(this)).controller('ngModel');
                    if(controller) {
                        controller.$setViewValue($(this).val());
                    }
                }
            });
        }, 250);
    });   
	
	/**
	 * Tooltip
	 */
	if($(document).tooltip != undefined) {
		$(document).tooltip({
			items: '.tooltip',
			content: function() {
				var element = $(this);
				var tooltipClass = element.attr('tooltip-class') || '';
				var tooltipHeader = element.attr('tooltip-header') || false;
				var tooltipBody = element.attr('tooltip-body') || false;
				var html = '<div class="' + tooltipClass  + '">';
				if(tooltipHeader) {
					html += '<div class="tooltip-header ">' + tooltipHeader + '</div>';
				}
				if(tooltipBody) {
					html += '<div class="tooltip-body">' + tooltipBody + '</div>';
				}
				// keep tooptip open on hover
				element.bind( "mouseleave", function( event ) {
					event.stopImmediatePropagation();
					var fixed = setTimeout(function(){
						element.tooltip().tooltip("close");
					}, 500);
					$('.ui-tooltip').hover(function(){
							clearTimeout (fixed);
						}, function(){
							element.tooltip().tooltip("close");
						}
					);
				});
				// ios fix
				if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
					$(document).bind("touchstart", function(event) {
						element.tooltip().tooltip("close");
					});
				}
				return html + '</div>';
			},
			position: {
				my: "center top+10",
				at: "center bottom",
				using: function( position, feedback ) {
					$(this).css( position );
					console.log(this);
					$("<div>" )
					.addClass( "arrow" )
					.addClass( $(this).find('div.ui-tooltip-content > div').first().attr('class') )
					.addClass( feedback.vertical )
					.addClass( feedback.horizontal )
					.css('left', feedback.target.width / 2 - 5 + feedback.target.left - feedback.element.left)
					.appendTo( this );
				}
			}
		});
	}
}); 

/**
 * some global angular directives
 */
angular.module('ng').

	//Fixing IE focusing and blurring
	directive('placeholder', function(){
	
		// Returns the version of Internet Explorer or a -1
		// (indicating the use of another browser).
		var ieVersion = (function () {
			var rv = -1; // Return value assumes failure.
			if (navigator.appName == 'Microsoft Internet Explorer') {
			  var ua = navigator.userAgent;
			  var re = new RegExp("MSIE ([0-9]{1,}[\\.0-9]{0,})");
			  if (re.exec(ua) !== null) {
				rv = parseFloat(RegExp.$1);
			  }
			}
			return rv;
		})();
		if (ieVersion >= 10 || ieVersion < 0 ) {
			return {};
		} else {
			return function(scope, elm, attrs){
				elm.focus(function() {
					var input = $(this);
					if (input.val() == input.attr('placeholder')) {
						input.val('');
						input.removeClass('placeholder');
					}
				}).blur(function() {
					var input = $(this);
					if (input.val() == '' || input.val() == input.attr('placeholder')) {
						input.addClass('placeholder');
						input.val(input.attr('placeholder'));
					}
				}).blur().parents('form').submit(function() {
					$(this).find('[placeholder]').each(function() {
						var input = $(this);
						if (input.val() == input.attr('placeholder')) {
						input.val('');
						}
					});
				});
			};
		}
	}).
	// disables the button after click to prevent multiple clicks
	directive('disableOnClick', function(){
		return function(scope, elm, attrs){
			elm.click(function() {
				elm.attr('disabled', 'disabled');
				elm.addClass(attrs.disableOnClick);
			});
		};
	}).
	//fix only show the validation/error message after leaving the input field, don´t already show when focus the field.
	directive('input', function() {
	return {
		restrict: 'E',
		require: 'ngModel',
		link: function(scope, elm, attr, ngModelCtrl) {
			if (attr.type === 'radio' || attr.type === 'checkbox') return;
			
			elm.unbind('input').unbind('keydown').unbind('change');
			elm.bind('blur', function() {
				scope.$apply(function() {
					ngModelCtrl.$setViewValue(elm.val());
				});         
			});
		}
	};
}).
	
	filter('timeAgo', function(){
		return function(input) {
			if(input) {
				return input.toDate().toTimeAgo();
			}
			return input;
		};
	});
	
	
	
angular.module('star.services', []).
factory('fdbModel', function($http, $q) {
	return function(modelName) {
		modelName = modelName.toUnderscore();
		var uri = baseUrl + '/proxy/' + modelName + 's';
		return {
			getUri: function() {
				return uri;
			},
			query: function(params) {                    
				return $http({method: 'GET', url: uri + '.json', params: params}).then(function(response){
					return response['data'];
					}, function(response) {
						return $q.reject(response);
				});
			},
			get: function(id) {
				return $http({method: 'GET', url: uri + '/' + id + '.json'}).then(function(response){
					return response['data'][modelName];
					}, function(response) {
						return $q.reject(response);
				});
			},
			add: function(data) {
				return $http({method: 'POST', url: uri + '.json', data: data}).then(function(response){
					return response['data'][modelName];
					}, function(response) {
						return $q.reject(response);
				});
			},
			edit: function(id, data) {
				return $http({method: 'POST', url: uri + '/' + id + '.json', data: data}).then(function(response){
					return response['data'][modelName];
					}, function(response) {
						return $q.reject(response);
				});
			},
			remove: function(id) {
				return $http({method: 'DELETE', url: uri + '/' + id + '.json'}).then(function(response){
					return response['data'];
					}, function(response) {
						return $q.reject(response);
				});                    
			}
		}; 
	};
});   