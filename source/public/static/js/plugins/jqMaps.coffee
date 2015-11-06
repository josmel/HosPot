mapsUtils=
	extend: ()->
		out= {}
		argL= arguments.length
		return out unless argL
		i = 0
		while i < argL
			for key of arguments[i]
				out[key]= arguments[i][key]
			i++
		out
	chargueJs: (src)->
		script = document.createElement 'script'
		script.type = 'text/javascript'
		script.src = src
		document.body.appendChild script
	unique: ()->
		return Math.random().toString(36).substr(2)
	maps:
		arrayCords: (gmaps,coords)->
			arrCords= []
			for coord in coords by 1
				if coord.constructor isnt Array
					arrCords= coords
					break
				arrCords.push new gmaps.LatLng(coord[0],coord[1])
			return arrCords
	messages:
		geolocation:
			1: "Permiso denegado"
			2: "Posición no disponible"
			3: "Tiempo de espera agotado"
			4: "El navegador no soporta geolocation"
###class customOverlay
	constructor: (options,map)->
		@map_= map

		@bounds_= options.coords || []
		@div_= options.content
		@pane_= options.pane || "floatPane"
		@position= options.position || null

		@setMap map
	#Método estático
	@autoload= (nspace)->
		customOverlay.prototype= new nspace.OverlayView()
		customOverlay::onAdd= -> 
			panes= this.getPanes()
			panes[@pane_].appendChild(@div_)
		customOverlay::draw= ->
			overlayProjection = this.getProjection()
			if @bounds_.constructor is Array
				@bounds_= mapsUtils.maps.arrayCords nspace, @bounds_
			@calcPosition(overlayProjection)
		customOverlay::calcPosition= (objOverlay)->
			if @position isnt null
				@position&&@position(objOverlay,@div_,@bounds_)
			else
				div= @div_
				overlayP= {} 
				console.log @bounds_
				if @bounds_.constructor is Array
					sw= objOverlay.fromLatLngToDivPixel(@bounds_[0])
					ne= objOverlay.fromLatLngToDivPixel(@bounds_[1])
					overlayP.left= sw.x + 'px'
					overlayP.top= ne.y + 'px'
				else
					cord= objOverlay.fromLatLngToDivPixel(@bounds_)
					overlayP.left= cord.x + 'px'
					overlayP.top= cord.y + 'px'				
				div.style.left= overlayP.left
				div.style.top= overlayP.top
		customOverlay::onRemove= ->
			@div_.parentNode.removeChild(@div_)
			@div_= null
###
window.jqMaps = class jqMaps
	constructor: (options)->
		opt=
			id: "jqMaps"															#Identificador del mapa
			protocol: "http"														#Protocolo puede ser http o https
			sensor: "true"															#Sensor de geolocalizacion
			geolocation:															#Opciones de geolocalizacion
				state: false														#Activa core de geolocalizacion
				success: null														#Callback cuando es satisfactorio la geolocalizacion
				error: null															#Callback cuando ocurre un error en la geolocalizacion
			success: null															#Callback cuando termina de cargar el google maps
		@settings= mapsUtils.extend opt,options
		@el= null
		@opts=																		#Opciones por defecto para la instancia de gmaps
			zoom: 8
			lt: -8.59087
			lg: -77.1025
			mapTypeId: "ROADMAP"
			streetViewControl: true
			panControl: true
			zoomControl: true
			mapTypeControl: false
			scaleControl: false
			streetViewControl: true
			overviewMapControl: false
		@dispatchState= false														#Estado para despachar las funciones declaradas en jqMaps
		@registerMethods= []														#Array que estoriza las funciones a despachar en jqMaps
		@markers= {}																#Almacena una colección de markers de la aplicación 
		@polilynes= {}																#Almacena una colección de polilynes de la aplicación
		@map= null																	#Estoriza la instancia de gmaps
		@_init()
	_init: ()->
		@_chargeLib()	
	_dispatch: ()->
		@nspace= google.maps
		settings= @settings
		@el= document.getElementById(settings.id) || false
		if @nspace == undefined
			throw "la carga de google maps a fallado o es una version no soportada"
		if @el
			target= @el
			opts= @_getOpts()
			###customOverlay.autoload(@nspace)												#Extendiendo la clase OverlayView ###
			if settings.geolocation.state
				@_geolocation(target)
			else
				@map= if @map is null then new @nspace.Map(target, opts)
				@dispatchState= true
				@_dispatchMethods()
				settings.success&&settings.success(@map,@nspace)
		else
			throw "el objeto map no existe en el documento"
	_dispatchMethods: ()->															#Despachador de Eventos###
		_this= @
		if @dispatchState
			for collectionMethod in @registerMethods by 1
				method= collectionMethod.method
				params= collectionMethod.params
				status= collectionMethod.status
				if status
					collectionMethod.func()
					collectionMethod.status= 0
	_getOpts: (opts)->																#Obtiene las opciones con el que se va a inicilizar gmaps
		_this = @
		opts= @opts
		return {
			center: new _this.nspace.LatLng(opts.lt, opts.lg)
			zoom: opts.zoom
			mapTypeId: _this.nspace.MapTypeId[opts.mapTypeId]
			streetViewControl: opts.streetViewControl
			panControl: opts.panControl
			zoomControl: opts.zoomControl
			mapTypeControl: opts.mapTypeControl
			scaleControl: opts.scaleControl
			streetViewControl: opts.streetViewControl
			overviewMapControl: opts.overviewMapControl
		}
	_chargeLib: ()->
		_this= @
		window.jqMapsDispatch= ->
			_this._dispatch()
		url= @settings.protocol+"://maps.googleapis.com/maps/api/js?sensor="+@settings.sensor+"&callback=jqMapsDispatch"
		mapsUtils.chargueJs url
	_geolocation: (target)->
		_this= @
		settings= @settings
		geolocation= settings.geolocation
		if navigator.geolocation
			navigator.geolocation.getCurrentPosition ((position)->
				geolocation.success&&geolocation.success(position)
				_this.opts.zoom= 18
				_this.opts.lt= position.coords.latitude
				_this.opts.lg= position.coords.longitude
				opts= _this._getOpts()
				_this.map= if _this.map is null then new _this.nspace.Map(target, opts)
				_this.dispatchState= true
				_this._dispatchMethods()
				settings.success&&settings.success(_this.map,_this.nspace)
			),((error)->
				geolocation.error&&geolocation.error(error)
			),
				enableHighAccuracy: true,
				timeout: 30000,
				maximumAge: 0
		else
			geolocation.error&&geolocation.error(4,mapsUtils.messages.geolocation["4"])
	_addMethods: (method)->
		method.status= 1
		@registerMethods.push method
		@_dispatchMethods()
	#Public Methods
	setOpts: (opts)->																#Setea las opciones al iniciar gmaps
		_this= @
		@opts= mapsUtils.extend _this.opts, opts
	mapEvents: (mapOpts)->
		_this= @
		@_addMethods
			method: 'evt'
			params: mapOpts
			func: ()->
				for nameEvt of mapOpts
					_this.nspace.event.addListener _this.map, nameEvt, mapOpts[nameEvt]
	addMarker: (markerOpts)->
		_this= @
		idMarker= markerOpts.id || mapsUtils.unique()
		@_addMethods
			method: "marker"
			params: markerOpts
			func: ()->
				settings= {}
				if not markerOpts.hasOwnProperty("position")
					settings.position =  new _this.nspace.LatLng(markerOpts.lt || -8.59087, markerOpts.lg || -77.1025)
				else
					settings.position = markerOpts.position
				settings.title = markerOpts.title || ""
				settings.map = _this.map
				settings.draggable = markerOpts.draggable || false
				#estableciendo el icono del marker
				settings.icon= markerOpts.icon
				if markerOpts.icon.constructor is Object and markerOpts.icon.path isnt ""
					settings.icon.path= _this.nspace.SymbolPath[markerOpts.icon.path]
				#el id del marker
				settings.id = idMarker
				#el marker será fijo y no se removerá
				settings.fixed = markerOpts.fixed || false
				#asociacion del marker con el mapa
				markerObj = new _this.nspace.Marker(settings)
				#se guarda en una colección de markers  
				_this.markers[idMarker] = markerObj
				#setData
				markerObj.gmap = _this
				markerObj.data = markerOpts.data || ""
				#se despacha los eventos que se solicitan
				for nameEvt of markerOpts.evts
					_this.nspace.event.addListener markerObj, nameEvt , markerOpts.evts[nameEvt]
	addPolyline: (polilyneOpts)->
		_this= @
		idPolilyne= polilyneOpts.id || mapsUtils.unique()
		@_addMethods
			method: "polilyne"
			params: polilyneOpts
			func: ()->
				settings= {}
				if polilyneOpts.hasOwnProperty("path")
					settings.path= mapsUtils.maps.arrayCords _this.nspace, polilyneOpts.path
				settings.id= idPolilyne
				settings.strokeColor= polilyneOpts.strokeColor || "#FF0000"
				settings.strokeOpacity= polilyneOpts.strokeOpacity || 1.0
				settings.strokeWeight= polilyneOpts.strokeWeight || 2
				polilyneObj= new _this.nspace.Polyline(settings)
				_this.polilynes[idPolilyne]= polilyneObj
				polilyneObj.gmap= _this
				polilyneObj.data= polilyneObj.data || ""
				for nameEvt of polilyneOpts.evts
					_this.nspace.event.addListener polilyneObj, nameEvt, polilyneOpts.evts[nameEvt]
				polilyneObj.setMap _this.map
	###addCustomOverlay: (customOverlayOpts)->
		new customOverlay customOverlayOpts, @map###
	getMap: ()->
		@map
	getMarker: (idMarker)->
		@markers[idMarker]
	getPolyline: (idPolilyne)->
		@polilynes[idPolilyne]