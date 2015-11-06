
/* Utilitarios Js que necesita Yoson
 * requires :Utils
 * type :Object
 */
var yOSONUtils;

window.log = typeof log !== "undefined" ? log : function() {
  var noPre;
  noPre = function() {
    return /(local\.|dev\.)/gi.test(document.domain);
  };
  if (typeof console !== "undefined" && noPre()) {
    if (typeof console.log.apply !== "undefined") {
      console.log.apply(console, arguments);
    } else {
      console.log(Array.prototype.slice.call(arguments));
    }
  }
};

yOSONUtils = {
  "copy": function(el, aArray) {
    var json, _i, _len;
    for (_i = 0, _len = aArray.length; _i < _len; _i++) {
      json = aArray[_i];
      el.push(json);
    }
    return el;
  },
  "remove": function(el, from, to) {
    var rest;
    rest = el.slice((to || from) + 1 || el.length);
    el.length = from < 0 ? el.length + from : from;
    el.push.apply(el, rest);
    return el;
  }
};


/* gestiona todos los oyentes y los notificadores de la aplicación
 * requires :Core.js
 * type :Object
 */

yOSON.AppSandbox = function() {
  return {

    /* member :Sandbox 
    	 * notifica un evento para todos los modulos que escuchan el evento
    	 * oTrigger.event type :String   oTrigger.data type :Array   
    	 * ejemplo: { event:'hacer-algo', data:{name:'jose', edad:27} }
     */
    trigger: function(sType, aData) {
      var nActL, oAction;
      oAction = null;
      aData = typeof aData !== "undefined" ? aData : [];
      if (typeof yOSON.AppSandbox.aActions[sType] !== "undefined") {
        nActL = yOSON.AppSandbox.aActions[sType].length;
        while (nActL--) {
          oAction = yOSON.AppSandbox.aActions[sType][nActL];
          oAction.handler.apply(oAction.module, aData);
        }
      }
    },
    stopEvents: function(aEventsToStopListen, oModule) {
      var aAuxActions, json, json2, nAction, nEvent, sEvent, _i, _j, _len, _len1, _ref;
      aAuxActions = [];
      for (nEvent = _i = 0, _len = aEventsToStopListen.length; _i < _len; nEvent = ++_i) {
        json = aEventsToStopListen[nEvent];
        sEvent = aEventsToStopListen[nEvent];
        _ref = yOSON.AppSandbox.aActions[sEvent];
        for (nAction = _j = 0, _len1 = _ref.length; _j < _len1; nAction = ++_j) {
          json2 = _ref[nAction];
          if (oModule !== yOSON.AppSandbox.aActions[sEvent][nAction].module) {
            aAuxActions.push(yOSON.AppSandbox.aActions[sEvent][nAction]);
          }
        }
        yOSON.AppSandbox.aActions[sEvent] = aAuxActions;
        if (yOSON.AppSandbox.aActions[sEvent].length === 0) {
          delete yOSON.AppSandbox.aActions[sEvent];
        }
      }
    },
    events: function(aEventsToListen, fpHandler, oModule) {
      var json, nEvent, sEvent, _i, _len;
      for (nEvent = _i = 0, _len = aEventsToListen.length; _i < _len; nEvent = ++_i) {
        json = aEventsToListen[nEvent];
        sEvent = aEventsToListen[nEvent];
        if (typeof yOSON.AppSandbox.aActions[sEvent] === "undefined") {
          yOSON.AppSandbox.aActions[sEvent] = [];
        }
        yOSON.AppSandbox.aActions[sEvent].push({
          module: oModule,
          handler: fpHandler
        });
      }
      return this;
    },
    objMerge: function() {
      var argL, json, key, out, _ref;
      out = {};
      argL = arguments.length;
      if (!argL) {
        return out;
      }
      while (--argL) {
        _ref = arguments[argL];
        for (key in _ref) {
          json = _ref[key];
          out[key] = json;
        }
      }
      return out;
    },
    request: function(sUrl, oData, oHandlers, sDatatype) {
      Core.ajaxCall(sUrl, oData, oHandlers, sDatatype);
    }
  };
};

yOSON.AppSandbox.aActions = [];


/*
 * applicaction :yOSON.AppScript
 * description :Carga script Javascript o Css en la pagina para luego ejecutar funcionalidades dependientes.
 * example :yOSON.AppScript.charge('lib/plugins/colorbox.js,plugins/colorbox.css', function(){ load! } );
 */

yOSON.AppScript = (function(statHost, filesVers) {
  var ScrFnc, addFnc, codear, execFncs, loadCss, loadJs, urlDirCss, urlDirJs, version;
  urlDirJs = "";
  urlDirCss = "";
  version = "";
  ScrFnc = {};
  (function(url, vers) {
    urlDirJs = url + 'js/';
    urlDirCss = url + 'styles/';
    version = true ? vers : '';
  })(statHost, typeof filesVers !== "undefined" ? filesVers : '');
  codear = function(url) {
    if (url.indexOf('//') !== -1) {
      return url.split('//')[1].split('?')[0].replace(/[\/\.\:]/g, '_');
    } else {
      return url.split('?')[0].replace(/[\/\.\:]/g, '_');
    }
  };
  addFnc = function(url, fnc) {
    if (!ScrFnc.hasOwnProperty(codear(url))) {
      ScrFnc[codear(url)] = {
        state: true,
        fncs: []
      };
    }
    ScrFnc[codear(url)].fncs.push(fnc);
  };
  execFncs = function(url) {
    var i, json, _i, _len, _ref;
    ScrFnc[codear(url)].state = false;
    _ref = ScrFnc[codear(url)].fncs;
    for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
      json = _ref[i];
      if (ScrFnc[codear(url)].fncs[i] === "undefined") {
        log(ScrFnc[codear(url)].fncs[i]);
      }
      ScrFnc[codear(url)].fncs[i]();
    }
  };
  loadJs = function(url, fnc) {
    var scr;
    scr = document.createElement("script");
    scr.type = "text/javascript";
    if (scr.readyState) {
      scr.onreadystatechange = function() {
        if (scr.readyState === "loaded" || scr.readyState === "complete") {
          scr.onreadystatechange = null;
          fnc(url);
        }
      };
    } else {
      scr.onload = function() {
        fnc(url);
      };
    }
    scr.src = url;
    document.getElementsByTagName("head")[0].appendChild(scr);
  };
  loadCss = function(url, fnc) {
    var img, link;
    link = document.createElement('link');
    link.type = 'text/css';
    link.rel = 'stylesheet';
    link.href = url;
    document.getElementsByTagName('head')[0].appendChild(link);
    if (document.all) {
      link.onload = function() {
        fnc(url);
      };
    } else {
      img = document.createElement('img');
      img.onerror = function() {
        if (fnc) {
          fnc(url);
        }
        document.body.removeChild(this);
      };
      document.body.appendChild(img);
      img.src = url;
    }
  };
  return {
    charge: function(aUrl, fFnc, sMod, lev) {
      var THAT, isCss, isJs, parts, urlDir;
      THAT = this;
      if (aUrl.length === 0 || aUrl === "undefined" || aUrl === "") {
        return false;
      }
      if (aUrl.constructor.toString().indexOf('Array') !== -1 && aUrl.length === 1) {
        aUrl = aUrl[0];
      }
      lev = typeof lev !== 'number' ? 1 : lev;
      if (aUrl.constructor.toString().indexOf('String') !== -1) {
        isJs = aUrl.indexOf('.js') !== -1;
        isCss = aUrl.indexOf('.css') !== -1;
        if (!isJs && !isCss) {
          return false;
        }
        parts = aUrl.split('/');
        parts[parts.length - 1] = (yOSON.min !== 'undefined' && isJs ? yOSON.min : '') + parts[parts.length - 1];
        aUrl = parts.join('/');
        urlDir = isJs ? urlDirJs : urlDirCss;
        if (isJs || isCss) {
          aUrl = aUrl.indexOf('http') !== -1 ? aUrl + version : urlDir + aUrl + version + (isCss ? new Date().getTime() : '');
          if (!ScrFnc.hasOwnProperty(codear(aUrl))) {
            addFnc(aUrl, fFnc);
            if (isJs) {
              loadJs(aUrl, execFncs);
            } else {
              loadCss(aUrl, execFncs);
            }
          } else {
            if (ScrFnc[codear(aUrl)].state) {
              addFnc(aUrl, fFnc);
            } else {
              fFnc();
            }
          }
        }
      } else {
        if (aUrl.constructor.toString().indexOf('Array') !== -1) {
          this.charge(aUrl[0], function() {
            THAT.charge(yOSONUtils.remove(aUrl, 0), fFnc, sMod, lev + 2);
          }, sMod, lev + 1);
        } else {
          log(aUrl + ' - no es un Array');
        }
      }
    }
  };
})(yOSON.statHost, yOSON.statVers);

yOSON.AppCore = (function() {
  var debug, doInstance, oModules, oSandBox;
  oSandBox = new yOSON.AppSandbox();
  oModules = {};
  debug = false;
  window.cont = 0;
  doInstance = function(sModuleId) {
    var instance, method, name;
    instance = oModules[sModuleId].definition(oSandBox);
    if (!debug) {
      for (name in instance) {
        method = instance[name];
        if (typeof method === "function") {
          instance[name] = (function(name, method) {
            return function() {
              var ex;
              try {
                return method.apply(this, arguments);
              } catch (_error) {
                ex = _error;
                return log(name + "(): " + ex.message);
              }
            };
          })(name, method);
        }
      }
    }
    return instance;
  };
  return {
    addModule: function(sModuleId, fDefinition, aDep) {
      aDep = typeof aDep === 'undefined' ? [] : aDep;
      if (typeof oModules[sModuleId] === "undefined") {
        oModules[sModuleId] = {
          definition: fDefinition,
          instance: null,
          dependency: aDep
        };
      } else {
        throw "module '" + sModuleId + "' is already defined, Please set it again";
      }
    },
    getModule: function(sModuleId) {
      if (sModuleId && oModules[sModuleId]) {
        return oModules[sModuleId];
      } else {
        throw 'structureline58 param "sModuleId" is not defined or module not found';
      }
    },
    runModule: function(sModuleId, oParams) {
      var mod, thisInstance;
      if (oModules[sModuleId] !== void 0) {
        if (oParams === void 0) {
          oParams = {};
        }
        oParams.moduleName = sModuleId;
        mod = this.getModule(sModuleId);
        thisInstance = mod.instance = doInstance(sModuleId);
        if (thisInstance.hasOwnProperty('init')) {
          if (mod.dependency.length > 0) {
            yOSON.AppScript.charge(yOSONUtils.copy([], mod.dependency), function() {
              thisInstance.init(oParams);
            }, sModuleId + window.cont, 1);
          } else {
            thisInstance.init(oParams);
          }
        } else {
          throw ' ---> init function is not defined in the module "' + oModules[sModuleId] + '"';
        }
      } else {
        throw 'module "' + sModuleId + '" is not defined or module not found';
      }
    },
    runModules: function(aModuleIds) {
      var id, json;
      for (id in aModuleIds) {
        json = aModuleIds[id];
        this.runModule(json);
      }
    },
    chargeDepends: function(arrDeps, callback) {
      var copyArr;
      copyArr = yOSONUtils.copy([], arrDeps);
      return yOSON.AppScript.charge(copyArr, function() {
        return callback && callback();
      });
    }
  };
})();

yOSON.AppCore.addModule("dataTable", (function(Sb) {
  return {
    init: function(oParams) {
      var dataUrl, json, opts;
      dataUrl = oParams.url;
      opts = {
        "autoWidth": false,
        "ordering": false,
        "lengthChange": false,
        "pageLength": 10,
        "searching": true,
        "processing": true,
        "serverSide": true,
        "language": {
          "processing": "Procesando...",
          "lengthMenu": "Mostrar _MENU_ registros",
          "zeroRecords": "No se encontraron resultados",
          "emptyTable": "Ningún dato disponible en esta tabla",
          "info": "Mostrando _START_ de _END_ de un total de _TOTAL_ registros",
          "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
          "infoFiltered": "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix": "",
          "search": "Buscar:&nbsp;&nbsp;",
          "loadingRecords": "Cargando...",
          "paginate": {
            "first": "Primero",
            "previous": "Anterior",
            "next": "Siguiente",
            "last": "Último"
          }
        }
      };
      json = $.extend(opts, yOSON.datable[oParams.table]);
      window.instDataTable = $('#datatable').DataTable(json);
      return json.callbackRender && json.callbackRender(window.instDataTable);
    }
  };
}), ["plugins/jqDataTableB.js", "data/datatable.js"]);

yOSON.AppCore.addModule("lnkDelTable", (function(Sb) {
  var bindEvents, catchDom, dom, st, urlDelete;
  st = {
    "context": "#datatable",
    "lnkDel": ".lnkDel"
  };
  dom = {};
  urlDelete = "";
  catchDom = function() {
    return dom.context = $(st.context);
  };
  bindEvents = function() {
    return dom.context.on("click", st.lnkDel, function() {
      var $this, id;
      $this = $(this);
      id = $this.data("id");
      return $.ajax({
        "url": urlDelete,
        "data": {
          "id": id
        },
        "success": function(json) {
          if (parseFloat(json.state) === 1) {
            return window.instDataTable.draw();
          } else {
            return warn(json.msg);
          }
        }
      });
    });
  };
  return {
    init: function(oParams) {
      urlDelete = oParams.url;
      catchDom();
      return bindEvents();
    }
  };
}), ["plugins/jqDataTableB.js", "data/datatable.js"]);

yOSON.AppCore.addModule("validation", (function(Sb) {
  return {
    init: function(oParams) {
      var extraOpt, forms;
      forms = oParams.form.split(",");
      extraOpt = oParams.config;
      return $.each(forms, function(index, value) {
        var prop, settings;
        settings = {};
        value = $.trim(value);
        for (prop in yOSON.require[value]) {
          settings[prop] = yOSON.require[value][prop];
        }
        if (typeof extraOpt !== "undefined") {
          settings = $.extend(settings, extraOpt);
        }
        return $(value).validate(settings);
      });
    }
  };
}), ["data/require.js", "plugins/jqValidate.js"]);

yOSON.AppCore.addModule("modalBootstrap", (function(Sb) {
  var addValid, bindEvents, dispatchModal, evtModal, initEvent, modals, modalsTable;
  modals = [];
  modalsTable = [];
  bindEvents = function() {
    var dataTable, el, target, value, _i, _j, _len, _len1, _results;
    dataTable = $("#datatable");
    for (_i = 0, _len = modals.length; _i < _len; _i++) {
      value = modals[_i];
      el = $(value);
      if (el.length > 0) {
        initEvent(el, false);
      }
    }
    _results = [];
    for (_j = 0, _len1 = modalsTable.length; _j < _len1; _j++) {
      target = modalsTable[_j];
      _results.push(dataTable.on("click", target, function() {
        var $this;
        $this = $(this);
        return initEvent($this, true);
      }));
    }
    return _results;
  };
  initEvent = function(el, table) {
    var target, tpl;
    target = el.data("target");
    tpl = _.template($(target).html().replace(/[\n\r]/g, ""));
    if (!table) {
      return el.on("click", function() {
        return evtModal.call(this, tpl, target, table);
      });
    } else {
      return evtModal.call(el, tpl, target, table);
    }
  };
  evtModal = function(tpl, target, table) {
    var THIS, objTpl;
    THIS = this;
    if (yOSON.modal[target]) {
      if (yOSON.modal[target].template && !table) {
        return yOSON.modal[target].template.call(THIS, tpl, dispatchModal, target);
      } else if (yOSON.modal[target].templateTable && table) {
        return yOSON.modal[target].templateTable.call(THIS, tpl, dispatchModal, target);
      } else {
        objTpl = $(tpl());
        return dispatchModal(objTpl, target);
      }
    } else {
      objTpl = $(tpl());
      return dispatchModal(objTpl, target);
    }
  };
  addValid = function(frm) {
    var idForm;
    if (frm.length > 0) {
      idForm = "#" + frm.attr("id");
      return yOSON.AppCore.runModule('validation', {
        "form": idForm
      });
    }
  };
  dispatchModal = function(objTpl, target) {
    objTpl.modal({
      "keyboard": false
    });
    objTpl.on("shown.bs.modal", function() {
      addValid(objTpl.find("form"));
      if (yOSON.modal[target]) {
        return yOSON.modal[target].callback && yOSON.modal[target].callback(objTpl);
      }
    });
    return objTpl.on("hidden.bs.modal", function() {
      return objTpl.remove();
    });
  };
  return {
    init: function(oParams) {
      if (typeof oParams.modals !== "undefined") {
        modals = oParams.modals.split(",");
      }
      if (typeof oParams.modalsTable !== "undefined") {
        modalsTable = oParams.modalsTable.split(",");
      }
      if (modals.length > 0 || modalsTable.length > 0) {
        if (typeof oParams.chargeDepends !== "undefined") {
          return yOSON.AppCore.chargeDepends(oParams.chargeDepends, function() {
            return bindEvents();
          });
        } else {
          return bindEvents();
        }
      }
    }
  };
}), ["plugins/jqUnderscore.js", "data/modal.js", "data/require.js", "plugins/jqValidate.js", "plugins/jqUI.js"]);

yOSON.AppCore.addModule("uploadImg", (function(Sb) {
  var bindEvents, catchDom, dom, flagContent, st, urlFile;
  st = {
    "fileUpload": ".img-profile",
    "ctnImg": ".img-profile img",
    "inpt": "#picture"
  };
  dom = {};
  urlFile = "";
  flagContent = true;
  catchDom = function() {
    dom.fileUpload = $(st.fileUpload);
    dom.ctnImg = $(st.ctnImg);
    return dom.inpt = $(st.inpt);
  };
  bindEvents = function() {
    var json;
    json = {
      "areaFile": st.fileUpload,
      "html5": true,
      "routeFile": urlFile,
      "limitFiles": 1,
      "beforeCharge": function(el) {
        return utils.loader(dom.fileUpload, true);
      },
      "success": function(json, el) {
        utils.loader(dom.fileUpload, false);
        if (parseFloat(json.state) === 1) {
          dom.ctnImg.attr("src", yOSON.eleHost + json.img);
          return dom.inpt.val(json.nameImg);
        } else {
          return warn(json.msg);
        }
      },
      "errorValid": function(state, msg) {
        utils.loader(dom.fileUpload, false);
        return warn(msg);
      }
    };
    if (flagContent) {
      json.content = st.fileUpload;
    } else {
      json.content = ".modal-content";
    }
    return $.jqFile(json);
  };
  return {
    init: function(oParams) {
      urlFile = oParams.url;
      if (typeof oParams.content !== "undefined") {
        flagContent = oParams.content;
      }
      catchDom();
      return bindEvents();
    }
  };
}), ["plugins/jqFile.js"]);

yOSON.AppCore.addModule("uploadImg2", (function(Sb) {
  var bindEvents, dom, files, flagContent, urlFile;
  dom = {};
  files = [];
  urlFile = "";
  flagContent = true;
  bindEvents = function() {
    var file, json, _i, _len, _results;
    json = {
      "html5": true,
      "routeFile": urlFile,
      "limitFiles": 1,
      "beforeCharge": function(el) {
        return utils.loader(el, true);
      },
      "success": function(json, el) {
        var target;
        console.log(json);
        console.log(el);
        utils.loader(el, false);
        if (parseFloat(json.state) === 1) {
          target = $(el.data("target"));
          el.find("img").attr("src", yOSON.eleHost + json.img);
          return target.val(json.nameImg);
        } else {
          return warn(json.msg);
        }
      },
      "errorValid": function(state, msg) {
        return warn(msg);
      }
    };
    if (flagContent) {
      json.content = st.fileUpload;
    } else {
      json.content = ".modal-content";
    }
    _results = [];
    for (_i = 0, _len = files.length; _i < _len; _i++) {
      file = files[_i];
      json.areaFile = file;
      _results.push($.jqFile(json));
    }
    return _results;
  };
  return {
    init: function(oParams) {
      urlFile = oParams.url;
      if (typeof oParams.content !== "undefined") {
        flagContent = oParams.content;
      }
      if (typeof oParams.modals !== "undefined") {
        files = oParams.modals;
      }
      return bindEvents();
    }
  };
}), ["plugins/jqFile.js"]);

yOSON.AppCore.addModule("parseSchedule", (function(Sb) {
  var bindEvents, catchDom, dom, fnParser, st;
  st = {
    "inpts": ".parseSchedule",
    "btn": ".btnParseS"
  };
  dom = {};
  catchDom = function() {
    dom.inpts = $(st.inpts);
    return dom.btn = $(st.btn);
  };
  bindEvents = function() {
    return dom.btn.on("click", function() {
      return dom.inpts.each(function() {
        var $this, valInpt;
        $this = $(this);
        valInpt = $.trim($this.val());
        if (valInpt !== "") {
          valInpt = fnParser(valInpt);
        }
        return $this.val(valInpt);
      });
    });
  };
  fnParser = function(value) {
    var arrValue, f, h, m, s, val, _i, _len;
    if (value.match(/-|\/|\|/) === null) {
      value = value.replace(/pm\s+/gi, 'pm -');
    }
    value = value.replace(/(\s+pm\s+|\s+pm|pm\s+|pm|\s|\((\w+)\))/gi, '');
    value = $.trim(value.replace(/(\/|\|)/gi, '-'));
    arrValue = value.split(/-/);
    h = [];
    for (_i = 0, _len = arrValue.length; _i < _len; _i++) {
      val = arrValue[_i];
      s = val.split(/:/);
      m = s[0].match(/^(\d){1}$/);
      if (m !== null) {
        h.push('0' + m[0] + ':' + s[1]);
      } else {
        h.push(s[0] + ':' + s[1]);
      }
    }
    f = h.join(' pm - ');
    f = f + ' pm';
    return f;
  };
  return {
    init: function(oParams) {
      catchDom();
      return bindEvents();
    }
  };
}));

yOSON.AppSchema.modules = {
  'default': {
    controllers: {
      'profile': {
        actions: {
          'index': function() {
            yOSON.AppCore.runModule('uploadImg', {
              "url": "/profile/image?folder=admin"
            });
            yOSON.AppCore.runModule('validation', {
              "form": "#frmProfile"
            });
          },
          'byDefault': function() {}
        },
        allActions: function() {}
      },
      'company': {
        actions: {
          'index': function() {
            yOSON.AppCore.runModule('dataTable', {
              "table": "#tblCompany"
            });
            yOSON.AppCore.runModule('lnkDelTable', {
              "url": "/company/delete"
            });
            yOSON.AppCore.runModule('modalBootstrap', {
              "modals": "#lnkCompany",
              "modalsTable": ".lnkEdit",
              "chargeDepends": ["plugins/jqFile.js"]
            });
          },
          'byDefault': function() {}
        },
        allActions: function() {}
      },
      'subsidiary': {
        actions: {
          'index': function() {
            yOSON.AppCore.runModule('dataTable', {
              "table": "#tblSubsidiary"
            });
            yOSON.AppCore.runModule('lnkDelTable', {
              "url": "/subsidiary/delete"
            });
            yOSON.AppCore.runModule('modalBootstrap', {
              "modals": "#lnkSubsidiary",
              "modalsTable": ".lnkEdit"
            });
          },
          'byDefault': function() {}
        },
        allActions: function() {}
      },
      'genre': {
        actions: {
          'index': function() {
            yOSON.AppCore.runModule('dataTable', {
              "table": "#tblGenre"
            });
            yOSON.AppCore.runModule('lnkDelTable', {
              "url": "/genre/delete"
            });
            yOSON.AppCore.runModule('modalBootstrap', {
              "modals": "#lnkGenre",
              "modalsTable": ".lnkEdit"
            });
          },
          'byDefault': function() {}
        },
        allActions: function() {}
      },
      'ads': {
        actions: {
          'index': function() {
            yOSON.AppCore.runModule('dataTable', {
              "table": "#tblAds"
            });
            yOSON.AppCore.runModule('lnkDelTable', {
              "url": "/ads/delete"
            });
            yOSON.AppCore.runModule('modalBootstrap', {
              "modals": "#lnkAds",
              "modalsTable": ".lnkEdit",
              "chargeDepends": ["plugins/jqFile.js"]
            });
          },
          'byDefault': function() {}
        },
        allActions: function() {}
      },
      'movie': {
        actions: {
          'index': function() {
            yOSON.AppCore.runModule('dataTable', {
              "table": "#tblMovie"
            });
            yOSON.AppCore.runModule('lnkDelTable', {
              "url": "/movie/delete"
            });
            yOSON.AppCore.runModule('modalBootstrap', {
              "modals": "#lnkMovie",
              "modalsTable": ".lnkEdit",
              "chargeDepends": ["plugins/jqFile.js"]
            });
          },
          'byDefault': function() {}
        },
        allActions: function() {}
      },
      'billboard': {
        actions: {
          'index': function() {
            yOSON.AppCore.runModule('dataTable', {
              "table": "#tblBillboard"
            });
            yOSON.AppCore.runModule('lnkDelTable', {
              "url": "/billboard/delete"
            });
            yOSON.AppCore.runModule('modalBootstrap', {
              "modals": "#lnkBillboard",
              "modalsTable": ".lnkEdit"
            });
          },
          'byDefault': function() {}
        },
        allActions: function() {}
      },
      'price': {
        actions: {
          'index': function() {
            yOSON.AppCore.runModule('dataTable', {
              "table": "#tblPrice"
            });
            yOSON.AppCore.runModule('lnkDelTable', {
              "url": "/price/delete"
            });
            yOSON.AppCore.runModule('modalBootstrap', {
              "modals": "#lnkPrice",
              "modalsTable": ".lnkEdit"
            });
          },
          'byDefault': function() {}
        },
        allActions: function() {}
      },
      byDefault: function() {},
      allActions: function() {}
    },
    byDefault: function() {},
    allControllers: function() {}
  },
  byDefault: function() {},
  allModules: function() {}
};

var acti, ctrl, modu;

modu = yOSON.modulo;

ctrl = yOSON.controller;

acti = yOSON.action;

log('==> mod:' + modu + ' - ctrl:' + ctrl + ' - acti:' + acti);

yOSON.AppSchema.modules.allModules();

if (modu === '' || !yOSON.AppSchema.modules.hasOwnProperty(modu)) {
  yOSON.AppSchema.modules.byDefault();
} else {
  yOSON.AppSchema.modules[modu].allControllers();
  if (ctrl === '' || !yOSON.AppSchema.modules[modu].controllers.hasOwnProperty(ctrl)) {
    yOSON.AppSchema.modules[modu].controllers.byDefault();
  } else {
    yOSON.AppSchema.modules[modu].controllers[ctrl].allActions();
    if (acti === '' || !yOSON.AppSchema.modules[modu].controllers[ctrl].actions.hasOwnProperty(acti)) {
      yOSON.AppSchema.modules[modu].controllers[ctrl].actions.byDefault();
    } else {
      yOSON.AppSchema.modules[modu].controllers[ctrl].actions[acti]();
    }
  }
}
