#-----------------------------------------------------------------------------------------------
 # @Module: DataTable
 # @Description: Modulo DataTable
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "dataTable", ((Sb) ->
	init: (oParams) ->
		dataUrl= oParams.url
		opts=
			"autoWidth": false
			"ordering": false
			"lengthChange": false
			"pageLength": 10
			"searching": true
			"processing": true
			"serverSide": true
			"language":
				"processing": "Procesando..."
				"lengthMenu": "Mostrar _MENU_ registros"
				"zeroRecords": "No se encontraron resultados"
				"emptyTable": "Ningún dato disponible en esta tabla"
				"info": "Mostrando _START_ de _END_ de un total de _TOTAL_ registros"
				"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros"
				"infoFiltered": "(filtrado de un total de _MAX_ registros)"
				"sInfoPostFix": ""
				"search": "Buscar:&nbsp;&nbsp;"
				"loadingRecords": "Cargando..."
				"paginate":
					"first": "Primero"
					"previous": "Anterior"
					"next": "Siguiente"
					"last": "Último"
		json= $.extend opts,yOSON.datable[oParams.table]
		window.instDataTable= $('#datatable').DataTable json
		json.callbackRender and json.callbackRender(window.instDataTable)
), ["plugins/jqDataTableB.js","data/datatable.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: lnkDelTable
 # @Description: Modulo para borrar registro de la tabla
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "lnkDelTable", ((Sb) ->
	st=
		"context": "#datatable"
		"lnkDel": ".lnkDel"
	dom= {}
	urlDelete= ""
	catchDom= ()->
		dom.context= $(st.context)
	bindEvents= ()->
		dom.context.on "click",st.lnkDel,()->
			$this= $(this)
			id= $this.data "id"
			$.ajax
				"url": urlDelete
				"data":
					"id": id
				"success": (json)->
					if parseFloat(json.state) is 1
						window.instDataTable.draw()
					else
						warn json.msg
	init: (oParams) ->
		urlDelete= oParams.url
		catchDom()
		bindEvents()
), ["plugins/jqDataTableB.js","data/datatable.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: Validate Form
 # @Description: Validacion de formularios
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "validation", ((Sb) ->
	init: (oParams) ->
		forms= oParams.form.split(",")
		extraOpt= oParams.config
		$.each forms,(index,value)->
			settings= {}
			value= $.trim value
			for prop of yOSON.require[value]
				settings[prop]= yOSON.require[value][prop]
			if typeof extraOpt isnt "undefined"
				settings= $.extend settings,extraOpt
			$(value).validate settings
), ["data/require.js","plugins/jqValidate.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: modalBootstrap
 # @Description: Validacion de formularios
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "modalBootstrap", ((Sb) ->
	modals= []
	modalsTable= []
	bindEvents= ()->
		dataTable= $("#datatable")
		for value in modals
			el= $(value)
			if el.length > 0
				initEvent el,false
		for target in modalsTable
			dataTable.on "click",target,()->
				$this= $(this)
				initEvent $this,true
	initEvent= (el,table)->
		target= el.data "target"
		tpl= _.template $(target).html().replace(/[\n\r]/g, "")
		if !table
			el.on "click",()->
				evtModal.call this,tpl,target,table
		else
			evtModal.call el,tpl,target,table
	evtModal= (tpl,target,table)->
		THIS= this
		if yOSON.modal[target]
			if yOSON.modal[target].template and !table
				yOSON.modal[target].template.call(THIS,tpl,dispatchModal,target)
			else if yOSON.modal[target].templateTable and table
				yOSON.modal[target].templateTable.call(THIS,tpl,dispatchModal,target)
			else
				objTpl= $ tpl()
				dispatchModal objTpl,target
		else
			objTpl= $ tpl()
			dispatchModal objTpl,target
	addValid= (frm)->
		if frm.length > 0
			idForm= "#"+frm.attr("id")
			yOSON.AppCore.runModule 'validation',{"form":idForm}
	dispatchModal= (objTpl,target)->
		objTpl.modal
			"keyboard": false
		objTpl.on "shown.bs.modal",()->
			addValid objTpl.find("form")
			if yOSON.modal[target]
				yOSON.modal[target].callback and yOSON.modal[target].callback(objTpl)
		objTpl.on "hidden.bs.modal",()->
			objTpl.remove()
	init: (oParams) ->
		if typeof oParams.modals isnt "undefined"
			modals= oParams.modals.split(",")
		if typeof oParams.modalsTable isnt "undefined"
			modalsTable= oParams.modalsTable.split(",")
		if modals.length > 0 or modalsTable.length > 0
			if typeof oParams.chargeDepends isnt "undefined"
				yOSON.AppCore.chargeDepends oParams.chargeDepends,()->
					bindEvents()
			else
				bindEvents()
), ["plugins/jqUnderscore.js","data/modal.js","data/require.js","plugins/jqValidate.js","plugins/jqUI.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: uploadImg
 # @Description: Modulo para subir imágenes
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "uploadImg", ((Sb) ->
	st=
		"fileUpload":".img-profile"
		"ctnImg": ".img-profile img"
		"inpt": "#picture"
	dom= {}
	urlFile= ""
	flagContent= true
	catchDom= ()->
		dom.fileUpload = $(st.fileUpload)
		dom.ctnImg= $(st.ctnImg)
		dom.inpt= $(st.inpt)
	bindEvents= ()->
		json=
			#"content": st.fileUpload
			"areaFile": st.fileUpload
			"html5": true
			"routeFile": urlFile
			"limitFiles": 1
			"beforeCharge": (el)->
				utils.loader dom.fileUpload,true
			"success": (json,el)->
				utils.loader dom.fileUpload,false
				if parseFloat(json.state) is 1
					dom.ctnImg.attr "src",yOSON.eleHost+json.img
					dom.inpt.val json.nameImg
				else
					warn json.msg
			"errorValid": (state,msg)->
				utils.loader dom.fileUpload,false
				warn msg
		if flagContent
			json.content= st.fileUpload
		else
			json.content= ".modal-content"
		$.jqFile json
	init: (oParams) ->
		urlFile= oParams.url
		if typeof oParams.content isnt "undefined"
			flagContent= oParams.content
		catchDom()
		bindEvents()
), ["plugins/jqFile.js"]
#-----------------------------------------------------------------------------------------------
 # @Module: uploadImg2
 # @Description: Modulo para subir muchas imágenes
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "uploadImg2", ((Sb) ->
	dom= {}
	files= []
	urlFile= ""
	flagContent= true
	bindEvents= ()->
		json=
			"html5": true
			"routeFile": urlFile
			"limitFiles": 1
			"beforeCharge": (el)->
				utils.loader el,true
			"success": (json,el)->
				console.log json
				console.log el
				utils.loader el,false
				if parseFloat(json.state) is 1
					target= $ el.data "target"
					el.find("img").attr "src",yOSON.eleHost+json.img
					target.val json.nameImg
				else
					warn json.msg
			"errorValid": (state,msg)->
				warn msg
		if flagContent
			json.content= st.fileUpload
		else
			json.content= ".modal-content"
		for file in files
			json.areaFile= file
			$.jqFile json
	init: (oParams) ->
		urlFile= oParams.url
		if typeof oParams.content isnt "undefined"
			flagContent= oParams.content
		if typeof oParams.modals isnt "undefined"
			files= oParams.modals
		bindEvents()
), ["plugins/jqFile.js"]

#-----------------------------------------------------------------------------------------------
 # @Module: parseSchedule
 # @Description: Formatear horario
#-----------------------------------------------------------------------------------------------
yOSON.AppCore.addModule "parseSchedule", ((Sb) ->
	st=
		"inpts":".parseSchedule"
		"btn": ".btnParseS"
	dom= {}
	catchDom= ()->
		dom.inpts= $ st.inpts
		dom.btn= $ st.btn
	bindEvents= ()->
		dom.btn.on "click",()->
			dom.inpts.each ()->
				$this= $(this)
				valInpt= $.trim $this.val()
				if valInpt isnt ""
					valInpt= fnParser(valInpt)
				$this.val valInpt
	fnParser= (value)->
		if(value.match(/-|\/|\|/) == null)
			value= value.replace(/pm\s+/gi,'pm -')

		value= value.replace(/(\s+pm\s+|\s+pm|pm\s+|pm|\s|\((\w+)\))/gi,'')
		value= $.trim value.replace(/(\/|\|)/gi,'-')
		arrValue= value.split(/-/)
		h= []
		for val in arrValue
			s = val.split(/:/);
			m = s[0].match(/^(\d){1}$/)
			if m isnt null
				h.push('0'+m[0]+':'+s[1])
			else
				h.push(s[0]+':'+s[1])
		f= h.join(' pm - ')
		f= f + ' pm'
		return f
	init: (oParams) ->
		catchDom()
		bindEvents()
)