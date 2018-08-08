schoex.config(function($routeProvider,$locationProvider) {

    $routeProvider.when('/', {
      templateUrl : 'templates/home.html',
      controller  : 'dashboardController'
    })

    .when('/dormitories', {
      templateUrl : 'templates/dormitories.html',
      controller  : 'dormitoriesController'
    })

    .when('/admins', {
      templateUrl : 'templates/admins.html',
      controller  : 'adminsController'
    })

    .when('/teachers', {
      templateUrl : 'templates/teachers.html',
      controller  : 'teachersController'
    })

    .when('/students', {
      templateUrl : 'templates/students.html',
      controller  : 'studentsController'
    })

    .when('/students/marksheet', {
      templateUrl : 'templates/students.html',
      controller  : 'studentsController',
      methodName: 'marksheet'
    })

    .when('/parents', {
      templateUrl : 'templates/stparents.html',
      controller  : 'parentsController'
    })

    .when('/hostel', {
      templateUrl : 'templates/hostel.html',
      controller  : 'hostelController',
    })

    .when('/hostelCat', {
      templateUrl : 'templates/hostelCat.html',
      controller  : 'hostelCatController',
    })

    .when('/classes', {
      templateUrl : 'templates/classes.html',
      controller  : 'classesController'
    })

    .when('/sections', {
      templateUrl : 'templates/sections.html',
      controller  : 'sectionsController'
    })

    .when('/subjects', {
      templateUrl : 'templates/subjects.html',
      controller  : 'subjectsController'
    })

    .when('/newsboard', {
      templateUrl : 'templates/newsboard.html',
      controller  : 'newsboardController'
    })

    .when('/newsboard/:newsId', {
      templateUrl : 'templates/newsboard.html',
      controller  : 'newsboardController'
    })

    .when('/library', {
      templateUrl : 'templates/library.html',
      controller  : 'libraryController'
    })

    .when('/accountSettings/profile', {
      templateUrl : 'templates/accountSettings.html',
      controller  : 'accountSettingsController',
      methodName: 'profile'
    })

    .when('/accountSettings/email', {
      templateUrl : 'templates/accountSettings.html',
      controller  : 'accountSettingsController',
      methodName: 'email'
    })

    .when('/accountSettings/password', {
      templateUrl : 'templates/accountSettings.html',
      controller  : 'accountSettingsController',
      methodName: 'password'
    })

    .when('/classschedule', {
      templateUrl : 'templates/classschedule.html',
      controller  : 'classScheduleController'
    })

    .when('/attendance', {
      templateUrl : 'templates/attendance.html',
      controller  : 'attendanceController'
    })

    .when('/gradeLevels', {
      templateUrl : 'templates/gradeLevels.html',
      controller  : 'gradeLevelsController'
    })

    .when('/examsList', {
      templateUrl : 'templates/examsList.html',
      controller  : 'examsListController'
    })

    .when('/events', {
      templateUrl : 'templates/events.html',
      controller  : 'eventsController'
    })

    .when('/events/:eventId', {
      templateUrl : 'templates/events.html',
      controller  : 'eventsController'
    })

    .when('/assignments', {
      templateUrl : 'templates/assignments.html',
      controller  : 'assignmentsController'
    })

    .when('/materials', {
      templateUrl : 'templates/materials.html',
      controller  : 'materialsController'
    })

    .when('/mailsms', {
      templateUrl : 'templates/mailsms.html',
      controller  : 'mailsmsController'
    })

    .when('/messages', {
      templateUrl : 'templates/messages.html',
      controller  : 'messagesController'
    })

    .when('/messages/:messageId', {
      templateUrl : 'templates/messages.html',
      controller  : 'messagesController'
    })

    .when('/onlineExams', {
      templateUrl : 'templates/onlineExams.html',
      controller  : 'onlineExamsController'
    })

    .when('/calender', {
      templateUrl : 'templates/calender.html',
      controller  : 'calenderController'
    })

    .when('/transports', {
      templateUrl : 'templates/transportation.html',
      controller  : 'TransportsController'
    })

    .when('/settings', {
      templateUrl : 'templates/settings.html',
      controller  : 'settingsController',
      methodName: 'settings'
    })

    .when('/terms', {
      templateUrl : 'templates/settings.html',
      controller  : 'settingsController',
      methodName: 'terms'
    })

    .when('/media', {
      templateUrl : 'templates/media.html',
      controller  : 'mediaController'
    })

    .when('/static', {
      templateUrl : 'templates/static.html',
      controller  : 'staticController'
    })

    .when('/static/:pageId', {
      templateUrl: 'templates/static.html',
      controller: 'staticController'
    })

    .when('/attendanceStats', {
      templateUrl : 'templates/attendanceStats.html',
      controller  : 'attendanceStatsController'
    })

    .when('/polls', {
      templateUrl : 'templates/polls.html',
      controller  : 'pollsController'
    })

    .when('/mailsmsTemplates', {
      templateUrl : 'templates/mailsmsTemplates.html',
      controller  : 'mailsmsTemplatesController'
    })

    .when('/feeType', {
      templateUrl : 'templates/feeType.html',
      controller  : 'feeTypeController'
    })

    .when('/feeAllocation', {
      templateUrl : 'templates/feeAllocation.html',
      controller  : 'feeAllocationController'
    })

    .when('/payments', {
      templateUrl : 'templates/payments.html',
      controller  : 'paymentsController'
    })

    .when('/expenses', {
      templateUrl : 'templates/expenses.html',
      controller  : 'expensesController'
    })

    .when('/languages', {
      templateUrl : 'templates/languages.html',
      controller  : 'languagesController'
    })

    .when('/upgrade', {
      templateUrl : 'templates/upgrade.html',
      controller  : 'upgradeController'
    })

    .when('/promotion', {
      templateUrl : 'templates/promotion.html',
      controller  : 'promotionController'
    })

    .when('/academicYear', {
      templateUrl : 'templates/academicYear.html',
      controller  : 'academicYearController'
    })

    .when('/staffAttendance', {
      templateUrl : 'templates/staffAttendance.html',
      controller  : 'staffAttendanceController'
    })

    .when('/reports', {
      templateUrl : 'templates/reports.html',
      controller  : 'reportsController'
    })

    .when('/vacation', {
      templateUrl : 'templates/vacation.html',
      controller  : 'vacationController'
    })

    .when('/mobileNotif', {
      templateUrl : 'templates/mobileNotif.html',
      controller  : 'mobileNotifController'
    })

    .otherwise({
      redirectTo:'/'
    });

});

schoex.factory('dataFactory', function($http) {
  var myService = {
    httpRequest: function(url,method,params,dataPost,upload) {
      var passParameters = {};
      passParameters.url = url;

      if (typeof method == 'undefined'){
        passParameters.method = 'GET';
      }else{
        passParameters.method = method;
      }

      if (typeof params != 'undefined'){
        passParameters.params = params;
      }

      if (typeof dataPost != 'undefined'){
        passParameters.data = dataPost;
      }

      if (typeof upload != 'undefined'){
         passParameters.upload = upload;
      }

      var promise = $http(passParameters).then(function (response) {
        if(typeof response.data == 'string' && response.data != 1){
          if(response.data.substr('loginMark')){
              location.reload();
              return;
          }
          $.gritter.add({
            title: 'Aplicativo',
            text: response.data
          });
          return false;
        }
        if(response.data.jsMessage){
          $.gritter.add({
            title: response.data.jsTitle,
            text: response.data.jsMessage
          });
        }
        return response.data;
      },function(){

        $.gritter.add({
          title: 'Aplicativo',
          text: 'Um erro ocorreu durante a requisição.'
        });
      });
      return promise;
    }
  };
  return myService;
});

schoex.directive('datePicker', function($parse, $timeout,$rootScope){
    return {
        restrict: 'A',
        replace: true,
        transclude: false,
        require: 'ngModel',
        link: function(scope, element, attrs,ngModel) {
            var dateformatVal = jQuery('#dateformatVal').val();
            if(typeof dateformatVal == "undefined"){
                var dateformatVal = $rootScope.dashboardData.dateformat;
            }
            var dateformat = dateformatVal;
            if(dateformat == ""){
                dateformat = 'mm/dd/yyyy';
            }else{
                dateformat = dateformat.replace('d','dd');
                dateformat = dateformat.replace('m','mm');
                dateformat = dateformat.replace('Y','yyyy');
            }
            $(element).datepicker({format: dateformat,autoclose:true,clearBtn:true,todayHighlight:true,todayBtn:true,rtl:false,keepEmptyValues: true});
            $.fn.datepicker.dates['en'] = {
                days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                today: "Today",
                clear: "Clear",
                format: "mm/dd/yyyy",
                titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
                weekStart: 0
            };
            ngModel.$render = function(){
                if(typeof ngModel.$modelValue != "undefined" && ngModel.$modelValue != ""){
                    $(element).datepicker('update', ngModel.$modelValue);
                }
            }
        }
    };
});
schoex.directive('mobileNumber', function($parse, $timeout){
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, element, attrs,ngModel) {
            var telInput = $(element);

            telInput.intlTelInput({utilsScript: jQuery('#utilsScript').val(),nationalMode: false});

            scope.$watch(attrs.ngModel, function(value) {
                telInput.intlTelInput("setNumber",element.val());
            });

            scope.$watch(attrs.ngModel, function(value) {
                if(value == "" || typeof value === "undefined"){
                    ngModel.$setValidity(attrs.ngModel, true);
                    return;
                }
                if (telInput.intlTelInput("isValidNumber")) {
                  ngModel.$setValidity(attrs.ngModel, true);
                } else {
                  ngModel.$setValidity(attrs.ngModel, false);
                }
            });
        }
    };
});
schoex.directive('checklistModel', ['$parse', '$compile', function($parse, $compile) {
  // contains
  function contains(arr, item, comparator) {
    if (angular.isArray(arr)) {
      for (var i = arr.length; i--;) {
        if (comparator(arr[i], item)) {
          return true;
        }
      }
    }
    return false;
  }

  // add
  function add(arr, item, comparator) {
    arr = angular.isArray(arr) ? arr : [];
      if(!contains(arr, item, comparator)) {
          arr.push(item);
      }
    return arr;
  }

  // remove
  function remove(arr, item, comparator) {
    if (angular.isArray(arr)) {
      for (var i = arr.length; i--;) {
        if (comparator(arr[i], item)) {
          arr.splice(i, 1);
          break;
        }
      }
    }
    return arr;
  }

  // http://stackoverflow.com/a/19228302/1458162
  function postLinkFn(scope, elem, attrs) {
     // exclude recursion, but still keep the model
    var checklistModel = attrs.checklistModel;
    attrs.$set("checklistModel", null);
    // compile with `ng-model` pointing to `checked`
    $compile(elem)(scope);
    attrs.$set("checklistModel", checklistModel);

    // getter for original model
    var checklistModelGetter = $parse(checklistModel);
    var checklistChange = $parse(attrs.checklistChange);
    var checklistBeforeChange = $parse(attrs.checklistBeforeChange);
    var ngModelGetter = $parse(attrs.ngModel);



    var comparator = angular.equals;

    if (attrs.hasOwnProperty('checklistComparator')){
      if (attrs.checklistComparator[0] == '.') {
        var comparatorExpression = attrs.checklistComparator.substring(1);
        comparator = function (a, b) {
          return a[comparatorExpression] === b[comparatorExpression];
        };

      } else {
        comparator = $parse(attrs.checklistComparator)(scope.$parent);
      }
    }

    // watch UI checked change
    var unbindModel = scope.$watch(attrs.ngModel, function(newValue, oldValue) {
      if (newValue === oldValue) {
        return;
      }

      if (checklistBeforeChange && (checklistBeforeChange(scope) === false)) {
        ngModelGetter.assign(scope, contains(checklistModelGetter(scope.$parent), getChecklistValue(), comparator));
        return;
      }

      setValueInChecklistModel(getChecklistValue(), newValue);

      if (checklistChange) {
        checklistChange(scope);
      }
    });

    // watches for value change of checklistValue
    var unbindCheckListValue = scope.$watch(getChecklistValue, function(newValue, oldValue) {
      if( newValue != oldValue && angular.isDefined(oldValue) && scope[attrs.ngModel] === true ) {
        var current = checklistModelGetter(scope.$parent);
        checklistModelGetter.assign(scope.$parent, remove(current, oldValue, comparator));
        checklistModelGetter.assign(scope.$parent, add(current, newValue, comparator));
      }
    }, true);

    var unbindDestroy = scope.$on('$destroy', destroy);

    function destroy() {
      unbindModel();
      unbindCheckListValue();
      unbindDestroy();
    }

    function getChecklistValue() {
      return attrs.checklistValue ? $parse(attrs.checklistValue)(scope.$parent) : attrs.value;
    }

    function setValueInChecklistModel(value, checked) {
      var current = checklistModelGetter(scope.$parent);
      if (angular.isFunction(checklistModelGetter.assign)) {
        if (checked === true) {
          checklistModelGetter.assign(scope.$parent, add(current, value, comparator));
        } else {
          checklistModelGetter.assign(scope.$parent, remove(current, value, comparator));
        }
      }

    }

    // declare one function to be used for both $watch functions
    function setChecked(newArr, oldArr) {
      if (checklistBeforeChange && (checklistBeforeChange(scope) === false)) {
        setValueInChecklistModel(getChecklistValue(), ngModelGetter(scope));
        return;
      }
      ngModelGetter.assign(scope, contains(newArr, getChecklistValue(), comparator));
    }

    // watch original model change
    // use the faster $watchCollection method if it's available
    if (angular.isFunction(scope.$parent.$watchCollection)) {
        scope.$parent.$watchCollection(checklistModel, setChecked);
    } else {
        scope.$parent.$watch(checklistModel, setChecked, true);
    }
  }

  return {
    restrict: 'A',
    priority: 1000,
    terminal: true,
    scope: true,
    compile: function(tElement, tAttrs) {

      if (!tAttrs.checklistValue && !tAttrs.value) {
        throw 'You should provide `value` or `checklist-value`.';
      }

      // by default ngModel is 'checked', so we set it if not specified
      if (!tAttrs.ngModel) {
        // local scope var storing individual checkbox model
        tAttrs.$set("ngModel", "checked");
      }

      return postLinkFn;
    }
  };
}]);
schoex.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});
schoex.directive('chatBox', function($parse, $timeout){
    return {
        restrict: 'A',
        replace: true,
        transclude: false,
        compile: function(element, attrs) {
          return function (scope, slider, attrs, controller) {
            $('#chat-box').slimScroll({
              height: '500px',alwaysVisible: true,start : "bottom"
            });
          };
        }
    };
});
schoex.directive('colorbox', function() {
  return {
    restrict: 'AC',
    link: function (scope, element, attrs) {
      var itemsVars = {transition:'elastic',title:attrs.title,rel:'gallery',scalePhotos:true};
      if(attrs.youtube){
          itemsVars['iframe'] = true;
          itemsVars['innerWidth'] = 640;
          itemsVars['innerHeight'] = 390;
      }
      if(attrs.vimeo){
          itemsVars['iframe'] = true;
          itemsVars['innerWidth'] = 500;
          itemsVars['innerHeight'] = 409;
      }
      if(!attrs.youtube && !attrs.vimeo){
          itemsVars['height'] = "100%";
      }
      $(element).colorbox(itemsVars);
    }
  };
});
schoex.directive('ckEditor', [function () {
    return {
        require: '?ngModel',
        link: function ($scope, elm, attr, ngModel) {
            var ck = CKEDITOR.replace(elm[0]);

            ck.on('pasteState', function () {
                $scope.$apply(function () {
                    ngModel.$setViewValue(ck.getData());
                });
            });

            ngModel.$render = function (value) {
                ck.setData(ngModel.$modelValue);
            };
        }
    };
}]);
schoex.directive('calendarBox', function($parse, $timeout,$rootScope){
    return {
        restrict: 'A',
        replace: true,
        transclude: false,
        compile: function(element, attrs) {
          return function (scope, slider, attrs, controller) {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                lang: $rootScope.dashboardData.languageUniversal,
                buttonIcons: false, // show the prev/next text
				weekNumbers: true,
                events: "calender"
            });
          };
        }
    };
});
schoex.directive('modal', function () {
return {
    template: '<div class="modal fade">' +
        '<div class="modal-dialog">' +
          '<div class="modal-content">' +
            '<div class="modal-header">' +
              '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
              '<h4 class="modal-title">{{ modalTitle }}</h4>' +
            '</div>' +
            '<div class="modal-body" ng-transclude></div>' +
          '</div>' +
        '</div>' +
      '</div>',
    restrict: 'E',
    transclude: true,
    replace:true,
    scope:true,
    link: function postLink(scope, element, attrs) {
      scope.$watch(attrs.visible, function(value){
        if(value == true)
          $(element).modal('show');
        else
          $(element).modal('hide');
      });

      $(element).on('shown.bs.modal', function(){
        scope.$apply(function(){
          scope.$parent[attrs.visible] = true;
        });
      });

      $(element).on('hidden.bs.modal', function(){
        scope.$apply(function(){
          scope.$parent[attrs.visible] = false;
        });
      });
    }
  };
});

schoex.directive('scalendarBox', function($parse, $timeout,$rootScope){
    return {
        restrict: 'A',
        replace: true,
        transclude: false,
        compile: function(element, attrs) {
          return function (scope, slider, attrs, controller) {
            $('#scalendar').fullCalendar({
                events: "calender",
                lang: $rootScope.dashboardData.languageUniversal
            });
          };
        }
    };
});
schoex.directive('tooltip', function(){
    return {
        restrict: 'A',
        link: function(scope, element, attrs){
          $(element).hover(function(){
              $(element).tooltip('show');
          }, function(){
              $(element).tooltip('hide');
          });
        }
    };
});
schoex.directive('showtab',
    function () {
        return {
            link: function (scope, element, attrs) {
                element.click(function(e) {
                    e.preventDefault();
                    $(element).tab('show');
                });
            }
        };
    });


schoex.filter('object2Array', function() {
  return function(input) {
    var out = [];
    for(i in input){
      out.push(input[i]);
    }
    return out;
  }
});

function uploadSuccessOrError(response){
  if(typeof response == 'string' && response != 1){
    $.gritter.add({
      title: 'Aplicativo',
      text: response
    });
    return false;
  }
  if(response.jsMessage){
    $.gritter.add({
      title: response.jsTitle,
      text: response.jsMessage
    });
  }
  if(response.jsStatus){
    if(response.jsStatus == "0"){
      return false;
    }
  }
  return response;
}

function successOrError(data){
  if(data.jsStatus){
    if(data.jsStatus == "0"){
      return false;
    }
  }
  return data;
}

//New Functions Implementation

function apiResponse(response,image){
    if(response.status){
        if(typeof response.title !== 'undefined'){
            if(response.status == "success"){
                if(typeof image !== 'undefined'){
                    $.gritter.add({
                      title: response.title,
                      text: response.message,
                      image: $("#rooturl").val()+"/assets/img/gritter_"+image+".png"
                    });
                }else{
                    $.gritter.add({
                      title: response.title,
                      text: response.message
                    });
                }
            }
            if(response.status == "failed"){
                $.gritter.add({
                  title: response.title,
                  text: response.message,
                  image: $("#rooturl").val()+"/assets/img/gritter_warning.png"
                });
            }
        }
        if(response.data){
            return response.data;
        }
    }else{
        return response;
    }
}

function apiModifyTable(originalData,id,response){
    angular.forEach(originalData, function (item,key) {
        if(item.id == id){
            originalData[key] = response;
        }
    });
    return originalData;
}
