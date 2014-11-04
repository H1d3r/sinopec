var TableTools;
(function($, window, document) {
TableTools = function( oDT, oOpts )
{
	if ( ! this instanceof TableTools )
	{
		alert( "Warning: TableTools must be initialised with the keyword 'new'" );
	}
	this.s = {
		"that": this,
		"dt": oDT.fnSettings(),
		"print": {
		  "saveStart": -1,
		  "saveLength": -1,
		  "saveScroll": -1,
		  "funcEnd": function () {}
	  },	
	  "buttonCounter": 0,
		"select": {
			"type": "",
			"selected": [],
			"preRowSelect": null,
			"postSelected": null,
			"postDeselected": null,
			"all": false,
			"selectedClass": ""
		},
		"custom": {},
		"swfPath": "",
		"buttonSet": [],
		"master": false,
		"tags": {}
	};
	this.dom = {
		"container": null,
		"table": null,
		"print": {
		  "hidden": [],
		  "message": null
	  },
		"collection": {
			"collection": null,
			"background": null
		}
	};
	this.classes = $.extend( true, {}, TableTools.classes );
	if ( this.s.dt.bJUI )
	{
		$.extend( true, this.classes, TableTools.classes_themeroller );
	}
	this.fnSettings = function () {
		return this.s;
	};
	if ( typeof oOpts == 'undefined' )
	{
		oOpts = {};
	}
	this._fnConstruct( oOpts );
	return this;
};
TableTools.prototype = {
	"fnGetSelected": function ()
	{
		var out=[];
		var data=this.s.dt.aoData;
		var i, iLen;
		for ( i=0, iLen=data.length ; i<iLen ; i++ )
		{
			if ( data[i]._DTTT_selected )
			{
				out.push( data[i].nTr );
			}
		}
		return out;
	},
	"fnGetSelectedData": function ()
	{
		var out = [];
		var data=this.s.dt.aoData;
		var i, iLen;

		for ( i=0, iLen=data.length ; i<iLen ; i++ )
		{
			if ( data[i]._DTTT_selected )
			{
				out.push( this.s.dt.oInstance.fnGetData(i) );
			}
		}
		return out;
	},	
	"fnIsSelected": function ( n )
	{
		var pos = this.s.dt.oInstance.fnGetPosition( n );
		return (this.s.dt.aoData[pos]._DTTT_selected===true) ? true : false;
	},
	"fnSelectAll": function ( filtered )
	{
		var s = this._fnGetMasterSettings();
		
		this._fnRowSelect( (filtered === true) ?
			s.dt.aiDisplay :
			s.dt.aoData
		);
	},

	"fnSelectNone": function ( filtered )
	{
		var s = this._fnGetMasterSettings();

		this._fnRowDeselect( (filtered === true) ?
			s.dt.aiDisplay :
			s.dt.aoData
		);
	},
	"fnSelect": function ( n )
	{
		if ( this.s.select.type == "single" )
		{
			this.fnSelectNone();
			this._fnRowSelect( n );
		}
		else if ( this.s.select.type == "multi" )
		{
			this._fnRowSelect( n );
		}
	},
	"fnDeselect": function ( n )
	{
		this._fnRowDeselect( n );
	},
	"fnGetTitle": function( oConfig )
	{
		var sTitle = "";
		if ( typeof oConfig.sTitle != 'undefined' && oConfig.sTitle !== "" ) {
			sTitle = oConfig.sTitle;
		} else {
			var anTitle = document.getElementsByTagName('title');
			if ( anTitle.length > 0 )
			{
				sTitle = anTitle[0].innerHTML;
			}
		}
		if ( "\u00A1".toString().length < 4 ) {
			return sTitle.replace(/[^a-zA-Z0-9_\u00A1-\uFFFF\.,\-_ !\(\)]/g, "");
		} else {
			return sTitle.replace(/[^a-zA-Z0-9_\.,\-_ !\(\)]/g, "");
		}
	},
	"fnCalcColRatios": function ( oConfig )
	{
		var
			aoCols = this.s.dt.aoColumns,
			aColumnsInc = this._fnColumnTargets( oConfig.mColumns ),
			aColWidths = [],
			iWidth = 0, iTotal = 0, i, iLen;
		
		for ( i=0, iLen=aColumnsInc.length ; i<iLen ; i++ )
		{
			if ( aColumnsInc[i] )
			{
				iWidth = aoCols[i].nTh.offsetWidth;
				iTotal += iWidth;
				aColWidths.push( iWidth );
			}
		}
		
		for ( i=0, iLen=aColWidths.length ; i<iLen ; i++ )
		{
			aColWidths[i] = aColWidths[i] / iTotal;
		}
		
		return aColWidths.join('\t');
	},
	"fnGetTableData": function ( oConfig )
	{
		if ( this.s.dt )
		{
			return this._fnGetDataTablesData( oConfig );
		}
	},
	"fnSetText": function ( clip, text )
	{
		this._fnFlashSetText( clip, text );
	},
	"fnResizeButtons": function ()
	{
		for ( var cli in ZeroClipboard_TableTools.clients )
		{
			if ( cli )
			{
				var client = ZeroClipboard_TableTools.clients[cli];
				if ( typeof client.domElement != 'undefined' &&
					 client.domElement.parentNode )
				{
					client.positionElement();
				}
			}
		}
	},
	"fnResizeRequired": function ()
	{
		for ( var cli in ZeroClipboard_TableTools.clients )
		{
			if ( cli )
			{
				var client = ZeroClipboard_TableTools.clients[cli];
				if ( typeof client.domElement != 'undefined' &&
					 client.domElement.parentNode == this.dom.container &&
					 client.sized === false )
				{
					return true;
				}
			}
		}
		return false;
	},
	"fnPrint": function ( bView, oConfig )
	{
		if ( oConfig === undefined )
		{
			oConfig = {};
		}

		if ( bView === undefined || bView )
		{
			this._fnPrintStart( oConfig );
		}
		else
		{
			this._fnPrintEnd();
		}
	},
	"fnInfo": function ( message, time ) {
		var nInfo = document.createElement( "div" );
		nInfo.className = this.classes.print.info;
		nInfo.innerHTML = message;

		document.body.appendChild( nInfo );
		
		setTimeout( function() {
			$(nInfo).fadeOut( "normal", function() {
				document.body.removeChild( nInfo );
			} );
		}, time );
	},
	"_fnConstruct": function ( oOpts )
	{
		var that = this;
		
		this._fnCustomiseSettings( oOpts );
		this.dom.container = document.createElement( this.s.tags.container );
		this.dom.container.className = this.classes.container;
		if ( this.s.select.type != 'none' )
		{
			this._fnRowSelectConfig();
		}
		this._fnButtonDefinations( this.s.buttonSet, this.dom.container );
		this.s.dt.aoDestroyCallback.push( {
			"sName": "TableTools",
			"fn": function () {
				that.dom.container.innerHTML = "";
			}
		} );
	},
	"_fnCustomiseSettings": function ( oOpts )
	{
		if ( typeof this.s.dt._TableToolsInit == 'undefined' )
		{
			this.s.master = true;
			this.s.dt._TableToolsInit = true;
		}
		this.dom.table = this.s.dt.nTable;
		this.s.custom = $.extend( {}, TableTools.DEFAULTS, oOpts );
		this.s.swfPath = this.s.custom.sSwfPath;
		if ( typeof ZeroClipboard_TableTools != 'undefined' )
		{
			ZeroClipboard_TableTools.moviePath = this.s.swfPath;
		}
		this.s.select.type = this.s.custom.sRowSelect;
		this.s.select.preRowSelect = this.s.custom.fnPreRowSelect;
		this.s.select.postSelected = this.s.custom.fnRowSelected;
		this.s.select.postDeselected = this.s.custom.fnRowDeselected;
		if ( this.s.custom.sSelectedClass )
		{
			this.classes.select.row = this.s.custom.sSelectedClass;
		}
		this.s.tags = this.s.custom.oTags;
		this.s.buttonSet = this.s.custom.aButtons;
	},
	"_fnButtonDefinations": function ( buttonSet, wrapper )
	{
		var buttonDef;
		
		for ( var i=0, iLen=buttonSet.length ; i<iLen ; i++ )
		{
			if ( typeof buttonSet[i] == "string" )
			{
				if ( typeof TableTools.BUTTONS[ buttonSet[i] ] == 'undefined' )
				{
					alert( "TableTools: Warning - unknown button type: "+buttonSet[i] );
					continue;
				}
				buttonDef = $.extend( {}, TableTools.BUTTONS[ buttonSet[i] ], true );
			}
			else
			{
				if ( typeof TableTools.BUTTONS[ buttonSet[i].sExtends ] == 'undefined' )
				{
					alert( "TableTools: Warning - unknown button type: "+buttonSet[i].sExtends );
					continue;
				}
				var o = $.extend( {}, TableTools.BUTTONS[ buttonSet[i].sExtends ], true );
				buttonDef = $.extend( o, buttonSet[i], true );
			}
			
			wrapper.appendChild( this._fnCreateButton( 
				buttonDef, 
				$(wrapper).hasClass(this.classes.collection.container)
			) );
		}
	},
	"_fnCreateButton": function ( oConfig, bCollectionButton )
	{
	  var nButton = this._fnButtonBase( oConfig, bCollectionButton );
		
		if ( oConfig.sAction.match(/flash/) )
		{
			this._fnFlashConfig( nButton, oConfig );
		}
		else if ( oConfig.sAction == "text" )
		{
			this._fnTextConfig( nButton, oConfig );
		}
		else if ( oConfig.sAction == "div" )
		{
			this._fnTextConfig( nButton, oConfig );
		}
		else if ( oConfig.sAction == "collection" )
		{
			this._fnTextConfig( nButton, oConfig );
			this._fnCollectionConfig( nButton, oConfig );
		}
		
		return nButton;
	},
	"_fnButtonBase": function ( o, bCollectionButton )
	{
		var sTag, sLiner, sClass;

		if ( bCollectionButton )
		{
			sTag = o.sTag !== "default" ? o.sTag : this.s.tags.collection.button;
			sLiner = o.sLinerTag !== "default" ? o.sLiner : this.s.tags.collection.liner;
			sClass = this.classes.collection.buttons.normal;
		}
		else
		{
			sTag = o.sTag !== "default" ? o.sTag : this.s.tags.button;
			sLiner = o.sLinerTag !== "default" ? o.sLiner : this.s.tags.liner;
			sClass = this.classes.buttons.normal;
		}
		var
		  nButton = document.createElement( sTag ),
		  nSpan = document.createElement( sLiner ),
		  masterS = this._fnGetMasterSettings();
		
		nButton.className = sClass+" "+o.sButtonClass;
		nButton.setAttribute('id', "ToolTables_"+this.s.dt.sInstance+"_"+masterS.buttonCounter );
		nButton.appendChild( nSpan );
		nSpan.innerHTML = o.sButtonText;
		
		masterS.buttonCounter++;
		
		return nButton;
	},
	"_fnGetMasterSettings": function ()
	{
		if ( this.s.master )
		{
			return this.s;
		}
		else
		{
			var instances = TableTools._aInstances;
			for ( var i=0, iLen=instances.length ; i<iLen ; i++ )
			{
				if ( this.dom.table == instances[i].s.dt.nTable )
				{
					return instances[i].s;
				}
			}
		}
	},
	"_fnCollectionConfig": function ( nButton, oConfig )
	{
		var nHidden = document.createElement( this.s.tags.collection.container );
		nHidden.style.display = "none";
		nHidden.className = this.classes.collection.container;
		oConfig._collection = nHidden;
		document.body.appendChild( nHidden );
		
		this._fnButtonDefinations( oConfig.aButtons, nHidden );
	},
	"_fnCollectionShow": function ( nButton, oConfig )
	{
		var
			that = this,
			oPos = $(nButton).offset(),
			nHidden = oConfig._collection,
			iDivX = oPos.left,
			iDivY = oPos.top + $(nButton).outerHeight(),
			iWinHeight = $(window).height(), iDocHeight = $(document).height(),
		 	iWinWidth = $(window).width(), iDocWidth = $(document).width();
		
		nHidden.style.position = "absolute";
		nHidden.style.left = iDivX+"px";
		nHidden.style.top = iDivY+"px";
		nHidden.style.display = "block";
		$(nHidden).css('opacity',0);
		var nBackground = document.createElement('div');
		nBackground.style.position = "absolute";
		nBackground.style.left = "0px";
		nBackground.style.top = "0px";
		nBackground.style.height = ((iWinHeight>iDocHeight)? iWinHeight : iDocHeight) +"px";
		nBackground.style.width = ((iWinWidth>iDocWidth)? iWinWidth : iDocWidth) +"px";
		nBackground.className = this.classes.collection.background;
		$(nBackground).css('opacity',0);
		document.body.appendChild( nBackground );
		document.body.appendChild( nHidden );
		var iDivWidth = $(nHidden).outerWidth();
		var iDivHeight = $(nHidden).outerHeight();
		if ( iDivX + iDivWidth > iDocWidth )
		{
			nHidden.style.left = (iDocWidth-iDivWidth)+"px";
		}
		if ( iDivY + iDivHeight > iDocHeight )
		{
			nHidden.style.top = (iDivY-iDivHeight-$(nButton).outerHeight())+"px";
		}
		this.dom.collection.collection = nHidden;
		this.dom.collection.background = nBackground;
		setTimeout( function () {
			$(nHidden).animate({"opacity": 1}, 500);
			$(nBackground).animate({"opacity": 0.25}, 500);
		}, 10 );
		this.fnResizeButtons();
		$(nBackground).click( function () {
			that._fnCollectionHide.call( that, null, null );
		} );
	},
	"_fnCollectionHide": function ( nButton, oConfig )
	{
		if ( oConfig !== null && oConfig.sExtends == 'collection' )
		{
			return;
		}
		
		if ( this.dom.collection.collection !== null )
		{
			$(this.dom.collection.collection).animate({"opacity": 0}, 500, function (e) {
				this.style.display = "none";
			} );
			
			$(this.dom.collection.background).animate({"opacity": 0}, 500, function (e) {
				this.parentNode.removeChild( this );
			} );
			
			this.dom.collection.collection = null;
			this.dom.collection.background = null;
		}
	},
	"_fnRowSelectConfig": function ()
	{
		if ( this.s.master )
		{
			var
				that = this, 
				i, iLen, 
				dt = this.s.dt,
				aoOpenRows = this.s.dt.aoOpenRows;
			
			$(dt.nTable).addClass( this.classes.select.table );
			
			$('tr', dt.nTBody).live( 'click', function(e) {
				if ( this.parentNode != dt.nTBody )
				{
					return;
				}
				if ( dt.oInstance.fnGetData(this) === null )
				{
				    return;
				}
				if ( that.s.select.preRowSelect !== null && !that.s.select.preRowSelect.call(that, e) )
				{
					return;
				}

				if ( that.fnIsSelected( this ) )
				{
					that._fnRowDeselect( this );
				}
				else if ( that.s.select.type == "single" )
				{
					that.fnSelectNone();
					that._fnRowSelect( this );
				}
				else if ( that.s.select.type == "multi" )
				{
					that._fnRowSelect( this );
				}
			} );
			dt.oApi._fnCallbackReg( dt, 'aoRowCreatedCallback', function (tr, data, index) {
				if ( dt.aoData[index]._DTTT_selected ) {
					$(tr).addClass( that.classes.select.row );
				}
			}, 'TableTools-SelectAll' );
		}
	},
	"_fnRowSelect": function ( src )
	{
		var data = this._fnSelectData( src );
		var firstTr = data.length===0 ? null : data[0].nTr;

		for ( var i=0, iLen=data.length ; i<iLen ; i++ )
		{
			data[i]._DTTT_selected = true;

			if ( data[i].nTr )
			{
				$(data[i].nTr).addClass( this.classes.select.row );
			}
		}

		if ( this.s.select.postSelected !== null )
		{
			this.s.select.postSelected.call( this, firstTr );
		}

		TableTools._fnEventDispatch( this, 'select', firstTr );
	},
	"_fnRowDeselect": function ( src )
	{
		var data = this._fnSelectData( src );
		var firstTr = data.length===0 ? null : data[0].nTr;

		for ( var i=0, iLen=data.length ; i<iLen ; i++ )
		{
			if ( data[i].nTr && data[i]._DTTT_selected )
			{
				$(data[i].nTr).removeClass( this.classes.select.row );
			}

			data[i]._DTTT_selected = false;
		}

		if ( this.s.select.postDeselected !== null )
		{
			this.s.select.postDeselected.call( this, firstTr );
		}

		TableTools._fnEventDispatch( this, 'select', firstTr );
	},
	"_fnSelectData": function ( src )
	{
		var out = [], pos, i, iLen;

		if ( src.nodeName )
		{
			pos = this.s.dt.oInstance.fnGetPosition( src );
			out.push( this.s.dt.aoData[pos] );
		}
		else if ( typeof src.length !== 'undefined' )
		{
			for ( i=0, iLen=src.length ; i<iLen ; i++ )
			{
				if ( src[i].nodeName )
				{
					pos = this.s.dt.oInstance.fnGetPosition( src[i] );
					out.push( this.s.dt.aoData[pos] );
				}
				else if ( typeof src[i] === 'number' )
				{
					out.push( this.s.dt.aoData[ src[i] ] );
				}
				else
				{
					out.push( src[i] );
				}
			}

			return out;
		}
		else
		{
			out.push( src );
		}

		return out;
	},
	"_fnTextConfig": function ( nButton, oConfig )
	{
		var that = this;
		
		if ( oConfig.fnInit !== null )
		{
			oConfig.fnInit.call( this, nButton, oConfig );
		}
		
		if ( oConfig.sToolTip !== "" )
		{
			nButton.title = oConfig.sToolTip;
		}
		
		$(nButton).hover( function () {
			if ( oConfig.fnMouseover !== null )
			{
				oConfig.fnMouseover.call( this, nButton, oConfig, null );
			}
		}, function () {
			if ( oConfig.fnMouseout !== null )
			{
				oConfig.fnMouseout.call( this, nButton, oConfig, null );
			}
		} );
		
		if ( oConfig.fnSelect !== null )
		{
			TableTools._fnEventListen( this, 'select', function (n) {
				oConfig.fnSelect.call( that, nButton, oConfig, n );
			} );
		}
		
		$(nButton).click( function (e) {
			if ( oConfig.fnClick !== null )
			{
				oConfig.fnClick.call( that, nButton, oConfig, null );
			}
			if ( oConfig.fnComplete !== null )
			{
				oConfig.fnComplete.call( that, nButton, oConfig, null, null );
			}
			
			that._fnCollectionHide( nButton, oConfig );
		} );
	},
	"_fnFlashConfig": function ( nButton, oConfig )
	{
		var that = this;
		var flash = new ZeroClipboard_TableTools.Client();
		
		if ( oConfig.fnInit !== null )
		{
			oConfig.fnInit.call( this, nButton, oConfig );
		}
		
		flash.setHandCursor( true );
		
		if ( oConfig.sAction == "flash_save" )
		{
			flash.setAction( 'save' );
			flash.setCharSet( (oConfig.sCharSet=="utf16le") ? 'UTF16LE' : 'UTF8' );
			flash.setBomInc( oConfig.bBomInc );
			flash.setFileName( oConfig.sFileName.replace('*', this.fnGetTitle(oConfig)) );
		}
		else if ( oConfig.sAction == "flash_pdf" )
		{
			flash.setAction( 'pdf' );
			flash.setFileName( oConfig.sFileName.replace('*', this.fnGetTitle(oConfig)) );
		}
		else
		{
			flash.setAction( 'copy' );
		}
		
		flash.addEventListener('mouseOver', function(client) {
			if ( oConfig.fnMouseover !== null )
			{
				oConfig.fnMouseover.call( that, nButton, oConfig, flash );
			}
		} );
		
		flash.addEventListener('mouseOut', function(client) {
			if ( oConfig.fnMouseout !== null )
			{
				oConfig.fnMouseout.call( that, nButton, oConfig, flash );
			}
		} );
		
		flash.addEventListener('mouseDown', function(client) {
			if ( oConfig.fnClick !== null )
			{
				oConfig.fnClick.call( that, nButton, oConfig, flash );
			}
		} );
		
		flash.addEventListener('complete', function (client, text) {
			if ( oConfig.fnComplete !== null )
			{
				oConfig.fnComplete.call( that, nButton, oConfig, flash, text );
			}
			that._fnCollectionHide( nButton, oConfig );
		} );
		
		this._fnFlashGlue( flash, nButton, oConfig.sToolTip );
	},
	"_fnFlashGlue": function ( flash, node, text )
	{
		var that = this;
		var id = node.getAttribute('id');
		
		if ( document.getElementById(id) )
		{
			flash.glue( node, text );
		}
		else
		{
			setTimeout( function () {
				that._fnFlashGlue( flash, node, text );
			}, 100 );
		}
	},
	"_fnFlashSetText": function ( clip, sData )
	{
		var asData = this._fnChunkData( sData, 8192 );
		
		clip.clearText();
		for ( var i=0, iLen=asData.length ; i<iLen ; i++ )
		{
			clip.appendText( asData[i] );
		}
	},
	"_fnColumnTargets": function ( mColumns )
	{
		var aColumns = [];
		var dt = this.s.dt;
		
		if ( typeof mColumns == "object" )
		{
			for ( i=0, iLen=dt.aoColumns.length ; i<iLen ; i++ )
			{
				aColumns.push( false );
			}
			
			for ( i=0, iLen=mColumns.length ; i<iLen ; i++ )
			{
				aColumns[ mColumns[i] ] = true;
			}
		}
		else if ( mColumns == "visible" )
		{
			for ( i=0, iLen=dt.aoColumns.length ; i<iLen ; i++ )
			{
				aColumns.push( dt.aoColumns[i].bVisible ? true : false );
			}
		}
		else if ( mColumns == "hidden" )
		{
			for ( i=0, iLen=dt.aoColumns.length ; i<iLen ; i++ )
			{
				aColumns.push( dt.aoColumns[i].bVisible ? false : true );
			}
		}
		else if ( mColumns == "sortable" )
		{
			for ( i=0, iLen=dt.aoColumns.length ; i<iLen ; i++ )
			{
				aColumns.push( dt.aoColumns[i].bSortable ? true : false );
			}
		}
		else 
		{
			for ( i=0, iLen=dt.aoColumns.length ; i<iLen ; i++ )
			{
				aColumns.push( true );
			}
		}
		
		return aColumns;
	},
	"_fnNewline": function ( oConfig )
	{
		if ( oConfig.sNewLine == "auto" )
		{
			return navigator.userAgent.match(/Windows/) ? "\r\n" : "\n";
		}
		else
		{
			return oConfig.sNewLine;
		}
	},
	"_fnGetDataTablesData": function ( oConfig )
	{
		var i, iLen, j, jLen;
		var aRow, aData=[], sLoopData='', arr;
		var dt = this.s.dt, tr, child;
		var regex = new RegExp(oConfig.sFieldBoundary, "g"); /* Do it here for speed */
		var aColumnsInc = this._fnColumnTargets( oConfig.mColumns );
		var bSelectedOnly = (typeof oConfig.bSelectedOnly != 'undefined') ? oConfig.bSelectedOnly : false;
		if ( oConfig.bHeader )
		{
			aRow = [];
			
			for ( i=0, iLen=dt.aoColumns.length ; i<iLen ; i++ )
			{
				if ( aColumnsInc[i] )
				{
					sLoopData = dt.aoColumns[i].sTitle.replace(/\n/g," ").replace( /<.*?>/g, "" ).replace(/^\s+|\s+$/g,"");
					sLoopData = this._fnHtmlDecode( sLoopData );
					
					aRow.push( this._fnBoundData( sLoopData, oConfig.sFieldBoundary, regex ) );
				}
			}

			aData.push( aRow.join(oConfig.sFieldSeperator) );
		}
		var aDataIndex = dt.aiDisplay;
		var aSelected = this.fnGetSelected();
		if ( this.s.select.type !== "none" && bSelectedOnly && aSelected.length !== 0 )
		{
			aDataIndex = [];
			for ( i=0, iLen=aSelected.length ; i<iLen ; i++ )
			{
				aDataIndex.push( dt.oInstance.fnGetPosition( aSelected[i] ) );
			}
		}
		
		for ( j=0, jLen=aDataIndex.length ; j<jLen ; j++ )
		{
			tr = dt.aoData[ aDataIndex[j] ].nTr;
			aRow = [];
			for ( i=0, iLen=dt.aoColumns.length ; i<iLen ; i++ )
			{
				if ( aColumnsInc[i] )
				{
					var mTypeData = dt.oApi._fnGetCellData( dt, aDataIndex[j], i, 'display' );
					if ( oConfig.fnCellRender )
					{
						sLoopData = oConfig.fnCellRender( mTypeData, i, tr, aDataIndex[j] )+"";
					}
					else if ( typeof mTypeData == "string" )
					{
						sLoopData = mTypeData.replace(/\n/g," ");
						sLoopData =
						 	sLoopData.replace(/<img.*?\s+alt\s*=\s*(?:"([^"]+)"|'([^']+)'|([^\s>]+)).*?>/gi,
						 		'$1$2$3');
						sLoopData = sLoopData.replace( /<.*?>/g, "" );
					}
					else
					{
						sLoopData = mTypeData+"";
					}
					sLoopData = sLoopData.replace(/^\s+/, '').replace(/\s+$/, '');
					sLoopData = this._fnHtmlDecode( sLoopData );
					aRow.push( this._fnBoundData( sLoopData, oConfig.sFieldBoundary, regex ) );
				}
			}
      
			aData.push( aRow.join(oConfig.sFieldSeperator) );
			if ( oConfig.bOpenRows )
			{
				arr = $.grep(dt.aoOpenRows, function(o) { return o.nParent === tr; });
				
				if ( arr.length === 1 )
				{
					sLoopData = this._fnBoundData( $('td', arr[0].nTr).html(), oConfig.sFieldBoundary, regex );
					aData.push( sLoopData );
				}
			}
		}
		if ( oConfig.bFooter && dt.nTFoot !== null )
		{
			aRow = [];
			
			for ( i=0, iLen=dt.aoColumns.length ; i<iLen ; i++ )
			{
				if ( aColumnsInc[i] && dt.aoColumns[i].nTf !== null )
				{
					sLoopData = dt.aoColumns[i].nTf.innerHTML.replace(/\n/g," ").replace( /<.*?>/g, "" );
					sLoopData = this._fnHtmlDecode( sLoopData );
					
					aRow.push( this._fnBoundData( sLoopData, oConfig.sFieldBoundary, regex ) );
				}
			}
			
			aData.push( aRow.join(oConfig.sFieldSeperator) );
		}
		
		_sLastData = aData.join( this._fnNewline(oConfig) );
		return _sLastData;
	},
	"_fnBoundData": function ( sData, sBoundary, regex )
	{
		if ( sBoundary === "" )
		{
			return sData;
		}
		else
		{
			return sBoundary + sData.replace(regex, sBoundary+sBoundary) + sBoundary;
		}
	},
	"_fnChunkData": function ( sData, iSize )
	{
		var asReturn = [];
		var iStrlen = sData.length;
		
		for ( var i=0 ; i<iStrlen ; i+=iSize )
		{
			if ( i+iSize < iStrlen )
			{
				asReturn.push( sData.substring( i, i+iSize ) );
			}
			else
			{
				asReturn.push( sData.substring( i, iStrlen ) );
			}
		}
		
		return asReturn;
	},
	"_fnHtmlDecode": function ( sData )
	{
		if ( sData.indexOf('&') == -1 )
		{
			return sData;
		}
		
		var 
			aData = this._fnChunkData( sData, 2048 ),
			n = document.createElement('div'),
			i, iLen, iIndex,
			sReturn = "", sInner;
		for ( i=0, iLen=aData.length ; i<iLen ; i++ )
		{
			iIndex = aData[i].lastIndexOf( '&' );
			if ( iIndex != -1 && aData[i].length >= 8 && iIndex > aData[i].length - 8 )
			{
				sInner = aData[i].substr( iIndex );
				aData[i] = aData[i].substr( 0, iIndex );
			}
			
			n.innerHTML = aData[i];
			sReturn += n.childNodes[0].nodeValue;
		}
		
		return sReturn;
	},
	"_fnPrintStart": function ( oConfig )
	{
	  var that = this;
	  var oSetDT = this.s.dt;
		this._fnPrintHideNodes( oSetDT.nTable );
		this.s.print.saveStart = oSetDT._iDisplayStart;
		this.s.print.saveLength = oSetDT._iDisplayLength;

		if ( oConfig.bShowAll )
		{
			oSetDT._iDisplayStart = 0;
			oSetDT._iDisplayLength = -1;
			oSetDT.oApi._fnCalculateEnd( oSetDT );
			oSetDT.oApi._fnDraw( oSetDT );
		}
		if ( oSetDT.oScroll.sX !== "" || oSetDT.oScroll.sY !== "" )
		{
			this._fnPrintScrollStart( oSetDT );
		}
		var anFeature = oSetDT.aanFeatures;
		for ( var cFeature in anFeature )
		{
			if ( cFeature != 'i' && cFeature != 't' && cFeature.length == 1 )
			{
				for ( var i=0, iLen=anFeature[cFeature].length ; i<iLen ; i++ )
				{
					this.dom.print.hidden.push( {
						"node": anFeature[cFeature][i],
						"display": "block"
					} );
					anFeature[cFeature][i].style.display = "none";
				}
			}
		}
		$(document.body).addClass( this.classes.print.body );
		if ( oConfig.sInfo !== "" )
		{
			this.fnInfo( oConfig.sInfo, 3000 );
		}
		if ( oConfig.sMessage )
		{
			this.dom.print.message = document.createElement( "div" );
			this.dom.print.message.className = this.classes.print.message;
			this.dom.print.message.innerHTML = oConfig.sMessage;
			document.body.insertBefore( this.dom.print.message, document.body.childNodes[0] );
		}
		this.s.print.saveScroll = $(window).scrollTop();
		window.scrollTo( 0, 0 );
		$(document).bind( "keydown.DTTT", function(e) {
			if ( e.keyCode == 27 )
			{
				e.preventDefault();
				that._fnPrintEnd.call( that, e );
			}
		} );
	},
	"_fnPrintEnd": function ( e )
	{
		var that = this;
		var oSetDT = this.s.dt;
		var oSetPrint = this.s.print;
		var oDomPrint = this.dom.print;
		this._fnPrintShowNodes();
		if ( oSetDT.oScroll.sX !== "" || oSetDT.oScroll.sY !== "" )
		{
			this._fnPrintScrollEnd();
		}
		window.scrollTo( 0, oSetPrint.saveScroll );
		if ( oDomPrint.message !== null )
		{
			document.body.removeChild( oDomPrint.message );
			oDomPrint.message = null;
		}
		$(document.body).removeClass( 'DTTT_Print' );
		oSetDT._iDisplayStart = oSetPrint.saveStart;
		oSetDT._iDisplayLength = oSetPrint.saveLength;
		oSetDT.oApi._fnCalculateEnd( oSetDT );
		oSetDT.oApi._fnDraw( oSetDT );
		
		$(document).unbind( "keydown.DTTT" );
	},
	"_fnPrintScrollStart": function ()
	{
		var 
			oSetDT = this.s.dt,
			nScrollHeadInner = oSetDT.nScrollHead.getElementsByTagName('div')[0],
			nScrollHeadTable = nScrollHeadInner.getElementsByTagName('table')[0],
			nScrollBody = oSetDT.nTable.parentNode;
		var nTheadSize = oSetDT.nTable.getElementsByTagName('thead');
		if ( nTheadSize.length > 0 )
		{
			oSetDT.nTable.removeChild( nTheadSize[0] );
		}
		
		if ( oSetDT.nTFoot !== null )
		{
			var nTfootSize = oSetDT.nTable.getElementsByTagName('tfoot');
			if ( nTfootSize.length > 0 )
			{
				oSetDT.nTable.removeChild( nTfootSize[0] );
			}
		}
		
		nTheadSize = oSetDT.nTHead.cloneNode(true);
		oSetDT.nTable.insertBefore( nTheadSize, oSetDT.nTable.childNodes[0] );
		
		if ( oSetDT.nTFoot !== null )
		{
			nTfootSize = oSetDT.nTFoot.cloneNode(true);
			oSetDT.nTable.insertBefore( nTfootSize, oSetDT.nTable.childNodes[1] );
		}
		if ( oSetDT.oScroll.sX !== "" )
		{
			oSetDT.nTable.style.width = $(oSetDT.nTable).outerWidth()+"px";
			nScrollBody.style.width = $(oSetDT.nTable).outerWidth()+"px";
			nScrollBody.style.overflow = "visible";
		}
		
		if ( oSetDT.oScroll.sY !== "" )
		{
			nScrollBody.style.height = $(oSetDT.nTable).outerHeight()+"px";
			nScrollBody.style.overflow = "visible";
		}
	},
	"_fnPrintScrollEnd": function ()
	{
		var 
			oSetDT = this.s.dt,
			nScrollBody = oSetDT.nTable.parentNode;
		
		if ( oSetDT.oScroll.sX !== "" )
		{
			nScrollBody.style.width = oSetDT.oApi._fnStringToCss( oSetDT.oScroll.sX );
			nScrollBody.style.overflow = "auto";
		}
		
		if ( oSetDT.oScroll.sY !== "" )
		{
			nScrollBody.style.height = oSetDT.oApi._fnStringToCss( oSetDT.oScroll.sY );
			nScrollBody.style.overflow = "auto";
		}
	},
	"_fnPrintShowNodes": function ( )
	{
	  var anHidden = this.dom.print.hidden;
	  
		for ( var i=0, iLen=anHidden.length ; i<iLen ; i++ )
		{
			anHidden[i].node.style.display = anHidden[i].display;
		}
		anHidden.splice( 0, anHidden.length );
	},
	"_fnPrintHideNodes": function ( nNode )
	{
	  var anHidden = this.dom.print.hidden;
	  
		var nParent = nNode.parentNode;
		var nChildren = nParent.childNodes;
		for ( var i=0, iLen=nChildren.length ; i<iLen ; i++ )
		{
			if ( nChildren[i] != nNode && nChildren[i].nodeType == 1 )
			{
				var sDisplay = $(nChildren[i]).css("display");
			 	if ( sDisplay != "none" )
				{
					anHidden.push( {
						"node": nChildren[i],
						"display": sDisplay
					} );
					nChildren[i].style.display = "none";
				}
			}
		}
		
		if ( nParent.nodeName != "BODY" )
		{
			this._fnPrintHideNodes( nParent );
		}
	}
};
TableTools._aInstances = [];
TableTools._aListeners = [];
TableTools.fnGetMasters = function ()
{
	var a = [];
	for ( var i=0, iLen=TableTools._aInstances.length ; i<iLen ; i++ )
	{
		if ( TableTools._aInstances[i].s.master )
		{
			a.push( TableTools._aInstances[i] );
		}
	}
	return a;
};
TableTools.fnGetInstance = function ( node )
{
	if ( typeof node != 'object' )
	{
		node = document.getElementById(node);
	}
	
	for ( var i=0, iLen=TableTools._aInstances.length ; i<iLen ; i++ )
	{
		if ( TableTools._aInstances[i].s.master && TableTools._aInstances[i].dom.table == node )
		{
			return TableTools._aInstances[i];
		}
	}
	return null;
};
TableTools._fnEventListen = function ( that, type, fn )
{
	TableTools._aListeners.push( {
		"that": that,
		"type": type,
		"fn": fn
	} );
};
TableTools._fnEventDispatch = function ( that, type, node )
{
	var listeners = TableTools._aListeners;
	for ( var i=0, iLen=listeners.length ; i<iLen ; i++ )
	{
		if ( that.dom.table == listeners[i].that.dom.table && listeners[i].type == type )
		{
			listeners[i].fn( node );
		}
	}
};
TableTools.buttonBase = {
	"sAction": "text",
	"sTag": "default",
	"sLinerTag": "default",
	"sButtonClass": "DTTT_button_text",
	"sButtonText": "Button text",
	"sTitle": "",
	"sToolTip": "",
	"sCharSet": "utf8",
	"bBomInc": false,
	"sFileName": "*.csv",
	"sFieldBoundary": "",
	"sFieldSeperator": "\t",
	"sNewLine": "auto",
	"mColumns": "all", 
	"bHeader": true,
	"bFooter": true,
	"bOpenRows": false,
	"bSelectedOnly": false,
	"fnMouseover": null,
	"fnMouseout": null,
	"fnClick": null,
	"fnSelect": null,
	"fnComplete": null,
	"fnInit": null,
	"fnCellRender": null
};
TableTools.BUTTONS = {
	"csv": $.extend( {}, TableTools.buttonBase, {
		"sAction": "flash_save",
		"sButtonClass": "DTTT_button_csv",
		"sButtonText": "CSV",
		"sFieldBoundary": '"',
		"sFieldSeperator": ",",
		"fnClick": function( nButton, oConfig, flash ) {
			this.fnSetText( flash, this.fnGetTableData(oConfig) );
		}
	} ),

	"xls": $.extend( {}, TableTools.buttonBase, {
		"sAction": "flash_save",
		"sCharSet": "utf16le",
		"bBomInc": true,
		"sButtonClass": "DTTT_button_xls",
		"sButtonText": "Excel",
		"fnClick": function( nButton, oConfig, flash ) {
			this.fnSetText( flash, this.fnGetTableData(oConfig) );
		}
	} ),

	"copy": $.extend( {}, TableTools.buttonBase, {
		"sAction": "flash_copy",
		"sButtonClass": "DTTT_button_copy",
		"sButtonText": "Copy",
		"fnClick": function( nButton, oConfig, flash ) {
			this.fnSetText( flash, this.fnGetTableData(oConfig) );
		},
		"fnComplete": function(nButton, oConfig, flash, text) {
			var
				lines = text.split('\n').length,
				len = this.s.dt.nTFoot === null ? lines-1 : lines-2,
				plural = (len==1) ? "" : "s";
			this.fnInfo( '<h6>Table copied</h6>'+
				'<p>Copied '+len+' row'+plural+' to the clipboard.</p>',
				1500
			);
		}
	} ),

	"pdf": $.extend( {}, TableTools.buttonBase, {
		"sAction": "flash_pdf",
		"sNewLine": "\n",
		"sFileName": "*.pdf",
		"sButtonClass": "DTTT_button_pdf",
		"sButtonText": "PDF",
		"sPdfOrientation": "portrait",
		"sPdfSize": "A4",
		"sPdfMessage": "",
		"fnClick": function( nButton, oConfig, flash ) {
			this.fnSetText( flash, 
				"title:"+ this.fnGetTitle(oConfig) +"\n"+
				"message:"+ oConfig.sPdfMessage +"\n"+
				"colWidth:"+ this.fnCalcColRatios(oConfig) +"\n"+
				"orientation:"+ oConfig.sPdfOrientation +"\n"+
				"size:"+ oConfig.sPdfSize +"\n"+
				"--/TableToolsOpts--\n" +
				this.fnGetTableData(oConfig)
			);
		}
	} ),

	"print": $.extend( {}, TableTools.buttonBase, {
		"sInfo": "<h6>Print view</h6><p>Please use your browser's print function to "+
		  "print this table. Press escape when finished.",
		"sMessage": null,
		"bShowAll": true,
		"sToolTip": "View print view",
		"sButtonClass": "DTTT_button_print",
		"sButtonText": "Print",
		"fnClick": function ( nButton, oConfig ) {
			this.fnPrint( true, oConfig );
		}
	} ),

	"text": $.extend( {}, TableTools.buttonBase ),

	"select": $.extend( {}, TableTools.buttonBase, {
		"sButtonText": "Select button",
		"fnSelect": function( nButton, oConfig ) {
			if ( this.fnGetSelected().length !== 0 ) {
				$(nButton).removeClass( this.classes.buttons.disabled );
			} else {
				$(nButton).addClass( this.classes.buttons.disabled );
			}
		},
		"fnInit": function( nButton, oConfig ) {
			$(nButton).addClass( this.classes.buttons.disabled );
		}
	} ),

	"select_single": $.extend( {}, TableTools.buttonBase, {
		"sButtonText": "Select button",
		"fnSelect": function( nButton, oConfig ) {
			var iSelected = this.fnGetSelected().length;
			if ( iSelected == 1 ) {
				$(nButton).removeClass( this.classes.buttons.disabled );
			} else {
				$(nButton).addClass( this.classes.buttons.disabled );
			}
		},
		"fnInit": function( nButton, oConfig ) {
			$(nButton).addClass( this.classes.buttons.disabled );
		}
	} ),

	"select_all": $.extend( {}, TableTools.buttonBase, {
		"sButtonText": "Select all",
		"fnClick": function( nButton, oConfig ) {
			this.fnSelectAll();
		},
		"fnSelect": function( nButton, oConfig ) {
			if ( this.fnGetSelected().length == this.s.dt.fnRecordsDisplay() ) {
				$(nButton).addClass( this.classes.buttons.disabled );
			} else {
				$(nButton).removeClass( this.classes.buttons.disabled );
			}
		}
	} ),

	"select_none": $.extend( {}, TableTools.buttonBase, {
		"sButtonText": "Deselect all",
		"fnClick": function( nButton, oConfig ) {
			this.fnSelectNone();
		},
		"fnSelect": function( nButton, oConfig ) {
			if ( this.fnGetSelected().length !== 0 ) {
				$(nButton).removeClass( this.classes.buttons.disabled );
			} else {
				$(nButton).addClass( this.classes.buttons.disabled );
			}
		},
		"fnInit": function( nButton, oConfig ) {
			$(nButton).addClass( this.classes.buttons.disabled );
		}
	} ),

	"ajax": $.extend( {}, TableTools.buttonBase, {
		"sAjaxUrl": "/xhr.php",
		"sButtonText": "Ajax button",
		"fnClick": function( nButton, oConfig ) {
			var sData = this.fnGetTableData(oConfig);
			$.ajax( {
				"url": oConfig.sAjaxUrl,
				"data": [
					{ "name": "tableData", "value": sData }
				],
				"success": oConfig.fnAjaxComplete,
				"dataType": "json",
				"type": "POST", 
				"cache": false,
				"error": function () {
					alert( "Error detected when sending table data to server" );
				}
			} );
		},
		"fnAjaxComplete": function( json ) {
			alert( 'Ajax complete' );
		}
	} ),

	"div": $.extend( {}, TableTools.buttonBase, {
		"sAction": "div",
		"sTag": "div",
		"sButtonClass": "DTTT_nonbutton",
		"sButtonText": "Text button"
	} ),

	"collection": $.extend( {}, TableTools.buttonBase, {
		"sAction": "collection",
		"sButtonClass": "DTTT_button_collection",
		"sButtonText": "Collection",
		"fnClick": function( nButton, oConfig ) {
			this._fnCollectionShow(nButton, oConfig);
		}
	} )
};
TableTools.classes = {
	"container": "DTTT_container",
	"buttons": {
		"normal": "DTTT_button",
		"disabled": "DTTT_disabled"
	},
	"collection": {
		"container": "DTTT_collection",
		"background": "DTTT_collection_background",
		"buttons": {
			"normal": "DTTT_button",
			"disabled": "DTTT_disabled"
		}
	},
	"select": {
		"table": "DTTT_selectable",
		"row": "DTTT_selected"
	},
	"print": {
		"body": "DTTT_Print",
		"info": "DTTT_print_info",
		"message": "DTTT_PrintMessage"
	}
};
TableTools.classes_themeroller = {
	"container": "DTTT_container ui-buttonset ui-buttonset-multi",
	"buttons": {
		"normal": "DTTT_button ui-button ui-state-default"
	},
	"collection": {
		"container": "DTTT_collection ui-buttonset ui-buttonset-multi"
	}
};
TableTools.DEFAULTS = {
	"sSwfPath":        "media/swf/copy_csv_xls_pdf.swf",
	"sRowSelect":      "none",
	"sSelectedClass":  null,
	"fnPreRowSelect":  null,
	"fnRowSelected":   null,
	"fnRowDeselected": null,
	"aButtons":        [ "copy", "csv", "xls", "pdf", "print" ],
	"oTags": {
		"container": "div",
		"button": "a", 
		"liner": "span",
		"collection": {
			"container": "div",
			"button": "a",
			"liner": "span"
		}
	}
};
TableTools.prototype.CLASS = "TableTools";
TableTools.VERSION = "2.1.3";
TableTools.prototype.VERSION = TableTools.VERSION;
if ( typeof $.fn.dataTable == "function" &&
	 typeof $.fn.dataTableExt.fnVersionCheck == "function" &&
	 $.fn.dataTableExt.fnVersionCheck('1.9.0') )
{
	$.fn.dataTableExt.aoFeatures.push( {
		"fnInit": function( oDTSettings ) {
			var oOpts = typeof oDTSettings.oInit.oTableTools != 'undefined' ? 
				oDTSettings.oInit.oTableTools : {};
			
			var oTT = new TableTools( oDTSettings.oInstance, oOpts );
			TableTools._aInstances.push( oTT );
			
			return oTT.dom.container;
		},
		"cFeature": "T",
		"sFeature": "TableTools"
	} );
}
else
{
	alert( "Warning: TableTools 2 requires DataTables 1.9.0 or newer - www.datatables.net/download");
}

$.fn.DataTable.TableTools = TableTools;

})(jQuery, window, document);
