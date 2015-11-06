(($) ->
	getBrowser = ->
		a = uaMatch(navigator.userAgent)
		b = {}
		if a.browser
			b[a.browser] = true
			b.version = a.version
		if b.chrome
			b.webkit = true
		else
			b.safari = true  if b.webkit
		b
	uaMatch = (b) ->
		b = b.toLowerCase()
		a = /(chrome)[ \/]([\w.]+)/.exec(b) or /(webkit)[ \/]([\w.]+)/.exec(b) or /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(b) or /(msie) ([\w.]+)/.exec(b) or b.indexOf("compatible") < 0 and /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(b) or []
		browser: a[1] or ""
		version: a[2] or "0"

	browser= getBrowser()						#Parámetro que devuelve la descripción del browser
	jqFileUtils=
		hash: ()->
			Math.random().toString(36).substr(2)
		valExt: (ext,eReg)->
			expr= new RegExp(eReg,"gi")
			flag= expr.test(ext)
			return flag
		validSize: (file,maxSize)->
			if browser.msie then return true
			sz= file[0].files[0].size
			if parseInt(sz) <= maxSize
				true
			else
				false
		messages:
			"0": "No se cargo un archivo"
			"1": "El archivo a cargar no esta permitido"
			"2": "El archivo excede su peso"
	class jqFile
		constructor: (options)->
			opt=
				areaFile: ".areaFile"					#Parámetro que referencia al boton a submitear
				content: "body"							#Selector de contenedor en el que va a estar el input file
				html5: false							#Parámetro que activa soporte para html5
				dragdrop: true							#Habilitar el Drag and Drop, solo funcionara  si html5 esta en true
				routeFile: "jqFile"						#Parámetro donde se envia el submit del formulario
				multipleFile: false						#Parámetro donde se define que se desea guardar multiples files, Pendiente validacion de todos los files ingresado en el evento clic
				createFile: true						#Crea el input file
				limitFiles: -1							#Si se establece en -1, la cantidad de archivos sera ilimitada
				nameFile: "inputFile"					#Nombre del input file
				methodForm: "POST"						#Method del Formulario al crear el file, es obligatorio si esta en HTML5 
				eReg: "jpg|gif|png|jpeg|bmp"			#Expresion regular para validar la extensión
				maxSize: 2097152						#Cantidad de bytes que se pueden subir por imágenes
				success: null							#Callback de respuestas
				processCharge: null						#Callback que muestra el porcentaje de avance
				dragEnter: null							#Callback que se muestra cuando el usuario realiza un drag y lo posiciona en el areaFile
				dragLeave: null							#Callback que se muestra cuando el usuario realiza un drag, lo posiciona en el areaFile y luego se  retira del mismo
				error: null								#Callback de error
				errorValid: null						#Callback error valid
				errorBrowser: null						#Error de compatilidad con navegador
				errorLimit: null						#Callback de límite de error
				beforeCharge: null						#Callback antes de cargar el archivo
				afterCharge: null						#Callback despues de cargar el archivo
			@settings= $.extend opt,options
			@arquitect= {}
			@_init()
		_init: ()->
			@_arquitect()
			@_bindEvents()
		_arquitect: ()->
			_this= @
			settings= @settings
			@arquitect.areaFile= $(settings.areaFile)
			@arquitect.content= $(settings.content)
			if !settings.html5
				@_createIframe()
			@_createFile()
		_bindEvents: ()->
			_this= @
			settings= @settings
			arquitect= @arquitect
			validFile= null
			#Eventos Input File
			@_evtInpt()
			if !settings.html5
				@_evtIframe()
			else if settings.dragdrop
				@_evtDragDrop()
		_createFile: ()->
			_this= @
			settings= @settings
			idIframe= @arquitect.idIframe
			if settings.createFile is true
				@_newFile()
			else
				@arquitect.file= $("input[name='"+settings.nameFile+"']")
				@arquitect.form= @arquitect.file.parents "form"
				if !settings.html5 then @arquitect.form.attr "target", idIframe
				@arquitect.form.attr "action", settings.routeFile
		_createIframe: ()->
			settings= @settings
			idIframe= jqFileUtils.hash()
			@arquitect.idIframe= idIframe
			#Creando Iframe
			@arquitect.iframe= $("<iframe />",
				"name": idIframe
				"id": idIframe
				"src": "javascript:false;"
				"style": "display:none;"
			)
			@arquitect.content.append @arquitect.iframe
		_newFile: (idIframe)->
			settings= @settings
			optFile= @_settingsFile()
			#Creando Form
			@arquitect.form= $("<form />",
				"id": "frmJqFile-"+jqFileUtils.hash()
				"action": settings.routeFile
				"method": settings.methodForm
				"enctype": "multipart/form-data"
				"target": @arquitect.idIframe
			)
			@arquitect.form.css optFile.form
			#Creando File
			file=
				"type": "file"
				"name": settings.nameFile
			if settings.multipleFile
				file["multiple"]="multiple"
			@arquitect.file= $("<input />",file)
			@arquitect.file.css optFile.file
			@arquitect.form.append @arquitect.file
			#Agregandolo al Contenedor
			@arquitect.content.append @arquitect.form
		_settingsFile: ()->
			areaFile= @arquitect.areaFile
			content= @arquitect.content
			dimentions=
				"width": areaFile.outerWidth()
				"height": areaFile.outerHeight()
			posBtn= areaFile.offset()
			posCtn= content.offset()
			positions=
				"top": (posBtn.top-posCtn.top)
				"left": (posBtn.left-posCtn.left)
			cssForm=
				"position": "absolute"
				"overflow":"hidden"
				"-ms-filter": "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"
				"filter": "alpha(opacity=0)"
				"opacity": 0
				"z-index": "99"
			cssFile=
				"display": "block"
				"font-size": "999px"
				"cursor": "pointer"
			cssForm= $.extend cssForm,dimentions,positions
			cssFile= $.extend cssFile,dimentions
			return {
				"form": cssForm
				"file": cssFile
			}
		_evtIframe: ()->
			_this= @
			settings= @settings
			arquitect= @arquitect
			arquitect.iframe.bind "load",()->
				response= if browser.msie and parseInt(browser.version.substr(0,1)) <= 8 then window.frames[arquitect.idIframe].document.body.innerHTML else arquitect.iframe[0].contentDocument.body.innerHTML
				if response isnt "false"
					json= (new Function("return " + response))()
					settings.afterCharge and settings.afterCharge()
					settings.success and settings.success(json)
					arquitect.form.show()
		_evtInpt: ()->
			_this= @
			settings= @settings
			arquitect= @arquitect
			validFile= null
			arquitect.file.bind "change",(event)->
				if !settings.html5
					validFile= _this._validFile.call this,settings
					if validFile
						settings.beforeCharge and settings.beforeCharge()
						arquitect.form.hide().submit()
				else
					_this._processFile(event.target)
		_evtDragDrop: ()->
			_this= @
			settings= @settings
			arquitect= @arquitect
			_this._addEvent arquitect.form,"dragover",(e)->
				e.preventDefault()
				e.stopPropagation()
			_this._addEvent arquitect.form,"dragenter",(e)->
				e.preventDefault()
				e.stopPropagation()
				arquitect.file.hide()
				settings.dragEnter and settings.dragEnter()
			_this._addEvent arquitect.form,"dragleave",(e)->
				e.preventDefault()
				e.stopPropagation()
				arquitect.file.show()
				settings.dragLeave and settings.dragLeave()
			_this._addEvent arquitect.form,"drop",(e)->
				e.preventDefault()
				e.stopPropagation()
				#event= e.originalEvent
				_this._processFile e.dataTransfer
				arquitect.file.show()
		_addEvent: (el,evt,callback)->
			if el[0].addEventListener
				el[0].addEventListener evt,callback
			else
				el[0].attachEvent evt,callback
		_processFile: (event)->							#Funcionalidad solo para html5
			settings= @settings
			arquitect= @arquitect
			files= event.files
			validLimit= if files.length <= settings.limitFiles then true else false
			if validLimit
				validFile= @_validFilesHtml5(files)
				if validFile
					settings.beforeCharge and settings.beforeCharge(arquitect.areaFile)
					for file in files
						@_sendFile file
			else
				settings.errorLimit and settings.errorLimit()
		_sendFile: (file)->
			settings= @settings
			arquitect= @arquitect
			objData= new FormData()
			objData.append "inputFile",file
			$.ajax
				contentType: false
				processData: false
				type: "POST"
				"url": settings.routeFile
				"cache": false
				"data": objData,
				"xhr": ()->
					xhrobj= $.ajaxSettings.xhr()
					if xhrobj.upload
						xhrobj.upload.addEventListener 'progress', (event)->
							percent= 0
							position= event.loaded || event.position
							total= event.total
							if event.lengthComputable
								percent = Math.ceil(position / total * 100)
							settings.processCharge and settings.processCharge percent,event
						, false
					return xhrobj
				"success": (json)->
					settings.success and settings.success(json,arquitect.areaFile)


			###arquitect.form.ajaxSubmit
				"url": settings.routeFile
				"data": fd
				"processData": false,
				"dataType": "json"
				"uploadProgress": (e,position,total,percentComplete)->
					console.log percentComplete
					console.log total
				"complete": (json)->
					console.log json.responseJSON###

			###$.ajax
				"url": settings.routeFile
				"method": "POST"
				"data":<
					"filename": file.name
					"file": file
				"success" : (json)->
					console.log json###
			###if xhr.upload
				xhr.upload.addEventListener "progress", (e)->
					percent= parseInt((e.loaded * 100)/ e.total )
					console.log e
					console.log e.loaded
					console.log e.total
					console.log percent
					settings.processCharge and settings.processCharge(percent,e)
				, false
				xhr.onreadystatechange= (e)->
					if xhr.readyState is 4
						if xhr.status is 200
							json= (new Function("return " + xhr.responseText))()
							settings.success and settings.success(json,arquitect.areaFile)
						else
							settings.error and settings.error()
				xhr.open "POST",settings.routeFile,true
				xhr.setRequestHeader "X-FILENAME",file.name
				xhr.send file
			else
				settings.errorBrowser and settings.errorBrowser()###
		_validFilesHtml5: (files)->
			settings= @settings
			ext= ""
			typeFile= ""
			for file in files
				arrParse= file.name.split "."
				#typeFile= file.type.split "/"
				typeFile= arrParse[arrParse.length-1]
				valExt= jqFileUtils.valExt typeFile,settings.eReg
				if file > settings.maxSize or !valExt
					if !valExt
						settings.errorValid and settings.errorValid(1,jqFileUtils.messages["1"])
					else
						settings.errorValid and settings.errorValid(2,jqFileUtils.messages["2"])
					return false
			return true
		_validFile: (settings)->
			file= $(this)
			srcFile= file.val()
			ext= ""
			eReg= settings.eReg
			if srcFile isnt ""
				srcFile= srcFile.split("\\");
				srcFile= srcFile[srcFile.length-1];
				ext= srcFile.split(".")
				ext= ext[ext.length-1]
				valExt= jqFileUtils.valExt ext,eReg
				valSize= jqFileUtils.validSize file,settings.maxSize
				if valExt and valSize
					return true
				else
					if !valExt
						settings.errorValid and settings.errorValid(1,jqFileUtils.messages["1"])
					else
						settings.errorValid and settings.errorValid(2,jqFileUtils.messages["2"])
					return false
			else
				settings.errorValid and settings.errorValid(0,jqFileUtils.messages["0"])
				return false
	$.extend
		jqFile: (json) ->
			new jqFile(json)
			return
	return
) jQuery