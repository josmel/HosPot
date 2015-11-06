yOSON.AppSchema.modules=
	'default':
		controllers:
			'profile':
				actions :
					'index': ->
						yOSON.AppCore.runModule 'uploadImg',{"url":"/profile/image?folder=admin"}
						yOSON.AppCore.runModule 'validation',{"form":"#frmProfile"}
						return
					'byDefault': ->
				allActions: ->
			'company':
				actions:
					'index': ->
						yOSON.AppCore.runModule 'dataTable',{"table": "#tblCompany"}
						yOSON.AppCore.runModule 'lnkDelTable',{"url": "/company/delete"}
						yOSON.AppCore.runModule 'modalBootstrap',{"modals": "#lnkCompany","modalsTable": ".lnkEdit","chargeDepends": ["plugins/jqFile.js"]}
						return
					'byDefault': ->
				allActions: ->
			'subsidiary':
				actions:
					'index': ->
						yOSON.AppCore.runModule 'dataTable',{"table": "#tblSubsidiary"}
						yOSON.AppCore.runModule 'lnkDelTable',{"url": "/subsidiary/delete"}
						yOSON.AppCore.runModule 'modalBootstrap',{"modals": "#lnkSubsidiary","modalsTable": ".lnkEdit"}
						return
					'byDefault': ->
				allActions: ->
			'genre':
				actions:
					'index': ->
						yOSON.AppCore.runModule 'dataTable',{"table": "#tblGenre"}
						yOSON.AppCore.runModule 'lnkDelTable',{"url": "/genre/delete"}
						yOSON.AppCore.runModule 'modalBootstrap',{"modals": "#lnkGenre","modalsTable": ".lnkEdit"}
						return
					'byDefault': ->
				allActions: ->
			'ads':
				actions:
					'index': ->
						yOSON.AppCore.runModule 'dataTable',{"table": "#tblAds"}
						yOSON.AppCore.runModule 'lnkDelTable',{"url": "/ads/delete"}
						yOSON.AppCore.runModule 'modalBootstrap',{"modals": "#lnkAds","modalsTable": ".lnkEdit","chargeDepends": ["plugins/jqFile.js"]}
						return
					'byDefault': ->
				allActions: ->
			'movie':
				actions:
					'index': ->
						yOSON.AppCore.runModule 'dataTable',{"table": "#tblMovie"}
						yOSON.AppCore.runModule 'lnkDelTable',{"url": "/movie/delete"}
						yOSON.AppCore.runModule 'modalBootstrap',{"modals": "#lnkMovie","modalsTable": ".lnkEdit","chargeDepends": ["plugins/jqFile.js"]}
						return
					'byDefault': ->
				allActions: ->
			'billboard':
				actions:
					'index': ->
						yOSON.AppCore.runModule 'dataTable',{"table": "#tblBillboard"}
						yOSON.AppCore.runModule 'lnkDelTable',{"url": "/billboard/delete"}
						yOSON.AppCore.runModule 'modalBootstrap',{"modals": "#lnkBillboard","modalsTable": ".lnkEdit"}
						return
					'byDefault': ->
				allActions: ->
			'price':
				actions:
					'index': ->
						yOSON.AppCore.runModule 'dataTable',{"table": "#tblPrice"}
						yOSON.AppCore.runModule 'lnkDelTable',{"url": "/price/delete"}
						yOSON.AppCore.runModule 'modalBootstrap',{"modals": "#lnkPrice","modalsTable": ".lnkEdit"}
						return
					'byDefault': ->
				allActions: ->
			byDefault : ->
			allActions: ->
		byDefault : ->
		allControllers : ->
	byDefault : ->
	allModules : ->