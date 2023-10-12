'use strict';

$(".switch-deploy").click(function(){
    if($(this).parent().find('.target-deploy').css('display') == 'none'){
        $(this).parent().find('.target-deploy').css("display","block")
        return
    }
    $(this).parent().find('.target-deploy').removeAttr("style");
});