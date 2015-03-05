'use strict';

/**
 * Directives
 */
angular.module('feedbackstr.directives', []).

    directive('starRating', function($window){
        return function(scope, iElement, iAttrs) {
            if($window.hasOwnProperty('starRatingCounter')) {
                $window.starRatingCounter++;
            } else {
                $window.starRatingCounter = 0;
            }
            var val = Math.round(scope.$eval(iAttrs.starRating));
            var html = "";
            for(var i = 0; i < 5; i++) {
                html += '<input name="star-rating-' + $window.starRatingCounter + '" type="radio" ';
                if(iAttrs.hasOwnProperty('disabled')) {
                    html += 'disabled="disabled" ';
                }
                if(val == i+1) {
                    html += 'checked="checked" ';
                }
                html += '/>';
            }
            iElement.html(html);
            iElement.find('input').rating();
        };
    }).

    directive('rtEditor', function($parse){
        return function(scope, iElement, iAttrs) {
            iElement.richtextarea({
                onChange: function(value) {
                    $parse(iAttrs.rtEditor).assign(scope, value);
                }
            });
            scope.$watch(iAttrs.rtEditor, function(value) {
                iElement.richtextarea('value', value);
            });
        };
    }).
    
    directive('rtBoldControl', function($timeout){
        return function(scope, iElement, iAttrs) {

            // wait for the js to create the richtext area
//            $timeout(function(){
//                $(iAttrs.rtBoldControl).siblings('.ui-richtextarea').find('.ui-richtextarea-content').keydown(function(event){
//                    console.log('key');
//                });
//                $(iAttrs.rtBoldControl).siblings('.ui-richtextarea').find('.ui-richtextarea-content').focus(function(event){
//                    console.log('f');
//                });
//            });
//            
            iElement.click(function(event){
                $(iAttrs.rtBoldControl).richtextarea('bold');
                event.stopPropagation();
            });
            
        };
    }).
    
    directive('rtItalicControl', function(){
        return function(scope, iElement, iAttrs) {
            iElement.click(function(event){
                $(iAttrs.rtItalicControl).richtextarea('italic');
                event.stopPropagation();
            });
        };
    }).
    
    directive('sortable', function(){
        return function(scope, iElement, iAttrs) {
            
            var toSort = scope.$eval(iAttrs.sortable);
            iElement.sortable({
                axis: 'y', 
                placeholder: 'drop-placeholder',
                cancel: '.ui-richtextarea, input, textarea',
                start: function(e, ui) {
                    ui.item.data('start', ui.item.index());
                },
                update: function(e, ui) {
                    var start = ui.item.data('start');
                    var end = ui.item.index();

                    toSort.splice(end, 0, toSort.splice(start, 1)[0]);
                    scope.$apply();
                }
            });  
        };
    }).
    
    // usage: <div image-upload="modelName" style="width: 150px; height: 200px;"></div>
    directive('imageUpload', function($window, $parse) {
        return {
            compile: function compile(tElement, tAttrs, transclude){
                if($window.File && $window.FileReader && $window.FileList) {
                    var maxImageSize = 2000000;
                    var crop = tAttrs.hasOwnProperty('imageCrop');
                    var cropRatio = tAttrs.imageCrop;
                    tElement.css('position', 'relative');
                    var html = '<input type="file" style="display:none" />';
                    html += '<div class="image-preview" >';
                    html +=     '<img ng-show="' + tAttrs.imageUpload + '!=\'\'" ng-src="{{' + tAttrs.imageUpload + '}}" />';
                    html += '</div>';
                    html += '<div class="image-control" style="height: 100%">';
                    html +=     'Click to select an image or drag a file into this field';
                    html += '</div>';
                    html += '<div class="image-remove">x</div>';
                    if(crop) {
                        html += '<div class="crop-dialog"></div>';
                    }
                    tElement.html(html);
                    
                    return function(scope, iElement, iAttrs) {
                        var modelSet = $parse(iAttrs.imageUpload).assign;
                        var input = iElement.find('input[type=file]');
                        var control = iElement.find('div.image-control');
                        var jCrop = null;
                        iElement.find('div.image-remove').click(function(event) {
                            modelSet(scope, '');
                            scope.$digest();
                        });
                        var modal = iElement.find('div.crop-dialog').dialog({
                            draggable: false,
                            modal: true,
                            resizable: false,
                            show: 'fade',
                            hide: 'fade',
                            closeOnEscape: true,
                            autoOpen: false,
                            buttons: {
                                'Crop': function() {
                                    var c = jCrop.tellSelect();
                                    var canvas = document.createElement('canvas');
                                    canvas.width = c.w;
                                    canvas.height = c.h;
                                    canvas.getContext("2d").drawImage(modal.find('img')[0], c.x, c.y, c.w, c.h, 0, 0, c.w, c.h);

                                    modelSet(scope, canvas.toDataURL());
                                    scope.$digest();
                                    $(this).dialog('close'); 
                                },
                                'Cancel': function() { 
                                    $(this).dialog('close'); 
                                }
                            }
                        });
                        
                        var handleFileSelect = function(fileList) {
                            
                            if(fileList.length < 1) {
                                return; // no image selected
                            }
                            var file = fileList[0];
                            if(file.size > maxImageSize) {
                                alert('Image size too big');
                                return;
                            }
                            if(!file.type.match('image.*')) {
                                alert("File is not an image");
                                return;
                            }
                            var reader = new FileReader();

                            reader.onload = (function(theFile) {
                                return function(e) {
                                    if(crop) {
                                        // dont scale image
                                        modal.html('<img style="max-width: 999999px; max-height:999999px" src="' + e.target.result + '" />');
                                        modal.find('img').Jcrop({
                                            aspectRatio: cropRatio
                                        }, function(){
                                            jCrop = this;
                                            var img = modal.find('img');
                                            var imageWidth = img.width();
                                            var imageHeight = img.height();
                                            jCrop.setSelect([imageWidth * 0.1, imageHeight * 0.1, imageWidth * 0.9, imageHeight * 0.9]);
                                            var width = $(window).width() * 0.8; // 80% of the screen
                                            if(imageWidth < width) {
                                                width = imageWidth;
                                            }
                                            modal.dialog({width: width + 30}); // add space for padding
                                            modal.dialog('open');
                                        });
                                    } else  {
                                        modelSet(scope, e.target.result);
                                        scope.$digest();
                                    }
                              };
                            })(file);

                            reader.readAsDataURL(file);
                        };
                        
                        input.change(function(event) {
                            handleFileSelect(event.target.files);
                        });

                        control.on('dragover', function(event){
                            event.stopPropagation();
                            event.preventDefault();
                            event.originalEvent.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.                            
                        });

                        control.on('dragenter', function(event){
                            event.stopPropagation();
                            event.preventDefault();
                            control.addClass('dragover');
                        });

                        control.on('dragleave', function(event){
                            event.stopPropagation();
                            event.preventDefault();
                            control.removeClass('dragover');
                        });
                        
                        control.on('drop', function(event){
                            control.removeClass('dragover');
                            event.stopPropagation();
                            event.preventDefault();
                            handleFileSelect(event.originalEvent.dataTransfer.files);
                        });
                        
                        control.click(function(event) {
                            input.click();
                        });
                        
                    };   
                } else {
                    tElement.html('Browser not supportet');
                }
            }
        };
    });

/**
 * Services
 */
angular.module('feedbackstr.services', []).
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