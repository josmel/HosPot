yOSON.require={
    "#frmProfile":{
        "rules":{
            "name":{
                "required":true
            },
            "lastnamepaternal":{
                "required": true
            },
            "lastnamematernal":{
                "required":true
            }
        },
        "messages":{
            "name":{
                "required":"Ingrese su nombre"
            },
            "lastnamepaternal":{
                "required": "Ingrese su apellido paterno"
            },
            "lastnamematernal":{
                "required": "Ingrese su apellido materno"
            }
        },
        "submitHandler":function(form){
            var frm= $(form),
                data= frm.serialize(),
                ctnLoader= $(".ctnProfile");
            utils.loader(ctnLoader,true);
            $.ajax({
                "url":"/profile/save",
                "data": data,
                "method": "POST",
                "success": function(json){
                    if(parseFloat(json.state)==1){
                        echo(json.msg);
                        utils.loader(ctnLoader,false);
                    }else{
                        warn(json.msg);
                    }
                }
            });
        }
    },
    "#frmCompany":{
        "rules":{
            "name":{
                "required":true
            }
        },
        "messages":{
            "name":{
                "required":"Ingrese nombre de la Cadena"
            }
        },
        "submitHandler":function(form){
            var frm= $(form),
                data= frm.serialize(),
                picture= $.trim($("#picture").val());
            if(picture!=""){
                utils.loader(frm,true);
                $.ajax({
                    "url":"/company/save",
                    "data": data,
                    "method": "POST",
                    "success": function(json){
                        utils.loader(frm,false);
                        if(parseFloat(json.state)==1){
                            frm.find(".btn-default").trigger("click");
                            window.instDataTable.draw();
                            echo(json.msg);
                        }else{
                            warn(json.msg);
                        }
                    }
                });
            }else{
                alert("Suba una imagen para la marca");
            }
        }
    },
    "#frmSubsidiary":{
        "rules":{
            "name":{
                "required":true
            }
        },
        "messages":{
            "name":{
                "required":"Ingrese nombre de la Sucursal"
            }
        },
        "submitHandler":function(form){
            var frm= $(form),
                data= frm.serialize();
            utils.loader(frm,true);
            $.ajax({
                "url":"/subsidiary/save",
                "data": data,
                "method": "POST",
                "success": function(json){
                    utils.loader(frm,false);
                    if(parseFloat(json.state)==1){
                        frm.find(".btn-default").trigger("click");
                        window.instDataTable.draw();
                        echo(json.msg);
                    }else{
                        warn(json.msg);
                    }
                }
            });
        }
    },
    "#frmGenre":{
        "rules":{
            "name":{
                "required":true
            }
        },
        "messages":{
            "name":{
                "required":"Ingrese nombre del Género"
            }
        },
        "submitHandler":function(form){
            var frm= $(form),
                data= frm.serialize();
            utils.loader(frm,true);
            $.ajax({
                "url":"/genre/save",
                "data": data,
                "method": "POST",
                "success": function(json){
                    utils.loader(frm,false);
                    if(parseFloat(json.state)==1){
                        frm.find(".btn-default").trigger("click");
                        window.instDataTable.draw();
                        echo(json.msg);
                    }else{
                        warn(json.msg);
                    }
                }
            });
        }
    },
    "#frmAds":{
        "rules":{
            "name":{
                "required":true
            }
        },
        "messages":{
            "name":{
                "required":"Ingrese nombre de la Publicidad"
            }
        },
        "submitHandler":function(form){
            var frm= $(form),
                data= frm.serialize(),
                picture= $.trim($("#picture").val());
            if(picture!=""){
                utils.loader(frm,true);
                $.ajax({
                    "url":"/ads/save",
                    "data": data,
                    "method": "POST",
                    "success": function(json){
                        utils.loader(frm,false);
                        if(parseFloat(json.state)==1){
                            frm.find(".btn-default").trigger("click");
                            window.instDataTable.draw();
                            echo(json.msg);
                        }else{
                            warn(json.msg);
                        }
                    }
                });
            }else{
                alert("Suba una imagen para la publicidad");
            }
        }
    },
    "#frmMovie":{
        "rules":{
            "name":{
                "required":true
            },
            "synopsis":{
                "required":true
            },
            "datepublication":{
                "required": true
            }
        },
        "messages":{
            "name":{
                "required":"Ingrese nombre de la Pelicula"
            },
            "synopsis":{
                "required":"Ingrese un resumen"
            },
            "datepublication":{
                "required":"Ingrese una fecha"
            }
        },
        "submitHandler":function(form){
            var frm= $(form),
                data= frm.serialize(),
                picture= $.trim($("#picture").val());
            if(picture!=""){
                utils.loader(frm,true);
                $.ajax({
                    "url":"/movie/save",
                    "data": data,
                    "method": "POST",
                    "success": function(json){
                        utils.loader(frm,false);
                        if(parseFloat(json.state)==1){
                            frm.find(".btn-default").trigger("click");
                            window.instDataTable.draw();
                            echo(json.msg);
                        }else{
                            warn(json.msg);
                        }
                    }
                });
            }else{
                alert("Suba una imagen para la pelicula");
            }
        }
    },
    "#frmBillboard":{
        "rules":{
            "schedule3dsubtitle":{
                "required":true
            },
            "schedule3ddubbing":{
                "required":true
            },
            "schedulesubtitle":{
                "required":true
            },
            "scheduledubbing":{
                "required":true
            }
        },
        "messages":{
            "schedule3dsubtitle":{
                "required":"Ingrese horario"
            },
            "schedule3ddubbing":{
                "required":"Ingrese horario"
            },
            "schedulesubtitle":{
                "required":"Ingrese horario"
            },
            "scheduledubbing":{
                "required":"Ingrese horario"
            }
        },
        "submitHandler":function(form){
            var frm= $(form),
                data= frm.serialize();
            utils.loader(frm,true);
            $.ajax({
                "url":"/billboard/save",
                "data": data,
                "method": "POST",
                "success": function(json){
                    utils.loader(frm,false);
                    if(parseFloat(json.state)==1){
                        frm.find(".btn-default").trigger("click");
                        window.instDataTable.draw();
                        echo(json.msg);
                    }else{
                        warn(json.msg);
                    }
                }
            });
        }
    },
    "#frmPrice":{
        "rules":{
            "name":{
                "required":true
            },
            "value":{
                "required":true
            }
        },
        "messages":{
            "name":{
                "required":"Ingrese un nombre"
            },
            "value":{
                "required":"Ingrese un costo"
            }
        },
        "submitHandler":function(form){
            var frm= $(form),
                data= frm.serialize();
            utils.loader(frm,true);
            $.ajax({
                "url":"/price/save",
                "data": data,
                "method": "POST",
                "success": function(json){
                    utils.loader(frm,false);
                    if(parseFloat(json.state)==1){
                        frm.find(".btn-default").trigger("click");
                        window.instDataTable.draw();
                        echo(json.msg);
                    }else{
                        warn(json.msg);
                    }
                }
            });
        }
    }
};