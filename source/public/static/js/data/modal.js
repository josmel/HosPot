yOSON.modal={
	"#tplCompany":{
		"templateTable": function(tpl,fn,target){
			var $this= $(this),
				id= $this.data("id");
			$.ajax({
				"url":"/ajax/get-company",
				"data": {
					"id": id
				},
				"success":function(json){
					if(parseFloat(json.state)==1){
						fn($(tpl(json.data)),target);
					}else{
						warn(json.msg);
					}
				}
			});
		},
		"callback": function(el){
			yOSON.AppCore.runModule('uploadImg2',{"url":"/company/image?folder=company","content":false,"modals":["#imgChain","#imgChainAds"]});
		}
	},
	"#tplGenre":{
		"templateTable": function(tpl,fn,target){
			var $this= $(this),
				id= $this.data("id");
			$.ajax({
				"url":"/ajax/get-genre",
				"data": {
					"id": id
				},
				"success":function(json){
					if(parseFloat(json.state)==1){
						fn($(tpl(json.data)),target);
					}else{
						warn(json.msg);
					}
				}
			});
		}
	},
	"#tplMovie":{
		"templateTable": function(tpl,fn,target){
			var $this= $(this),
				id= $this.data("id");
			$.ajax({
				"url":"/ajax/get-movie",
				"data": {
					"id": id
				},
				"success":function(json){
					if(parseFloat(json.state)==1){
						fn($(tpl(json.data)),target);
					}else{
						warn(json.msg);
					}
				}
			});
		},
		"callback": function(el){
                    yOSON.AppCore.runModule('uploadImg',{"url":"/movie/image?folder=movie","content":false});
                    $("#datepublication").datepicker({dateFormat: 'dd/mm/yy',minDate:0});
		}
	},
	"#tplSubsidiary":{
		"templateTable": function(tpl,fn,target){
			var $this= $(this),
				id= $this.data("id");
			$.ajax({
				"url":"/ajax/get-subsidiary",
				"data": {
					"id": id
				},
				"success":function(json){
					if(parseFloat(json.state)==1){
						fn($(tpl(json.data)),target);
					}else{
						warn(json.msg);
					}
				}
			});
		}
	},
        "#tplSubsidiary":{
		"templateTable": function(tpl,fn,target){
			var $this= $(this),
				id= $this.data("id");
			$.ajax({
				"url":"/ajax/get-subsidiary",
				"data": {
					"id": id
				},
				"success":function(json){
					if(parseFloat(json.state)==1){
						fn($(tpl(json.data)),target);
					}else{
						warn(json.msg);
					}
				}
			});
		}
	},
	"#tplHall":{
		"templateTable": function(tpl,fn,target){
			var $this= $(this),
				id= $this.data("id");
			$.ajax({
				"url":"/ajax/get-subsidiary",
				"data": {
					"id": id
				},
				"success":function(json){
					if(parseFloat(json.state)==1){
						fn($(tpl(json.data)),target);
					}else{
						warn(json.msg);
					}
				}
			});
		}
	},
	"#tplBillboard":{
		"templateTable": function(tpl,fn,target){
			var $this= $(this),
				id= $this.data("id");
			$.ajax({
				"url":"/ajax/get-billboard",
				"data": {
					"id": id
				},
				"success":function(json){
					if(parseFloat(json.state)==1){
						fn($(tpl(json.data)),target);
					}else{
						warn(json.msg);
					}
				}
			});
		},
		"callback":function(el){
			yOSON.AppCore.runModule('parseSchedule');
		}
	},
	"#tplPrice":{
		"templateTable": function(tpl,fn,target){
			var $this= $(this),
				id= $this.data("id");
			$.ajax({
				"url":"/ajax/get-price",
				"data": {
					"id": id
				},
				"success":function(json){
					if(parseFloat(json.state)==1){
						fn($(tpl(json.data)),target);
					}else{
						warn(json.msg);
					}
				}
			});
		}
	}
};