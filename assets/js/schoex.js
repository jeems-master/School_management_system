
$(function() {
    "use strict";

    var latestOne = "";
    $(".aj").click(function(e) {
        if($(this).attr('href') != latestOne){
            $('html,body').animate({ scrollTop: 0 }, 'fast');
            if(latestOne != ""){
                showHideLoad();
                $('body').removeClass('sidebar-open');
            }
            latestOne = $(this).attr('href');
        }
    });

    $("#chgAcademicYear").click(function(e) {
        $('#myModal').modal('show');
    });

});

var showHideLoad = function(hideIndicator) {
    if (typeof hideIndicator === "undefined" || hideIndicator === null) {
        $('#overlay').show();
    }else{
        $('#overlay').hide();
    }
}
