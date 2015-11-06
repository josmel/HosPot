modu= yOSON.modulo
ctrl= yOSON.controller
acti= yOSON.action

log('==> mod:'+modu+' - ctrl:'+ctrl+' - acti:'+acti)

yOSON.AppSchema.modules.allModules()
if modu=='' or !yOSON.AppSchema.modules.hasOwnProperty(modu)
	yOSON.AppSchema.modules.byDefault()
else
	yOSON.AppSchema.modules[ modu ].allControllers()
	if ctrl=='' or !yOSON.AppSchema.modules[modu].controllers.hasOwnProperty(ctrl)
		yOSON.AppSchema.modules[ modu ].controllers.byDefault()
	else
		yOSON.AppSchema.modules[ modu ].controllers[ ctrl ].allActions()
		if acti is '' or !yOSON.AppSchema.modules[ modu ].controllers[ ctrl ].actions.hasOwnProperty(acti)
			yOSON.AppSchema.modules[ modu ].controllers[ ctrl ].actions.byDefault()
		else
			yOSON.AppSchema.modules[ modu ].controllers[ ctrl ].actions[ acti ]()