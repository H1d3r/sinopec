var ZeroClipboard_TableTools = {
	
	version: "1.0.4-TableTools2",
	clients: {}, 
	moviePath: '', 
	nextId: 1, 
	
	$: function(thingy) {
		if (typeof(thingy) == 'string') thingy = document.getElementById(thingy);
		if (!thingy.addClass) {
			thingy.hide = function() { this.style.display = 'none'; };
			thingy.show = function() { this.style.display = ''; };
			thingy.addClass = function(name) { this.removeClass(name); this.className += ' ' + name; };
			thingy.removeClass = function(name) {
				this.className = this.className.replace( new RegExp("\\s*" + name + "\\s*"), " ").replace(/^\s+/, '').replace(/\s+$/, '');
			};
			thingy.hasClass = function(name) {
				return !!this.className.match( new RegExp("\\s*" + name + "\\s*") );
			}
		}
		return thingy;
	},
	
	setMoviePath: function(path) {
		this.moviePath = path;
	},
	
	dispatch: function(id, eventName, args) {	
		var client = this.clients[id];
		if (client) {
			client.receiveEvent(eventName, args);
		}
	},
	
	register: function(id, client) {
		this.clients[id] = client;
	},
	
	getDOMObjectPosition: function(obj) {
		var info = {
			left: 0, 
			top: 0, 
			width: obj.width ? obj.width : obj.offsetWidth, 
			height: obj.height ? obj.height : obj.offsetHeight
		};
		
		if ( obj.style.width != "" )
			info.width = obj.style.width.replace("px","");
		
		if ( obj.style.height != "" )
			info.height = obj.style.height.replace("px","");

		while (obj) {
			info.left += obj.offsetLeft;
			info.top += obj.offsetTop;
			obj = obj.offsetParent;
		}

		return info;
	},
	
	Client: function(elem) {
		this.handlers = {};
		
		this.id = ZeroClipboard_TableTools.nextId++;
		this.movieId = 'ZeroClipboard_TableToolsMovie_' + this.id;
		
		ZeroClipboard_TableTools.register(this.id, this);
		
		if (elem) this.glue(elem);
	}
};

ZeroClipboard_TableTools.Client.prototype = {
	
	id: 0,
	ready: false, 
	movie: null,
	clipText: '', 
	fileName: '', 
	action: 'copy', 
	handCursorEnabled: true, 
	cssEffects: true, 
	handlers: null, 
	sized: false,
	
	glue: function(elem, title) {
		this.domElement = ZeroClipboard_TableTools.$(elem);
		
		var zIndex = 99;
		if (this.domElement.style.zIndex) {
			zIndex = parseInt(this.domElement.style.zIndex) + 1;
		}
		
		var box = ZeroClipboard_TableTools.getDOMObjectPosition(this.domElement);
		
		this.div = document.createElement('div');
		var style = this.div.style;
		style.position = 'absolute';
		style.left = '0px';
		style.top = '0px';
		style.width = (box.width) + 'px';
		style.height = box.height + 'px';
		style.zIndex = zIndex;
		
		if ( typeof title != "undefined" && title != "" ) {
			this.div.title = title;
		}
		if ( box.width != 0 && box.height != 0 ) {
			this.sized = true;
		}
		
		if ( this.domElement ) {
			this.domElement.appendChild(this.div);
			this.div.innerHTML = this.getHTML( box.width, box.height );
		}
	},
	
	positionElement: function() {
		var box = ZeroClipboard_TableTools.getDOMObjectPosition(this.domElement);
		var style = this.div.style;
		
		style.position = 'absolute';
		style.width = box.width + 'px';
		style.height = box.height + 'px';
		
		if ( box.width != 0 && box.height != 0 ) {
			this.sized = true;
		} else {
			return;
		}
		
		var flash = this.div.childNodes[0];
		flash.width = box.width;
		flash.height = box.height;
	},
	
	getHTML: function(width, height) {
		var html = '';
		var flashvars = 'id=' + this.id + 
			'&width=' + width + 
			'&height=' + height;
			
		if (navigator.userAgent.match(/MSIE/)) {
			var protocol = location.href.match(/^https/i) ? 'https://' : 'http://';
			html += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="'+protocol+'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="'+width+'" height="'+height+'" id="'+this.movieId+'" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="'+ZeroClipboard_TableTools.moviePath+'" /><param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="best" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="'+flashvars+'"/><param name="wmode" value="transparent"/></object>';
		}
		else {
			html += '<embed id="'+this.movieId+'" src="'+ZeroClipboard_TableTools.moviePath+'" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="'+width+'" height="'+height+'" name="'+this.movieId+'" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="'+flashvars+'" wmode="transparent" />';
		}
		return html;
	},
	
	hide: function() {
		if (this.div) {
			this.div.style.left = '-2000px';
		}
	},
	
	show: function() {
		this.reposition();
	},
	
	destroy: function() {
		if (this.domElement && this.div) {
			this.hide();
			this.div.innerHTML = '';
			
			var body = document.getElementsByTagName('body')[0];
			try { body.removeChild( this.div ); } catch(e) {;}
			
			this.domElement = null;
			this.div = null;
		}
	},
	
	reposition: function(elem) {
		if (elem) {
			this.domElement = ZeroClipboard_TableTools.$(elem);
			if (!this.domElement) this.hide();
		}
		
		if (this.domElement && this.div) {
			var box = ZeroClipboard_TableTools.getDOMObjectPosition(this.domElement);
			var style = this.div.style;
			style.left = '' + box.left + 'px';
			style.top = '' + box.top + 'px';
		}
	},
	
	clearText: function() {
		this.clipText = '';
		if (this.ready) this.movie.clearText();
	},
	
	appendText: function(newText) {
		this.clipText += newText;
		if (this.ready) { this.movie.appendText(newText) ;}
	},
	
	setText: function(newText) {
		this.clipText = newText;
		if (this.ready) { this.movie.setText(newText) ;}
	},
	
	setCharSet: function(charSet) {
		this.charSet = charSet;
		if (this.ready) { this.movie.setCharSet(charSet) ;}
	},
	
	setBomInc: function(bomInc) {
		this.incBom = bomInc;
		if (this.ready) { this.movie.setBomInc(bomInc) ;}
	},
	
	setFileName: function(newText) {
		this.fileName = newText;
		if (this.ready) this.movie.setFileName(newText);
	},
	
	setAction: function(newText) {
		this.action = newText;
		if (this.ready) this.movie.setAction(newText);
	},
	
	addEventListener: function(eventName, func) {
		eventName = eventName.toString().toLowerCase().replace(/^on/, '');
		if (!this.handlers[eventName]) this.handlers[eventName] = [];
		this.handlers[eventName].push(func);
	},
	
	setHandCursor: function(enabled) {
		this.handCursorEnabled = enabled;
		if (this.ready) this.movie.setHandCursor(enabled);
	},
	
	setCSSEffects: function(enabled) {
		this.cssEffects = !!enabled;
	},
	
	receiveEvent: function(eventName, args) {
		eventName = eventName.toString().toLowerCase().replace(/^on/, '');
		
		switch (eventName) {
			case 'load':
				
				this.movie = document.getElementById(this.movieId);
				if (!this.movie) {
					var self = this;
					setTimeout( function() { self.receiveEvent('load', null); }, 1 );
					return;
				}
				
				if (!this.ready && navigator.userAgent.match(/Firefox/) && navigator.userAgent.match(/Windows/)) {
					var self = this;
					setTimeout( function() { self.receiveEvent('load', null); }, 100 );
					this.ready = true;
					return;
				}
				
				this.ready = true;
				this.movie.clearText();
				this.movie.appendText( this.clipText );
				this.movie.setFileName( this.fileName );
				this.movie.setAction( this.action );
				this.movie.setCharSet( this.charSet );
				this.movie.setBomInc( this.incBom );
				this.movie.setHandCursor( this.handCursorEnabled );
				break;
			
			case 'mouseover':
				if (this.domElement && this.cssEffects) {
					if (this.recoverActive) this.domElement.addClass('active');
				}
				break;
			
			case 'mouseout':
				if (this.domElement && this.cssEffects) {
					this.recoverActive = false;
					if (this.domElement.hasClass('active')) {
						this.domElement.removeClass('active');
						this.recoverActive = true;
					}
				}
				break;
			
			case 'mousedown':
				if (this.domElement && this.cssEffects) {
					this.domElement.addClass('active');
				}
				break;
			
			case 'mouseup':
				if (this.domElement && this.cssEffects) {
					this.domElement.removeClass('active');
					this.recoverActive = false;
				}
				break;
		} 
		
		if (this.handlers[eventName]) {
			for (var idx = 0, len = this.handlers[eventName].length; idx < len; idx++) {
				var func = this.handlers[eventName][idx];
			
				if (typeof(func) == 'function') {
					func(this, args);
				}
				else if ((typeof(func) == 'object') && (func.length == 2)) {
					func[0][ func[1] ](this, args);
				}
				else if (typeof(func) == 'string') {
				
					window[func](this, args);
				}
			} 
		} 
	}
	
};
