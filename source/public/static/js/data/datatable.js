yOSON.datable={
    "#tblCompany":{
        "ajax":{
            "url": "/company/list",
            "dataSrc": function(json){
                var data= json.aaData,
                    tmpData,routeImg;
                for(var i=0;i<data.length;i++){
                    tmpData= data[i];
                    routeImg= (tmpData[0]!="")?yOSON.eleHost+"company/"+tmpData[0]:yOSON.statHost+"img/profile.png";
                    tmpData[0]= "<img src='"+routeImg+"' width='140' height='42' >";
                    tmpData[2]= (tmpData[2])?"Si":"No";
                    data[i]= tmpData;
                }
                return data;
            }
        },
        "columns":[
            {className:"tdImg140"},
            {className:"tdName"},
            {className:"tdAds"},
            {className:"tdAction3"}
        ]
    },
    "#tblSubsidiary":{
        "ajax":{
            "url": "/subsidiary/list",
            "data": function(json){
                json.id= (typeof yOSON.idCompany!="undefined")?yOSON.idCompany:0;
            }
        },
        "columns":[
            {className: "tdName"},
            {className: "tdAddress"},
            {className: "tdUbigeo"},
            {className: "tdActions"}
        ]
    },
     "#tblHall":{
        "ajax":{
            "url": "/hall/list",
            "data": function(json){
                json.id= (typeof yOSON.idSubsidiary!="undefined")?yOSON.idSubsidiary:0;
            }
        },
        "columns":[
            {className: "tdName"},
            {className: "tdAddress"},
            {className: "tdUbigeo"},
            {className: "tdActions"}
        ]
    },
    "#tblGenre":{
        "ajax":{
            "url": "/genre/list"
        },
        "columns":[
            {className:"tdName"},
            {className:"tdAction"}
        ]
    },
    "#tblChain":{
        "ajax":{
            "url": "/chain/list",
            "dataSrc": function(json){
                var data= json.aaData,
                    tmpData,routeImg;
                for(var i=0;i<data.length;i++){
                    tmpData= data[i];
                    routeImg= (tmpData[0]!="")?yOSON.eleHost+"ads/"+tmpData[0]:yOSON.statHost+"img/profile.png";
                    tmpData[0]= "<img src='"+routeImg+"' width='140' height='42' >";
                    data[i]= tmpData;
                }
                return data;
            }
        },
        "columns":[
            {className:"tdPicture"},
            {className:"tdName"},
            {className:"tdCompany"},
            {className:"tdUbigeo"},
            {className:"tdAction"}
        ]
    },
    "#tblMovie":{
        "ajax":{
            "url": "/movie/list",
            "dataSrc": function(json){
                var data= json.aaData,
                    tmpData,routeImg;
                for(var i=0;i<data.length;i++){
                    tmpData= data[i];
                    routeImg= (tmpData[0]!="")?yOSON.eleHost+"movie/"+tmpData[0]:yOSON.statHost+"img/profile.png";
                    tmpData[0]= "<img src='"+routeImg+"' width='140' height='42' >";
                    data[i]= tmpData;
                }
                return data;
            }
        },
        "columns":[
            {className:"tdPicture"},
            {className:"tdName"},
            {className:"tdSinopsis"},
            {className:"tdGenre"},
            {className:"tdUbigeo"},
            {className:"tdDatepublication"},
            {className:"tdAction"}
        ]
    },
    "#tblBillboard":{
        "ajax":{
            "url": "/billboard/list",
            "dataSrc": function(json){
                var data= json.aaData,
                    tmpData,routeImg;
                for(var i=0;i<data.length;i++){
                    tmpData= data[i];
                    routeImg= (tmpData[0]!="")?yOSON.eleHost+"movie/"+tmpData[0]:yOSON.statHost+"img/profile.png";
                    tmpData[0]= "<img src='"+routeImg+"' width='140' height='42' >";
                    data[i]= tmpData;
                }
                return data;
            }
        },
        "callbackRender": function(datatable){
            datatable.columns().eq( 0 ).each( function ( colIdx ) {
                var fnTiming=null,
                    currentVal= "";
                $('input',datatable.column( colIdx ).footer() ).on( 'keyup', function () {
                    currentVal= this.value;
                    if(fnTiming !=null){
                        clearTimeout(fnTiming);
                    }
                    fnTiming= setTimeout(function(){
                        datatable.column( colIdx ).search( currentVal ).draw();
                        fnTiming= null;
                    }, 600);
                });
            });
        },
        "columns":[
            {className:"tdPicture"},
            {className:"tdName"},
            {className:"tdGenre"},
            {className:"tdSchedule3Dsubititle"},
            {className:"tdSchedule3Ddubbing"},
            {className:"tdSchedulesubititle"},
            {className:"tdScheduledubbing"},
            {className:"tdDatepublication"},
            {className:"tdCompany"},
            {className:"tdSubsidiary"},
            {className:"tdAction"}
        ]
    },
    "#tblPrice":{
        "ajax":{
            "url": "/price/list"
        },
        "columns":[
            {className:"tdName"},
            {className:"tdValue"},
            {className:"tdDay"},
            {className:"tdSubsidiaryCompany"},
            {className:"tdAction"}
        ]
    }
};