(function($){
    var instEcho= null,
        oldtimeout=null,
    Echo = function(options){
        var opt = {
            context : "body",
            selector: ".jqAlert",
            msg : null,
            lifetime : 5000,
            themes : {
                'echo' : {
                    'background' : '#F9EDBE',
                    'border' : '1px solid #E5AD43',
                    'color' : '#222222'
                },
                'success' : {
                    'background' : '#dff0d8',
                    'border' : '1px solid #d6e9c6',
                    'color' : '#468847'
                },
                'warn' : {
                    'background' : '#f2dede',
                    'border' : '1px solid #eed3d7',
                    'color' : '#b94a48'
                },
                'debug' : {
                    'background' : '#d9edf7',
                    'border' : '1px solid #bce8f1',
                    'color' : '#3a87ad'
                }
            },
            type: "alert-warning"
        };
        this.settings = $.extend(opt, options);
        settings= this.settings;
        this.$el = $(settings.selector,settings.context);
        this.init(settings.msg,settings.type,settings.lifetime);
    };

    Echo.prototype.init = function (msg,type,time) {
        if(oldtimeout!=null){
            clearTimeout(oldtimeout);
        }
        var THIS=this,
            el= this.$el;
        el.removeClass("alert-success alert-info alert-warning alert-danger");
        el.addClass(type);
        el.find(".jqAlert-desc").html(msg);
        el.find(".close").on("click",function(){
            oldtimeout= null;
            el.stop(true).slideUp(600);
        });
        el.stop(true,true).slideDown(600,function(){
            oldtimeout= setTimeout(function(){
                oldtimeout= null;
                el.stop(true).slideUp(600);
            },time);
        });
    };

    $.fn.Echo = function( params ) {
        if(typeof params == 'undefined' || params.constructor == Object){
            new Echo(params);
        }else{
            $.error( 'El parámetro proporcionado ' +  params + ' esta mal declarado o no es un objeto' );
        }
    };

})(jQuery);


function echo(message, lifetime) {
    $.fn.Echo({msg : message, type : 'alert-warning', lifetime : lifetime});
}

function debug(message, lifetime) {
    $.fn.Echo({msg : message, type : 'alert-info', lifetime : lifetime});
}

function warn(message, lifetime, bottom) {
    $.fn.Echo({msg : message, type : 'alert-danger', lifetime : lifetime,});
}

function success(message, lifetime) {
    $.fn.Echo({msg : message, type : 'alert-success', lifetime : lifetime});
}