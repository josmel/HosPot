### Utilitarios Js que necesita Yoson
# requires :Utils
# type :Object ###
window.log= if typeof log isnt "undefined" then log else ()->
	noPre= ()->
		return /(local\.|dev\.)/gi.test(document.domain)
	if typeof console isnt "undefined" and noPre()
		if typeof console.log.apply isnt "undefined"
			console.log.apply console,arguments
		else
			console.log Array.prototype.slice.call arguments
		return
yOSONUtils=
	"copy": (el,aArray)->
		for json in aArray
			el.push json
		return el
	"remove": (el,from, to)->
		rest= el.slice((to || from) + 1 || el.length)
		el.length= if from < 0 then el.length + from else from
		el.push.apply el,rest
		return el
### gestiona todos los oyentes y los notificadores de la aplicación
# requires :Core.js
# type :Object ###
yOSON.AppSandbox= ()->
	### member :Sandbox 
	# notifica un evento para todos los modulos que escuchan el evento
	# oTrigger.event type :String   oTrigger.data type :Array   
	# ejemplo: { event:'hacer-algo', data:{name:'jose', edad:27} } ###
	trigger: (sType, aData)->
		oAction= null
		aData= if typeof aData isnt "undefined" then aData else []
		if typeof yOSON.AppSandbox.aActions[sType] isnt "undefined"
			nActL= yOSON.AppSandbox.aActions[sType].length
			while nActL--
				oAction = yOSON.AppSandbox.aActions[sType][nActL]
				oAction.handler.apply(oAction.module, aData)
			return
	stopEvents: (aEventsToStopListen,oModule)->
		aAuxActions = []
		for json,nEvent in aEventsToStopListen
			sEvent= aEventsToStopListen[nEvent]
			for json2,nAction in yOSON.AppSandbox.aActions[sEvent]
				if oModule isnt yOSON.AppSandbox.aActions[sEvent][nAction].module
					aAuxActions.push yOSON.AppSandbox.aActions[sEvent][nAction]
			yOSON.AppSandbox.aActions[sEvent]= aAuxActions
			if yOSON.AppSandbox.aActions[sEvent].length is 0 then delete yOSON.AppSandbox.aActions[sEvent]
		return
	events: (aEventsToListen, fpHandler, oModule)->
		for json,nEvent in aEventsToListen
			sEvent= aEventsToListen[nEvent]
			if typeof yOSON.AppSandbox.aActions[sEvent] is "undefined"
				yOSON.AppSandbox.aActions[sEvent] = []
			yOSON.AppSandbox.aActions[sEvent].push
				module: oModule,
				handler: fpHandler
		return this
	objMerge: ()->
		out= {}
		argL= arguments.length
		if !argL
			return out
		while --argL
			for key,json of arguments[argL]
				out[key]= json
		return out
	request: (sUrl, oData, oHandlers, sDatatype)->
		Core.ajaxCall(sUrl,oData,oHandlers,sDatatype)
		return
yOSON.AppSandbox.aActions= []
###
# applicaction :yOSON.AppScript
# description :Carga script Javascript o Css en la pagina para luego ejecutar funcionalidades dependientes.
# example :yOSON.AppScript.charge('lib/plugins/colorbox.js,plugins/colorbox.css', function(){ load! } );
###
yOSON.AppScript= ((statHost, filesVers)->
	urlDirJs= ""
	urlDirCss= ""
	version= ""
	ScrFnc= {}
	((url, vers)->
		urlDirJs= url+'js/'
		urlDirCss= url+'styles/'
		version= if true then vers else ''
		return
	)(statHost, if typeof filesVers isnt "undefined" then filesVers else '')
	codear= (url)->
		if url.indexOf('//') isnt -1 then url.split('//')[1].split('?')[0].replace(/[\/\.\:]/g,'_') else url.split('?')[0].replace(/[\/\.\:]/g,'_')
	addFnc = (url, fnc)->
		if !ScrFnc.hasOwnProperty(codear(url))
			ScrFnc[codear(url)]=
				state:true
				fncs:[]
		ScrFnc[codear(url)].fncs.push(fnc)
		return
	execFncs= (url)->
		ScrFnc[codear(url)].state= false
		for json,i in ScrFnc[codear(url)].fncs
			if ScrFnc[codear(url)].fncs[i] is "undefined"
				log(ScrFnc[codear(url)].fncs[i])
			ScrFnc[codear(url)].fncs[i]()
		return
	loadJs= (url, fnc)->
		scr = document.createElement "script"
		scr.type = "text/javascript"
		if scr.readyState
			scr.onreadystatechange= ()->
				if scr.readyState is "loaded" or scr.readyState is "complete"
					scr.onreadystatechange= null
					fnc(url)
					return
		else
			scr.onload= ()->
				fnc(url)
				return
		scr.src = url
		document.getElementsByTagName("head")[0].appendChild(scr)
		return
	loadCss= (url,fnc)->
		link= document.createElement('link')
		link.type= 'text/css'
		link.rel= 'stylesheet'
		link.href= url
		document.getElementsByTagName('head')[0].appendChild(link)
		if(document.all)
			link.onload= ()->
				fnc(url)
				return
		else
			img= document.createElement('img')
			img.onerror= ()->
				if(fnc)
					fnc(url)
				document.body.removeChild(this)
				return
			document.body.appendChild(img)
			img.src= url
		return
	charge: (aUrl, fFnc, sMod, lev)->
		THAT= this
		if aUrl.length is 0 or aUrl is "undefined" or aUrl is ""
			return false
		if aUrl.constructor.toString().indexOf('Array') isnt -1 and aUrl.length is 1
			aUrl= aUrl[0]
		lev= if typeof lev isnt 'number' then 1 else lev
		if aUrl.constructor.toString().indexOf('String') isnt -1
			isJs= (aUrl.indexOf('.js') !=-1)
			isCss= (aUrl.indexOf('.css')!=-1)
			if !isJs and !isCss
				return false
			parts= aUrl.split('/')
			parts[parts.length-1]= (if yOSON.min isnt 'undefined' and isJs then yOSON.min else '')+parts[parts.length-1]
			aUrl= parts.join('/')
			urlDir= if isJs then urlDirJs else urlDirCss
			if isJs or isCss
				aUrl= if aUrl.indexOf('http') isnt -1 then (aUrl+version) else (urlDir+aUrl+version+(if isCss then (new Date().getTime()) else ''))
				if !ScrFnc.hasOwnProperty(codear(aUrl))
					addFnc(aUrl, fFnc)
					if isJs then loadJs(aUrl, execFncs) else loadCss(aUrl, execFncs)
				else
					if ScrFnc[codear(aUrl)].state
						addFnc(aUrl,fFnc)
					else
						fFnc()
		else
			if aUrl.constructor.toString().indexOf('Array') isnt -1
				this.charge aUrl[0],()->
					THAT.charge yOSONUtils.remove(aUrl,0), fFnc, sMod, (lev+2)
					return
				, sMod, (lev+1)
			else
				log aUrl+' - no es un Array'
		return
)(yOSON.statHost, yOSON.statVers)
yOSON.AppCore= ( ()->
	oSandBox = new yOSON.AppSandbox()
	oModules = {} 
	debug    = false
	window.cont  = 0
	doInstance= (sModuleId)->
		instance= oModules[sModuleId].definition oSandBox
		if !debug
			for name,method of instance
				if typeof method is "function"
					instance[name]= ((name,method)->
						->
							try
								return method.apply this,arguments
							catch ex
								log name+"(): "+ex.message
					)(name,method)
		return instance
	addModule: (sModuleId, fDefinition, aDep)->
		aDep = if typeof aDep is 'undefined' then [] else aDep
		if typeof oModules[sModuleId] is "undefined"
			oModules[sModuleId]=
				definition: fDefinition
				instance: null
				dependency: aDep
		else
			throw "module '"+sModuleId+"' is already defined, Please set it again"
		return
	getModule: (sModuleId)->
		if sModuleId and oModules[sModuleId]
			return oModules[sModuleId]
		else
			throw 'structureline58 param "sModuleId" is not defined or module not found'
		return
	runModule: (sModuleId, oParams)->
		if oModules[sModuleId] isnt undefined
			if oParams is undefined
				oParams = {}
			oParams.moduleName= sModuleId
			mod= this.getModule(sModuleId)
			thisInstance = mod.instance = doInstance(sModuleId)
			if thisInstance.hasOwnProperty('init')
				if mod.dependency.length > 0
					yOSON.AppScript.charge yOSONUtils.copy([],mod.dependency), ()->
						thisInstance.init(oParams)
						return
					, sModuleId+window.cont, 1
				else
					thisInstance.init oParams
			else
				throw ' ---> init function is not defined in the module "'+oModules[sModuleId]+'"'
		else
			throw 'module "'+sModuleId+'" is not defined or module not found'
		return
	runModules: (aModuleIds)->
		for id,json of aModuleIds
			this.runModule json
		return
	chargeDepends: (arrDeps,callback)->
		copyArr= yOSONUtils.copy([],arrDeps)
		yOSON.AppScript.charge copyArr,()->
			callback and callback()
)()