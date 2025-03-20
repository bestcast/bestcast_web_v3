(function (window){

	var VPLUtils = function(){};

	VPLUtils.test = document.createElement("div");

	VPLUtils.isEmpty = function(str){
		return str.replace(/^\s+|\s+$/g, '').length == 0;
	}

	VPLUtils.strip = function(str){
		return str.replace(/^\s+|\s+$/g,"");
	}

	VPLUtils.getUrlParameter = function(k) {
		var p={};
		window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
		return k?p[k]:p;
	};

	VPLUtils.htmlToElement = function(html) {
	    var template = document.createElement('template');
	    html = html.trim(); // Never return a text node of whitespace as the result
	    template.innerHTML = html;
	    return template.content.firstChild;
	}

	VPLUtils.b64DecodeUnicode = function(str) {
	    // Going backwards: from bytestream, to percent-encoding, to original string.
	    return decodeURIComponent(atob(str).split('').map(function(c) {
	        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
	    }).join(''));
	};

	VPLUtils.rgbToHex = function(color) {
		var isHex  = /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(color);
		if(isHex){
			return color;
		} else{
		    color = color.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
			return (color && color.length === 4) ? "#" +
		    ("0" + parseInt(color[1],10).toString(16)).slice(-2) +
		    ("0" + parseInt(color[2],10).toString(16)).slice(-2) +
		    ("0" + parseInt(color[3],10).toString(16)).slice(-2) : '';
		}
	}

	VPLUtils.isNumber = function(n){
	   return !isNaN(parseFloat(n)) && isFinite(n);
	}

	VPLUtils.isMobile = function(){
		return (/Android|webOS|iPhone|iPad|iPod|sony|BlackBerry/i.test(navigator.userAgent));
	}

	VPLUtils.isIOS = function(){
		return navigator.userAgent.match(/(iPad|iPhone|iPod)/g);
	}

	VPLUtils.isiPhoneIpod = function() {
		var agent = navigator.userAgent;
		return agent.indexOf('iPhone') > -1 || agent.indexOf('iPod') > -1;
	}

	VPLUtils.isChrome = function(){
		return !!window.chrome && !!window.chrome.webstore;
	}

	VPLUtils.isSafari = function() {
		return navigator.vendor && navigator.vendor.indexOf('Apple') > -1 &&
               navigator.userAgent &&
               navigator.userAgent.indexOf('CriOS') == -1 &&
               navigator.userAgent.indexOf('FxiOS') == -1;
	}

	VPLUtils.canPlayMp4 = function() {
		var v = document.createElement('video');
		return !!(v.canPlayType && v.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"').replace(/no/, ''));
	}

	VPLUtils.canPlayMp3 = function() {
		var a = document.createElement('audio');
		return !!(a.canPlayType && a.canPlayType('audio/mpeg;').replace(/no/, ''));
	}

	VPLUtils.canPlayWav = function() {
		var a = document.createElement('audio');
		return !!(a.canPlayType && a.canPlayType('audio/wav;').replace(/no/, ''));
	}

	VPLUtils.hasDownloadSupport = function(){  
		return ("download" in document.createElement("a"));
    }

    VPLUtils.hasLocalStorage = function() {
	    try{
			return 'localStorage' in window && window['localStorage'] !== null;
	    }catch(e){
			return false;
	    }
	}

	VPLUtils.isAndroid = function() {
		return navigator.userAgent.indexOf("Android") > -1;
	}


	VPLUtils.hasFullscreen = function(){
		return VPLUtils.test.requestFullscreen || VPLUtils.test.mozRequestFullScreen || VPLUtils.test.msRequestFullscreen || VPLUtils.test.oRequestFullscreen || VPLUtils.test.webkitRequestFullScreen;
	};

	VPLUtils.relativePath = function(s){
    	//https://stackoverflow.com/questions/10687099/how-to-test-if-a-url-string-is-absolute-or-relative
		var r = new RegExp('^(?:[a-z]+:)?//', 'i');
		return r.test(s);
	}

	VPLUtils.qualifyURL = function(url) {
		var a = document.createElement('a');
		a.href = url;
		return a.href;
	}

	VPLUtils.supportsWebGL = function() {
		try {
	        return !! window.WebGLRenderingContext && !! document.createElement( 'canvas' ).getContext( 'experimental-webgl' );
	    } catch( e ) {
	        return false;
	    }
	};

	VPLUtils.formatTime = function(seconds) {
		var sec_num = parseInt(seconds, 10); 
	    var hours   = Math.floor(sec_num / 3600);
	    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
	    var seconds = sec_num - (hours * 3600) - (minutes * 60);
	    if(hours > 0){
	    	if (hours   < 10) {hours   = "0"+hours;}
		    if (minutes < 10) {minutes = "0"+minutes;}
		    if (seconds < 10) {seconds = "0"+seconds;}
		    return hours+':'+minutes+':'+seconds;
	    }else{
		    if (minutes < 10) {minutes = "0"+minutes;}
		    if (seconds < 10) {seconds = "0"+seconds;}
		    return minutes+':'+seconds;
	    }
	}	

	VPLUtils.toSeconds = function(n){
		var a = n.split(/[\.:,]+/),
		seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
		return Number(seconds);
	}

	VPLUtils.keysrt = function(arr, key, reverse) {
		var sortOrder = 1;
		if(reverse)sortOrder = -1;
		return arr.sort(function(a, b) {
			var x = a[key]; var y = b[key];
			return sortOrder * ((x < y) ? -1 : ((x > y) ? 1 : 0));
		});
	}	

	VPLUtils.regroupArray = function(arr){
		var result = arr.reduce((r, { key, value }) => {
	        let [, i, k] = key.split('-');
	        r[i] = r[i] || [];
	        value.forEach((v, j) => (r[i][j] = r[i][j] || {})[k] = v);
	        return r;
	    }, []);
	    return result;
	}

	VPLUtils.getElementOffsetTop = function(el) {
	    var boundingClientRect = el.getBoundingClientRect();
	    var bodyEl = document.body;
	    var docEl = document.documentElement;
	    var scrollTop = window.pageYOffset || docEl.scrollTop || bodyEl.scrollTop;
	    var clientTop = docEl.clientTop || bodyEl.clientTop || 0;
	    return Math.round(boundingClientRect.bottom - 100 + scrollTop - clientTop);
	}

	VPLUtils.getViewportSize = function(isMobile){	
		if(isMobile) return {w:window.innerWidth, h:window.innerHeight};
		else return {w:document.documentElement.clientWidth || window.innerWidth, h:document.documentElement.clientHeight || window.innerHeight};
	};

	VPLUtils.isScrolledIntoView = function(el){
	    var rect = el.getBoundingClientRect();
	    var elemTop = rect.top;
	    var elemBottom = rect.bottom;

	    var isVisible = (elemTop + rect.height/2 >= 0) && (elemBottom - rect.height/2 <= window.innerHeight);
	    return isVisible;
	}

	VPLUtils.getScrollTop = function(el) {
	    var docEl = document.documentElement;
  		return (window.pageYOffset || docEl.scrollTop) - (docEl.clientTop || 0);
	}

	// Pass in the objects to merge as arguments.
	// For a deep extend, set the first argument to `true`.
	VPLUtils.extendObj = function() {

		// Variables
		var extended = {};
		var deep = false;
		var i = 0;
		var length = arguments.length;

		// Check if a deep merge
		if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
			deep = arguments[0];
			i++;
		}

		// Merge the object into the extended object
		var merge = function (obj) {
			for ( var prop in obj ) {
				if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
					// If deep merge and property is an object, merge properties
					if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
						extended[prop] = VPLUtils.extendObj( true, extended[prop], obj[prop] );
					} else {
						extended[prop] = obj[prop];
					}
				}
			}
		};

		// Loop through each object and conduct a merge
		for ( ; i < length; i++ ) {
			var obj = arguments[i];
			merge(obj);
		}

		return extended;

	};

	window.VPLUtils = VPLUtils;

}(window));	

(function (){
	"use strict"
	var VPLEventDispatcher = function (){

		var self = this;
		
	    self.events = {};

	    self.addEventListener = function(name, handler) {
	        if (self.events.hasOwnProperty(name)){
	            self.events[name].push(handler);
	        }else{
	            self.events[name] = [handler];
	        }
	    };

	    self.removeEventListener = function(name, handler) {
	        /* This is a bit tricky, because how would you identify functions?
	           This simple solution should work if you pass THE SAME handler. */
	        if (!self.events.hasOwnProperty(name))return;

	        var index = self.events[name].indexOf(handler);
	        if (index != -1)self.events[name].splice(index, 1);
	    };

	    self.fireEvent = function(name, args) {
	        if (!self.events.hasOwnProperty(name))return;

	        if (!args || !args.length)args = [];

	        var evs = self.events[name], l = evs.length;
	        for (var i = 0; i < l; i++) {
	            evs[i].apply(null, args);
	        }
	    };
	    
	};	
	
	window.VPLEventDispatcher = VPLEventDispatcher;
}(window));


(function (window){
	"use strict"
	var VPLPlaylistManager = function (data){
		
		var self = this,
		loopingOn = data.loopingOn,
		randomPlay = data.randomPlay,
		playlistItems,
		lastInOrder = false,
		counter = -1,
		lastPlayedFromPlaylistClick,//last played on click.
		lastRandomCounter,//last played random media in random playlist.
		randomPaused = false,//when random is playing and we interrupt it by click on the playlist.
		randomArr = [],
		playlistSelect = false;//prevent geting counter from randomArr on playlist click (get 'normal' counter instead)		

		VPLEventDispatcher.call(this);	
		
		//set counter to specific number or add it to the currect counter value		 
		this.setCounter = function(value, _add) {
			if (typeof _add === 'undefined') _add = true;
			if(_add){
				counter += parseInt(value, 10);
			}else{
				counter = parseInt(value, 10);
			}
			checkCounter();
		}
		this.getCounter = function() {
			var i;
			if(randomPlay){
				if(!playlistSelect){
					i = randomArr[counter];
				}else{
					i = counter;
				}
			}else{
				i = counter;
			}
			return i;
		}
		this.advanceHandler = function(a) {
			playlistSelect = false;//reset
			if(randomPaused){
				handleRandomPaused(a);
			}else{
				self.setCounter(a);
			}
		}
		this.processPlaylistRequest = function(id) {
			playlistSelect = false;//reset
			if(randomPlay){
				playlistSelect = true;
				lastPlayedFromPlaylistClick = id;//always remember last played on each click.
				if(!randomPaused){
					lastRandomCounter = counter;
					randomPaused = true;//needs to stay until random play comes back again! So that the above reference to last random counter doesnt get lost. (if we constantly clicking playlist)
				}
			}
			self.setCounter(id, false);
		}
		this.setPlaylistItems = function(val, resetCounter) {
			if(typeof resetCounter === 'undefined') resetCounter = true;
			if(resetCounter)counter = -1;
			playlistItems = val;
			if(randomPlay) makeRandomList();
		}
		this.reSetCounter = function(num) {
			if(typeof num === 'undefined'){
				 counter = -1;
			}else{//set counter to specific number
				var n = parseInt(num,10);
				if(playlistItems){
					if(n > playlistItems - 1){
						n = playlistItems - 1;
					}else if(n < 0){
						n = 0;
					}
					counter = n;
				}else{
					counter = -1;
				}
			}
		}
		this.setRandom = function(val) {
			randomPlay = val;
			if(playlistItems < 3) randomPlay = false;
			if(randomPlay) makeRandomList();
			randomChange();

			self.fireEvent('VPLPlaylistManager.RANDOM_CHANGE', [{randomPlay:randomPlay}]);
		}
		this.setLooping = function(val) {
			loopingOn = val;

			self.fireEvent('VPLPlaylistManager.LOOP_CHANGE', [{loopingOn:loopingOn}]);
		}
		this.getPosition = function(val) {
			return randomArr.indexOf(val);
		}
		
		//exiting randomPaused and going back to random mode
		function handleRandomPaused(a) {
			//just an exit out of randomPaused (because of a playlist click) and back to random again
			randomPaused = false;//reset before because of the getCounter()
			
			if(lastRandomCounter + a > playlistItems - 1){
				counter = playlistItems - 1;
				self.fireEvent('VPLPlaylistManager.COUNTER_READY', [{counter:k}]);
				return;
			} else if( lastRandomCounter + a < 0){
				counter = 0;
				self.fireEvent('VPLPlaylistManager.COUNTER_READY', [{counter:k}]);
				return;
			}
			setCounter(lastRandomCounter + a, false);
		}
		function randomChange() {//when random is turned on / off
			if(randomPlay){
				activeIndexFirst();
				counter = 0;//we have to do it like this, because with (setCounter(0, false)) media starts to play from the beginning if its already playing. (when random requested)
				//we need to say this on the every beginning of random to redirect the counter from wherever the currently is to 0, so that it becomes first index in randomArr. (after we have moved active index to beginning of randomArr)
				
			}else{
				//we are not going through setCounter here because its just getting out of random mode, and its not changing counter, it just stays where it is (playing or not)
				if(randomPaused){
					counter = lastPlayedFromPlaylistClick;
					randomPaused = false;//when random mode stops randomPaused stops also.
				}else{
					counter = randomArr[counter];//when we turn off random we need to set counter to the value of the current counter in randomArr, so if the counter is 1, and thats value 3 in randomArr for example, we want the active counter to stay 3, not 1, and next to go to 4, not 2.
				}
			}
		}
		function checkCounter() {
			if(isNaN(counter)){
				alert('VPLPlaylistManager message: No active media, counter = ' + counter);
				return;
			}
			//reset
			lastInOrder = false;
			
			if(loopingOn){
				if(randomPlay){
					
					if(counter > playlistItems - 1){//moving fowards
						counter = randomArr[ playlistItems - 1];//remember counter for comparison
						makeRandomList();
						_firstIndexCheck(randomArr, counter);
						counter = 0;
						
					}else if(counter < 0){//moving backwards
						counter = randomArr[0];//remember counter for comparison
						makeRandomList();
						lastIndexCheck(randomArr, counter);
						counter = playlistItems - 1;
					}
					
				}else{//random off
					if(counter > playlistItems - 1){
						counter = 0;
					}else if( counter < 0){
						counter = playlistItems - 1;
					}
				}

				var k = self.getCounter()
				
				self.fireEvent('VPLPlaylistManager.COUNTER_READY', [{counter:k}]);
				
			}else{//looping off
				
				if(counter > playlistItems - 1){
					counter = playlistItems - 1;
					lastInOrder = true;//last item
				}else if(counter < 0){
					lastInOrder = true;//last item
					counter = 0;
				}
				
				if(!lastInOrder){
					var k = self.getCounter()
					self.fireEvent('VPLPlaylistManager.COUNTER_READY', [{counter:k}]);
				}else{
					self.fireEvent('VPLPlaylistManager.PLAYLIST_END');
				}
			}
			
		}
		function makeRandomList() {
			randomArr = randomiseArray(playlistItems);
		}
		function _firstIndexCheck () {
			//check that first item in newly generated random array isnt equal to last active item.
			if(randomArr[0] == counter){//if yes, put it at the last place in array.
				var i = randomArr.splice(0,1);
				randomArr.push(i);
			}
		}
		function lastIndexCheck() {
			if(randomArr[playlistItems - 1] == counter){//if yes, put it at the first place in array.
				var i = randomArr.splice(playlistItems - 1,1);
				randomArr.unshift(i);
			}
		}
		function activeIndexFirst() {//when going into random (playing or not) put currently active index on the first place of random array.
			var i,len = randomArr.length, j;
			for(i = 0; i < len; i++){
				if(randomArr[i] == counter){
					if(i == 0){//if its already on the first place no need for action.
						break;
					}
					j = randomArr.splice(i,1);
					randomArr.unshift(parseInt(j,10));
					break;
				}
			}
		} 
		function randomiseArray(num){
			var arr = [], randomArr = [], i, j, randomIndex;
			for(i = 0; i < num; i++){
				arr[i] = i;
			}
			for(j = 0; j < num; j++){
				randomIndex = Math.round(Math.random()*(arr.length-1));
				randomArr[j] = arr[randomIndex];
				arr.splice(randomIndex, 1);
			}
			return randomArr;
		}
	
	};
	
	window.VPLPlaylistManager = VPLPlaylistManager;	

}(window));

(function (window){
	
	 var VPLAspectRatio = function(){};
		
	 VPLAspectRatio.resizeMedia = function(type, aspectRatio, holder, target) {

		var o, x, y, w = holder.offsetWidth, h = holder.offsetHeight;
	
		if(aspectRatio == 0) {//original dimensions
			o = getMediaSize(type, target);
		}else if(aspectRatio == 1) {//fitscreen
			o = retrieveObjectRatio(true, type, holder, target);
		}else if(aspectRatio == 2) {//fullscreen
			o = retrieveObjectRatio(false, type, holder, target);
		}

		x = parseInt(((w - o.width) / 2),10);
		y = parseInt(((h - o.height) / 2),10);

		target.style.width = o.width +'px';
		target.style.height = o.height +'px';
		target.style.left = x +'px';
		target.style.top = y +'px';
		
	}
	
	function retrieveObjectRatio(fitScreen, type, holder, target) {

		var val = {}, paddingX = 0, paddingY = 0, 
		w = holder.offsetWidth, h = holder.offsetHeight, 
		o = getMediaSize(type, target),
		targetWidth = o.width, targetHeight = o.height, 
		destinationRatio = (w - paddingX) / (h - paddingY), targetRatio = targetWidth / targetHeight;
	
		if (targetRatio < destinationRatio) {
			if (!fitScreen) {//fullscreen
				val.height = ((w - paddingX) /targetWidth) * targetHeight;
				val.width = (w - paddingX);
			} else {//fitscreen
				val.width = ((h - paddingY) / targetHeight) *targetWidth;
				val.height = (h - paddingY);
			}
		} else if (targetRatio > destinationRatio) {
			if (fitScreen) {//fitscreen
				val.height = ((w - paddingX) /targetWidth) * targetHeight;
				val.width = (w - paddingX);
			} else {//fullscreen
				val.width = ((h - paddingY) / targetHeight) *targetWidth;
				val.height = (h - paddingY);
			}
		} else {//fitscreen and fullscreen
			val.width = (w - paddingX);
			val.height = (h - paddingY);
		}

		return val;
	}

	function getMediaSize(type, target) {

		var o={}, default_w = 16, default_h = 9;//default values 

		if(type == 'video'){
			if(target && target.videoWidth && target.videoHeight){
				o.width = target.videoWidth;
				o.height = target.videoHeight;
			}else{
				o.width = default_w;
				o.height = default_h;
			}
		}else if(type == 'iframe'){
			if(target.sw && target.sh){
				o.width = target.sw;
				o.height = target.sh;
			}else{
				o.width = default_w;
				o.height = default_h;
			}
		}else if(type == 'image'){
			o.width = target.offsetWidth;
			o.height = target.offsetHeight;	
		}
		return o;
	}

	window.VPLAspectRatio = VPLAspectRatio;

}(window));

(function() {

	var vpl = function(delem, settings) {
	
	"use strict"

	var self = this;

	VPLEventDispatcher.call(this);

	//############################################//
	/* settings */
	//############################################//

	var defaults = {
		//scripts
		vimeo_js: "https://player.vimeo.com/api/player.js",
		youtube_js: "https://www.youtube.com/iframe_api",
		hls_js: "https://cdn.jsdelivr.net/npm/hls.js@latest",
		dash_js: "https://cdn.dashjs.org/latest/dash.all.min.js",
		three_js: "https://cdnjs.cloudflare.com/ajax/libs/three.js/105/three.min.js",
	    orbitControls_js: "https://unpkg.com/three@0.85.0/examples/js/controls/OrbitControls.js",
	    md5_js: "https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.10.0/js/md5.min.js",
	    share_js: "js/share_manager.js",

		sourcePath:"",
		instanceName: "",
		playerType:'normal',
		preload: 'auto',
		autoPlay:false,
		autoPlayAfterFirst:false,
		autoPlayInViewport:false,
		swipeTolerance:100,
 		aspectRatio: 2,
 		mediaEndAction: 'loop',
 		volume: 0.5,
 		useKeyboardNavigationForPlayback:false,
 		useMobileNativePlayer:false,
 		rightClickContextMenu:'custom',
 		blockYoutubeEvents:true,
 		blockVimeoEvents:true,
 		useShare:true,
 		hideQualityMenuOnSingleQuality: true,
 		displayPosterOnMobile:false,
 		subtitleOffText: 'Disabled',
 		idleTimeout:2000,
 		youtubePlayerType: 'chromeless',
 		vimeoPlayerType: 'default',
 		vimeoPlayerColor: '#00adef',
 		minimizeClass: 'vpl-minimize-br',
 		minimizeOnScroll:false,
 		minimizeOnScrollOnlyIfPlaying:false,
 		playbackPositionKey:"vpl-playback-position",
 		captionStateKey: "vpl-caption-state",//caption enabled / disabled
 		playerRatio: 1.7777777,//16/9
 		youtubePlayerColor: 'red',
 		caption_breakPointArr:[
            {width:0, size:18},
            {width:480, size:20},
            {width:768, size:22},
            {width:1024, size:24},
            {width:1280, size:36}
        ],
        swipeAction:'advance',
        seekTime:10,
        showStreamVideoBitrateMenu:true,
 		showStreamAudioBitrateMenu:true,
 		closeSettingsMenuOnSelect:true,
 		lightboxCloseTooltip: "Close",
 		clickOnBackgroundClosesLightbox: true,
 		keepCaptionFontSizeAfterManualResize:false,
 		disableVideoSkip:false,
 		useResumeScreen:false,
 		playbackPositionTime:null,//we use this to set video start time, we cant use start alone in querystring because query playlist does not exist (unless we have other required query string parameters for playlist, then we can use start)
		mediaId:null,
		embedSrc: 'Embed url goes here',
		tooltipClose: 'Close',

		minimizeCloseIcon:'<svg aria-hidden="true" focusable="false" role="img" viewBox="0 0 320 512"><path d="M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z"></path></svg>',
	}

	var settings = VPLUtils.extendObj(true, {}, defaults, settings);




	//############################################//
	/* url params */
	//############################################//

	//check url params, overwrite settings
	//check playlist from url params

	var urlParams = VPLUtils.getUrlParameter(),
	queryPlaylistArr = [], 
	queryPlaylist = [];
	var q_pathArr = [], q_subtitleArr = [];


	if(decodeURIComponent(urlParams['vpl-query-instance']) == settings.instanceName){//on if share url is for this instance

		delete urlParams['vpl-query-instance'];

		Object.keys(urlParams).forEach(function(key) {
			if(key.indexOf('vpl-')==0){//url params start with 'vpl-'
				var k = key.substr(4),//remove vpl-
				camelCased = k;
				var value = decodeURIComponent(urlParams[key]).replace(/\+/g, ' ');//+ to space

				if(k.indexOf('path-') == -1 && k.indexOf('subtitle-') == -1){//dont camel case
					camelCased = k.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });
				}
				
				if(value){
					if(settings.hasOwnProperty(camelCased)){//update settings 
						if(value === "true")value = true;
						else if(value === "false")value = false;
						settings[camelCased] = value;//only accept if property already exist
					}else{//check for playlist

						value = value.split(",").map(function(item){
						    return item.trim();
						});
						if(camelCased.indexOf('path-') > -1){
							q_pathArr.push({key:camelCased, value: value});
						}else if(camelCased.indexOf('subtitle-') > -1){
							q_subtitleArr.push({key:camelCased, value: value});
						}else{
							queryPlaylistArr.push({key:camelCased, value: value});
						}
					}
				}
			}
		});

	}

	//check if path or subttiles

	if(q_pathArr.length){
		q_pathArr = VPLUtils.regroupArray(q_pathArr);
		queryPlaylistArr.push({key: "path", value: q_pathArr});
	}
	if(q_subtitleArr.length){
		q_subtitleArr = VPLUtils.regroupArray(q_subtitleArr);
		queryPlaylistArr.push({key: "subtitles", value: q_subtitleArr});
	}
	q_pathArr = null;
	q_subtitleArr = null;

	//playlist from url params
	if(queryPlaylistArr.length){
		var i, j, len = queryPlaylistArr.length, len2 = queryPlaylistArr[0].value.length, item, obj, value;
		for(j = 0;j<len2;j++){
			obj = {};
			for(i = 0;i<len;i++){
				item = queryPlaylistArr[i];
				value = item.value[j];
				
				if(item.key.indexOf('path-') == -1 && item.key.indexOf('subtitle-') == -1){
					if(value === "true")value = true;
					else if(value === "false")value = false;
					obj[item.key] = value;
				}
			}
			queryPlaylist.push(obj);
		}
	}
	urlParams = null;






	//check url params

	if(settings.mediaId != null){
		var i;
		for(i = 0; i < settings.media.length; i++){
			if(settings.mediaId == settings.media[i].mediaId){
				settings.activeItem = i;
				break;
			}
		}
	}
	












	//############################################//
	/* elements */
	//############################################//

	var wrapper = delem;
	wrapper.style.display = 'block';


	var playerHolder = wrapper.querySelector('.vpl-player-holder'),
	mediaHolder = wrapper.querySelector('.vpl-media-holder'),
	playerControls = wrapper.querySelectorAll('.vpl-player-controls'),
	playerControlsMain = wrapper.querySelector('.vpl-player-controls-main'),
	playbackToggle = wrapper.querySelector('.vpl-playback-toggle'),
	prevToggle = wrapper.querySelector('.vpl-previous-toggle'),
	nextToggle = wrapper.querySelector('.vpl-next-toggle'),
	rewindToggle = wrapper.querySelector('.vpl-rewind-toggle'),
	skipBackwardToggle = wrapper.querySelector('.vpl-skip-backward-toggle'),
	skipForwardToggle = wrapper.querySelector('.vpl-skip-forward-toggle'),
	volumeWrapper = wrapper.querySelector('.vpl-volume-wrapper'),
	volumeToggle = wrapper.querySelector('.vpl-volume-toggle'),
	volumeSeekbar = wrapper.querySelector('.vpl-volume-seekbar'),
	volumeBg = wrapper.querySelector('.vpl-volume-bg'),
	volumeLevel = wrapper.querySelector('.vpl-volume-level'),
	seekbar = wrapper.querySelector('.vpl-seekbar'),
	progressBg = wrapper.querySelector('.vpl-progress-bg'),
	loadLevel = wrapper.querySelector('.vpl-load-level'),
	progressLevel = wrapper.querySelector('.vpl-progress-level'),
	vrInfo = wrapper.querySelector('.vpl-vr-info'),
	liveNote = wrapper.querySelector('.vpl-live-note'),
	mediaTimeCurrent = wrapper.querySelector('.vpl-media-time-current'),
	mediaTimeSeparator = wrapper.querySelector('.vpl-media-time-separator'),
	mediaTimeTotal = wrapper.querySelector('.vpl-media-time-total'),
	bigPlay = wrapper.querySelector('.vpl-big-play'),
	videoTitle = wrapper.querySelector('.vpl-video-title'),
	shareToggle = wrapper.querySelector('.vpl-share-toggle'),
	playerLoader = wrapper.querySelector('.vpl-player-loader'),
	pipToggle = wrapper.querySelector('.vpl-pip-toggle'),
	captionToggle = wrapper.querySelector('.vpl-cc-toggle'),
	theaterToggle = wrapper.querySelector('.vpl-theater-toggle'),
	unmuteToggle = wrapper.querySelector('.vpl-unmute-toggle'),
	downloadToggle = wrapper.querySelector('.vpl-download-toggle'),
	previewSeekWrap = wrapper.querySelector('.vpl-preview-seek-wrap'),
	previewSeekInner = wrapper.querySelector('.vpl-preview-seek-inner'),
	previewSeekTimeCurrent = wrapper.querySelector('.vpl-preview-seek-time-current'),
	settingsToggle = wrapper.querySelector('.vpl-settings-toggle'),
	playbackRateMenu = wrapper.querySelector('.vpl-playback-rate-menu'),
	qualityMenu = wrapper.querySelector('.vpl-quality-menu'),
	qualitySettingsMenu = wrapper.querySelector('.vpl-quality-settings-menu'),
	subtitleMenu = wrapper.querySelector('.vpl-subtitle-menu'),
	subtitleHolder = wrapper.querySelector('.vpl-subtitle-holder'),
	subtitleHolderInner = wrapper.querySelector('.vpl-subtitle-holder-inner'),
	subtitleSettingsMenu = wrapper.querySelector('.vpl-subtitle-settings-menu'),
	audioLanguageMenu = wrapper.querySelector('.vpl-audio-language-menu'),
	audioLanguageMenuHolder = wrapper.querySelector('.vpl-audio-language-menu-holder'),
	audioLanguageToggle = wrapper.querySelector('.vpl-audio-language-toggle'),
	audioLanguageSettingsMenu = wrapper.querySelector('.vpl-audio-language-settings-menu'),
	shareToggle = wrapper.querySelector('.vpl-share-toggle'),
	shareHolder = wrapper.querySelector('.vpl-share-holder'),
	infoHolder = wrapper.querySelector('.vpl-info-holder'),
	infoData = wrapper.querySelector('.vpl-info-data'),
	infoInner = wrapper.querySelector('.vpl-info-inner'),
	infoToggle = wrapper.querySelector('.vpl-info-toggle'),
	infoDesc = wrapper.querySelector('.vpl-info-description'),
	pwdHolder = wrapper.querySelector('.vpl-pwd-holder'),
	pwdField = wrapper.querySelector('.vpl-pwd-field'),
	pwdErrorMsg = wrapper.querySelector('.vpl-pwd-error')?.textContent,
	resumeHolder = wrapper.querySelector('.vpl-resume-holder'),
	embedToggle = wrapper.querySelector('.vpl-embed-toggle'),
	embedHolder = wrapper.querySelector('.vpl-embed-holder'),

	contextMenu = wrapper.querySelector('.vpl-context-menu')




	if(mediaTimeCurrent)mediaTimeCurrent.textContent = ('00:00')
	if(mediaTimeTotal)mediaTimeTotal.textContent = ('00:00')


	if(pipToggle)pipToggle.style.display = 'none'
	if(captionToggle)captionToggle.style.display = 'none'
	if(subtitleHolder)subtitleHolder.style.display = 'none'
	if(audioLanguageToggle)audioLanguageToggle.style.display = 'none'
	if(infoToggle)infoToggle.style.display = 'none'
	if(downloadToggle)downloadToggle.style.display = 'none'

	
	
	//############################################//
	/* vars */
	//############################################//

	var isMobile = VPLUtils.isMobile(),
	hasLocalStorage = VPLUtils.hasLocalStorage(),
	autoPlay = settings.autoPlay,
	volume = Number(settings.volume),
	initialVolume = volume,
	lastVolume = volume || 0.5,//if we click unmute from mute on the beginning
	useSwipeNavigation = settings.useSwipeNavigation && "ontouchstart" in window,
	playbackPositionKey = settings.instanceName + settings.playbackPositionKey, 
 	captionStateKey = settings.instanceName + settings.captionStateKey,
 	rememberPlaybackPosition = settings.rememberPlaybackPosition && hasLocalStorage,

 	autoMargins,
	vpading = 'vpading',
	curr = location.hostname,
	onl = curr.indexOf(window.atob('bG9jYWxob3N0')) < 0 && curr.indexOf(window.atob('MTI3LjAuMC4x')) < 0 && curr != "",
	
	hasFullscreen = VPLUtils.hasFullscreen(),
	isChrome = VPLUtils.isChrome(),
	isSafari = VPLUtils.isSafari(),
	isIOS = VPLUtils.isIOS(),
	isiPhone = VPLUtils.isiPhoneIpod(),
	pictureInPictureEnabled = document.pictureInPictureEnabled,
	mp4Support = VPLUtils.canPlayMp4(),
	mp3Support = VPLUtils.canPlayMp3(),
	wavSupport = VPLUtils.canPlayWav(),
	supportsWebGL = VPLUtils.supportsWebGL(),
	hasDownloadSupport = VPLUtils.hasDownloadSupport(),
	componentInited,
	_doc = document,
	_body = document.body,
	_html = document.documentElement,
	bsf_match = 'ebsfm:',
	lastScrollX = 0, lastScrollY = 0,//restore scroll position after fs exit

	theaterModeOn,
	focusVideoInTheater,

	windowResizeTimeoutID,
	windowResizeTimeout = 250,
	startResizeDone,
	documentFullsceen = false,

	mediaStarted,
	dataInterval = 250,//track progress
	dataIntervalID,
	currMediaData, 
	qualityChange,
	qualityArr = [],
	resumeTime,
	mediaType,
	mediaPath,
	subMediaType,
	mediaCounter = 0,
	mediaPlaying,
	playlistDataArr = [],
	playlistLength,
	lastTime = null,
	documentFullscreen = false,
	interfaceHidden = true,
	idleTimeoutID,
	idleTimeoutID2,
	toggleResumeOpened,

	infoOpened,
	shareOpened,
	mediaForcePause,
	embedOpened,

	//lightbox
	lightboxOpened,

	//audio
	audio,
	audioUp2Js,
	audioInited,
	audioReady,

	sub_xhrRequest,
	ps_xhrRequest,

	//video
	poster,
	isPoster,
	posterExist,
	videoHolder,
	video,
	videoUp2Js,
	videoInited,
	videoReady,

	//image
	imageHolder,
	durationTimeoutID,
	imageStartTime,
	imageDuration,
	image,

	//360 image
	image_360_data,
	image_360_ready,
	icanvas,
	itextureLoader,
 	imaterial,
 	iscene, 
 	irenderer, 
 	imesh,

 	settingsMenuHeightDiff = 60,//player height - settings menu bottom

	//hls
	hls,
	hlsSupport,
	streamHasAudioTracks,
	hlsInited,
	streamVideoBitrateMenuBuilt,
	streamAudioBitrateMenuBuilt,
	streamSubtitleMenuBuilt,
	externalSubtitle,//hls, dash

	//dash
	dash,
	dashSupport = window.MediaSource,
	dashInited,
	dashInitialized,

	//poster
	poster,
	posterHolder,

	//youtube
	youtubeHolder,
	youtubeIframe,
	youtubePlayer,
	youtubeInited,
	youtubeReady,
	youtubePlayed,
	youtubeStarted,
	youtubeBlocker,

	//vimeo
	_VBVimeoLoader,
	activeVimeoIframe,
	activeVimeoPlayer,
	activeVimeoHolder,
	vimeoPlayerDefault,
	vimeoPlayerChromeless,
	vimeoHolderDefault,
	vimeoHolderChromeless,
	vimeoInitedDefault,
	vimeoInitedChromeless,
	vimeoIframeDefault,
	vimeoIframeChromeless,
	vimeoReadyDefault,
	vimeoReadyChromeless,
	vimeoStarted,
	vimeoPlayed,
	vimeoDuration = 0,//because of promise
	vimeoCurrentTime = 0,
	vimeoProgress = 0,
	vimeoBlocker,

	//360 video
	renderAnimationID,
	supportFor360Video = true,
	vrInfoVisible,
	video_360_data,
	vcanvas,
	vwidth = 640,
	vheight = 360,
	vrenderer,
	videoTexture,
	vscene,
	doRender,

	camera,
	orbitControls,
	cameraCreated,

 	//preview seek
	previewSeekImg,
	previewSeekArr = [],

	//viewport autoplay 
	autoPlayInViewportDone,

	//minimize interval
	playerOffsetTop,
	minimizeIntervalID,
	minimizeInterval = 400,
	isScrollTimeout,

	//playback rate ,quality, subtitles
	activePlaybackRateMenuItem,
	activeQualityMenuItem,
	activeSubtitleMenuItem,
	activeAudioLanguageMenuItem,
	subtitleOn,
	allSubtitleArr = [],//all subtitles for media
	subtitleArr = [],//current subtitle for media
	activeSubtitle,
	subtitleSizeSet,

	unmuteToggleInited,
	unmuteHappened,

	//context menu
	contextMenu,
	contextMenuVideoUrl,
	contextMenuFullscreen,

	//share
	_VPLShareManager,

	useBlob = settings.useBlob && window.location.protocol != "file:",
	burl_request,
	bUrl,
	bUrlIntervalID,

	//preview seek
	previewSeekVideoInited,
	previewSeekAuto,
	previewSeekSnapshotDone,
	videoForPreviewSeek,
    hlsForPreviewSeek,
    dashForPreviewSeek,
	dashForPreviewSeekInitialized,
	previewSeekCanvas,
	previewSeekCanvasCtx,
	previewSeekCanvasWidth,
	previewSeekCanvasHeight,
	previewSeekReady





	//############################################//
	/* setup */
	//############################################//


	//wrapper classes

	var classList = wrapper.className.split(/\s+/), i;
	for(i = 0; i < classList.length; i++) {
	    if (classList[i].indexOf('vpl-skin-flat')>-1) {
	        wrapper.classList.add('vpl-skin-flat');
	        break;
	    }
	}

	wrapper.classList.add('vpl-player')





	if(settings.wrapperMaxWidth){
		if(settings.wrapperMaxWidth.indexOf('px') || settings.wrapperMaxWidth.indexOf('%')){
			wrapper.style.maxWidth = settings.wrapperMaxWidth;
		}
	}



	var lightbox,
	lightboxWrap,
	lightboxClose

	if(settings.playerType == 'lightbox'){

		if(theaterToggle)theaterToggle.remove();
		settings.autoPlayInViewport = false;
		settings.minimizeOnScroll = false;

		var id_sel = 'vpl-lightbox-wrap-'+Math.floor(Math.random() * 999999999)

		lightboxWrap = '<div class="vpl-lightbox-wrap" id="'+id_sel+'">'+
        '<div class="vpl-lightbox"></div>'+
        '<div class="vpl-lightbox-close" title="'+settings.lightboxCloseTooltip+'">'+
            '<svg viewBox="0 0 384 512"><path d="M217.5 256l137.2-137.2c4.7-4.7 4.7-12.3 0-17l-8.5-8.5c-4.7-4.7-12.3-4.7-17 0L192 230.5 54.8 93.4c-4.7-4.7-12.3-4.7-17 0l-8.5 8.5c-4.7 4.7-4.7 12.3 0 17L166.5 256 29.4 393.2c-4.7 4.7-4.7 12.3 0 17l8.5 8.5c4.7 4.7 12.3 4.7 17 0L192 281.5l137.2 137.2c4.7 4.7 12.3 4.7 17 0l8.5-8.5c4.7-4.7 4.7-12.3 0-17L217.5 256z"></path></svg>'+
        '</div>'+
        '</div>'

        _body.insertAdjacentHTML('beforeend', lightboxWrap)
        lightboxWrap = document.getElementById(id_sel)

        lightbox = lightboxWrap.querySelector('.vpl-lightbox')

        lightbox.append(wrapper)
        wrapper.classList.add('vpl-lightbox-center')


        lightboxClose = lightboxWrap.querySelector('.vpl-lightbox-close')
        lightboxClose.addEventListener('click',function(){

        	lightboxWrap.addEventListener('transitionend', function cb(e){
				e.currentTarget.removeEventListener(e.type, cb);

				lightboxWrap.style.display = 'none';
                lightboxOpened = false;
			})
			lightboxWrap.style.opacity = '0';

            if(mediaType)cleanMedia();
        
        });

        if(settings.lightboxBgColor)lightbox.style.backgroundColor = settings.lightboxBgColor;
        if(settings.lightboxCloseBtnColor)lightboxClose.querySelector('svg').style.color = settings.lightboxCloseBtnColor;

        if(settings.clickOnBackgroundClosesLightbox){

            lightbox.addEventListener('click',function(e){

                if(e.target == this){ // only if the target itself has been clicked

                    lightboxWrap.addEventListener('transitionend', function cb(e){
						e.currentTarget.removeEventListener(e.type, cb);

						lightboxWrap.style.display = 'none';
		                lightboxOpened = false;
					})
					lightboxWrap.style.opacity = '0';

		            if(mediaType)cleanMedia();
                }
            });
        }
	}


	if(settings.minimizeOnScroll && settings.useMinimizeCloseBtn){
		wrapper.append(VPLUtils.htmlToElement('<button type="button" class="vpl-minimize-close vpl-contr-btn vpl-btn-reset" title="'+settings.tooltipClose+'">'+settings.minimizeCloseIcon+'</button>'));
	}




	if(!playerControlsMain)interfaceHidden = false







	mediaHolder.addEventListener('click', function(){
		if(!componentInited) return false;
		if(!currMediaData)return false;
		if(settings.displayPosterOnMobile)return false;
		if(mediaType == 'image') return false;
		if(currMediaData.is360) return false;
		//show controls on first tap, pause on second

		if(!interfaceHidden)self.togglePlayback();
		else if(!mediaStarted)self.togglePlayback();
		else if(!playerControlsMain)self.togglePlayback();
	});

	if(typeof window.ap_mediaArr === 'undefined')window.ap_mediaArr = [];
	ap_mediaArr.push({inst: self, id: settings.instanceName});

	if(!supportsWebGL){
		supportFor360Video = false;
		if(vrInfo){
			vrInfo.remove();
			vrInfo = null;
		}
		console.log('This browsers does not support Hardware Acceleration required for 360 playback!');
	    if(isChrome){
	    	console.log('Turn Hardware Acceleration On Within Chrome Browser to enable 360 video!');
	    }
	}

	if(settings.elementsVisibilityArr && settings.elementsVisibilityArr.length)VPLUtils.keysrt(settings.elementsVisibilityArr, 'width');//sort from low to high



	if(isiPhone){//https://stackoverflow.com/questions/39430331/safari-picture-in-picture-custom-html5-video-controller
		if(pipToggle)pipToggle.remove();
	}

	if(!(settings.theaterElement && !VPLUtils.isEmpty(settings.theaterElement) && settings.theaterElementClass && !VPLUtils.isEmpty(settings.theaterElementClass))) delete settings.theaterElement;


	if(settings.displayPosterOnMobile){
		autoPlay = false;
		settings.autoPlayInViewport = false;
	}
	if(settings.autoPlayInViewport || settings.autoPlayAfterFirst){
		autoPlay = false;
	}
	if(autoPlay || settings.autoPlayInViewport){
		volume = 0;

		if(settings.autoPlayInViewport && initialVolume != 0){//unmute html5 video
			['mousedown', 'keydown', 'touchstart'].forEach(function(event) { 
				_doc.addEventListener(event, docUnmuteHandler);
			});
		}

	}

	function docUnmuteHandler(){
		['mousedown', 'keydown', 'touchstart'].forEach(function(event) { 
			_doc.removeEventListener(event, docUnmuteHandler);
		});

		if(!unmuteHappened){
			unmuteHappened = true;
			if(!autoPlayInViewportDone){
				if(videoUp2Js){
					videoUp2Js.volume = initialVolume;
					videoUp2Js.muted = false;
				}
			}
		}
	}




	//hide embed functionality if already embeded 
	if(location.href.indexOf('apvpl/includes/embed.php?') > -1){
		if(embedToggle)embedToggle.remove()
		if(embedHolder)embedHolder.remove()
		settings.isEmbed = true;

		if(settings.preset){

			var fileref = document.createElement("link")
	        fileref.setAttribute("rel", "stylesheet")
	        fileref.setAttribute("type", "text/css")

			if(settings.preset.indexOf('flat')>-1){
				fileref.setAttribute("href", "../source/css/flat.css")
			}else{
				fileref.setAttribute("href", "../source/css/"+settings.preset+".css")
			}

			document.getElementsByTagName("head")[0].appendChild(fileref)
		}
		
	}


	//playback position
	if( rememberPlaybackPosition){
		//save data on page close

		var eventName = isIOS ? "pagehide" : "beforeunload";

		window.addEventListener(eventName, function (event) { 
		    if(window.event)window.event.cancelBubble = true;

	   		if(!componentInited) return false;
			if(!mediaType) return false;

     		if(hasLocalStorage)saveMediaState()

		});

		/*if(isIOS){
			document.addEventListener("unload", function(e){
			    saveMediaState()
			});
		}*/

	}

	function saveMediaState(){

		var d = {
		    volume: volume,
            activeItem: mediaCounter,
		    resumeTime: parseInt(self.getCurrentTime(),10)
		};


		localStorage.setItem(playbackPositionKey, JSON.stringify(d));

	}




	if(settings.youtubePlayerType == 'default')settings.blockYoutubeEvents = false;
	else if(useSwipeNavigation)settings.blockYoutubeEvents = true;


	if(settings.caption_breakPointArr && settings.caption_breakPointArr.length){
		VPLUtils.keysrt(settings.caption_breakPointArr, 'width');//sort from low to high
	}


	//############################################//
	/* share */
	//############################################//
	
	if(settings.useShare){

		if(typeof VPLShareManager === 'undefined'){
			var script = document.createElement('script');
			script.type = 'text/javascript';
			script.src = VPLUtils.qualifyURL(settings.sourcePath+settings.share_js);
			script.onload = script.onreadystatechange = function() {
			    if(!this.readyState || this.readyState == 'complete'){
			    	_VPLShareManager = new VPLShareManager(settings);
			    }
			};
			script.onerror = function(){
				alert("Error loading " + this.src);
			}
			var tag = document.getElementsByTagName('script')[0];
			tag.parentNode.insertBefore(script, tag);
		}else{
			_VPLShareManager = new VPLShareManager(settings);
		}
	  
	}



	//############################################//
	/* swipe */
	//############################################//

	let touchstartX = 0
	let touchendX = 0
	
	if(useSwipeNavigation){
		//https://stackoverflow.com/questions/2264072/detect-a-finger-swipe-through-javascript-on-the-iphone-and-android

		mediaHolder.addEventListener('touchstart', e => {
		    touchstartX = e.changedTouches[0].screenX
		})

		mediaHolder.addEventListener('touchend', e => {
		    touchendX = e.changedTouches[0].screenX
		    checkSwipeDirection()
		})

	}

	function checkSwipeDirection() {

		if(!componentInited) return false;
	  	if(settings.disableVideoSkip) return false;

	    if(touchendX < touchstartX - parseInt(settings.swipeTolerance,10)){
	  	   //left

	    	if(mediaType == 'audio' || 
	    	   mediaType == 'video' && !currMediaData.is360 || 
	    	   mediaType == 'image' && !currMediaData.is360 || 
	    	   mediaType == 'youtube' && settings.youtubePlayerType == 'chromeless' && !currMediaData.is360 || 
	    	   mediaType == 'vimeo' && activeVimeoPlayerType == 'chromeless' && !currMediaData.is360){

	    		if(settings.swipeAction == 'advance'){
	    			self.nextMedia();
	    		}
	    		else if(settings.swipeAction == 'rewind'){
	    			self.seekForward();
	    		}

		   	}
	    }
	    //right
	    if(touchendX > touchstartX + parseInt(settings.swipeTolerance,10)){

	    	if(mediaType == 'audio' || 
	    	   mediaType == 'video' && !currMediaData.is360 || 
	    	   mediaType == 'image' && !currMediaData.is360 || 
	    	   mediaType == 'youtube' && settings.youtubePlayerType == 'chromeless' && !currMediaData.is360 || 
	    	   mediaType == 'vimeo' && activeVimeoPlayerType == 'chromeless' && !currMediaData.is360){

		   		if(settings.swipeAction == 'advance'){
	    			self.previousMedia();
	    		}
	    		else if(settings.swipeAction == 'rewind'){
	    			self.seekBackward();
	    		}
		   	}
	    }
	}

	//############################################//
	/* embed */
	//############################################//
	
	function toggleEmbed(){

		if(settings.rightClickContextMenu == 'custom'){
			if(contextMenu && contextMenu.style.display == 'block')hideContextMenu();
		}


		if(shareHolder && shareHolder.classList.contains('vpl-holder-visible')){
			shareHolder.style.display = 'none'
			shareHolder.classList.remove('vpl-holder-visible');//close share if opened
		}

		if(infoHolder && infoHolder.classList.contains('vpl-holder-visible')){
			infoHolder.style.display = 'none'
			infoHolder.classList.remove('vpl-holder-visible');
		}

		if(settingsHolder && settingsHolder.classList.contains('vpl-holder-visible')){
			settingsHolder.classList.remove('vpl-holder-visible')

			settingsHolder.style.display = 'none';
			settingsHolder.style.width = 'auto';
			settingsHolder.style.height = 'auto';

			settingsHolder.querySelector('.vpl-settings-menu').style.display = 'none';
			settingsHome.style.display = 'block';	
		}

		if(embedHolder.classList.contains('vpl-holder-visible')){

			embedHolder.addEventListener('transitionend', function cb(e){
				e.currentTarget.removeEventListener(e.type, cb);

				embedHolder.style.display = 'none';
				embedOpened = false;
				embedHolder.querySelector('.vpl-embed-field-wrap').classList.remove('vpl-embed-field-wrap-selected')
				
			})
			embedHolder.classList.remove('vpl-holder-visible')

			if(mediaForcePause){//if it was playing
				self.playMedia();
				mediaForcePause = false;
			}

		}else{

			if(settings.pauseVideoOnDialogOpen && mediaPlaying){
				self.pauseMedia();
				mediaForcePause = true;
			}


			//set embed data

			embedHolder.querySelectorAll('.vpl-embed-field-wrap').forEach(function(el){
				el.classList.remove('vpl-embed-field-wrap-selected')
			})

			if(settings.wpEmbedUrl){
				//wp

				var e_url = settings.wpEmbedUrl + 'includes/embed.php?',
				pid_exist

				if(settings.playerId != null && settings.playerId != -1){
					e_url += 'player_id='+settings.playerId 
					pid_exist = true;
				}else{
					//use default preset
				}

				/*if(settings.useSingleVideoEmbed){

					//get track data

					var s = encodeURIComponent(getEmbedCode())

					if(!pid_exist && s.charAt(0) == '&'){
						s = s.substr(1)
					}

					e_url += s

				}else{*/

					if(pid_exist)e_url += '&'

					if(settings.playlistId != null){

						e_url += 'playlist_id='+settings.playlistId+'&active_item='+mediaCounter

						if(settings.useSingleVideoEmbed){
							e_url += '&single_video=1&media_id='+currMediaData.mediaId;
						}

					}else{
						//copy html
						var h = playlistList.querySelector('.vpl-playlist-anon').innerHTML.replace(/\n/g,''),
						v = encodeURIComponent(h)

						e_url += 'plhtml='+v
					}

				//}
				
			}else{

				//manual embed
				var e_url = '';
				if(settings.embedSrc)e_url = settings.embedSrc

			}

			var es = '<iframe width="100%" height="100%" src="'+e_url+'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'

			//embed
			wrapper.querySelector('.vpl-video-embed').textContent = (es)

			//link
			var url = self.getCurrentMediaUrl();
			wrapper.querySelector('.vpl-video-link').textContent = (url)




			embedHolder.style.display = 'block';
    		setTimeout(function(){
				embedHolder.classList.add('vpl-holder-visible');
				embedOpened = true;
			},20);
		}

	}

	/*function getEmbedCode(){

		//we need to use this way because for youtube and other types then get processed, html is not the same on start!

		var skip_keys = ['playlistId','mediaPath','safeTitle']

		var s = ''
		$.each(currMediaData, function(key, value) {
			
		    if(typeof value === 'string'){

		    	if(skip_keys.indexOf(key) > 0) return

		    	key = key.split(/(?=[A-Z])/).join('_').toLowerCase();//camelCase to _

		    	s += '&' + key + '=' + value
		    }
		});

		if((currMediaData.type == 'video' || currMediaData.type == 'audio') && currMediaData.path){
			var path = '', quality = ''
			
			var i, len = currMediaData.path.length, p
			for (i = 0; i < len; i++) {
				p = currMediaData.path[i]

				$.each(p, function(key, value) {

					if(key == 'active' || key == 'defMobile')return;

					if(key == 'label')quality += value + ','
					else path += value + ','
				    console.log(key, i, value)
				   
				});

			}

			path = path.substr(0, path.length - 1);//remove comma
			quality = quality.substr(0, quality.length - 1);

			console.log(path)
			
			s += '&path=' + path + '&label=' + quality 

		}

		//subs

		if(currMediaData.subtitles){

			var i, len = currMediaData.subtitles.length, p
			var subtitle_src = '', subtitle_label = '', subtitle_active

			for (i = 0; i < len; i++) {
				p = currMediaData.subtitles[i]

				if(p['label'] == settings.subtitleOffText)continue;

				subtitle_src += p['src'] + ','
				subtitle_label += p['label'] + ','

				if(p['default'])subtitle_active = p['label']
			}

			subtitle_src = subtitle_src.substr(0, subtitle_src.length - 1);//remove comma
			subtitle_label = subtitle_label.substr(0, subtitle_label.length - 1);

			s += '&subtitle_src=' + subtitle_src + '&subtitle_label=' + subtitle_label 
			if(subtitle_active)s += '&subtitle_active='+subtitle_active
			
		}

		return s;

	}*/

	if(embedHolder){

		embedHolder.addEventListener('click', function(e){

			if(e.target.closest('.vpl-embed-copy')){

				embedHolder.querySelector('.vpl-embed-field-wrap').classList.remove('vpl-embed-field-wrap-selected')

				var target = e.target.closest('.vpl-embed-copy'),
				field = target.closest('.vpl-embed-box').querySelector('.vpl-embed-field-wrap')
				field.classList.add('vpl-embed-field-wrap-selected')

				var data = field.textContent.trim()

				var dummy = document.createElement("input");
				dummy.setAttribute("id", "vpl-copy-url");
				document.body.appendChild(dummy);
				document.getElementById("vpl-copy-url").value = data;
				dummy.select();
				try{
			        document.execCommand("copy");
				}catch(er){}
				document.body.removeChild(dummy);

			}

		})

	}

	//############################################//
	/* share */
	//############################################//

	function toggleShare(){

		if(settings.rightClickContextMenu == 'custom'){
			if(contextMenu && contextMenu.style.display == 'block')hideContextMenu();
		}

		if(infoHolder && infoHolder.classList.contains('vpl-holder-visible')){
			infoHolder.style.display = 'none'
			infoHolder.classList.remove('vpl-holder-visible');//close info if opened
		}

		if(embedHolder && embedHolder.classList.contains('vpl-holder-visible')){
			embedHolder.style.display = 'none'
			embedHolder.classList.remove('vpl-holder-visible');
		}

		if(settingsHolder && settingsHolder.classList.contains('vpl-holder-visible')){

			settingsHolder.classList.remove('vpl-holder-visible')
			settingsHolder.style.display = 'none';
			settingsHolder.style.width = 'auto';
			settingsHolder.style.height = 'auto';

			settingsHolder.querySelector('.vpl-settings-menu').style.display = 'none';
			settingsHome.style.display = 'block';	
		}

		if(shareHolder.classList.contains('vpl-holder-visible')){

			shareHolder.addEventListener('transitionend', function cb(e){
				e.currentTarget.removeEventListener(e.type, cb);

				shareHolder.style.display = 'none';
				shareOpened = false;
				
			})
			shareHolder.classList.remove('vpl-holder-visible')
		
			if(mediaForcePause){//if it was playing
				self.playMedia();
				mediaForcePause = false;
			}

		}else{

			if(settings.pauseVideoOnDialogOpen && mediaPlaying){
				self.pauseMedia();
				mediaForcePause = true;
			}

			shareHolder.style.display = 'block';
    		setTimeout(function(){
				shareHolder.classList.add('vpl-holder-visible');
				shareOpened = true;
			},20);
		}

	}

	//############################################//
	/* description */
	//############################################//

	function toggleInfo(){

		if(settings.rightClickContextMenu == 'custom'){
			if(contextMenu && contextMenu.style.display == 'block')hideContextMenu();
		}

		if(shareHolder && shareHolder.classList.contains('vpl-holder-visible')){
			shareHolder.style.display = 'none'
			shareHolder.classList.remove('vpl-holder-visible');//close share if opened
		}

		if(embedHolder && embedHolder.classList.contains('vpl-holder-visible')){
			embedHolder.style.display = 'none'
			embedHolder.classList.remove('vpl-holder-visible');
		}

		if(settingsHolder && settingsHolder.classList.contains('vpl-holder-visible')){
			settingsHolder.classList.remove('vpl-holder-visible')
			settingsHolder.style.display = 'none';
			settingsHolder.style.width = 'auto';
			settingsHolder.style.height = 'auto';
			settingsHolder.querySelector('.vpl-settings-menu').style.display = 'none';
			settingsHome.style.display = 'block';	
		}

		if(infoHolder.classList.contains('vpl-holder-visible')){

			infoHolder.addEventListener('transitionend', function cb(e){
				e.currentTarget.removeEventListener(e.type, cb);

				infoHolder.style.display = 'none';
				infoOpened = false;
				
			})
			infoHolder.classList.remove('vpl-holder-visible')

			if(mediaForcePause){//if it was playing
				self.playMedia();
				mediaForcePause = false;
			}
			
		}else{

			if(settings.pauseVideoOnDialogOpen && mediaPlaying){
				self.pauseMedia();
				mediaForcePause = true;
			}

			infoHolder.style.display = 'block';
    		setTimeout(function(){
				infoHolder.classList.add('vpl-holder-visible');
				infoOpened = true;
			},20);

		}
	}

	//############################################//
	/* resume modal */
	//############################################//

	function toggleResumeScreen(v){

		if(typeof v !== 'undefined'){
			if(toggleResumeOpened && v == true)return false;
			else if(!toggleResumeOpened && v == false)return false;
			toggleResumeOpened = !v;	
		}

		if(toggleResumeOpened){

			resumeHolder.addEventListener('transitionend', function cb(e){
				e.currentTarget.removeEventListener(e.type, cb);

				resumeHolder.style.display = 'none';
				toggleResumeOpened = false;
				
			})
			resumeHolder.classList.remove('vpl-holder-visible')

		}else{

			resumeHolder.style.display = 'block';
    		setTimeout(function(){
				resumeHolder.classList.add('vpl-holder-visible');
				toggleResumeOpened = true;
			},20);
		}

	}


	
	//############################################//
	/* controls */
	//############################################//

	if(settings.useKeyboardNavigationForPlayback && settings.keyboardControls && settings.keyboardControls.length){

		if(!Array.isArray(settings.keyboardControls)){
			settings.keyboardControls = settings.keyboardControls.split(';')
		}

		wrapper.addEventListener('mouseenter', function(){
			_doc.addEventListener('keydown', handleKeyboard);
		})
		wrapper.addEventListener('mouseleave', function(){
			_doc.removeEventListener('keydown', handleKeyboard);
		})

	}

	function handleKeyboard(e){
	
		if(!componentInited)return false;

		var _keycode = e.keyCode, target = e.target;

		if(target.classList.contains('vpl-search-field'))return true;
		else if(target.classList.contains('vpl-pwd-field'))return true;

		if(settings.modifierKey){

			var i, len = settings.keyboardControls.length, obj
			for(i = 0; i < len; i++){
				obj = settings.keyboardControls[i]

				if(e[settings.modifierKey] && _keycode == obj.keycode){

					if(obj.action == 'seekBackward') {//left arrow
						if(settings.disableVideoSkip) return false;
					  	self.seekBackward(settings.seekTime);
					} 
					else if(obj.action == 'seekForward') {//right arrow
						if(settings.disableVideoSkip) return false;
						self.seekForward(settings.seekTime);
					}
					else if(obj.action == 'togglePlayback') {//space
						e.preventDefault()

						self.togglePlayback();

					}else if (obj.action == 'volumeUp'){//up arrow

						settings.volume += .1;
						if(settings.volume > 1) settings.volume = 1;
						self.setVolume(settings.volume)

					}else if (obj.action == 'volumeDown'){//down arrow

						settings.volume -= .1;
						if(settings.volume < 0) settings.volume = 0;
						self.setVolume(settings.volume)

					}
					else if(obj.action == 'toggleMute') {//m
						self.toggleMute();
					}
					else if(obj.action == 'nextMedia') {//page up
						e.preventDefault()
						self.nextMedia();
					}
					else if(obj.action == 'previousMedia') {//page down
						e.preventDefault()
						self.previousMedia();
					}
					else if(obj.action == 'rewind') {//r
						self.seek(0);
					}
					else if(obj.action == 'toggleFullscreen') {//f
					  	toggleFullscreen();
					} 
					else if(obj.action == 'toggleTheater') {//t
						if(componentSize == 'fullscreen') return false;
						theaterToggle.click();
					}
					else if(obj.action == 'toggleSubtitle') {//c
						self.toggleSubtitle();
					}
					else if(obj.action == 'subtitleSizeUp') {//+
						if(activeSubtitle){
							var fs = parseInt(subtitleHolderInner.style.fontSize,10) + 1;
							subtitleHolderInner.style.fontSize = fs+'px';
						}
					}else if(obj.action == 'subtitleSizeDown') {//-
						if(activeSubtitle){
							var fs = parseInt(subtitleHolderInner.style.fontSize,10) - 1;
							if(fs < 10) fs = 10;
							subtitleHolderInner.style.fontSize = fs+'px';
						}
					}
					else{
						return true;
					}

					break;
				}
			}

		}else{

			var i, len = settings.keyboardControls.length, obj
			for(i = 0; i < len; i++){
				obj = settings.keyboardControls[i]
				if(_keycode == obj.keycode){

					if(obj.action == 'seekBackward') {//left arrow
						if(settings.disableVideoSkip) return false;
					  	self.seekBackward(settings.seekTime);
					} 
					else if(obj.action == 'seekForward') {//right arrow
						if(settings.disableVideoSkip) return false;
						self.seekForward(settings.seekTime);
					}
					else if(obj.action == 'togglePlayback') {//space
						e.preventDefault()

						self.togglePlayback();

					}else if (obj.action == 'volumeUp'){//up arrow

						settings.volume += .1;
						if(settings.volume > 1) settings.volume = 1;
						self.setVolume(settings.volume)

					}else if (obj.action == 'volumeDown'){//down arrow

						settings.volume -= .1;
						if(settings.volume < 0) settings.volume = 0;
						self.setVolume(settings.volume)

					}
					else if(obj.action == 'toggleMute') {//m
						self.toggleMute();
					}
					else if(obj.action == 'nextMedia') {//page up
						e.preventDefault()
						self.nextMedia();
					}
					else if(obj.action == 'previousMedia') {//page down
						e.preventDefault()
						self.previousMedia();
					}
					else if(obj.action == 'rewind') {//r
						self.seek(0);
					}
					else if(obj.action == 'toggleFullscreen') {//f
					  	toggleFullscreen();
					} 
					else if(obj.action == 'toggleTheater') {//t
						if(componentSize == 'fullscreen') return false;
						theaterToggle.click();
					}
					else if(obj.action == 'toggleSubtitle') {//c
						self.toggleSubtitle();
					}
					else if(obj.action == 'subtitleSizeUp') {//+
						if(activeSubtitle){
							var fs = parseInt(subtitleHolderInner.style.fontSize,10) + 1;
							subtitleHolderInner.style.fontSize = fs+'px';
						}
					}else if(obj.action == 'subtitleSizeDown') {//-
						if(activeSubtitle){
							var fs = parseInt(subtitleHolderInner.style.fontSize,10) - 1;
							if(fs < 10) fs = 10;
							subtitleHolderInner.style.fontSize = fs+'px';
						}
					}
					else{
						return true;
					}

					break;
				}
			}
	
		}

	}

	var buttons = [
		playbackToggle,
		bigPlay,
		infoToggle,
		embedToggle,
		shareToggle,
		nextToggle,
		prevToggle,
		rewindToggle,
		skipBackwardToggle,
		skipForwardToggle,
		pipToggle,
		theaterToggle,
		captionToggle,
		wrapper.querySelector('.vpl-pwd-confirm'),
		wrapper.querySelector('.vpl-info-close'),
		wrapper.querySelector('.vpl-share-item'),
		wrapper.querySelector('.vpl-share-close'),
		wrapper.querySelector('.vpl-resume-continue'),
		wrapper.querySelector('.vpl-resume-restart'),
		wrapper.querySelector('.vpl-embed-close'),
		wrapper.querySelector('.vpl-minimize-close'),
	]

	var shareList = wrapper.querySelectorAll('.vpl-share-item'),
	shares = [...shareList]; 

	var buttonArr = buttons.concat(shares);

	var btn,len = buttonArr.length,i;
	for(i = 0;i<len;i++){
		btn = buttonArr[i]
		if(btn){
			btn.style.cursor = 'pointer';
			btn.addEventListener('click', clickControls);
		}
	}

	function clickControls(e){
		e.preventDefault();
		e.stopPropagation()
		if(!componentInited) return false;
		
		var currentTarget = e.currentTarget;
		
		if(currentTarget.classList.contains('vpl-playback-toggle') || currentTarget.classList.contains('vpl-big-play')){	
			self.togglePlayback();
		}else if(currentTarget.classList.contains('vpl-info-toggle')){	
			toggleInfo();
		}else if(currentTarget.classList.contains('vpl-info-close')){	
			toggleInfo();
		}else if(currentTarget.classList.contains('vpl-share-close')){	
			toggleShare();

		}else if(currentTarget.classList.contains('vpl-embed-close')){	
			toggleEmbed(false);

		}else if(currentTarget.classList.contains('vpl-embed-toggle')){	
			toggleEmbed();	

		}else if(currentTarget.classList.contains('vpl-resume-continue')){
			toggleResumeScreen(false);	
			if(initialVolume != 0 && volume == 0 && !unmuteHappened){
				unmuteHappened = true;
				volume = initialVolume;
			}
			setMedia();
		}else if(currentTarget.classList.contains('vpl-resume-restart')){		
			toggleResumeScreen(false);	
			if(initialVolume != 0 && volume == 0 && !unmuteHappened){
				unmuteHappened = true;
				volume = initialVolume;
			}	
			resumeTime = 0;
			setMedia();
		}else if(currentTarget.classList.contains('vpl-cc-toggle')){		
			self.toggleSubtitle();	
		}else if(currentTarget.classList.contains('vpl-pwd-confirm')){	

			var pwd = pwdField.value;

			if(VPLUtils.isEmpty(pwd)){
				alert(pwdErrorMsg);
			}else{

				if(typeof md5 === 'undefined'){

					var script = document.createElement('script');
					script.type = 'text/javascript';
					if(!VPLUtils.relativePath(settings.md5_js))var src = VPLUtils.qualifyURL(settings.sourcePath+settings.md5_js);
					else var src = settings.md5_js;
					script.src = src;
					script.onload = script.onreadystatechange = function() {
					    if(!this.readyState || this.readyState == 'complete'){

					    	if(md5(pwd) != currMediaData.pwd){
								alert(pwdErrorMsg);
							}else{
								delete currMediaData.pwd;

								pwdHolder.addEventListener('transitionend', function cb(e){
									e.currentTarget.removeEventListener(e.type, cb);

									pwdHolder.style.display = 'none';
								})
								pwdHolder.classList.remove('vpl-holder-visible');
								pwdField.value = '';

								if(mediaType == 'audio'){
									if(posterExist)setPoster();
									else setMedia();
								}else{
									if(!autoPlay && posterExist)setPoster();
									else setMedia();
								}
												
							}

					    }
					};
					script.onerror = function(){
						alert("Error loading " + this.src);
					}
					var tag = document.getElementsByTagName('script')[0];
					tag.parentNode.insertBefore(script, tag);

				}else{

					if(md5(pwd) != currMediaData.pwd){
						alert(pwdErrorMsg);
					}else{
						delete currMediaData.pwd;

						pwdHolder.addEventListener('transitionend', function cb(e){
							e.currentTarget.removeEventListener(e.type, cb);

							pwdHolder.style.display = 'none';
						})
						pwdHolder.classList.remove('vpl-holder-visible');
						pwdField.value = '';

						if(mediaType == 'audio'){
							if(posterExist)setPoster();
							else setMedia();
						}else{
							if(!autoPlay && posterExist)setPoster();
							else setMedia();
						}
									
					}
				}
			}

		}else if(currentTarget.classList.contains('vpl-previous-toggle')){	
			if(settings.disableVideoSkip) return false;
			self.previousMedia();
		}else if(currentTarget.classList.contains('vpl-next-toggle')){	
			if(settings.disableVideoSkip) return false;
			self.nextMedia();
		}else if(currentTarget.classList.contains('vpl-rewind-toggle')){	
			self.seek(0);
		}else if(currentTarget.classList.contains('vpl-skip-backward-toggle')){
			if(settings.disableVideoSkip) return false;	
			self.seekBackward();
		}else if(currentTarget.classList.contains('vpl-skip-forward-toggle')){	
			if(settings.disableVideoSkip) return false;
			self.seekForward();
		}else if(currentTarget.classList.contains('vpl-share-toggle')){	
			toggleShare();

		}else if(currentTarget.classList.contains('vpl-minimize-close')){	
			
		    window.removeEventListener('scroll', minimizeScrollHander);
			settings.minimizeOnScroll = false;
			wrapper.classList.remove(settings.minimizeClass);
			doneResizing()

		}else if(currentTarget.classList.contains('vpl-pip-toggle')){	
			
			if(videoUp2Js){

				if(pictureInPictureEnabled){
				    if(!document.pictureInPictureElement) {
				    	try{
							videoUp2Js.requestPictureInPicture();
						}catch(er){}
				    }else{
				    	try{
							document.exitPictureInPicture();
						}catch(er){}
				    }
				}
				else if(videoUp2Js.webkitSupportsPresentationMode && typeof videoUp2Js.webkitSetPresentationMode === "function"){
					videoUp2Js.webkitSetPresentationMode(videoUp2Js.webkitPresentationMode === "picture-in-picture" ? "inline" : "picture-in-picture");
				}

			}

		}else if(currentTarget.classList.contains('vpl-theater-toggle')){		

			//only in normal mode this button is available, in fs we cant use theater

			if(theaterModeOn){

				if(settings.theaterElement)document.querySelector(settings.theaterElement).classList.remove(settings.theaterElementClass);

				self.fireEvent('beforeTheaterExit', [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

				wrapper.classList.remove('vpl-theater');
				doneResizing();

			}else{

				if(settings.theaterElement)document.querySelector(settings.theaterElement).classList.add(settings.theaterElementClass);

				self.fireEvent('beforeTheaterEnter', [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

				wrapper.classList.add('vpl-theater');
				doneResizing();
				setTimeout(function(){doneResizing();},250);//to resize once again if vertical scrollbar appears

				if(settings.focusVideoInTheater){
		        	var rect = playerHolder.getBoundingClientRect();
					var top = rect.top + window.scrollY;
					document.querySelector('html,body').scrollTo({
					   top: top,
					   behavior: 'smooth'
					});
				}

			}

			theaterModeOn = !theaterModeOn;

		}else{
			
			if(currentTarget.classList.contains('vpl-share-item')){		
 		    	if(!mediaType)return false;

				if(_VPLShareManager)_VPLShareManager.share(currentTarget.getAttribute('data-type').toLowerCase(), currMediaData, self.getCurrentMediaUrl());
			}
		}
	}

	//############################################//
	/* fullscreen */
	//############################################//

	var componentSize = 'normal',
	fullscreenRequestId;//which instance requested fullscreen (if there are multiple instances in page)

	['fullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange', 'webkitfullscreenchange'].forEach( evt => 
	    _doc.addEventListener(evt, handleFsAction)
	);

	function handleFsAction(){

		if(fullscreenRequestId == settings.instanceName){

			if(componentSize == "fullscreen"){
				fullscreenExitAction();
			}else{
				fullscreenEnterAction();
			}

		}
	}
 
	var fullscreenToggle = wrapper.querySelector('.vpl-fullscreen-toggle')
	if(fullscreenToggle){
		fullscreenToggle.addEventListener('click', function(e){
			toggleFullscreen();
		});
	}

	function toggleFullscreen(){

		//cancel theater mode
		theaterModeOn = false;
		wrapper.classList.remove('vpl-theater');
		if(settings.theaterElement)document.querySelector(settings.theaterElement).classList.remove(settings.theaterElementClass);

		fullscreenRequestId = settings.instanceName;

		var elem;
		if(documentFullsceen){
			elem = document.documentElement;
		}else{
			elem = wrapper;	
		}

		if(componentSize == "normal"){
			var d = document, r = d.documentElement, b = d.body;
            lastScrollX = r.scrollLeft || b.scrollLeft || 0;
            lastScrollY = r.scrollTop  || b.scrollTop  || 0;

			self.fireEvent('fullscreenBeforeEnter', [{instance:self, instanceName: settings.instanceName, media:currMediaData}]);
		}

		if(hasFullscreen){
				
			if (elem.requestFullscreen) {
				if (document.fullscreenElement) {
					document.exitFullscreen();
				} else {
					elem.requestFullscreen();
				}
			} else if (elem.webkitRequestFullScreen) {
				if (document.webkitIsFullScreen) {
					document.webkitCancelFullScreen();
				} else {
					elem.webkitRequestFullScreen();
				}
			} else if (elem.msRequestFullscreen) {
				if (document.msIsFullscreen || document.msFullscreenElement) {
					document.msExitFullscreen();
				} else {
					elem.msRequestFullscreen();
				}
			} else if (elem.mozRequestFullScreen) {
				if (document.fullscreenElement || document.mozFullScreenElement) {
					document.mozCancelFullScreen();
				} else {
					elem.mozRequestFullScreen();
				}
			}

		}else{

			if(componentSize == "fullscreen"){
				fullscreenExitAction();
			}else{
				fullscreenEnterAction();
			}

		}

		if('onorientationchange' in window){
			setTimeout(function(){
				doneResizing();
			}, 250);
		}	

	}

	function fullscreenEnterAction(){

		componentSize = "fullscreen";
		_html.classList.add('vpl-fs-overflow');
		wrapper.classList.add('vpl-fs');

		if(fullscreenToggle){
			fullscreenToggle.querySelector('.vpl-btn-fullscreen').style.display = 'none';
			fullscreenToggle.querySelector('.vpl-btn-normal').style.display = 'block';
		}

		if(theaterToggle)theaterToggle.style.display = 'none';
		document.body.style.cursor = 'default';

		if(settings.rightClickContextMenu == 'custom'){
			contextMenuFullscreen.querySelector('.vpl-context-fullscreen-enter').style.display = 'none';
			contextMenuFullscreen.querySelector('.vpl-context-fullscreen-exit').style.display = 'block';
		}

		self.fireEvent('fullscreenEnter', [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

	}

	function fullscreenExitAction(){

		componentSize = "normal";
		_html.classList.remove('vpl-fs-overflow');
		wrapper.classList.remove('vpl-fs');

		if(fullscreenToggle){
			fullscreenToggle.querySelector('.vpl-btn-fullscreen').style.display = 'block';
			fullscreenToggle.querySelector('.vpl-btn-normal').style.display = 'none';
		}

		if(theaterToggle)theaterToggle.style.display = 'block';
		document.body.style.cursor = 'default';

		if(settings.rightClickContextMenu == 'custom'){
			contextMenuFullscreen.querySelector('.vpl-context-fullscreen-enter').style.display = 'block';
			contextMenuFullscreen.querySelector('.vpl-context-fullscreen-exit').style.display = 'none';
		}

		fullscreenRequestId = null;

		window.scrollTo(lastScrollX, lastScrollY);

		self.fireEvent('fullscreenExit', [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

	}

	if(fullscreenToggle){
		//set visibility on start
		fullscreenToggle.querySelector('.vpl-btn-normal').style.display = 'none';
		fullscreenToggle.querySelector('.vpl-btn-fullscreen').style.display = 'block';
	}
	

	//############################################//
	/* context menu */
	//############################################//

	if(settings.rightClickContextMenu == 'disabled'){

		wrapper.addEventListener("contextmenu", function(e){
			e.preventDefault();
		});

	}else if(settings.rightClickContextMenu == 'custom'){

		contextMenu.addEventListener("contextmenu", function(e){//prevent right click on context menu
		    e.preventDefault();
		});

		contextMenuVideoUrl = contextMenu.querySelector(".vpl-context-copy-video-url")

		if(contextMenuVideoUrl)contextMenuVideoUrl.addEventListener("click", function(e){

			var url = self.getCurrentMediaUrl();

			var dummy = document.createElement("input");
			dummy.setAttribute("id", "vpl-copy-url");
			document.body.appendChild(dummy);
			document.getElementById("vpl-copy-url").value = url;
			dummy.select();
			try{
		        document.execCommand("copy");
			}catch(er){}
			document.body.removeChild(dummy);

		});

		contextMenuFullscreen = contextMenu.querySelector(".vpl-context-fullscreen")
		if(contextMenuFullscreen){
			contextMenuFullscreen.addEventListener("click", function(e){
				toggleFullscreen();
			});
		}

		playerHolder.addEventListener("contextmenu", showContextMenu)
		playerHolder.addEventListener("mouseleave", hideContextMenu);

		_body.addEventListener("mouseleave", hideContextMenu);
		_doc.addEventListener("contextmenu", hideContextMenu)
		_doc.addEventListener('keyup', function(e){
		    if(e.keyCode == 27){//esc
			    hideContextMenu();
		    }  
		});

	}

	function hideContextMenu(){
		_body.removeEventListener("click", hideContextMenu);
		contextMenu.style.display = 'none';
	}

	function showContextMenu(e){
		e.preventDefault();
		e.stopPropagation();

		if(settings.displayPosterOnMobile)return false;

		if(seekBarDown)return false;
		if(e.target.classList.contains('vpl-volume-level'))return false;//volume
		if(e.target.classList.contains('vpl-volume-bg'))return false;
		if(e.target.classList.contains('vpl-progress-level'))return false;//seekbar
		if(e.target.classList.contains('vpl-progress-bg'))return false;
		if(e.target.classList.contains('vpl-load-level'))return false;
		if(pwdHolder.style.display == 'block')return false;

		contextMenu.style.display = 'block';

		var main = playerHolder.getBoundingClientRect(),
		    cw = contextMenu.offsetWidth, ch = contextMenu.offsetHeight,
		    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
			scrollTop = window.pageYOffset || document.documentElement.scrollTop,
			l = parseInt(e.pageX - scrollLeft - main.left, 10),
			t = parseInt(e.pageY - scrollTop - main.top,10);

		//keep within component
		if(l > playerHolder.offsetWidth - cw)l -= cw;
		if(t > playerHolder.offsetHeight - ch)t -= ch;	

		contextMenu.style.left = l+'px';
		contextMenu.style.top = t+'px';

		_body.addEventListener("click", hideContextMenu);

	}


	//############################################//
	/* interface idle */
	//############################################//

	function startIdleTimer() {

		if(!mediaType)return;
		if(mediaType == 'youtube' && settings.youtubePlayerType == 'default' || mediaType == 'vimeo' && settings.vimeoPlayerType == 'default')return;

    	idleTimeoutID = window.setTimeout(function(){
    		if(mediaPlaying){

    			playerControlsMain.addEventListener('transitionend', function cb(e){
					e.currentTarget.removeEventListener(e.type, cb);
					interfaceHidden = true;
				})
    			playerControls.forEach(el => {
    				el.classList.remove('vpl-player-controls-visible');
    			})

				if(activeSubtitle)subtitleHolderInner.classList.remove('vpl-subtitle-raised');
				if(componentSize == "fullscreen")document.body.style.cursor = 'none';
				if(tooltip)tooltip.style.display = 'none';

				if(currMediaData.previewSeek){
					previewSeekWrap.style.display = 'none';
					if(previewSeekAuto)stopDrawPreviewSeek()
				}
    		}
    	}, settings.idleTimeout);

	}
	 
	function resetIdleTimer(e) {

		if(!mediaType)return;
		if(mediaType == 'youtube' && settings.youtubePlayerType == 'default' || mediaType == 'vimeo' && settings.vimeoPlayerType == 'default')return;

	    if(idleTimeoutID)clearTimeout(idleTimeoutID);

	    if(mediaStarted){

	    	if(interfaceHidden){

	    		if(idleTimeoutID2)clearTimeout(idleTimeoutID2);
		    	idleTimeoutID2 = window.setTimeout(function(){

		    		playerControlsMain.addEventListener('transitionend', function cb(e){
						e.currentTarget.removeEventListener(e.type, cb);
						interfaceHidden = false;
					})
	    			playerControls.forEach(el => {
	    				el.classList.add('vpl-player-controls-visible');
	    			})

					if(activeSubtitle)subtitleHolderInner.classList.add('vpl-subtitle-raised');
					if(componentSize == "fullscreen")document.body.style.cursor = 'default';

		    	},50);

		    }
		    
		}

		if(playerControlsMain && mediaPlaying)startIdleTimer();
	}

	//############################################//
	/* tooltips */
	//############################################//

	var tooltip = wrapper.querySelector('.vpl-tooltip');

	if(!isMobile && tooltip){

		wrapper.querySelectorAll('[data-tooltip]').forEach(el => {

			el.addEventListener("mouseenter", function(e){
				showTooltip(e)
			})

			el.addEventListener("mouseleave", function(e){
				tooltip.style.display = 'none';
			});	

		})

	}

	function showTooltip(e){

		var item = e.currentTarget

		var text = item.getAttribute('data-tooltip')
		if(VPLUtils.isEmpty(text))return false;

		tooltip.style.display = 'block';

		tooltip.innerHTML = text;

		var main = wrapper.getBoundingClientRect(),
		element = item.getBoundingClientRect();

		if(item.getAttribute('data-tooltip-position') != undefined){

			if(item.getAttribute('data-tooltip-position') == 'left'){

				var t = parseInt(element.top - main.top - tooltip.offsetHeight/2 + item.offsetHeight/2,10),
				l = parseInt(element.left - main.left - tooltip.offsetWidth - 3,10);

			}
			else if(item.getAttribute('data-tooltip-position') == 'bottom'){

				var t = parseInt(element.top - main.top + item.offsetHeight,10),
				l = parseInt(element.left - main.left - tooltip.offsetWidth/2 + item.offsetWidth/2,10);

			}
		}
		else{//top

			var t = parseInt(element.top - main.top - tooltip.offsetHeight,10),
			l = parseInt(element.left - main.left - tooltip.offsetWidth/2 + item.offsetWidth/2,10);

		}
		
		//keep within component
		if(l < 0) l = 0;
		else if(l + tooltip.offsetWidth > wrapper.offsetWidth)l = wrapper.offsetWidth - tooltip.offsetWidth;

		if(t + main.top < 0)t = parseInt(element.top - main.top + tooltip.offsetHeight + 15,10);//position below

		tooltip.style.left = l+'px';
		tooltip.style.top = t+'px';

	}

	//############################################//
	/* seekbar */
	//############################################//

	var seekBarDown,
	seekbarSize

	if(seekbar){
		seekbar.addEventListener('pointerdown',function(e){
			if(!componentInited) return;
			if(settings.disableVideoSkip) return false;
			
			if(!seekBarDown){					
				
				seekBarDown = true;

				document.addEventListener('pointermove', onDragMoveSeek)
				document.addEventListener('pointerup', onDragReleaseSeek)

			}

		});
	}
				
	function onDragMoveSeek(e) {	
		setProgress(e);

		if(tooltip)mouseMoveHandlerSeekTooltip(e);
	}
	
	function onDragReleaseSeek(e) {
		if(seekBarDown){	
			seekBarDown = null;		
		
			document.removeEventListener('pointermove', onDragMoveSeek)
			document.removeEventListener('pointerup', onDragReleaseSeek);	

			setProgress(e);
			
		}
	}	

	function setProgress(e) {

		var rect = progressBg.getBoundingClientRect();
		
		var seekPercent = e.clientX - rect.left;
		if(seekPercent < 0) seekPercent = 0;
		else if(seekPercent > seekbarSize) seekPercent = seekbarSize;
		var v = Math.max(0, Math.min(1, seekPercent / seekbarSize));

		if(mediaType == 'audio'){

			try{
				if(audioUp2Js)audioUp2Js.currentTime = v * audioUp2Js.duration;
			}catch(er){console.log(er)}

		}else if(mediaType == 'video'){

			try{
				if(videoUp2Js)videoUp2Js.currentTime = v * videoUp2Js.duration;
			}catch(er){console.log(er)}

		}else if(mediaType == 'youtube'){
		
			if(youtubePlayer)youtubePlayer.seekTo(v * youtubePlayer.getDuration());

		}else if(mediaType == 'vimeo'){

			if(settings.vimeoPlayerType == 'chromeless'){
				playerLoader.style.display = 'block';//slow vimeo api!
			}

			if(activeVimeoPlayer){
				if(vimeoDuration != 0){
					var d = vimeoDuration,
					t = v * d;
					activeVimeoPlayer.setCurrentTime(t).then(function(seconds) {
					}).catch(function(error) {
						//console.log(error)
					});
				}else{
					activeVimeoPlayer.getDuration().then(function(duration) {
						var d = duration,
						t = v * d;
						activeVimeoPlayer.setCurrentTime(t).then(function(seconds) {
						}).catch(function(error) {
							//console.log(error)
						});
					});
				}
			}

		}

		if(!mediaPlaying){
			trackProgress(true);//update screen elements 
		}

	}

	//############################################//
	/* seekbar tooltip */
	//############################################//

	if(seekbar){

		if(!isMobile){

			seekbar.addEventListener('mouseover', function mouseOverHandlerSeek(){
				if(!mediaType) return false;
				if(!mediaStarted && !currMediaData && !currMediaData.duration) return false;

				seekbar.addEventListener("pointermove", mouseMoveHandlerSeekTooltip)
				seekbar.addEventListener('mouseout', mouseOutHandlerSeek);
				_doc.addEventListener('mouseout', mouseOutHandlerSeek);

			});

		}

		if('ontouchstart' in window){
			_doc.addEventListener('touchend', onDragReleaseSeekTooltip);
		}
		
	}

	function onDragReleaseSeekTooltip(e) {
		if(tooltip)tooltip.style.display = 'none';
		if(previewSeekWrap)previewSeekWrap.style.display = 'none';
	}

	function mouseOutHandlerSeek() {
		if(!mediaStarted && !currMediaData && !currMediaData.duration) return;

		seekbar.removeEventListener("pointermove", mouseMoveHandlerSeekTooltip)
		seekbar.removeEventListener('mouseout', mouseOutHandlerSeek);
		_doc.removeEventListener('mouseout', mouseOutHandlerSeek);
		
		if(currMediaData.previewSeek){
			previewSeekWrap.style.display = 'none';
			if(previewSeekAuto)stopDrawPreviewSeek()
		}
		if(tooltip)tooltip.style.display = 'none';

	}	
	
	function mouseMoveHandlerSeekTooltip(e){

		var rect = progressBg.getBoundingClientRect();

		var s = e.clientX - rect.left;
		if(!VPLUtils.isNumber(s))return false;
		if(s < 0) s = 0;
		else if(s > seekbarSize) s = seekbarSize;
		var newPercent = Math.max(0, Math.min(1, s / seekbarSize));

		if(!VPLUtils.isNumber(newPercent))return false;

		var main = wrapper.getBoundingClientRect(),
			element = seekbar.getBoundingClientRect();

		var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
			scrollTop = window.pageYOffset || document.documentElement.scrollTop;

		if(mediaType == 'audio'){
			var d = audioUp2Js.duration;
		}else if(mediaType == 'video'){
			var d = videoUp2Js.duration;
		}else if(mediaType == 'youtube'){
			var d = youtubePlayer.getDuration();	
		}else if(mediaType == 'vimeo'){
			var d = vimeoDuration;	
		}

		if(currMediaData.previewSeek){

			previewSeekWrap.style.display = 'block';

			var time = d*newPercent;

			if(VPLUtils.isNumber(d))previewSeekTimeCurrent.textContent = (VPLUtils.formatTime(time));

			var i, len = previewSeekArr.length, item, start, end;
			for(i = 0; i < len; i++){
				item = previewSeekArr[i];
				start = item.start, end = item.end;
		
				if(time >= start && time <= end){
					if(!previewSeekImg){

						var data = item.url, fragment = data.substr(data.lastIndexOf('=')+1), f = fragment.split(','), pos = '-'+f[0]+'px' + ' ' +  '-'+f[1]+'px';

						previewSeekImg = {};
						previewSeekImg.start = start;
						previewSeekImg.end = end;

						previewSeekInner.style.backgroundImage = 'url("'+data+'")';
						previewSeekInner.style.backgroundPosition = pos;
					}
				}else{
					if(previewSeekImg){
						if(time < previewSeekImg.start || time > previewSeekImg.end){
							previewSeekImg = null;
						}
					}
				}
			}

			var l = e.pageX - scrollLeft - main.left - previewSeekWrap.offsetWidth/2,
			t = element.top - main.top - previewSeekWrap.offsetHeight - 10;

			//keep within component
			if(settings.playerType != 'lightbox'){
				if(l < 0) l = 0;
				else if(l + previewSeekWrap.offsetWidth > wrapper.offsetWidth)l = wrapper.offsetWidth - previewSeekWrap.offsetWidth;
			}

			if(previewSeekAuto){
				startDrawPreviewSeek(time)
			}

			previewSeekWrap.style.left = parseInt(l,10)+'px';
			previewSeekWrap.style.top = parseInt(t,10)+'px';

		}else{	

			tooltip.innerHTML = VPLUtils.formatTime(d*newPercent)+' / '+VPLUtils.formatTime(d);

			tooltip.style.display = 'block';

			var l = e.clientX - scrollLeft - main.left - tooltip.offsetWidth/2,
				t = element.top - main.top - tooltip.offsetHeight;

			//keep within component
			if(settings.playerType != 'lightbox'){
				if(l < 0) l = 0;
				else if(l + tooltip.offsetWidth > wrapper.offsetWidth)l = wrapper.offsetWidth - tooltip.offsetWidth;
			}

			tooltip.style.left = parseInt(l,10)+'px';
			tooltip.style.top = parseInt(t,10)+'px';
		}

	}
	
	//############################################//
	/* volume */
	//############################################//
		
	var volumeBarDown,
	volumeHorizontal = volumeSeekbar?.classList.contains('vpl-volume-horizontal'),
	volumeSize

	if(volume<0) volume = 0;
	else if(volume>1)volume = 1;
	if(volume!=0)lastVolume = volume;

	if(volumeSeekbar){
		volumeSeekbar.addEventListener('pointerdown',function(e){
		
			if(!volumeBarDown){					
				
				volumeBarDown = true;

				document.addEventListener('pointermove', onDragMoveVolume)
				document.addEventListener('pointerup', onDragReleaseVolume)

			}

		});
	}
	
	if(volumeToggle){
		volumeToggle.addEventListener('click',function(e){
			toggleMute();
		});
	}
				
	function onDragMoveVolume(e) {	
		volumeTo(e);
	}
	
	function onDragReleaseVolume(e) {
		if(volumeBarDown){	
			volumeBarDown = false;			
			
			document.removeEventListener('pointermove', onDragMoveVolume)
			document.removeEventListener('pointerup', onDragReleaseVolume)
			
			volumeTo(e);
		}
	}	

	function toggleMute(){
		if(!componentInited) return false;
		if(volume>0){
			lastVolume = volume;//remember last volume
			volume = 0;
		}else{
			volume = lastVolume;//restore last volume
		}
		setVolume();
	}

	function volumeTo(e) {

		var rect = volumeBg.getBoundingClientRect()

		if(!VPLUtils.isNumber(volumeSize) || volumeSize == 0)volumeSize = volumeHorizontal ? rect.width : rect.height;

		if(volumeHorizontal){
			volume = Math.max(0, Math.min(1, (e.clientX - rect.left) / volumeSize));
		}else{
			volume = Math.max(0, Math.min(1, (e.clientY - rect.top) / volumeSize));
			volume = 1 - volume;//reverse for up dir
		} 

		self.setVolume();
	}

	function setVolume(v){

		if(typeof v !== 'undefined')volume = v;

		if(typeof volume !== 'undefined'){

			if(mediaType == 'audio'){

				if(audioUp2Js)audioUp2Js.volume = volume;

			}else if(mediaType == 'video'){
				
				if(videoUp2Js){
					videoUp2Js.volume = volume;
					if(volume == 0){
						videoUp2Js.muted = true;
					}else{
						videoUp2Js.muted = false;
					}
				}

			}else if(mediaType == 'youtube'){

				if(youtubePlayer){
					youtubePlayer.setVolume(volume * 100);
					if(volume == 0){
						youtubePlayer.mute();
					}else{
						youtubePlayer.unMute();
					}
				}

			}else if(mediaType == 'vimeo'){
				if(activeVimeoPlayer)activeVimeoPlayer.setVolume(volume);
			}
		}

		if(volumeSeekbar)self.adjustvolumeVisual();

	}

	this.adjustvolumeVisual = function(){

		var rect = volumeBg.getBoundingClientRect();

		volumeSize = volumeHorizontal ? volumeBg.offsetWidth : rect.height;

		if(volumeSize == 0){
			//controls are hidden on start so we need to get volume after they show
			setTimeout(function(){
				self.adjustvolumeVisual();
			},200)
			return
		}

		var prop = volumeHorizontal ? 'width' : 'height';

		if(prop == 'width')volumeLevel.style.width = volume * volumeSize+'px';
		else volumeLevel.style.height = volume * volumeSize+'px';

		//hide all volume btns
		volumeToggle.querySelectorAll('.vpl-btn').forEach(function(el){
			el.style.display = 'none';
		})

		if(volume == 0){
			volumeToggle.querySelector('.vpl-btn-volume-off').style.display = 'block';
		}else if(volume > 0 && volume < 0.5){	
			volumeToggle.querySelector('.vpl-btn-volume-down').style.display = 'block';
		}else{
			volumeToggle.querySelector('.vpl-btn-volume-up').style.display = 'block';
		}

		if(volume > 0){
			if(unmuteToggle){
				unmuteToggle.remove();
				unmuteToggle = null;
			}
		}
	}

	//############################################//
	/* volume tooltip */
	//############################################//

	if(!isMobile && tooltip && volumeSeekbar){
		volumeSeekbar.addEventListener('mouseover', function mouseOverHandlerSeek(){
			volumeSeekbar.addEventListener('pointermove', mouseMoveHandlerVolumeTooltip);
			volumeSeekbar.addEventListener('mouseout', mouseOutHandlerVolume);
			_doc.addEventListener('mouseout', mouseOutHandlerVolume);
		});
	}

	function mouseOutHandlerVolume() {
		volumeSeekbar.removeEventListener('pointermove', mouseMoveHandlerVolumeTooltip);
		volumeSeekbar.removeEventListener('mouseout', mouseOutHandlerVolume);
		_doc.removeEventListener('mouseout', mouseOutHandlerVolume);
		tooltip.style.display = 'none';
	}

	function mouseMoveHandlerVolumeTooltip(e){

		var rect = volumeBg.getBoundingClientRect();

		if(volumeHorizontal)var s = e.pageX - rect.left;
		else var s = e.pageY - rect.top;

		volumeSize = volumeHorizontal ? rect.width : rect.height;

		if(s<0) s=0;
		else if(s>volumeSize) s=volumeSize;

		var newPercent = Math.max(0, Math.min(1, s / volumeSize));
		if(!VPLUtils.isNumber(newPercent))return false;
		if(!volumeHorizontal)newPercent = 1 - newPercent;//reverse for up dir
		var value=parseInt(newPercent * 100, 10);

		tooltip.innerHTML = value+' %';

		tooltip.style.display = 'block';

		var main = wrapper.getBoundingClientRect(),
		element = volumeSeekbar.getBoundingClientRect();

		var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
			scrollTop = window.pageYOffset || document.documentElement.scrollTop;

		if(volumeHorizontal){
			var l = parseInt(e.pageX - scrollLeft - main.left - tooltip.offsetWidth/2),
				t = parseInt(element.top - main.top - tooltip.offsetHeight);
		}else{
			var l = parseInt(element.left - main.left - tooltip.offsetWidth/2 + volumeSeekbar.offsetWidth/2),
				t = parseInt(e.pageY - scrollTop - main.top - tooltip.offsetHeight - 10);
		}
		
		tooltip.style.left = l+'px';
		tooltip.style.top = t+'px';
	}


	//############################################//
	/* settings menu */
	//############################################//

	//grouped controls

	var settingsWrap = wrapper.querySelector('.vpl-settings-wrap'),
	settingsHolder = wrapper.querySelector('.vpl-settings-holder'),
	settingsHolderInner = wrapper.querySelector('.vpl-settings-holder-inner'),
	settingsHome = wrapper.querySelector('.vpl-settings-home');

	if(settingsToggle){
		settingsToggle.addEventListener('click', function() {

	    	if(settingsHolder.classList.contains('vpl-holder-visible')){

	    		settingsHolder.addEventListener('transitionend', function cb(e){
					e.currentTarget.removeEventListener(e.type, cb);

					settingsHolder.style.display = 'none';
					settingsHolder.style.width = 'auto';
					settingsHolder.style.height = 'auto';
	                
					//restore home menu
		    		settingsHolder.querySelectorAll('.vpl-settings-menu').forEach(el => {
						el.style.display = 'none';
					})

					settingsHome.style.display = 'block';
					settingsHolder.style.maxHeight = 'none'
					settingsHolder.classList.remove('vpl-settings-holder-scrollable');

				})
				settingsHolder.classList.remove('vpl-holder-visible');

	    	}else{

	    		if(infoHolder.classList.contains('vpl-holder-visible'))toggleInfo();//close info if opened
				if(shareHolder.classList.contains('vpl-holder-visible'))toggleShare();//close share if opened

	    		settingsHolder.style.display = 'block';
	    		setTimeout(function(){
					settingsHolder.classList.add('vpl-holder-visible');
				},20);
	    	}
	    });
	}

	if(settingsHome){
		settingsHome.querySelectorAll('.vpl-menu-item').forEach(el => {

			el.addEventListener('click', function(e){
				var target = e.target.closest('.vpl-menu-item').getAttribute('data-target');

				var w0 = settingsHolder.offsetWidth, h0 = settingsHolder.offsetHeight;//get last size

				settingsHome.style.display = 'none';
				settingsHolder.querySelectorAll('.vpl-settings-menu').forEach(function(el){
					el.style.display = 'none';
				})

				settingsHolder.style.maxHeight = 'none';
				settingsHolder.classList.remove('vpl-settings-holder-scrollable');
				
				settingsHolder.style.width = 'auto';
				settingsHolder.style.height = 'auto';//reset size to auto to get child size

				var sh = settingsHolder.querySelector('.'+target)
				sh.style.display = 'block';//show child

				var w = sh.offsetWidth, h = sh.offsetHeight;//get child size

				if(h > playerHolder.offsetHeight - settingsMenuHeightDiff){//doesnt fit in player
					settingsHolder.style.maxHeight = playerHolder.offsetHeight - settingsMenuHeightDiff + 'px';
					settingsHolder.classList.add('vpl-settings-holder-scrollable');
				}

				settingsHolder.style.width = w0+'px';
				settingsHolder.style.height = h0+'px';

				setTimeout(function(){
					settingsHolder.style.width = w+'px';
					settingsHolder.style.height = h+'px';
				},20);

			});	

		});

	}

	if(settingsHolder){
		settingsHolder.querySelectorAll('.vpl-menu-header').forEach(el => {
			el.addEventListener('click', function(){//header click, close submenu
				showSettingsMenu();
			});
		})

	}

	function showSettingsMenu(){

		settingsHolder.querySelectorAll('.vpl-settings-menu').forEach(el => {
			el.style.display = 'none';
		})

		var w0 = settingsHolder.offsetWidth, h0 = settingsHolder.offsetHeight;//get last size
		settingsHolder.style.width = 'auto';
		settingsHolder.style.height = 'auto';//reset size to auto to get child size

		settingsHome.style.display = 'block';//show child
		var w = settingsHome.offsetWidth, h = settingsHome.offsetHeight;//get child size

		settingsHolder.style.width = w0+'px';
		settingsHolder.style.height = h0+'px';

		setTimeout(function(){
			settingsHolder.style.width = w+'px';
			settingsHolder.style.height = h+'px';
		},20);
	}
	
	//############################################//
	/* playback rate */
	//############################################//

	if(playbackRateMenu){
		playbackRateMenu.addEventListener('click', function(e){
			if(e.target.classList.contains('vpl-menu-item')){
				clickPlaybackRate(e);
			}
		})
	}

	function clickPlaybackRate(e){

		var target = e.target;

		if(target.classList.contains('vpl-menu-active')){
			if(settings.closeSettingsMenuOnSelect){
				settingsToggle.click();
			}else{
				showSettingsMenu();
			}
			return false;//active item
		}

		var value = target.getAttribute('data-value');

		currMediaData.playbackRate = value;//remember for quality change
		if(playbackRateMenu)setPlaybackRateActiveMenuItem(value);
		if(mediaStarted)self.setPlaybackRate(value);

		if(settings.closeSettingsMenuOnSelect){
			settingsToggle.click();
		}else{
			showSettingsMenu();
		}
	}

	function setPlaybackRateActiveMenuItem(value){
		//needed for youtube where we only set quality in menu if quality has been changed! 
		if(activePlaybackRateMenuItem){
			activePlaybackRateMenuItem.classList.remove('vpl-menu-active')
		}
		activePlaybackRateMenuItem = playbackRateMenu.querySelector(".vpl-menu-item[data-value='" + value + "']")
		activePlaybackRateMenuItem.classList.add('vpl-menu-active')

		settingsHolder.querySelector('.vpl-playback-rate-menu-value').textContent = activePlaybackRateMenuItem.textContent;
	}

	//############################################//
	/* playback quality */
	//############################################//

	function buildQualityMenu(quality_levels, current_quality){

		var i, len = quality_levels.length, item, li;

		for(i=0;i<len;i++){
			item = quality_levels[i];
	
			if(item.label){
				li = VPLUtils.htmlToElement('<button type="button"></button>')

				li.classList.add('vpl-menu-item', 'vpl-btn-reset')

				li.setAttribute('data-value', item.label);
				li.setAttribute('tabindex', '0');

				li.textContent = item.label

				qualityMenu.append(li)

			}else{//same label and value

				li = VPLUtils.htmlToElement('<button type="button"></button>')

				li.classList.add('vpl-menu-item', 'vpl-btn-reset')

				li.setAttribute('data-value', item);
				li.setAttribute('tabindex', '0');

				li.textContent = item

				qualityMenu.append(li)
			}
		}

		if(current_quality)setQualityActiveMenuItem(current_quality);

		qualitySettingsMenu.classList.remove('vpl-force-hide');

	}

	if(qualityMenu){
		qualityMenu.addEventListener('click', function(e){
			if(e.target.classList.contains('vpl-menu-item')){
				clickQualityMenuItem(e);
			}
		})	
	}

	function clickQualityMenuItem(e){

		var target = e.target;

		if(target.classList.contains('vpl-menu-active')){
			if(settings.closeSettingsMenuOnSelect){
				settingsToggle.click();
			}else{
				showSettingsMenu();
			}
			return false;//active item
		}

		var quality = target.getAttribute('data-value');
		currMediaData.quality = quality;
		setQualityActiveMenuItem(quality);
		if(mediaStarted)self.setPlaybackQuality(quality);
		else getQuality();//update quality
		
		if(settings.closeSettingsMenuOnSelect){
			settingsToggle.click();
		}else{
			showSettingsMenu();
		}

	}

	function setQualityActiveMenuItem(value){
		//set active only on callback because for some api it may not be accepted on request
		if(activeQualityMenuItem){
			activeQualityMenuItem.classList.remove('vpl-menu-active');
		}

		activeQualityMenuItem = qualityMenu.querySelector(".vpl-menu-item[data-value='" + value + "']")
		activeQualityMenuItem.classList.add('vpl-menu-active')

		settingsHolder.querySelector('.vpl-quality-menu-value').textContent = activeQualityMenuItem.textContent;
	}

	//############################################//
	/* audio language */
	//############################################//

	function toggleAudioLanguage(){

		var curr_audio = e.target.getAttribute('data-value');

		if(subMediaType == 'hls')hls.audioTrack = curr_audio;	
		else if(subMediaType == 'dash'){
			dash.setQualityFor('audio', curr_audio);
			setAudioLanguageActiveMenuItem(curr_audio)
		}

		if(settings.closeSettingsMenuOnSelect){
			settingsToggle.click();
		}else{
			showSettingsMenu();
		}
	}

	function setAudioLanguageActiveMenuItem(value){

		if(activeAudioLanguageMenuItem){
			activeAudioLanguageMenuItem.classList.remove('vpl-menu-active');
		}
		activeAudioLanguageMenuItem = audioLanguageMenu.querySelector(".vpl-menu-item[data-value='" + value + "']")
		activeAudioLanguageMenuItem.classList.add('vpl-menu-active')

		settingsHolder.querySelector('.vpl-audio-language-menu-value').textContent = activeAudioLanguageMenuItem.textContent;

	}


	//############################################//
	/* subtitles */
	//############################################//

	function buildSubtitleMenu(){

		var i, len = currMediaData.subtitles.length, item, li, default_subtitle, subtitle_state;

		if(hasLocalStorage && localStorage.getItem(captionStateKey)){
			subtitle_state = JSON.parse(localStorage.getItem(captionStateKey));
		}


		for(i = 0; i < len; i++){

			item = currMediaData.subtitles[i];

			li = VPLUtils.htmlToElement('<button type="button"></button>')

			li.classList.add('vpl-menu-item', 'vpl-btn-reset')

			li.setAttribute('data-label', item.label);
			li.setAttribute('tabindex', '0');
			li.setAttribute('data-id', i.toString());

			li.textContent = item.label

			subtitleMenu.append(li)
		
			if(subtitle_state){
				if(subtitle_state.active && subtitle_state.value == item.label){//check if subtitle exist becuase if we move to next video, that subtitle might not be present
					default_subtitle = item.label;
				}
			}
			else if(item.default){
				default_subtitle = item.label;
				default_transcript = item.label;
			}
			
		}

		if(!default_subtitle){
			default_subtitle = settings.subtitleOffText;
		}

		self.setSubtitle(default_subtitle);

		subtitleSettingsMenu.classList.remove('vpl-force-hide');

		if(captionToggle)captionToggle.style.display = 'block';
	}

	if(subtitleMenu){
		subtitleMenu.addEventListener('click', function(e){
			if(e.target.classList.contains('vpl-menu-item')){
				clickSubtitleMenuItem(e);
			}
		})	
	}

	function clickSubtitleMenuItem(e){

		var target = e.target;

		if(target.classList.contains('vpl-menu-active')){
			if(settings.closeSettingsMenuOnSelect){
				settingsToggle.click();
			}else{
				showSettingsMenu();
			}
			return false;//active item
		}

		if(activeSubtitleMenuItem)activeSubtitleMenuItem.classList.remove('vpl-menu-active');
		activeSubtitleMenuItem = target
		activeSubtitleMenuItem.classList.add('vpl-menu-active');

		var value = target.getAttribute('data-label');

		self.setSubtitle(value);

		if(settings.closeSettingsMenuOnSelect){
			settingsToggle.click();
		}else{
			showSettingsMenu();
		}
	}

	this.toggleSubtitle = function(){
		if(!componentInited) return false;
		if(!mediaType) return false;

		if(currMediaData && currMediaData.subtitles){

			var i, len = currMediaData.subtitles.length, item, default_subtitle, subtitle_state;

			if(hasLocalStorage && localStorage.getItem(captionStateKey)){
				subtitle_state = JSON.parse(localStorage.getItem(captionStateKey));
				
				//reverse (if exist set off, if not exist set last active)
				if(subtitle_state.active)default_subtitle = settings.subtitleOffText;
				else default_subtitle = subtitle_state.value;

				if(default_subtitle)self.setSubtitle(default_subtitle);
			}
		}
	}

	this.setSubtitle = function(value){
		if(!componentInited) return false;
		if(!mediaType) return false;

		subtitleHolderInner.innerHTML = '';
		subtitleHolder.style.display = 'block';

		if(value == settings.subtitleOffText || value == ''){//subtitle off

			if(value == '')value = settings.subtitleOffText;
			
			subtitleOn = false;
			activeSubtitle = null;

			if(activeSubtitleMenuItem)activeSubtitleMenuItem.classList.remove('vpl-menu-active')
			activeSubtitleMenuItem = subtitleMenu.querySelector(".vpl-menu-item[data-label='" + settings.subtitleOffText + "']")
			if(activeSubtitleMenuItem)activeSubtitleMenuItem.classList.add('vpl-menu-active')

			//caption toggle
			captionToggle.classList.add('vpl-btn-disabled');

		}else{
			
			if(allSubtitleArr[value]){//already have subtitle

				subtitleOn = false;
				activeSubtitle = null;

				if(activeSubtitleMenuItem){
					activeSubtitleMenuItem.classList.remove('vpl-menu-active')
				}
				activeSubtitleMenuItem = subtitleMenu.querySelector(".vpl-menu-item[data-label='" + value + "']")
				activeSubtitleMenuItem.classList.add('vpl-menu-active')

				subtitleArr = allSubtitleArr[value];
				subtitleOn = true;

				lastTime = null;
				trackProgress(true);//update subtitle

			}else{//get subtitle

				var i, len = currMediaData.subtitles.length, item;

				for(i = 0; i < len; i++){
					item = currMediaData.subtitles[i];

					if(item.label == value){

						subtitleOn = false;
						activeSubtitle = null;

						if(activeSubtitleMenuItem){
							activeSubtitleMenuItem.classList.remove('vpl-menu-active')
						}
						activeSubtitleMenuItem = subtitleMenu.querySelector(".vpl-menu-item[data-label='" + value + "']")
						activeSubtitleMenuItem.classList.add('vpl-menu-active')

						if(externalSubtitle)getSubtitleUrl(item);
						else getSubtitle(item);
						
						break;
					}
				}
			}

			//caption toggle
			if(captionToggle)captionToggle.classList.remove('vpl-btn-disabled');
		}

		//remember active subtitle for mainMediaData and ads
		var i = 0, len = currMediaData.subtitles.length;
		for(i = 0; i < len; i++){
			item = currMediaData.subtitles[i];
			delete item.default;
			if(item.value == value){
				item.default = true;
			}
		}

		settingsHolder.querySelector('.vpl-subtitle-menu-value').textContent = value;

		if(hasLocalStorage){
			if(localStorage.getItem(captionStateKey))var d = JSON.parse(localStorage.getItem(captionStateKey));
			else var d = {};
			if(value == settings.subtitleOffText){
				d.active = false; 
			}else{
				d.active = true; //save last active subtitle before disabled
				d.value = value;
			}
			localStorage.setItem(captionStateKey, JSON.stringify(d));
		}

	}

	function getSubtitleUrl(item){

		if(sub_xhrRequest){
			sub_xhrRequest.abort();
			sub_xhrRequest = null;
		}

		sub_xhrRequest = new XMLHttpRequest();
		sub_xhrRequest.onreadystatechange = function() {
			if (sub_xhrRequest.readyState == 4) {

				var file = sub_xhrRequest.responseText;

				var lines = file.split(/[\r\n]/), found;
				for (var i in lines){
				    var line = lines[i];
				    if (/.vtt/.test(line)) {
				        //console.log(line)
				        item.src = item.src.substr(0, item.src.lastIndexOf('/')+1)+line;
				        found = true;
				        break;
				    }
				}

				if(found)getSubtitle(item);
				else console.log('Error loading subtitle!')
					
		    }
		}
		sub_xhrRequest.onerror = function(e) { 
		    console.log('Error getSubtitleUrl: ' + e);
		};
		sub_xhrRequest.open('GET', item.src);
		sub_xhrRequest.setRequestHeader("Content-Type", "text/plain");
		sub_xhrRequest.send();
	}

	function getSubtitle(item){

		subtitleArr = []

		if(window.location.protocol == 'file:'){
			console.log('Getting subtitle requires server connection.');
			return false;
		}

		var src = item.src;
		if(src.indexOf(bsf_match) != -1)src = VPLUtils.b64DecodeUnicode(src.substr(6));

		//check cors
		if(item.cors && settings.cors){
			src = settings.cors + src
		}

		if(sub_xhrRequest){
			sub_xhrRequest.abort();
			sub_xhrRequest = null;
		}

		sub_xhrRequest = new XMLHttpRequest();
		sub_xhrRequest.onreadystatechange = function() {
			if (sub_xhrRequest.readyState == 4) {

				var data = sub_xhrRequest.responseText;

				var srt = data.replace(/\r\n|\r|\n/g, '\n');
				//var srt = data.replace(/\r\n\s*\r\n/g, '\n');

				var arr = []

			    srt = VPLUtils.strip(srt);

			    var sub = srt.split('\n\n'), s, st, z = 0, j, number, start, end, text;

			    for(s in sub) {

			        st = sub[s].split('\n');
			      
			        if(st == "WEBVTT")continue;

			        if(st.length >= 2) {
			            //number = st[0];

			            if(st[0] == "WEBVTT")continue;

				        if(st.length > 2){

				        	if(st[0] == '')st.shift();

			            	start = VPLUtils.strip(st[1].split(' --> ')[0]);
				            end = VPLUtils.strip(st[1].split(' --> ')[1]);
				            text = st[2];

				            if(st.length > 3) {
				                for(j=3; j<st.length;j++){
				                   text += '\n'+st[j];
				                }
				            }

			            }else{
			            	start = VPLUtils.strip(st[0].split(' --> ')[0]);
				            end = VPLUtils.strip(st[0].split(' --> ')[1]);
				            text = st[1];
			            }

				        arr[z] = {};
			            arr[z].start = VPLUtils.toSeconds(start);
			            arr[z].end = VPLUtils.toSeconds(end);
			            arr[z].text = text;

				        z++;

			        }
			    }

		    	subtitleArr = arr;

			    subtitleOn = true;

			    if(mediaStarted){
				    lastTime = null;
				    trackProgress(true);//update
				}
		
			    allSubtitleArr[item.label] = arr;//save
						
		    }
		}
		sub_xhrRequest.onerror = function(e) { 
		    console.log('Error getSubtitle: ' + e);
		};
		sub_xhrRequest.open('GET', src);
		sub_xhrRequest.setRequestHeader("Content-Type", "text/plain");
		sub_xhrRequest.send();	
	}

	//############################################//
	/* preview seek */
	//############################################//

	//auto

	function initPreviewSeekVideo(){

		if(!previewSeekCanvas){

	    	previewSeekCanvas = document.createElement('canvas');
	    	previewSeekCanvas.className = "vpl-preview-seek-canvas";

	    	previewSeekWrap.classList.add('vpl-measure-size')//to get size

	    	previewSeekCanvasWidth = previewSeekWrap.offsetWidth;
			previewSeekCanvasHeight = previewSeekWrap.offsetHeight;
		    previewSeekCanvas.width = previewSeekCanvasWidth;
		    previewSeekCanvas.height = previewSeekCanvasHeight;

		    previewSeekWrap.classList.remove('vpl-measure-size')
		    
		    previewSeekCanvasCtx = previewSeekCanvas.getContext('2d');
		    previewSeekInner.append(previewSeekCanvas)
	  
			videoForPreviewSeek = document.createElement("video");
			videoForPreviewSeek.muted = true;
			videoForPreviewSeek.playsinline = true;
			videoForPreviewSeek.setAttribute("playsinline", "playsinline");
			videoForPreviewSeek.setAttribute("muted", "muted");

			videoForPreviewSeek.addEventListener('seeked', function(){
				if(!previewSeekSnapshotDone){
					previewSeekSnapshotDone = true;
					
					//draw preview seek
					/*
					//auto calculate size and set preview seek size automatically?
					var w = videoForPreviewSeek.videoWidth;
				    var h = videoForPreviewSeek.videoHeight;

				    var tw = previewSeekInner.width()
				    var th = Math.round(videoForPreviewSeek.videoHeight * (tw/videoForPreviewSeek.videoWidth));*/
				    
				    previewSeekCanvasCtx.drawImage(videoForPreviewSeek, 0, 0, previewSeekCanvasWidth, previewSeekCanvasHeight);

				}
			})
		}

		if(subMediaType == 'hls'){

			if(hlsSupport){

				if(settings.hlsConfig){
					hlsForPreviewSeek = new Hls(settings.hlsConfig);
				}else{
					hlsForPreviewSeek = new Hls();
				}

				hlsForPreviewSeek.on(Hls.Events.MEDIA_ATTACHED, function () {
					hlsForPreviewSeek.loadSource(mediaPath);
			    });

			}

		}else if(subMediaType == 'dash'){

			if(!dashForPreviewSeek)dashForPreviewSeek = dashjs.MediaPlayer().create();

		}

	}

	function setPreviewSeekVideoSource(){

		if(subMediaType == 'hls'){

			if(hlsSupport){
				hlsForPreviewSeek.attachMedia(videoForPreviewSeek);
			}else if(videoForPreviewSeek.canPlayType('application/vnd.apple.mpegurl') == 'true'){
			    videoForPreviewSeek.src = mediaPath;
			}else if(currMediaData.mp4){//backup
				videoForPreviewSeek.src = currMediaData.mp4;
				videoForPreviewSeek.load();	
			}else{
				try{
					videoForPreviewSeek.src = mediaPath;
					videoForPreviewSeek.load();	
				}catch(er){
					console.log("This browser or device does not support HLS extension. Please use mp4 video for playback!");
				}
			}
			
		}else if(subMediaType == 'dash'){

			if(dashSupport){ 
				if(!dashForPreviewSeekInitialized){
					dashForPreviewSeek.initialize(videoForPreviewSeek, mediaPath, true);
					dashForPreviewSeekInitialized = true;
				}else{
					dashForPreviewSeek.attachSource(mediaPath);
				}
			}else{
				if(currMediaData.mp4){//backup
					videoForPreviewSeek.src = currMediaData.mp4;
					videoForPreviewSeek.load();	
				}else{
					console.log("This browser or device does not support MPEG-DASH extension. Please use mp4 video for playback!");
				}
			}
				
		}else{

			videoForPreviewSeek.src = mediaPath;
			videoForPreviewSeek.load();	
			
		}
	}

	function startDrawPreviewSeek(t){

		previewSeekSnapshotDone = false;

		var promise = videoForPreviewSeek.play();
		if (promise !== undefined) {
			promise.then(function(){
		    }).catch(function(error){
		    });
		}

		try{
			videoForPreviewSeek.currentTime = t;
		}catch(er){}

	}

	function stopDrawPreviewSeek(){

		if(videoForPreviewSeek)videoForPreviewSeek.pause();

		if(previewSeekCanvasCtx)previewSeekCanvasCtx.clearRect(0, 0, previewSeekCanvasWidth, previewSeekCanvasHeight);

	}

	//vtt

	function getPreviewSeek(){

		if(window.location.protocol == 'file:'){
			console.log('Getting preview seek requires server connection.');
			return false;
		}

		previewSeekArr = [];

		if(ps_xhrRequest){
			ps_xhrRequest.abort();
			ps_xhrRequest = null;
		}

		ps_xhrRequest = new XMLHttpRequest();
		ps_xhrRequest.onreadystatechange = function() {
			if (ps_xhrRequest.readyState == 4) {

				var data = ps_xhrRequest.responseText;

				var srt = data.replace(/\r\n|\r|\n/g, '\n');

			    srt = VPLUtils.strip(srt);

			    var sub = srt.split('\n\n'), s, st, z = 0, j, start, end, url;

			    for(s in sub) {
			        st = sub[s].split('\n');
			      
			        if(st == "WEBVTT")continue;

			        if(st.length >= 2) {

			            start = VPLUtils.strip(st[0].split(' --> ')[0]);
			            end = VPLUtils.strip(st[0].split(' --> ')[1]);
			            url = st[1];

			            previewSeekArr[z] = {};
			            previewSeekArr[z].start = VPLUtils.toSeconds(start);
			            previewSeekArr[z].end = VPLUtils.toSeconds(end);
			            previewSeekArr[z].url = url;

			        }
			        z++;
			    }

			    //console.log(previewSeekArr)

			    previewSeekReady = true;
					
		    }
		}
		ps_xhrRequest.onerror = function(e) { 
		    console.log('Error getPreviewSeek: ' + e);
		};
		ps_xhrRequest.open('GET', currMediaData.previewSeek);
		ps_xhrRequest.setRequestHeader("Content-Type", "text/xml");
		ps_xhrRequest.send();
	}

	//############################################//
	/* playlist manager */
	//############################################//

	function getQuality(){

		qualityArr = [];

		if(Array.isArray(currMediaData.path)){

			var i, len = currMediaData.path.length, obj, item;

			for(i = 0;i<len; i++){
				obj = currMediaData.path[i];
				qualityArr.push(obj);

				if(!item){
				
					if(currMediaData.quality && currMediaData.quality == obj.label){
						item = obj;
					}
					else{

						if(isMobile){
							if(obj.activeMobile){
								item = obj;
								delete obj.activeMobile;
							}
						} 
						else if(obj.active){
							item = obj;
							delete obj.active;
						}

					} 
				}
				
			}

			if(!item){
				item = currMediaData.path[0];

			}

			if(mediaType == 'audio'){
				if(wavSupport && item['wav']){
					mediaPath = item['wav'];
				}
				else if(mp3Support && item['mp3']){
					mediaPath = item['mp3'];
				}
			}else if(mediaType == 'video'){
				if(mp4Support && item['mp4']){
					mediaPath = item['mp4'];
				}
			}

			currMediaData.quality = item.label;

		}else{

			if(mediaType == 'audio'){
				if(wavSupport && currMediaData.wav){
					mediaPath = currMediaData.wav;
				}
				else if(mp3Support && currMediaData.mp3){
					mediaPath = currMediaData.mp3;
				}
				else if(currMediaData.path){
					mediaPath = currMediaData.path;
				}
				else{
					console.log('No audio source found!')
					return;
				}
			}else if(mediaType == 'video'){
				if(mp4Support && currMediaData.mp4){
					mediaPath = currMediaData.mp4;
				}
			}

		}

		if(!mediaPath)console.log('No mediaPath set!');

	}

	var _VPLPlaylistManager = new VPLPlaylistManager({'randomPlay': settings.randomPlay, 'loopingOn': settings.loopingOn});
	_VPLPlaylistManager.addEventListener('VPLPlaylistManager.COUNTER_READY', function(e){

		isPoster = false;

		if(mediaType)cleanMedia();

		mediaCounter = e.counter;
		currMediaData = playlistDataArr[mediaCounter];

		mediaType = currMediaData.type;
		currMediaData.origtype = currMediaData.type;

		self.fireEvent('mediaRequest', [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);
		
		qualityChange = false;

		if(mediaType == 'video' || mediaType == 'audio'){
			getQuality();

			if(settings.hideQualityMenuOnSingleQuality && qualityArr.length == 1){
			}else{
				if(qualityArr.length && qualityMenu)buildQualityMenu(qualityArr, currMediaData.quality);
			}
			
		}else{
			mediaPath = currMediaData.path;
		}


		if(mediaPath.indexOf(bsf_match) != -1){
			mediaPath = VPLUtils.b64DecodeUnicode(mediaPath.substr(6));
		}

		//redirect
		if(mediaType == 'hls'){
			mediaType = 'video';
			subMediaType = 'hls';
		}
		else if(mediaType == 'dash'){
			mediaType = 'video';
			subMediaType = 'dash';
		}

		//check if poster exist
		posterExist = false;

		if(mediaType == 'audio'){
			if(currMediaData.poster){
				posterExist = true;
			}
		}
		else if(mediaType == 'video' || mediaType == 'youtube' || mediaType == 'vimeo'){
			if(currMediaData.poster){
				posterExist = true;
			}
		}
		

		//check password
		if(currMediaData.pwd){

			pwdHolder.style.display = 'block';
			setTimeout(function(){
				pwdHolder.classList.add('vpl-holder-visible');
			},20);

		}else{
			if(mediaType == 'audio'){
				if(settings.autoPlayInViewport && !autoPlayInViewportDone){
					if(posterExist)setPoster();
					checkViewport();
				}
				else if(posterExist)setPoster();
				else setMedia();
			}else{
				if(settings.autoPlayInViewport && !autoPlayInViewportDone)checkViewport();
				else if(!autoPlay && posterExist)setPoster();
				else setMedia();
			}
		}

		//show lightbox
		if(settings.playerType == 'lightbox' && !lightboxOpened){
			lightboxOpened = true;

			lightboxWrap.style.display = 'block';
			
			doneResizing();//resize player after lightbox is shown

			if(lightboxWrap.style.opacity != '1'){
				setTimeout(function(){
					clearTimeout(this);
					lightboxWrap.style.opacity = '1';
				},50);
			}
		}

	});

	function setPoster(){

		if(!posterHolder){
			posterHolder = VPLUtils.htmlToElement('<div class="vpl-poster-holder"></div>')
			mediaHolder.append(posterHolder);
		}

		if(mediaType == 'audio'){

			isPoster = true;

			var img = new Image();
			img.classList.add('vpl-media');

			posterHolder.innerHTML = '';
			posterHolder.style.display = 'block';
			posterHolder.append(img);

			img.addEventListener('load',function() {
				poster = this;
				VPLAspectRatio.resizeMedia('image', settings.aspectRatio, playerHolder, poster);
				poster.classList.add('vpl-visible');

				if(!settings.displayPosterOnMobile){
					if(!autoPlay){
						if(bigPlay)bigPlay.style.display = 'block';
					}else{
						if(!autoPlayInViewportDone)setMedia();
					}
				}

			})

			img.addEventListener('error',function(e){console.log(e)})
			img.setAttribute('alt', currMediaData.title);
			img.src = currMediaData.poster;

		}
		else if(mediaType == 'video' || mediaType == 'youtube' || mediaType == 'vimeo'){

			playerControls.forEach(el => {
				el.style.display = 'none';
				el.classList.remove('vpl-player-controls-visible');
			})

			interfaceHidden = true;
		
			isPoster = true;

			var img = new Image();
			img.classList.add('vpl-media');

			posterHolder.innerHTML = '';
			posterHolder.style.display = 'block';
			posterHolder.append(img);

			img.addEventListener('load',function() {
				poster = this;

				VPLAspectRatio.resizeMedia('image', settings.aspectRatio, playerHolder, poster);
				poster.classList.add('vpl-visible');

				if(playerLoader)playerLoader.style.display = 'none';

				if(!settings.displayPosterOnMobile){
					if(bigPlay)bigPlay.style.display = 'block';
				}
				
			})

			img.addEventListener('error',function(e){console.log(e)})
			img.setAttribute('alt', currMediaData.title);
			img.src = currMediaData.poster;

		}

	}

	function getBUrl(url){
console.log(url)
		bUrl = null;

		if(burl_request){
			burl_request.abort();
			burl_request = null;
		}

		burl_request = new XMLHttpRequest();
		burl_request.onreadystatechange = function() {
			if (burl_request.readyState == 4) {

			    var reader = new FileReader();
			    reader.readAsArrayBuffer(burl_request.response);
			    reader.onload = function(e){

			        var contents = e.target.result;
			        var uint8Array  = new Uint8Array(contents);
					var arrayBuffer = uint8Array.buffer;
					if(isIOS || isSafari){
						var blob = new Blob([arrayBuffer], {type: "video/mp4" });
					}else{
						var blob = new Blob([arrayBuffer]);
					}
			        bUrl = URL.createObjectURL(blob);

		    	}
		    }
		};
		burl_request.responseType = 'blob';
		burl_request.open('GET', url, true);
		burl_request.send();

	}

	function setPosterForPause(){
		//set poster for pause

		if(!posterHolder){
			posterHolder = VPLUtils.htmlToElement('<div class="vpl-poster-holder"/>')
			mediaHolder.append(posterHolder);
		}

		var img = new Image();
		img.classList.add('vpl-media');

		posterHolder.innerHTML = '';
		posterHolder.style.display = 'block';
		posterHolder.append(img);

		img.addEventListener('load',function() {
			poster = this;
			VPLAspectRatio.resizeMedia('image', settings.aspectRatio, playerHolder, poster);
			poster.classList.add('vpl-visible');
		})

		img.addEventListener('error',function(e){console.log(e)})
		img.setAttribute('alt', currMediaData.title || 'image');
		img.src = currMediaData.poster;

	}

	function setMedia(){

		if(mediaType == 'audio' || mediaType == 'video' || mediaType == 'youtube' && settings.youtubePlayerType == 'chromeless' && autoPlay && youtubePlayed || mediaType == 'vimeo' && settings.vimeoPlayerType == 'chromeless' && autoPlay && vimeoPlayed){
			if(playerLoader)playerLoader.style.display = 'block';	
		}

		if(currMediaData.is360){

			if(typeof THREE === 'undefined'){

				var script = document.createElement('script');
				script.type = 'text/javascript';
				if(!VPLUtils.relativePath(settings.three_js))var src = VPLUtils.qualifyURL(settings.sourcePath+settings.three_js);
				else var src = settings.three_js;
				script.src = src;
				script.onload = script.onreadystatechange = function() {
				    if(!this.readyState || this.readyState == 'complete'){

				    	if(typeof THREE.OrbitControls === 'undefined'){
				      	
					      	var script2 = document.createElement('script');
							script2.type = 'text/javascript';
							if(!VPLUtils.relativePath(settings.orbitControls_js))var src = VPLUtils.qualifyURL(settings.sourcePath+settings.orbitControls_js);
							else var src = settings.orbitControls_js;
							script2.src = src;
							script2.onload = script2.onreadystatechange = function() {
							    if(!this.readyState || this.readyState == 'complete'){
							      	setMedia();
							    }
							};
							script2.onerror = function(){
								alert("Error loading " + this.src);
							}
							var tag2 = document.getElementsByTagName('script')[0];
							tag2.parentNode.insertBefore(script2, tag2);

						}else{

							setMedia();
						}
				    }
				};
				script.onerror = function(){
					alert("Error loading " + this.src);
				}
				var tag = document.getElementsByTagName('script')[0];
				tag.parentNode.insertBefore(script, tag);

				return;

			}else if(typeof THREE.OrbitControls === 'undefined'){

				var script2 = document.createElement('script');
				script2.type = 'text/javascript';
				if(!VPLUtils.relativePath(settings.orbitControls_js))var src = VPLUtils.qualifyURL(settings.sourcePath+settings.orbitControls_js);
				else var src = settings.orbitControls_js;
				script2.src = src;
				script2.onload = script2.onreadystatechange = function() {
				    if(!this.readyState || this.readyState == 'complete'){
				      	setMedia();
				    }
				};
				script2.onerror = function(){
					alert("Error loading " + this.src);
				}
				var tag2 = document.getElementsByTagName('script')[0];
				tag2.parentNode.insertBefore(script2, tag2);

				return;

			}
		}
		if(subMediaType == 'hls'){

			if(!hlsInited){

				if(typeof Hls === 'undefined'){
					
					var script = document.createElement('script');
					script.type = 'text/javascript';
					if(!VPLUtils.relativePath(settings.hls_js))var src = VPLUtils.qualifyURL(settings.sourcePath+settings.hls_js);
					else var src = settings.hls_js;
					script.src = src;
					script.onload = script.onreadystatechange = function() {
					    if(!this.readyState || this.readyState == 'complete'){
					      	initHls();
							setMedia();
					    }
					};
					script.onerror = function(){
						alert("Error loading " + this.src);
					}
					var tag = document.getElementsByTagName('script')[0];
					tag.parentNode.insertBefore(script, tag);

				}else{
					initHls();
					setMedia();
				}

				return;
			}
		}
		if(subMediaType == 'dash'){

			if(!dashInited){

				if(typeof dashjs === 'undefined'){
					
					var script = document.createElement('script');
					script.type = 'text/javascript';
					if(!VPLUtils.relativePath(settings.dash_js))var src = VPLUtils.qualifyURL(settings.sourcePath+settings.dash_js);
					else var src = settings.dash_js;
					script.src = src;
					script.onload = script.onreadystatechange = function() {
					    if(!this.readyState || this.readyState == 'complete'){
					      	initDash();
							setMedia();
					    }
					};
					script.onerror = function(){
						alert("Error loading " + this.src);
					}
					var tag = document.getElementsByTagName('script')[0];
					tag.parentNode.insertBefore(script, tag);

				}else{
					initDash();
					setMedia();
				}

				return;
			}
		}


		if(mediaPath.indexOf(bsf_match) != -1){
			mediaPath = VPLUtils.b64DecodeUnicode(mediaPath.substr(6));
		}


		
		if(isPoster){
			resumeTime = currMediaData.start || 0;//force play after poster
			isPoster = false;
		}

		//resume playback position
		if(settings.playbackPositionTime != undefined){//remember playback position for main video (adverts are ignored)
			resumeTime = settings.playbackPositionTime;
			delete settings.playbackPositionTime;

			if(settings.useResumeScreen/* && resumeTime > 0*/){
				if(playerLoader)playerLoader.style.display = 'none';
				toggleResumeScreen();
				return;
			}
		}

		if(mediaType == 'audio'){

			if(!audioInited){
				audio = document.createElement("audio")
				audio.setAttribute('preload', settings.preload);
				audioUp2Js = audio;
				audioInited = true;
			}

			audioReady = false;

			audio.addEventListener("ended", mediaEndHandler);	
			audio.addEventListener("loadedmetadata", handleAudioLoadedmetadata);
			audio.addEventListener("canplay", handleAudioCanplay)
			audio.addEventListener("play", playHandler);
			audio.addEventListener("pause", pauseHandler);
			audio.addEventListener("ratechange", audioRateChangeHandler);
			audio.addEventListener("error", audioErrorHandler);

			if(useBlob && (currMediaData.origtype == 'audio')){

				if(bUrlIntervalID){
					clearInterval(bUrlIntervalID);
					bUrlIntervalID = null;
				}
				console.log(6)
				getBUrl(mediaPath);
				bUrlIntervalID = setInterval(function(){
					if(bUrl){
						clearInterval(bUrlIntervalID);
						bUrlIntervalID = null;

						audioUp2Js.src = bUrl;
						if(autoPlay){
							audioUp2Js.load();
						}

					}
				},100);

			}else{
				audioUp2Js.src = mediaPath;
				if(autoPlay){
					audioUp2Js.load();
				}
			}

		}else if(mediaType == 'video'){

			if(currMediaData.videoFrameTime)mediaPath += '#t=' + currMediaData.videoFrameTime;

			videoReady = false;

			if(!videoInited){
				
				if(!videoHolder){
					videoHolder = VPLUtils.htmlToElement('<div class="vpl-video-holder"/>');
					mediaHolder.prepend(videoHolder)
				}
			
				var playsinline = ' playsinline';
				if(settings.useMobileNativePlayer)playsinline = '';

				var airplay = ''
				if(settings.useAirPlay) airplay = 'x-webkit-airplay="allow"';

				var disableRemotePlayback = '';
				if(settings.disableRemotePlayback) disableRemotePlayback = 'disableRemotePlayback';

				var videoCode = '<video class="vpl-media" '+disableRemotePlayback+' '+airplay+' preload="'+settings.preload+'"'+playsinline+'';

				if(settings.crossorigin)videoCode += ' crossorigin="'+settings.crossorigin+'"';
				videoCode += '></video>';

				videoHolder.innerHTML = videoCode;

			}

			videoInited = true;

			video = videoHolder.querySelector('.vpl-media');
			videoUp2Js = video;

			//subs, do this here because we dont recreate video each time!
			if(settings.useMobileNativePlayer && isiPhone){

				var ht = ''
				if(currMediaData.subtitles){
				
					//note : rememeberCaptionState currently does not work in iphone (complicated to set up, events fire multiple?)
					var subtitle_state
					if(settings.rememeberCaptionState && hasLocalStorage && localStorage.getItem(captionStateKey)){
						subtitle_state = JSON.parse(localStorage.getItem(captionStateKey));
					}

					var s, slen = currMediaData.subtitles.length, so, default_is_set
					for(s = 0; s < slen; s++){
						so = currMediaData.subtitles[s];
						if(so.value != settings.subtitleOffText){
							ht += '<track src="'+so.src+'" kind="captions" label="'+so.value+'"'

							if(!default_is_set){
								if(subtitle_state){
									if(subtitle_state.active && subtitle_state.value == so.value){
										ht += ' default'
										default_is_set = true;
									}
								}
								else if(so.default){
									ht += ' default'
									default_is_set = true;
								}
							}

							ht += '>'
						}
					}
				}
				videoUp2Js.innerHTML = ht;//even if it dont subs have becuase we have to clear from previous video

			}



			if(!currMediaData.is360){
				videoHolder.style.display = 'block';
			}else{
				videoHolder.style.display = 'none';
			} 

			if(supportFor360Video && currMediaData.is360){

				if(window.location.protocol == 'file:'){
					console.log('Playing 360 videos requires online server connection!');
				}

				if(!video_360_data){//create only once

					vrenderer = new THREE.WebGLRenderer({ antialias: true});
					vrenderer.setSize(vwidth, vheight);
					vrenderer.domElement.className += 'vpl-canvas-video vpl-media';

					mediaHolder.prepend(vrenderer.domElement);
					vcanvas = mediaHolder.querySelector('.vpl-canvas-video');

					//videoUp2Js.crossOrigin = '';
					videoTexture = new THREE.Texture(videoUp2Js);

					vscene = new THREE.Scene();
					var cubeGeometry = new THREE.SphereGeometry(500, 60, 40);
					cubeGeometry.scale( - 1, 1, 1 );

					var sphereMat = new THREE.MeshBasicMaterial({map: videoTexture});
					var cube = new THREE.Mesh(cubeGeometry, sphereMat);
					vscene.add(cube);

					if(!cameraCreated){
						cameraCreated = true;

						camera = new THREE.PerspectiveCamera(90, vwidth / vheight, 0.1, 10000);
						camera.position.x = camera.position.x + 0.1;

						orbitControls = new THREE.OrbitControls(camera, mediaHolder[0]);//https://gist.github.com/mrflix/8351020
						orbitControls.enableZoom = false;
						orbitControls.enableKeys = false;

					}

					video_360_data = true;

				}

			}

			video.addEventListener("ended", mediaEndHandler);
			video.addEventListener("loadedmetadata", handleVideoLoadedMeta);
			video.addEventListener("canplay", handleVideoCanplay);
			video.addEventListener("canplaythrough", videoCanPlayThroughHandler);
			video.addEventListener("waiting", videoWaitingHandler);
			video.addEventListener("playing", videoPlayingHandler);
			video.addEventListener("play", videoPlayHandler);
			video.addEventListener("pause", videoPauseHandler);
			video.addEventListener("seeked", videoSeekedHandler);
			video.addEventListener("ratechange", videoRateChangeHandler);
			video.addEventListener("error", videoErrorHandler);
			
			

			if(subMediaType == 'hls'){

				if(hlsSupport){
					hls.attachMedia(videoUp2Js);
				}else if(videoUp2Js.canPlayType('application/vnd.apple.mpegurl')){//ios backup
				    
					if(useBlob){

						if(bUrlIntervalID){
							clearInterval(bUrlIntervalID);
							bUrlIntervalID = null;
						}
						getBUrl(mediaPath);
						bUrlIntervalID = setInterval(function(){
							if(bUrl){
								clearInterval(bUrlIntervalID);
								bUrlIntervalID = null;

								videoUp2Js.src = bUrl;
							    videoUp2Js.load();	

							}
						},100);

					}else{
						videoUp2Js.src = mediaPath;
						videoUp2Js.load();	
					}

				}else if(currMediaData.mp4){//backup
					
					if(useBlob){

						if(bUrlIntervalID){
							clearInterval(bUrlIntervalID);
							bUrlIntervalID = null;
						}
						getBUrl(currMediaData.mp4);
						bUrlIntervalID = setInterval(function(){
							if(bUrl){
								clearInterval(bUrlIntervalID);
								bUrlIntervalID = null;

								videoUp2Js.src = bUrl;
							    videoUp2Js.load();	

							}
						},100);

					}else{

						videoUp2Js.src = currMediaData.mp4;
						videoUp2Js.load();	

					}

				}else{
					try{
						videoUp2Js.src = mediaPath;
						videoUp2Js.load();	
					}catch(er){
						alert("This browser or device does not support HLS extension. Please use mp4 video for playback!");
					}
				}
				
			}else if(subMediaType == 'dash'){

				if(dashSupport){ 
					if(!dashInitialized){
						dash.initialize(videoUp2Js, mediaPath, autoPlay);
						dashInitialized = true;
					}else{
						dash.attachSource(mediaPath);
					}
				}else{
					if(currMediaData.mp4){//backup
						
						if(useBlob){

							if(bUrlIntervalID){
								clearInterval(bUrlIntervalID);
								bUrlIntervalID = null;
							}
							getBUrl(currMediaData.mp4);
							bUrlIntervalID = setInterval(function(){
								if(bUrl){
									clearInterval(bUrlIntervalID);
									bUrlIntervalID = null;

									videoUp2Js.src = bUrl;
								    videoUp2Js.load();	

								}
							},100);

						}else{
							videoUp2Js.src = currMediaData.mp4;
							videoUp2Js.load();	
						}

					}else{
						alert("This browser or device does not support MPEG-DASH extension. Please use mp4 video for playback!");
					}
				}
					
			}else{
			
				if(useBlob && (currMediaData.origtype == 'video')){

					if(bUrlIntervalID){
						clearInterval(bUrlIntervalID);
						bUrlIntervalID = null;
					}
					getBUrl(mediaPath);
					bUrlIntervalID = setInterval(function(){
						if(bUrl){
							clearInterval(bUrlIntervalID);
							bUrlIntervalID = null;

							videoUp2Js.src = bUrl;
						    videoUp2Js.load();	

						}
					},100);

				}else{
					videoUp2Js.src = mediaPath;
					videoUp2Js.load();	
				}
				
			}

			videoInited = true;

		}
		else if(mediaType == 'image'){

			if(!imageHolder){
				imageHolder = VPLUtils.htmlToElement('<div class="vpl-image-holder"></div>');
				imageHolder.style.display = 'none';
				mediaHolder.prepend(imageHolder)
			}

			if(currMediaData.is360){

				if(window.location.protocol == 'file:'){
					console.log('Playing 360 video and images requires online server connection!');
				}

				if(!image_360_data){//create only once

					iscene = new THREE.Scene();

					var geometry = new THREE.SphereBufferGeometry( 500, 60, 40 );
					// invert the geometry on the x-axis so that all of the faces point inward
					geometry.scale( - 1, 1, 1 );

					itextureLoader = new THREE.TextureLoader();

					imaterial = new THREE.MeshBasicMaterial( {
						map: itextureLoader
					} );

					imesh = new THREE.Mesh( geometry, imaterial );

					iscene.add( imesh );

					irenderer = new THREE.WebGLRenderer();
					irenderer.setPixelRatio( window.devicePixelRatio );
					irenderer.setSize( vwidth, vheight );
					irenderer.domElement.className += 'vpl-canvas-image vpl-media';

					mediaHolder.prepend(irenderer.domElement);
					icanvas = mediaHolder.querySelector('.vpl-canvas-image');

					if(!cameraCreated){
						cameraCreated = true;

						camera = new THREE.PerspectiveCamera(90, vwidth / vheight, 0.1, 10000);
						camera.position.x = camera.position.x + 0.1;

						orbitControls = new THREE.OrbitControls(camera, mediaHolder[0]);//https://gist.github.com/mrflix/8351020
						orbitControls.enableZoom = false;
						orbitControls.enableKeys = false;

					}

					image_360_data = true;
					
				}

				itextureLoader.load(
				    // resource URL
				    mediaPath,
				    // Function when resource is loaded
				    function ( texture ) {
				        // do something with the texture

				        icanvas.style.display = 'block';

						icanvas.classList.add('vpl-visible');

				        imaterial.map = texture;

				        var w = mediaHolder.offsetWidth, h = mediaHolder.offsetHeight;
					    irenderer.setSize(w, h);
					    camera.aspect = w / h;
					    camera.updateProjectionMatrix();

					    orbitControls.addEventListener('change', controlsChange);

						irenderer.render(iscene, camera);

						mediaStarted = true;
						mediaPlaying = true;//because of hide interface

						if(vrInfo){
							vrInfo.style.display = 'block';
							vrInfoVisible = true;
						}

						image_360_ready = true;

						if(currMediaData.duration){

							if(durationTimeoutID)clearTimeout(durationTimeoutID);
							durationTimeoutID = setTimeout(function() {
								clearTimeout(this);
								if(!infoOpened && !shareOpened)mediaEndHandler();
							},currMediaData.duration*1000);
							
						}

				    },
				    // Function called when download progresses
				    function ( xhr ) {
				        //console.log( (xhr.loaded / xhr.total * 100) + '% loaded' );
				    },
				    // Function called when download errors
				    function ( xhr ) {
				        //console.log( 'An error happened' );
				    }
				);

			}
			else{//image

				if(playerLoader)playerLoader.style.display = 'block';


				var img = new Image();
				img.classList.add('vpl-media');

				imageHolder.innerHTML = '';
				imageHolder.style.display = 'block';
				imageHolder.append(img);

				img.addEventListener('load',function() {
					if(playerLoader)playerLoader.style.display = 'none';

					image = this;

					VPLAspectRatio.resizeMedia('image', settings.aspectRatio, playerHolder, image);
					image.classList.add('vpl-visible');
					
					mediaStarted = true;
					mediaPlaying = true;//because of hide interface

					if(currMediaData.duration){

						imageStartTime = new Date().getTime();// so we can display ad seekbar when ad is image
						imageDuration = currMediaData.duration*1000;

						if(durationTimeoutID)clearTimeout(durationTimeoutID);
						durationTimeoutID = setTimeout(function() {
							clearTimeout(this);
							if(!infoOpened && !shareOpened)mediaEndHandler();
						},imageDuration);
						
					}

				})

				img.addEventListener('error',function(e){console.log(e)})
				img.setAttribute('alt', mediaPath);
				img.src = mediaPath;

			}

			/*playerControls.forEach(el => {
				el.style.display = 'block';
			})*/

			if(playerControlsMain){
				if(idleTimeoutID)clearTimeout(idleTimeoutID);
				resetIdleTimer();
			}

		}
		else if(mediaType == 'youtube'){
			
			if(!youtubeInited){

				var yt_frameId = 'ytplayer' + Math.floor(Math.random()*0xFFFFFF)

				youtubeHolder = VPLUtils.htmlToElement('<div class="vpl-youtube-holder"><div id="'+yt_frameId+'" class="vpl-media vpl-emiframe"></div></div>');

				youtubeHolder.style.display = 'block';
				mediaHolder.prepend(youtubeHolder)
				
				var yt_sett = {
		            height: '100%',
		            width: '100%',
		            playerVars: { 
			          	//autoplay: 1,
			          	controls: settings.youtubePlayerType == 'chromeless' ? 0 : 1,
			            modestbranding: 1, 
			          	playsinline: settings.useMobileNativePlayer ? 0 : 1,
			          	rel: 0,
			          	wmode: 'transparent',
			          	iv_load_policy: 3,
			          	cc_load_policy: 0,
			          	showinfo: 0,
			          	disablekb: 1,
			          	color: settings.youtubePlayerColor,
		            },
		            videoId: mediaPath,
		            events: {
		            	onReady: onYtPlayerReady,
						onPlaybackRateChange: onYtPlayerPlaybackRateChange,
						onStateChange: onYtPlayerStateChange,
						onError: onYtPlayerError
		        	}
		        }

		        if(settings.youtubeNoCookie)yt_sett.host = 'https://www.youtube-nocookie.com/';

		        var domain = window.location.href.split("/"),
				origin = domain[0] + "//" + domain[2];

		        if(/^http/.test(origin))yt_sett.playerVars.origin = origin;	

		        if(resumeTime)yt_sett.playerVars.start = resumeTime;
				else if(currMediaData.start)yt_sett.playerVars.start = parseInt(currMediaData.start,10);
				if(currMediaData.end)yt_sett.playerVars.end = parseInt(currMediaData.end,10);


				if(!window.YT){
					var tag = document.createElement('script');
					tag.src = settings.youtube_js;
					var firstScriptTag = document.getElementsByTagName('script')[0];
					firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
				}

				var interval = setInterval(function(){
					if(window.YT && window.YT.Player){
						if(interval) clearInterval(interval);
						youtubePlayer = new YT.Player(yt_frameId, yt_sett);
					}
				}, 100);
			 
				youtubeInited = true;

			}else{
console.log(youtubeReady)
				if(youtubeReady){

					var st = 0;
					if(resumeTime)st = resumeTime;
					else if(currMediaData.start)st = currMediaData.start;

					if(autoPlay){
						youtubePlayer.loadVideoById({'videoId':mediaPath, 'startSeconds':st, 'endSeconds':currMediaData.end});
					}else{
						youtubePlayer.cueVideoById({'videoId':mediaPath, 'startSeconds':st, 'endSeconds':currMediaData.end});
					}
					
				}
			}

			youtubeHolder.style.display = 'block';

			if(youtubeIframe)resizeYtIframe()
		
		}
		else if(mediaType == 'vimeo'){

			//check if video can be chromeless (we need second vimeo instance for this, with and without bg)
			if(settings.vimeoPlayerType == 'chromeless'){
			
				if(currMediaData.userAccount){
					if(currMediaData.userAccount == 'basic'){
						settings.vimeoPlayerType = 'default';
					}else{
						settings.vimeoPlayerType = 'chromeless';
					}
				}else{
					settings.vimeoPlayerType = 'chromeless';
				}
			}else{
				settings.vimeoPlayerType = 'default';
			}

			if(settings.vimeoPlayerType == 'default'){

				if(!vimeoInitedDefault){

					if(!vimeoHolderDefault){
						vimeoHolderDefault = VPLUtils.htmlToElement('<div class="vpl-vimeo-holder-default"/>');
						mediaHolder.prepend(vimeoHolderDefault);
					}

					vimeoIframeDefault = getVimeoParams('0');
					vimeoHolderDefault.style.display = 'block';
					vimeoHolderDefault.append(vimeoIframeDefault); 

					activeVimeoHolder = vimeoHolderDefault;
					activeVimeoIframe = vimeoIframeDefault;
					
					if(!window.Vimeo){
						var tag = document.createElement('script');
						tag.src = settings.vimeo_js;
						var firstScriptTag = document.getElementsByTagName('script')[0];
						firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
					}

					var interval = setInterval(function(){
						if(window.Vimeo){
							if(interval) clearInterval(interval);

							vimeoPlayerDefault = new Vimeo.Player(vimeoIframeDefault);
							activeVimeoPlayer = vimeoPlayerDefault;
							activeVimeoPlayer.on('loaded', onVimLoaded);//do not dispose, needed for every video

							activeVimeoPlayer.on('play', onVimPlay);
							activeVimeoPlayer.on('pause', onVimPause);
							activeVimeoPlayer.on('ended', onVimEnded);
							activeVimeoPlayer.on('error', onVimError);

							if(rememberPlaybackPosition){
								//we cant wait for promise in unload to get current time
								activeVimeoPlayer.on('timeupdate', onVimPlayProgress);
							}

							vimeoReadyDefault = true;

						}
					}, 100);
				 
					vimeoInitedDefault = true;

				}else{

					activeVimeoHolder = vimeoHolderDefault;
					activeVimeoIframe = vimeoIframeDefault;
					activeVimeoPlayer = vimeoPlayerDefault;

					if(vimeoReadyDefault){
						activeVimeoHolder.style.display = 'block';
						activeVimeoPlayer.loadVideo(mediaPath);

						//if(mediaPath == vimeoDefaultLastID){//fix, loaded does not fire if same video?
							setTimeout(function(){
								clearTimeout(this);
								onVimLoaded();
							},500);
						//}
					}
				}

				//vimeoDefaultLastID = mediaPath;

			}else{

				if(!vimeoInitedChromeless){

					if(!vimeoHolderChromeless){
						vimeoHolderChromeless = VPLUtils.htmlToElement('<div class="vpl-vimeo-holder-chromeless"></div>');
						mediaHolder.prepend(vimeoHolderChromeless);
					}

					vimeoIframeChromeless = getVimeoParams('1');
					vimeoHolderChromeless.style.display = 'block';
					vimeoHolderChromeless.append(vimeoIframeChromeless); 

					activeVimeoHolder = vimeoHolderChromeless;
					activeVimeoIframe = vimeoIframeChromeless;
					
					if(!window.Vimeo){
						var tag = document.createElement('script');
						tag.src = settings.vimeo_js;
						var firstScriptTag = document.getElementsByTagName('script')[0];
						firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
					}

					var interval = setInterval(function(){
						if(window.Vimeo){
							if(interval) clearInterval(interval);

							vimeoPlayerChromeless = new Vimeo.Player(vimeoIframeChromeless);
							activeVimeoPlayer = vimeoPlayerChromeless;
							activeVimeoPlayer.on('loaded', onVimLoaded);//do not dispose, needed for every video

							activeVimeoPlayer.on('play', onVimPlay);
							activeVimeoPlayer.on('pause', onVimPause);
							activeVimeoPlayer.on('ended', onVimEnded);
							activeVimeoPlayer.on('error', onVimError);
							activeVimeoPlayer.on('seeking', onVimSeeking);
							activeVimeoPlayer.on('seeked', onVimSeeked);
							if(settings.vimeoPlayerType == 'chromeless'){
								activeVimeoPlayer.on('playbackratechange', onVimPlaybackRateChange);
								activeVimeoPlayer.on('timeupdate', onVimPlayProgress);
							}

							vimeoReadyChromeless = true;
						}
					}, 100);
				
					vimeoInitedChromeless = true;

				}else{

					activeVimeoHolder = vimeoHolderChromeless;
					activeVimeoIframe = vimeoIframeChromeless;
					activeVimeoPlayer = vimeoPlayerChromeless;

					if(vimeoReadyChromeless){
						activeVimeoHolder.style.display = 'block';
						activeVimeoPlayer.loadVideo(mediaPath);

						//if(mediaPath == vimeoChromelessLastID){
							setTimeout(function(){
								clearTimeout(this);
								onVimLoaded();
							},500);
						//}
					}
				}

				//vimeoChromelessLastID = mediaPath;

				if(activeVimeoIframe){
					if(settings.aspectRatio == 2 && currMediaData.width && currMediaData.height){
						activeVimeoIframe.sw = currMediaData.width;
						activeVimeoIframe.sh = currMediaData.height;
						VPLAspectRatio.resizeMedia('iframe', settings.aspectRatio, vimeoHolder, activeVimeoIframe);
					}else{
						activeVimeoIframe.style.width = '100%';
						activeVimeoIframe.style.height = '100%';
					}
				}
				

			}

		}
		


		if(!qualityChange){
			
			//subtitles
			if(currMediaData.subtitles && currMediaData.subtitles.length && subtitleMenu){
				currMediaData.subtitles.push({label: settings.subtitleOffText})
				buildSubtitleMenu();
			}

			//preview seek
			if(previewSeekWrap){
				if(currMediaData.previewSeek){
					if(currMediaData.previewSeek == 'auto' && mediaType == 'video'){
						previewSeekReady = true;
						previewSeekAuto = true;

						if(!previewSeekVideoInited){
					    	initPreviewSeekVideo()
					    	previewSeekVideoInited = true;
					    }

					}else{
						previewSeekReady = false;
						getPreviewSeek();
					}
				}else{
					previewSeekReady = true;
					previewSeekAuto = false;
				}
			}

			//video title
			if(videoTitle && settings.showVideoTitle && currMediaData.title){
				videoTitle.innerHTML = (currMediaData.title)
				videoTitle.classList.add('vpl-visible');
			}

			//description
			if(infoDesc && currMediaData.description){
				var td = '';
				if(currMediaData.title)td += '<p class="vpl-is-video-title">'+currMediaData.title+'</p>';
				td += '<p class="vpl-media-description">'+currMediaData.description+'</p>';
				infoDesc.innerHTML = (td);
                if(infoToggle)infoToggle.style.display = 'block';
			}

			//download
			if(hasDownloadSupport && currMediaData.download && downloadToggle){
				downloadToggle.style.display = 'block';
				downloadToggle.setAttribute('href', currMediaData.download);
				downloadToggle.setAttribute('download', '');
			}

		}

		//show elements
		if(mediaType == 'audio' || mediaType == 'video' || mediaType == 'youtube' && settings.youtubePlayerType == 'chromeless' || mediaType == 'vimeo' && settings.vimeoPlayerType == 'chromeless'){
			
			if(currMediaData.liveStream){
				if(mediaTimeCurrent)mediaTimeCurrent.style.display = 'none';
				if(mediaTimeSeparator)mediaTimeSeparator.style.display = 'none';
				if(mediaTimeTotal)mediaTimeTotal.style.display = 'none';
				if(seekbar)seekbar.style.display = 'none';
				if(liveNote)liveNote.style.display = 'block';
			}else{
				if(mediaTimeCurrent)mediaTimeCurrent.style.display = 'block';
				if(mediaTimeSeparator)mediaTimeSeparator.style.display = 'block';
				if(mediaTimeTotal)mediaTimeTotal.style.display = 'block';
				if(seekbar)seekbar.style.display = 'block';
			}
			if(playbackToggle)playbackToggle.style.display = 'block';
			if(volumeWrapper)volumeWrapper.style.display = 'block';
			if(settingsWrap)settingsWrap.style.display = 'block';
			if(skipBackwardToggle)skipBackwardToggle.style.display = 'block';
			if(skipForwardToggle)skipForwardToggle.style.display = 'block';
			if(rewindToggle)rewindToggle.style.display = 'block';
			
		}else{
			
			//image
			if(playbackToggle)playbackToggle.style.display = 'none';
			if(volumeWrapper)volumeWrapper.style.display = 'none';
			if(mediaTimeCurrent)mediaTimeCurrent.style.display = 'none';
			if(mediaTimeSeparator)mediaTimeSeparator.style.display = 'none';
			if(mediaTimeTotal)mediaTimeTotal.style.display = 'none';
			if(seekbar)seekbar.style.display = 'none';
			if(settingsWrap)settingsWrap.style.display = 'none';
			if(skipBackwardToggle)skipBackwardToggle.style.display = 'none';
			if(skipForwardToggle)skipForwardToggle.style.display = 'none';
			if(rewindToggle)rewindToggle.style.display = 'none';

		}

	}

	//############################################//
	/* audio */
	//############################################//

	function handleAudioLoadedmetadata(){
		setTimeout(function(){
			if(!audioReady)handleAudioCanplay()
		},1000);
	}

	function handleAudioCanplay(){
		if(!audioReady){

			audioReady = true;

			setVolume();

			if(currMediaData.playbackRate){
				audioUp2Js.playbackRate = Number(currMediaData.playbackRate);
			}
			if(playbackRateMenu)setPlaybackRateActiveMenuItem(audioUp2Js.playbackRate);

			if(resumeTime)audioUp2Js.currentTime = resumeTime;
			else if(currMediaData.start)audioUp2Js.currentTime = currMediaData.start;


		
			if(autoPlay || resumeTime != null){

				var promise = audioUp2Js.play();
				if (promise !== undefined) {
					promise.then(function(){
				    	
				    }).catch(function(error){
				    	if(bigPlay)bigPlay.style.display = 'block';
				    	if(playerLoader)playerLoader.style.display = 'none';
				    });
				}

			}else{
				if(bigPlay)bigPlay.style.display = 'block';
				if(playerLoader)playerLoader.style.display = 'none';
			}

			resumeTime = null;

		}

	}

	function audioRateChangeHandler(){
		if(playbackRateMenu)setPlaybackRateActiveMenuItem(audioUp2Js.playbackRate);
	}

	function audioErrorHandler(e){
		console.log(e)
		self.fireEvent('mediaError', [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

		if(settings.autoAdvanceToNextMediaOnError)self.nextMedia()

	}

	//############################################//
	/* video */
	//############################################//

	function handleVideoLoadedMeta(){

		/*if(supportFor360Video && currMediaData.is360){
		}else{
			VPLAspectRatio.resizeMedia('video', settings.aspectRatio, playerHolder, video);
		}*/

		setTimeout(function(){
			if(!videoReady)handleVideoCanplay()
		},100);
	}

	function handleVideoCanplay(){

		if(!videoReady){

			videoReady = true;

			if(settings.selectorInit && !unmuteHappened){
				volume = initialVolume;
				unmuteHappened = true;
			}

			setVolume();

			if(supportFor360Video && currMediaData.is360){
			}else{
				VPLAspectRatio.resizeMedia('video', settings.aspectRatio, playerHolder, video);
			}

			if(currMediaData.playbackRate){
				videoUp2Js.playbackRate = Number(currMediaData.playbackRate);
			}
			if(playbackRateMenu)setPlaybackRateActiveMenuItem(videoUp2Js.playbackRate);


			if(resumeTime)videoUp2Js.currentTime = resumeTime;
			else if(currMediaData.start)videoUp2Js.currentTime = currMediaData.start;

		

			if(autoPlay || resumeTime != null){

				var promise = videoUp2Js.play();
				if (promise !== undefined) {
					promise.then(function(){
				    	
				    }).catch(function(error){
				    	if(bigPlay)bigPlay.style.display = 'block';
				    	if(playerLoader)playerLoader.style.display = 'none';
				    });
				}

			}else{
				if(bigPlay)bigPlay.style.display = 'block';
				if(playerLoader)playerLoader.style.display = 'none';
			}


			
			resumeTime = null;

			if(pictureInPictureEnabled || videoUp2Js.webkitSupportsPresentationMode && typeof videoUp2Js.webkitSetPresentationMode === "function")if(pipToggle)pipToggle.style.display = 'block';

			if(previewSeekAuto){
				setPreviewSeekVideoSource()
			}

			

		}

	}

	function videoCanPlayThroughHandler(){

		video.classList.add('vpl-visible');
		VPLAspectRatio.resizeMedia('video', settings.aspectRatio, playerHolder, video);

	}

	function videoWaitingHandler(){
		if(subMediaType == 'hls' || subMediaType == 'dash'){
			//loader sometimes not hiding, possibly other events show it again after seek?
		}else{
			//if(settings.mediaEndAction != 'loop')if(mediaPlaying)playerLoader.style.display = 'block';//do not show preloader on video end, especially if we are in loop mode
		} 
	}

	function videoPlayingHandler(){
		if(playerLoader)playerLoader.style.display = 'none';	
	}

	function videoPlayHandler(){
		playHandler();
	}

	function videoPauseHandler(){
		if(!(videoUp2Js.currentTime >= videoUp2Js.duration)){
			//console.log('calling pause') 
			//note was called on video end?, but we dont need it
			pauseHandler();
		}
		else if(settings.mediaEndAction == 'rewind'){
			pauseHandler();
		}
	}

	function videoSeekedHandler(){
		if(playerLoader)playerLoader.style.display = 'none';

		if(supportFor360Video && currMediaData.is360){
			doRender = true;
		}

	}

	function videoRateChangeHandler(){
		if(playbackRateMenu)setPlaybackRateActiveMenuItem(videoUp2Js.playbackRate);
	}

	function videoErrorHandler(e){

		switch (e.target.error.code) {
		   case e.target.error.MEDIA_ERR_ABORTED:
			   console.log('You aborted the video playback.');
			   break;
		   case e.target.error.MEDIA_ERR_NETWORK:
			   console.log('A network error caused the video download to fail part-way.');
			   break;
		   case e.target.error.MEDIA_ERR_DECODE:
			   console.log('The video playback was aborted due to a corruption problem or because the video used features your browser did not support.');
			   break;
		   case e.target.error.MEDIA_ERR_SRC_NOT_SUPPORTED:
			   console.log('The video could not be loaded, either because the server or network failed or because the format is not supported.');
			   break;
		   default:
			   console.log('An unknown error occurred.');
			   break;
		}	

		if(settings.autoAdvanceToNextMediaOnError)self.nextMedia()
	}


	//############################################//
	/* HLS */
	//############################################//

	var recoverMediaErrorDate,
    swapAudioCodecDate

	function initHls(){

		hls = new Hls();

		hlsSupport = Hls.isSupported();
		hlsInited = true;

		if(hlsSupport){

			hls.subtitleDisplay = false;
			hls.subtitleTrack = -1;
			//https://github.com/video-dev/hls.js/issues/1184
			
		    hls.on(Hls.Events.MEDIA_ATTACHED, function () {
		        //console.log("video and hls.js are now bound together !");
				hls.loadSource(mediaPath);
		    });

		    hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
	            //console.log('Hls.Events.MANIFEST_PARSED: ', data.levels, hls.currentLevel, hls.autoLevelEnabled);

	            if(settings.showStreamVideoBitrateMenu && !streamVideoBitrateMenuBuilt){
	            	streamVideoBitrateMenuBuilt = true;

		            var arr = [], i, len = data.levels.length, level, curr_qual;

		            for(i = 0; i < len; i++) {
		            	level = data.levels[i];
			           	if(level.width){
					        arr.push({
					        	label: level.width+'x'+level.height+', '+Math.ceil(Math.round(level.bitrate/1000))+'kbps',
					            value: i.toString()
					        });

					    }
				    }
				    arr.push({
				        label: 'auto',
				        value: '-1',
				        selected: true
				    });

				    if(settings.hideQualityMenuOnSingleQuality && arr.length == 1){
					}else{
						//console.log(arr)
					    if(qualityMenu)buildQualityMenu(arr, 'auto');
					}

				}
			    
	        });

	        hls.on(Hls.Events.MANIFEST_LOADED, function (event, data) {
	        	//console.log(data)
			});

	        hls.on(Hls.Events.LEVEL_LOADED, function (event, data) {
	        	//console.log(data.level)
			});

	        hls.on(Hls.Events.AUDIO_TRACKS_UPDATED, function (event, data) {
			    //console.log('Number of audio tracks found: ' + data.audioTracks.length);

			    if(settings.showStreamAudioBitrateMenu && !streamAudioBitrateMenuBuilt){	
			    	streamAudioBitrateMenuBuilt = true;

				    //groups
					/*var result = hls.audioTracks.reduce(function (r, a) {
				        r[a.groupId] = r[a.groupId] || [];
				        r[a.groupId].push(a);
				        return r;
				    }, Object.create(null));

					var audioTracks = Object.values(result)[0];*/
					//console.log(audioTracks)

					var audioTracks = hls.audioTracks;

				    var len = audioTracks.length;
				    if(len > 1){
					    var i, track, label, value, li;
						for(i = 0; i < len; i++){
					        track = audioTracks[i];
					        label = track.groupId + ' - ' + track.name;
					        value = track.id.toString();
					        li = VPLUtils.htmlToElement('<button type="button"></button>')

					        li.classList.add('vpl-menu-item', 'vpl-btn-reset')
					        li.setAttribute('data-value', value);
					        li.setAttribute('data-label', label);
					        li.setAttribute('tabindex', '0');
					        li.textContent = label;
					        li.addEventListener('click', toggleAudioLanguage)
					        audioLanguageMenu.append(li);

					    }

					    streamHasAudioTracks = true;
					    if(audioLanguageToggle)audioLanguageToggle.style.display = 'block';
					    audioLanguageSettingsMenu.classList.remove('vpl-force-hide');
					}

				}
			});

			hls.on(Hls.Events.AUDIO_TRACK_SWITCHED, function (event, data) {
				//console.log('Hls.Events.AUDIO_TRACK_SWITCHED: ' + data.id)
				if(streamHasAudioTracks)setAudioLanguageActiveMenuItem(data.id);
			});

			hls.on(Hls.Events.AUDIO_TRACK_LOADED, function (event, data) {
				//console.log('Hls.Events.AUDIO_TRACK_LOADED: ' + data.id)
			});

			hls.on(Hls.Events.SUBTITLE_TRACK_SWITCH, function (event, data) {
				//console.log('Hls.Events.SUBTITLE_TRACK_SWITCH: ' + data.id)
				//console.log(hls.subtitleTracks)
			});

			hls.on(Hls.Events.SUBTITLE_TRACK_LOADED, function (event, data) {
				//console.log('Hls.Events.SUBTITLE_TRACK_LOADED: ' + data.id)
			});

			hls.on(Hls.Events.SUBTITLE_TRACKS_UPDATED, function (data) {

				if(subtitleMenu){

					var len = hls.subtitleTracks.length;

					if(len > 0 && !currMediaData.subtitles){

						if(!streamSubtitleMenuBuilt){
							streamSubtitleMenuBuilt = true;

							var i, track, d, value;
							currMediaData.subtitles = [];

							for(i = 0; i < len; i++){
								track = hls.subtitleTracks[i];
						        //console.log(track)
						        value = i.toString();
								d = {label: track.name, value: value, src: track.url};
								if(track.default)d.default = true;
								currMediaData.subtitles.push(d);
						    }

						    externalSubtitle = true;
						    buildSubtitleMenu();
						}

					}

				}

			});

			hls.on(Hls.Events.ERROR, function (event, data) {
		    	if(data.fatal){
		        	switch(data.type) {
			        case Hls.ErrorTypes.NETWORK_ERROR:
				        // try to recover network error
				        console.log("fatal network error encountered, try to recover");
				        hls.startLoad();
				        break;
			        case Hls.ErrorTypes.MEDIA_ERROR:
				        console.log("fatal media error encountered, try to recover");
				        //hls.recoverMediaError();

				        var now = performance.now();
					    if (!recoverMediaErrorDate || now - recoverMediaErrorDate > 3000) {
					        recoverMediaErrorDate = performance.now();
					        hls.recoverMediaError();
					    } else if (!swapAudioCodecDate || (now - swapAudioCodecDate) > 3000) {
					        swapAudioCodecDate = performance.now();
					        hls.swapAudioCodec();
					        hls.recoverMediaError();
					    }


				        break;
			        default:
				        // cannot recover
				        hls.destroy();
				        break;
			        }
		        }
		    });

		}

	}

	//############################################//
	/* DASH */
	//############################################//

	function initDash(){

		/*
		https://reference.dashif.org/dash.js/v2.9.1/samples/dash-if-reference-player/index.html
		http://vm2.dashif.org/dash.js/docs/jsdocs/MediaPlayer.js.html
		http://cdn.dashjs.org/latest/jsdoc/module-MediaPlayer.html
		http://mediapm.edgesuite.net/dash/public/nightly/samples/dash-if-reference-player/app/main.js
		*/

		dash = dashjs.MediaPlayer().create();
		dashInited = true;

		dash.setFastSwitchEnabled = true;

		//dash.attachTTMLRenderingDiv(document.getElementById(''));
        //https://github.com/Dash-Industry-Forum/dash.js/issues/1634

	    dash.on(dashjs.MediaPlayer.events.STREAM_INITIALIZED, function (e) {
			//onsole.log("dashjs.MediaPlayer.events.STREAM_INITIALIZED", e);

			if(settings.showStreamVideoBitrateMenu && !streamVideoBitrateMenuBuilt){
				streamVideoBitrateMenuBuilt = true;

				//video quality tracks

				var bitRates = dash.getBitrateInfoListFor('video');

	            var arr = [], i, len = bitRates.length, level;

	            for(i = 0; i < len; i++) {
	            	level = bitRates[i];
		           	if(level.width){
				        arr.push({
				        	label: level.width+'x'+level.height+', '+Math.ceil(Math.round(level.bitrate/1000))+'kbps',
				            value: level.qualityIndex.toString()
				        });
				    }
			    }
			    arr.push({
			        label: 'auto',
			        value: '-1',
			        selected: true
			    });

			    if(settings.hideQualityMenuOnSingleQuality && arr.length == 1){
				}else{
				    if(qualityMenu)buildQualityMenu(arr, 'auto');
				}

			}

			if(settings.showStreamAudioBitrateMenu && !streamAudioBitrateMenuBuilt){
				streamAudioBitrateMenuBuilt = true;

				//audio tracks

	            var audioTracks = dash.getBitrateInfoListFor('audio');
	            len = audioTracks.length;

	            if(len > 1){
		            
		            var track, label, value, li;
					for(i = 0; i < len; i++){
						track = audioTracks[i];
				        label = Math.ceil(Math.round(track.bitrate/1000))+'kbps',
				        //console.log(track)
				        value = track.qualityIndex.toString();

				        li = VPLUtils.htmlToElement('<button type="button"></button>')

				        li.classList.add('vpl-menu-item', 'vpl-btn-reset')
				        li.setAttribute('data-value', value);
				        li.setAttribute('data-label', label);
				        li.setAttribute('tabindex', '0');
				        li.textContent = label;
				        li.addEventListener('click', toggleAudioLanguage)
				        audioLanguageMenu.append(li);

				    }

				    streamHasAudioTracks = true;
				    audioLanguageToggle.style.display = 'block';
				    audioLanguageSettingsMenu.classList.remove('vpl-force-hide');

				}

			}

	    });

	    dash.on(dashjs.MediaPlayer.events.QUALITY_CHANGE_REQUESTED, function (e) {
	        //console.log("QUALITY_CHANGE_REQUESTED", e.oldQuality, e.newQuality);
	    });

	    dash.on(dashjs.MediaPlayer.events.QUALITY_CHANGE_RENDERED, function (e) {
	        //console.log("dashjs.MediaPlayer.events.QUALITY_CHANGE_RENDERED", e.newQuality);
	    });

	    dash.on(dashjs.MediaPlayer.events.TRACK_CHANGE_RENDERED, function (e) {
	        //console.log("TRACK_CHANGE_RENDERED", e);
	    });

	    /*

		this.dashJsInstance.setAutoSwitchQualityFor('video', false);
		this.dashJsInstance.setQualityFor('video', quality);
        

		hls.on(Hls.Events.AUDIO_TRACK_SWITCHED, function (event, data) {
			if(streamHasAudioTracks)setAudioLanguageActiveMenuItem(data.id);
		});*/

		dash.on(dashjs.MediaPlayer.events.TEXT_TRACKS_ADDED, function (e) {
	        //console.log("dashjs.MediaPlayer.events.TEXT_TRACKS_ADDED", e);
	    });

		dash.on(dashjs.MediaPlayer.events.ERROR, function (e) {
	        console.log('dashjs.MediaPlayer.events.ERROR ' + e.error + ' : ' + e.event.message);
	    });
	    dash.on(dashjs.MediaPlayer.events.PLAYBACK_ERROR, function (e) {
	        //console.log("dashjs.MediaPlayer.events.PLAYBACK_ERROR");
	    });

	}

	//############################################//
	/* 360 */
	//############################################//

	function controlsChange(){//360 info message

		if(mediaType == 'image' && currMediaData.is360){//static scene, no animation loop
			irenderer.render(iscene, camera);
		}

		if(vrInfo && vrInfoVisible){
			vrInfoVisible = false;
			setTimeout(function(){
				clearTimeout(this);
				vrInfo.style.display = 'none';
				vrInfo.style.opacity = 0;
			},2000);
		}
	}

	//############################################//
	/* youtube player */
	//############################################//

	window.onYouTubeIframeAPIReady = function() {}
			 
	function onYtPlayerReady(event) {

		youtubeReady = true;

		if(!youtubeIframe){
			youtubeIframe = youtubeHolder.querySelector('.vpl-emiframe');
			resizeYtIframe()
		}

		if(settings.youtubePlayerType == 'chromeless' && settings.forceYoutubeChromeless)youtubeIframe.classList.add('vpl-yt-clean');

		youtubeIframe.classList.add('vpl-visible');

		if(settings.selectorInit && !unmuteHappened){
			volume = initialVolume;
			unmuteHappened = true;
		}
		
		setVolume(0);

		if(autoPlay){
			youtubePlayer.playVideo();
		}else{
			if(resumeTime != null || currMediaData.poster){//if coming from poster
				youtubePlayer.playVideo();
			}

			if(bigPlay)bigPlay.style.display = 'block';
		}

	}

	function onYtPlayerPlaybackRateChange(event) {
		setPlaybackRateActiveMenuItem(event.data);
	}

	function onYtPlayerStateChange(event) {

		if(event.data == -1){//unstarted
		}
		else if(event.data == 0){//ended
			mediaEndHandler();
		}
		else if(event.data == 1){//playing
			
			if(!youtubeStarted){
				resumeTime = null;
			
				if(currMediaData.quality)youtubePlayer.setPlaybackQuality(currMediaData.quality);

				if(currMediaData.playbackRate && currMediaData.playbackRate != 1){
					youtubePlayer.setPlaybackRate(Number(currMediaData.playbackRate));
				}else{
					setPlaybackRateActiveMenuItem(1);
				}

				if(settings.blockYoutubeEvents){
					if(!youtubeBlocker){//transparent div over yt iframe (+ hide right click, - cannot close yt ads)
						youtubeBlocker = VPLUtils.htmlToElement('<div class="vpl-iframe-blocker"></div>')
						youtubeBlocker.style.display = 'none';
						youtubeHolder.append(youtubeBlocker);
					}
				}

				youtubeStarted = true;	

			}

			if(youtubeBlocker && !currMediaData.is360){
				youtubeBlocker.style.display = 'block';//hide for 360 videos
			}

			playHandler();

			youtubePlayed = true;
		}
		else if(event.data == 2){//paused
			//if(youtubeBlocker)youtubeBlocker.style.display = 'none';

			pauseHandler();
			
		}
		else if(event.data == 3){//buffering
			youtubeIframe.classList.add('vpl-visible');//we cant have it in playHandler in case video does not start in chrome, safari?
		}
		else if(event.data == 5){//cued
			if(!autoPlay){
				youtubeIframe.classList.add('vpl-visible');
				///if(!isMobile)
				if(bigPlay)bigPlay.style.display = 'block'
			}
		}
	}
	
	function onYtPlayerError(e) {
		switch(e.data){
			case 2:
			console.log("Error code = "+e.data+": The request contains an invalid parameter value. For example, this error occurs if you specify a video ID that does not have 11 characters, or if the video ID contains invalid characters, such as exclamation points or asterisks.")
			break;
			case 100:
			console.log("Error code = "+e.data+": Video not found, removed, or marked as private")
			break;
			case 101:
			console.log("Error code = "+e.data+": Embedding disabled for this video")
			break;
			case 150:
			console.log("Error code = "+e.data+": Video not found, removed, or marked as private [same as error 100]")
			break;
		}

		if(settings.autoAdvanceToNextMediaOnError)self.nextMedia()
		
	}

	//############################################//
	/* vimeo */
	//############################################//

	function getVimeoParams(bg){

		var color = VPLUtils.rgbToHex(settings.vimeoPlayerColor);
		if(color.charAt(0) == '#')color = color.substr(1);

		var np = '1';
		if(settings.useMobileNativePlayer)np = '0';

		var iap = autoPlay ? '1' : '0';
		var loop = settings.mediaEndAction == 'loop' ? '1' : '0';
		
		//https://developer.vimeo.com/apis/oembed
		//https://github.com/vimeo/player.js
		//https://help.vimeo.com/hc/en-us/articles/360001494447-Using-Player-Parameters
		var vim_frameId = 'player'+Math.floor(Math.random()*0xFFFFFF),
		color = '?color=' + color,
		byline = '&byline=1',
		portrait = '&portrait=1',
		title = '&title=1',
		autopause = '&autopause=1',
		loop = '&loop='+loop,
		playsinline = '&playsinline='+np,
		dnt = '&dnt=1',
		muted = '&muted=0',
		ap = '&autoplay='+iap,
		speed = '&speed=1',//pro
		background = '&background='+bg//plus+

		/*
		background bugs:
			1. 360 video cannot be panned, https://github.com/vimeo/player.js/issues/287
			2. click not detected as above, we have to use blocker
			3. set playback rate does no work, https://github.com/vimeo/player.js/issues/195
			4. with autoplay true, force-m-a? false, it makes play, then 2x pause events which show our controls
		
		volume: https://github.com/vimeo/player.js/issues/236
		*/

		if(autoPlay){
			//muted = '&muted=1';
		    if(settings.autoPlayInViewport){
		    	ap = '&autoplay=0';
		    }else{
		    	ap = '&autoplay=1';
		    	ap = true;
		    }
			//setVolume(0);
		}

		if(mediaPath.indexOf('/')>-1)mediaPath = mediaPath.substr(0,mediaPath.indexOf('/'));//unlisted

		var iframeSrc = 'https://player.vimeo.com/video/' + mediaPath + color + byline + portrait + title + autopause + playsinline + loop + dnt + background + speed + muted + ap;

		if(currMediaData.quality)iframeSrc += '&quality='+currMediaData.quality;//https://help.vimeo.com/hc/en-us/articles/224983008-Setting-default-quality-for-embedded-videos
	
		var iframe = VPLUtils.htmlToElement('<iframe class="vpl-media vpl-emiframe" frameborder=0 src="'+iframeSrc+'" width="100%" height="100%" webkitAllowFullScreen mozallowfullscreen allowFullScreen allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"><iframe>');

		return iframe;

	}

	function onVimPlay(){

		if(!vimeoStarted){

			activeVimeoIframe.classList.add('vpl-visible');

			if(currMediaData.start || resumeTime){

				if(resumeTime)var st = resumeTime;
				else var st = currMediaData.start;
				resumeTime = null;

				activeVimeoPlayer.setCurrentTime(st).then(function(seconds) {
				    // seconds = the actual time that the player seeked to
				}).catch(function(error) {
				    //console.log(error.name)
				});
			}

			var pr = currMediaData.playbackRate || 1;
			activeVimeoPlayer.setPlaybackRate(pr).then(function(playbackRate) {
			    // playback rate was set
			}).catch(function(error) {
				//playbackRateToggle.hide();//https://github.com/vimeo/player.js/issues/195
			});
			
			vimeoStarted = true;

		}

		playHandler();

		vimeoPlayed = true;

	}

	function onVimPause(){
		if(mediaType && mediaType != 'vimeo')return;//stupid vimeo bugs
		
		pauseHandler();
		
	}

	function onVimPlaybackRateChange(event){
		setPlaybackRateActiveMenuItem(event.playbackRate);
	}
	
	function onVimLoaded(){

		if(settings.selectorInit && !unmuteHappened){
			volume = initialVolume;
			unmuteHappened = true;
		}

		setVolume();

		if(settings.vimeoPlayerType == 'default')settings.blockVimeoEvents = false;
		else settings.blockVimeoEvents = true;

		if(settings.blockVimeoEvents){//unlike yt can add blocker before first play
			
			if(!vimeoBlocker){//transparent div over iframe (no right click like yt, but neccesary to detect click, vimeo doesnt do it)
				vimeoBlocker = VPLUtils.htmlToElement('<div class="vpl-iframe-blocker"></div>')
				vimeoBlocker.style.display = 'none';
				activeVimeoHolder.append(vimeoBlocker);
			}

			//we cant detect 360 video for vimeo over api at all
			if(currMediaData.is360 || currMediaData.password)vimeoBlocker.style.display = 'none';//hide for 360 videos
			else vimeoBlocker.style.display = 'block';
		}

		if(autoPlay || resumeTime != null){

			activeVimeoPlayer.play().then(function() {
				//onVimPlay();//fix, no onVimPlay event first time?
			}).catch(function(error) {
				//console.log(error)
				if(settings.vimeoPlayerType == 'chromeless'){
					if(!currMediaData.vpwd)if(bigPlay)bigPlay.style.display = 'block';
					if(playerLoader)playerLoader.style.display = 'none';
				}
			});
			
		}else{

			if(settings.vimeoPlayerType == 'chromeless'){
				if(!currMediaData.vpwd)if(bigPlay)bigPlay.style.display = 'block';
				if(playerLoader)playerLoader.style.display = 'none';
			}
			
		}

		activeVimeoIframe.classList.add('vpl-visible');

	}
	function onVimPlayProgress(data){

		vimeoDuration = data.duration;
		vimeoCurrentTime = data.seconds;
		vimeoProgress = data.percent;

		/*if(currMediaData.end){
			if(data.seconds>=currMediaData.end){
				mediaEndHandler();
				return;
			}
		}*/
	}
	function onVimEnded(){
		mediaEndHandler();
	}
	function onVimSeeking(){
		if(playerLoader)playerLoader.style.display = 'block';
	}
	function onVimSeeked(){
		if(playerLoader)playerLoader.style.display = 'none';
	}
	function onVimError(error){
		console.log('Vimeo Player Error!', error);	
		if(settings.autoAdvanceToNextMediaOnError)self.nextMedia()
	}

	//############################################//
	/*  */
	//############################################//

	function mediaEndHandler(){

		self.fireEvent('mediaEnd', [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

		if(typeof currMediaData.endLink !== 'undefined' && !VPLUtils.isEmpty(currMediaData.endLink)){
			if(currMediaData.endTarget == '_blank'){	
				window.open(currMediaData.endLink);
			}else{
				var link = currMediaData.endLink;
				cleanMedia(); 
				window.location = link;
				return;
			}
		}



		var st = currMediaData.start || 0;

		if(settings.mediaEndAction == 'loop'){
			
			if(mediaType == 'audio'){
				if(audioUp2Js){
					audioUp2Js.currentTime = st;
					audioUp2Js.play();
				} 
			}else if(mediaType == 'video'){
				if(videoUp2Js){
					videoUp2Js.currentTime = st;
					videoUp2Js.play();
				} 
			}else if(mediaType == 'youtube'){
				if(youtubePlayer){
					youtubePlayer.seekTo(st);
					youtubePlayer.playVideo();
				}
			}else if(mediaType == 'vimeo'){
				if(activeVimeoPlayer){
					//activeVimeoPlayer.pause();//fix
					activeVimeoPlayer.setCurrentTime(st);
					//activeVimeoPlayer.play();
				}
			}

		}else if(settings.mediaEndAction == 'rewind'){

			if(mediaType == 'audio'){
				if(audioUp2Js){
					audioUp2Js.currentTime = st;
					audioUp2Js.pause();
				} 
			}else if(mediaType == 'video'){
				if(videoUp2Js){
					videoUp2Js.currentTime = st;
					videoUp2Js.pause();
				} 
			}else if(mediaType == 'youtube'){
				if(youtubePlayer) {
					youtubePlayer.seekTo(st);
					youtubePlayer.pauseVideo();
				}

			}else if(mediaType == 'vimeo'){
				if(activeVimeoPlayer){//does not work properly, shows rewind screen
					activeVimeoPlayer.pause();
					activeVimeoPlayer.setCurrentTime(st);
				}
			}

		}else if(settings.mediaEndAction == 'next'){

			self.nextMedia();

		}else if(settings.mediaEndAction == 'poster'){

			if(mediaType == 'audio'){
				if(audioUp2Js){
					audioUp2Js.currentTime = st;
					audioUp2Js.pause();
				} 
			}else if(mediaType == 'video'){
				if(videoUp2Js){
					videoUp2Js.currentTime = st;
					videoUp2Js.pause();
				} 

				if(currMediaData.poster){

					mediaStarted = false;

					if(supportFor360Video && currMediaData.is360){
						if(vcanvas)vcanvas.style.display = 'none';
					}else{
						if(videoHolder)videoHolder.style.display = 'none';
					}

					setPoster();

				}

			}else if(mediaType == 'youtube'){
				if(youtubePlayer) {
					youtubePlayer.seekTo(st);
					youtubePlayer.pauseVideo();
				}

				if(currMediaData.poster){
					mediaStarted = false;
					youtubeHolder.style.display = 'none';
					setPoster();
				}

			}else if(mediaType == 'vimeo'){
				if(activeVimeoPlayer){//does not work properly, shows rewind screen
					activeVimeoPlayer.pause();
					activeVimeoPlayer.setCurrentTime(st);
				}

				if(currMediaData.poster){
					mediaStarted = false;
					activeVimeoHolder.style.display = 'none';
					setPoster();
				}
			}

		}
	}

	function cleanMedia(){

		doRender = false;
		if(renderAnimationID) cancelAnimationFrame(renderAnimationID);
		
		if(dataIntervalID) clearInterval(dataIntervalID);

		window.removeEventListener('scroll', handleAutoplayScroll);

		if(posterHolder){
			posterHolder.style.display = 'none';
			posterHolder.innerHTML = '';
		}
		if(poster)poster = null;

		mediaHolder.querySelectorAll('.vpl-media').forEach(el => {
			el.classList.remove('vpl-visible');
		});

		if(vrInfo){
			vrInfo.style.display = 'none';
			vrInfo.style.opacity = 0;
			vrInfoVisible = false;
		}

		if(orbitControls){
			orbitControls.removeEventListener('change', controlsChange);
			orbitControls.reset();
		}
		
		if(mediaType == 'audio'){

			if(audioUp2Js){
				audioUp2Js.pause();
				audioUp2Js.src = '';
			}
			if(audio){
				audio.removeEventListener("ended", mediaEndHandler);	
				audio.removeEventListener("loadedmetadata", handleAudioLoadedmetadata);
				audio.removeEventListener("canplay", handleAudioCanplay)
				audio.removeEventListener("play", playHandler);
				audio.removeEventListener("pause", pauseHandler);
				audio.removeEventListener("ratechange", audioRateChangeHandler);
				audio.removeEventListener("error", audioErrorHandler);
			}

		}else if(mediaType == 'video'){

			if(hlsSupport && subMediaType == 'hls'){
				if(hls){
					hls.detachMedia();
					hls.off();
					hls.destroy();
					hls = null;
					hlsInited = false;
				}
			}
			if(dashSupport && subMediaType == 'dash'){
				dash.attachSource(null);
			}

			if(supportFor360Video && currMediaData.is360){
				if(vcanvas)vcanvas.style.display = 'none';
			}
			
			if(videoUp2Js){
				videoUp2Js.pause();
				try{
					videoUp2Js.currentTime = 0;
				}catch(er){}
				videoUp2Js.src = '';
				videoUp2Js = null;
			}
			
			if(video){
				video.removeEventListener("ended", mediaEndHandler);
				video.removeEventListener("loadedmetadata", handleVideoLoadedMeta);
				video.removeEventListener("canplay", handleVideoCanplay);
				video.removeEventListener("canplaythrough", videoCanPlayThroughHandler);
				video.removeEventListener("waiting", videoWaitingHandler);
				video.removeEventListener("playing", videoPlayingHandler);
				video.removeEventListener("play", videoPlayHandler);
				video.removeEventListener("pause", videoPauseHandler);
				video.removeEventListener("seeked", videoSeekedHandler);
				video.removeEventListener("ratechange", videoRateChangeHandler);
				video.removeEventListener("error", videoErrorHandler);
			}


			if(videoHolder)videoHolder.style.display = 'none';

		}else if(mediaType == 'image'){

			if(durationTimeoutID){
				clearTimeout(durationTimeoutID);
				durationTimeoutID = null;
			}

			if(currMediaData.is360){

				if(icanvas)icanvas.style.display = 'none';
				image_360_ready = false;

			}else{

				if(imageHolder){
					imageHolder.style.display = 'none';
					imageHolder.innerHTML = '';
				}
				image = null;
			}

		}else if(mediaType == 'youtube'){

			if(youtubePlayer){
				if(youtubeReady)youtubePlayer.stopVideo();
			}
			youtubeStarted = false;
			if(youtubeHolder)youtubeHolder.style.display = 'none';

		}else if(mediaType == 'vimeo'){

			vimeoDuration = 0;
			vimeoCurrentTime = 0;
			vimeoProgress = 0;

			if(activeVimeoPlayer){
				activeVimeoPlayer.unload().then(function() {
				}).catch(function(error) {
				    console.log(error)
				});

			}
			vimeoStarted = false;
			if(activeVimeoHolder)activeVimeoHolder.style.display = 'none';

		}

		//pwd
		if(pwdHolder){
			pwdHolder.style.display = 'none';
			pwdHolder.classList.remove('vpl-holder-visible');
		}
		if(pwdField)pwdField.value = '';

		if(mediaTimeCurrent){
			mediaTimeCurrent.textContent = '00:00';
			mediaTimeCurrent.style.display = 'none';
		}
		if(mediaTimeSeparator)mediaTimeSeparator.style.display = 'none';
		if(mediaTimeTotal){
			mediaTimeTotal.textContent = '00:00';
			mediaTimeTotal.style.display = 'none';
		}

		if(downloadToggle){
			downloadToggle.style.display = 'none';
			downloadToggle.setAttribute('href', '#')
			downloadToggle.removeAttribute('download');
		}
		

		lastTime = null;

		//subtitle
		if(subtitleHolder)subtitleHolder.style.display = 'none';
		if(subtitleHolderInner)subtitleHolderInner.innerHTML = '';
		subtitleOn = false;
		subtitleArr = [];
		allSubtitleArr = [];
		activeSubtitle = null;
		activeSubtitleMenuItem = null;
		if(subtitleMenu)subtitleMenu.innerHTML = '';
		if(subtitleSettingsMenu)subtitleSettingsMenu.classList.add('vpl-force-hide');
		if(captionToggle)captionToggle.style.display = 'none';
		externalSubtitle = false;

		if(qualityMenu)qualityMenu.innerHTML = '';
		if(qualitySettingsMenu)qualitySettingsMenu.classList.add('vpl-force-hide');

		if(audioLanguageMenu)audioLanguageMenu.innerHTML = '';
	    if(audioLanguageSettingsMenu)audioLanguageSettingsMenu.classList.add('vpl-force-hide');
	    streamHasAudioTracks = false;
	    streamVideoBitrateMenuBuilt = false;
		streamAudioBitrateMenuBuilt = false;
		streamSubtitleMenuBuilt = false;

		mediaForcePause = false;
		mediaPath = null;

		if(videoTitle){
			videoTitle.innerHTML = '';
			videoTitle.classList.remove('vpl-visible');
		}
		if(infoHolder){
			infoHolder.style.display = 'none';
			infoHolder.classList.remove('vpl-holder-visible');
		}
		if(infoToggle)infoToggle.style.display = 'none';
		infoOpened = false;

		if(shareHolder){
			shareHolder.style.display = 'none';
			shareHolder.classList.remove('vpl-holder-visible');
		}
		shareOpened = false;

		if(liveNote)liveNote.style.display = 'none';

		if(pipToggle)pipToggle.style.display = 'none';

		//settings menu
		if(settingsHolder){
			settingsHolder.classList.remove('vpl-holder-visible')
			settingsHolder.style.display = 'none';
			settingsHolder.style.width = 'auto';
			settingsHolder.style.height = 'auto';

			settingsHolder.querySelectorAll('.vpl-settings-menu').forEach(function(el){
				el.style.display = 'none';
			})
		}
		
		if(settingsHome)settingsHome.style.display = 'block';

		if(contextMenu)contextMenu.style.display = 'none';

		mediaType = null;
		subMediaType = null;
		mediaPlaying = false;
		currMediaData = null;
		mediaStarted = false;
		if(loadLevel)loadLevel.style.width = 0;
		if(progressLevel)progressLevel.style.width = 0;

		if(playerLoader)playerLoader.style.display = 'none';	
		if(bigPlay)bigPlay.style.display = 'none';
		if(playbackToggle){
			playbackToggle.querySelector('.vpl-btn-play').style.display = 'block';
			playbackToggle.querySelector('.vpl-btn-pause').style.display = 'none';
		}

		previewSeekImg = null;
		previewSeekArr = [];
		if(previewSeekWrap)previewSeekWrap.style.display = 'none';
		if(previewSeekInner)previewSeekInner.style.backgroundImage = 'none';

		if(previewSeekAuto){

			previewSeekAuto = false
			previewSeekSnapshotDone = false

			if(hlsSupport && subMediaType == 'hls'){
				if(hlsForPreviewSeek)hlsForPreviewSeek.detachMedia();
			}
			if(dashSupport && subMediaType == 'dash'){
				if(dashForPreviewSeek)dashForPreviewSeek.attachSource(null);
			}

			if(videoForPreviewSeek){
				videoForPreviewSeek.pause();
				videoForPreviewSeek.src = '';
			}

			if(previewSeekCanvasCtx)previewSeekCanvasCtx.clearRect(0, 0, previewSeekCanvasWidth, previewSeekCanvasHeight);

		}

		if(activeSubtitle)subtitleHolderInner.classList.remove('vpl-subtitle-raised');
		if(tooltip)tooltip.style.display = 'none';

		if(!autoPlay){
			//poster or not, we want to hide controls in autoPlay off

			playerControls.forEach(el => {
				el.style.display = 'none';
				el.classList.remove('vpl-player-controls-visible');
			})

			interfaceHidden = true;
		}

	}

	function cleanMediaQuality(){
		//console.log('cleanMediaQuality')

		doRender = false;
		if(renderAnimationID) cancelAnimationFrame(renderAnimationID);
		
		if(dataIntervalID) clearInterval(dataIntervalID);

		resumeTime = null;

		if(orbitControls){
			orbitControls.removeEventListener('change', controlsChange);
			orbitControls.reset();
		}

		if(mediaType == 'audio'){

			if(audioUp2Js){
				resumeTime = audioUp2Js.currentTime;
				audioUp2Js.pause();
				audioUp2Js.src = '';
			}
			if(audio){
				audio.removeEventListener("ended", mediaEndHandler);	
				audio.removeEventListener("loadedmetadata", handleAudioLoadedmetadata);
				audio.removeEventListener("canplay", handleAudioCanplay)
				audio.removeEventListener("play", playHandler);
				audio.removeEventListener("pause", pauseHandler);
				audio.removeEventListener("ratechange", audioRateChangeHandler);
				audio.removeEventListener("error", audioErrorHandler);
			}

		}else if(mediaType == 'video'){

			if(supportFor360Video && currMediaData.is360){
				if(vcanvas)vcanvas.style.display = 'none';
			}

			if(videoUp2Js){
				resumeTime = videoUp2Js.currentTime;
				videoUp2Js.pause();
				try{
					videoUp2Js.currentTime = 0;
				}catch(er){}
				videoUp2Js.src = '';
				videoUp2Js = null;
			}

			if(video){
				video.removeEventListener("ended", mediaEndHandler);
				video.removeEventListener("loadedmetadata", handleVideoLoadedMeta);
				video.removeEventListener("canplay", handleVideoCanplay);
				video.removeEventListener("canplaythrough", videoCanPlayThroughHandler);
				video.removeEventListener("waiting", videoWaitingHandler);
				video.removeEventListener("playing", videoPlayingHandler);
				video.removeEventListener("play", videoPlayHandler);
				video.removeEventListener("pause", videoPauseHandler);
				video.removeEventListener("seeked", videoSeekedHandler);
				video.removeEventListener("ratechange", videoRateChangeHandler);
				video.removeEventListener("error", videoErrorHandler);
			}

		}

		if(bigPlay)bigPlay.style.display = 'none';
	
		mediaPlaying = false;
		mediaStarted = false;

	}

	function destroyMedia(){
		if(!componentInited || !mediaType) return;
		cleanMedia();
		_VPLPlaylistManager.reSetCounter();

		playerControls.forEach(el => {
			el.classList.remove('vpl-player-controls-visible');
		})

		if(componentSize == "fullscreen")document.body.style.cursor = 'none';
		interfaceHidden = true;

	}

	this.destroyPlaylist = function(){
		if(!componentInited || !mediaType) return;
		destroyMedia();

		playlistDataArr = [];
		playlistLength = 0;
		mediaCounter = 0;

	}

	this.destroyInstance = function(){
		cleanMedia();
		if(_VPLPlaylistManager){
			_VPLPlaylistManager = null;
		}
		if(hls){
			hls.off();
			hls.destroy();
			hls = null;
		}
		if(dash){
			dash.off();
			dashjs.MediaPlayer().reset();
			dash = null;
		}

	}
	

	function render_video_360(){
		if(!doRender)return;
	
        if(video.readyState === video.HAVE_ENOUGH_DATA){
            videoTexture.needsUpdate = true;
        }
        vrenderer.render(vscene, camera);

	    renderAnimationID = requestAnimationFrame(render_video_360);
	}

	function trackProgress(overwrite){
		if(typeof overwrite === 'undefined' && !mediaPlaying) return;

		var loadPercent, t, d;

		if(mediaType == 'audio'){

			if(audioUp2Js){

				t = audioUp2Js.currentTime;
				d = audioUp2Js.duration;

				if(typeof audioUp2Js.buffered !== 'undefined' && audioUp2Js.buffered.length != 0) {
					try{
						var bufferedEnd = audioUp2Js.buffered.end(audioUp2Js.buffered.length - 1);
					}catch(error){}
					if(!isNaN(bufferedEnd)){
						var loadPercent = bufferedEnd / d;
					}
				}
			}

		}else if(mediaType == 'video'){

			if(videoUp2Js){

				t = videoUp2Js.currentTime;
				d = videoUp2Js.duration;

				if (typeof videoUp2Js.buffered !== 'undefined' && videoUp2Js.buffered.length != 0) {
					try{
						var bufferedEnd = videoUp2Js.buffered.end(videoUp2Js.buffered.length - 1);
					}catch(error){}
					if(!isNaN(bufferedEnd)){
						loadPercent = bufferedEnd / Math.floor(d);
					}
				}
			}

		}else if(mediaType == 'youtube'){

			if(youtubePlayer){

				t = youtubePlayer.getCurrentTime();
				d = youtubePlayer.getDuration();	

				loadPercent = youtubePlayer.getVideoLoadedFraction();

			}

		}else if(mediaType == 'vimeo'){

			t = vimeoCurrentTime;
			d = vimeoDuration;
			loadPercent = vimeoProgress;

		}

		if(VPLUtils.isNumber(t) && VPLUtils.isNumber(d)){

			//var ct = parseInt(t,10);
			var ct = t;

			if(loadPercent>1)loadPercent = 1;

			if(ct != lastTime){

				if(currMediaData.end){
					if(t>=currMediaData.end){
						if(settings.mediaEndAction != 'loop' && settings.mediaEndAction != 'rewind'){
							if(dataIntervalID) clearInterval(dataIntervalID);
							doRender = false;
							if(renderAnimationID) cancelAnimationFrame(renderAnimationID);
						}
						mediaEndHandler();
						return;
					}
				}
			
				//subtitles
				if(subtitleOn && subtitleArr.length){
					var i, len = subtitleArr.length, item, start, end;
					for(i = 0; i < len; i++){
						item = subtitleArr[i];
						start = item.start, end = item.end;
						if(ct >= start && ct <= end){
							if(!activeSubtitle){
								activeSubtitle = VPLUtils.htmlToElement('<div class="vpl-subtitle">'+item.text+'</div>');
								if(playerControlsMain){
									if(playerControlsMain.classList.contains('vpl-player-controls-visible')){
										subtitleHolderInner.classList.add('vpl-subtitle-raised');
									}else{
										subtitleHolderInner.classList.remove('vpl-subtitle-raised');
									}
								}

								activeSubtitle.start = start;
								activeSubtitle.end = end;
								subtitleHolderInner.append(activeSubtitle);
							}
						}else{
							if(activeSubtitle){
								if(ct < activeSubtitle.start || ct > activeSubtitle.end){
									activeSubtitle.remove();
									activeSubtitle = null;
								}
							}
						}
					}
				}

				//time
				if(mediaTimeCurrent)mediaTimeCurrent.textContent = (VPLUtils.formatTime(ct));
				if(mediaTimeTotal)mediaTimeTotal.textContent = (VPLUtils.formatTime(d));
				
			}

			if(loadLevel)loadLevel.style.width = (loadPercent * seekbarSize) + 'px';
			if(progressLevel)progressLevel.style.width = ((t / d) * seekbarSize) + 'px';

			if(progressBg)seekbarSize = progressBg.offsetWidth;

			if(settings.caption_breakPointArr && !subtitleSizeSet && activeSubtitle){
				//resize subtitle
				var w = subtitleHolderInner.offsetWidth, i, len = settings.caption_breakPointArr.length, point, size;
				for(i=0;i<len;i++){
					point = settings.caption_breakPointArr[i];
					if(w > point.width){
						size = point.size;
					}
				}
				if(!size && settings.caption_breakPointArr[0]){
					//if no width defined for zero size take first available
					size = settings.caption_breakPointArr[0].size
				}
				subtitleHolderInner.style.fontSize = size+'px';
				subtitleSizeSet = true;
			}

			lastTime = ct;

		}
	}
	
	function playHandler(){

		if(playerLoader)playerLoader.style.display = 'none';
		if(bigPlay)bigPlay.style.display = 'none';

		if(playbackToggle){
			playbackToggle.querySelector('.vpl-btn-play').style.display = 'none';
			playbackToggle.querySelector('.vpl-btn-pause').style.display = 'block';
		}

		if(settings.showPosterOnPause && mediaType != 'audio'){

			if(poster){


	            poster.addEventListener('transitionend', function cb(e){
					e.currentTarget.removeEventListener(e.type, cb);
					
					if(!mediaPlaying)return;
	                posterHolder.style.display = 'none';
				})
				poster.classList.remove('vpl-visible');
			}
		}

		if(!mediaStarted){
			mediaStarted = true;	

			if(settings.autoPlayAfterFirst){
				autoPlay = true;
			}

			if(!unmuteToggleInited && mediaType != 'image'){
				unmuteToggleInited = true;
				if(unmuteToggle && volume == 0){
					setTimeout(function(){
						if(unmuteToggle){
							unmuteToggle.classList.add('vpl-unmute-toggle-visible')

							unmuteToggle.addEventListener("click", function() {
							    toggleMute();
							}, {once : true});

						}
					},1000)
				}
			}

			if(settings.minimizeOnScroll && settings.minimizeOnScrollOnlyIfPlaying)checkPlayerMinimize();

			if(mediaType == 'video'){
				if(supportFor360Video && currMediaData.is360){

					if(vrInfo){
						setTimeout(function(){
							vrInfo.style.display = 'block';
							setTimeout(function(){
								vrInfo.style.opacity = 1;
							},20)
							vrInfoVisible = true;
						},1000)
					}

					videoHolder.style.display = 'none';
					vcanvas.style.display = 'block';
					vrInfoVisible = true;

					orbitControls.addEventListener('change', controlsChange);

					var w = mediaHolder.offsetWidth, h = mediaHolder.offsetHeight;
				    vrenderer.setSize(w, h);
				    camera.aspect = w / h;
				    camera.updateProjectionMatrix();
				    vcanvas.classList.add('vpl-visible');

					doRender = true;

					if(renderAnimationID) cancelAnimationFrame(renderAnimationID);
					renderAnimationID = requestAnimationFrame(render_video_360);
				}
			}

			if(mediaType == 'youtube' && settings.youtubePlayerType == 'default' || mediaType == 'vimeo' && settings.vimeoPlayerType == 'default'){

				playerControls.forEach(el => {
					el.style.display = 'none'
					el.classList.remove('vpl-player-controls-visible');
				})

				document.body.style.cursor = 'default';
				interfaceHidden = true;
			}else{

				playerControls.forEach(el => {
					el.style.display = 'block';
					el.classList.add('vpl-player-controls-visible');
				})
			}

			self.fireEvent("mediaStart", [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

		}

		if(settings.togglePlaybackOnMultipleInstances){
			if(ap_mediaArr.length > 1){
				var i, len = ap_mediaArr.length;
				for(i=0;i<len;i++){
					if(self != ap_mediaArr[i].inst){
						ap_mediaArr[i].inst.pauseMedia();
					}
				}
			}
		}

		if(mediaType == 'audio' || mediaType == 'video' || mediaType == 'youtube' && settings.youtubePlayerType == 'chromeless' || mediaType == 'vimeo' && settings.vimeoPlayerType == 'chromeless'){
			if(dataIntervalID) clearInterval(dataIntervalID);
			dataIntervalID = setInterval(trackProgress, dataInterval);
		}

		mediaPlaying = true;
		self.fireEvent('mediaPlay', [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

		if(playerControlsMain){
			if(idleTimeoutID)clearTimeout(idleTimeoutID);
			resetIdleTimer();
		}
		
	}

	function pauseHandler(){

		if(dataIntervalID) clearInterval(dataIntervalID);

		if(playerLoader)playerLoader.style.display = 'none';	

		if(mediaType == 'audio' || mediaType == 'video' || mediaType == 'youtube' && settings.youtubePlayerType == 'chromeless' || mediaType == 'vimeo' && settings.vimeoPlayerType == 'chromeless')if(bigPlay)bigPlay.style.display = 'block';

		if(playbackToggle){
			playbackToggle.querySelector('.vpl-btn-play').style.display = 'block';
			playbackToggle.querySelector('.vpl-btn-pause').style.display = 'none';
		}

		mediaPlaying = false;

		if(settings.showPosterOnPause && mediaType != 'audio'){

			if(poster){
				posterHolder.style.display = 'block';
				setTimeout(function(){
					poster.classList.add('vpl-visible');
				},20);
			}else if(posterExist){
				setPosterForPause()
			}
		}

		self.fireEvent("mediaPause", [{instance:self, instanceName:settings.instanceName, media:currMediaData}]);

	}

	//############################################//
	/* window resize */
	//############################################//

	window.addEventListener('resize', function(){
		if(!componentInited) return false;
		if(windowResizeTimeoutID) clearTimeout(windowResizeTimeoutID);
		windowResizeTimeoutID = setTimeout(doneResizing, windowResizeTimeout);
	});


	function doneResizing(){

		if(!startResizeDone){//if parent hidden on start
			startResizeDone = true;
			if(wrapper.offsetWidth == 0){
				setTimeout(function(){
					if(wrapper.offsetWidth == 0)console.log("Player width appears to be 0. Are you using this in a tab maybe where the parent container is hidden with css display none? If so, you need to initialize the player after its being shown in tab or call player.resize() API method so the player can correctly resize itself!")
					doneResizing();
				},500);
				return;
			}
		}

		var vs = VPLUtils.getViewportSize(isMobile);

		


		var w = wrapper.offsetWidth, h = w / Number(settings.playerRatio);
		if(h > vs.h)h = vs.h;

		wrapper.style.height = h + 'px';




		if(progressBg)seekbarSize = progressBg.offsetWidth;


		if(mediaType){

			if(poster){//because poster can be visible with video if media end handler = poster
				VPLAspectRatio.resizeMedia('image', settings.aspectRatio, playerHolder, poster);
			}

			if(mediaType == 'video'){

				if(supportFor360Video && currMediaData.is360){

					if(doRender){

						var w = mediaHolder.offsetWidth, h = mediaHolder.offsetHeight;
					    vrenderer.setSize(w, h);
					    camera.aspect = w / h;
					    camera.updateProjectionMatrix();

					}

				}else{

					if(video){
						VPLAspectRatio.resizeMedia('video', settings.aspectRatio, videoHolder, video);
					}

				}

			}else if(mediaType == 'image'){

				if(currMediaData.is360){

					if(image_360_ready){

						var w = mediaHolder.offsetWidth, h = mediaHolder.offsetHeight;
					    irenderer.setSize(w, h);
					    camera.aspect = w / h;
					    camera.updateProjectionMatrix();

					    irenderer.render(iscene, camera)

					}

				}else{

					if(image){

						VPLAspectRatio.resizeMedia('image', settings.aspectRatio, playerHolder, image);

					}
				}	

			}else if(mediaType == 'youtube'){

				if(youtubeIframe){
					if(settings.aspectRatio == 2 && currMediaData.width && currMediaData.height){
						youtubeIframe.sw = currMediaData.width;
						youtubeIframe.sh = currMediaData.height;
						VPLAspectRatio.resizeMedia('iframe', settings.aspectRatio, youtubeHolder, youtubeIframe);
					}
				}

			}else if(mediaType == 'vimeo'){

				if(activeVimeoIframe){
					if(settings.aspectRatio == 2 && currMediaData.width && currMediaData.height){
						activeVimeoIframe.sw = currMediaData.width;
						activeVimeoIframe.sh = currMediaData.height;
						VPLAspectRatio.resizeMedia('iframe', settings.aspectRatio, vimeoHolder, activeVimeoIframe);
					}
				}

			}

		}



		//hide controls on narrow screens
		if(settings.elementsVisibilityArr){
			var i, len = settings.elementsVisibilityArr.length, ev, w = mediaHolder.offsetWidth;
			for(i=0;i<len;i++){
				ev = settings.elementsVisibilityArr[i];

				if(w<ev.width){

					if(ev.elements.indexOf('seekbar') == -1){
						if(seekbar)seekbar.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('play') == -1){
						if(playbackToggle)playbackToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('time') == -1){
						if(mediaTimeCurrent)mediaTimeCurrent.classList.add('vpl-force-hide');
						if(mediaTimeTotal)mediaTimeTotal.classList.add('vpl-force-hide');
						if(mediaTimeSeparator)mediaTimeSeparator.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('download') == -1){
						if(downloadToggle)downloadToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('pip') == -1){
						if(pipToggle)pipToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('cc') == -1){
						if(captionToggle)captionToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('share') == -1){
						if(shareToggle)shareToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('info') == -1){
						if(infoToggle)infoToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('next') == -1){
						if(nextToggle)nextToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('previous') == -1){
						if(prevToggle)prevToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('rewind') == -1){
						if(rewindToggle)rewindToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('skip_backward') == -1){
						if(skipBackwardToggle)skipBackwardToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('skip_forward') == -1){
						if(skipForwardToggle)skipForwardToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('volume') == -1){
						if(volumeWrapper)volumeWrapper.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('fullscreen') == -1){
						if(fullscreenToggle)fullscreenToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('theater') == -1){
						if(settingsWrap)theaterToggle.classList.add('vpl-force-hide');
					}
					if(ev.elements.indexOf('settings') == -1){
						if(settingsWrap)settingsWrap.classList.add('vpl-force-hide');
					}

				}else{

					if(ev.elements.indexOf('seekbar') == -1){
						if(seekbar)seekbar.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('play') == -1){
						if(playbackToggle)playbackToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('time') == -1){
						if(mediaTimeCurrent)mediaTimeCurrent.classList.remove('vpl-force-hide');
						if(mediaTimeTotal)mediaTimeTotal.classList.remove('vpl-force-hide');
						if(mediaTimeSeparator)mediaTimeSeparator.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('download') == -1){
						if(downloadToggle)downloadToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('pip') == -1){
						if(pipToggle)pipToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('cc') == -1){
						if(captionToggle)captionToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('share') == -1){
						if(shareToggle)shareToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('info') == -1){
						if(infoToggle)infoToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('next') == -1){
						if(nextToggle)nextToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('previous') == -1){
						if(prevToggle)prevToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('rewind') == -1){
						if(rewindToggle)rewindToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('skip_backward') == -1){
						if(skipBackwardToggle)skipBackwardToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('skip_forward') == -1){
						if(skipForwardToggle)skipForwardToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('volume') == -1){
						if(volumeWrapper)volumeWrapper.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('fullscreen') == -1){
						if(fullscreenToggle)fullscreenToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('theater') == -1){
						if(theaterToggle)theaterToggle.classList.remove('vpl-force-hide');
					}
					if(ev.elements.indexOf('settings') == -1){
						if(settingsWrap)settingsWrap.classList.remove('vpl-force-hide');
					}

				}
			}
		}

		//resize subtitle
		if(!settings.keepCaptionFontSizeAfterManualResize){
			if(settings.caption_breakPointArr && activeSubtitle){
				var w = subtitleHolderInner.offsetWidth, i, len = settings.caption_breakPointArr.length, point, size;
				for(i=0;i<len;i++){
					point = settings.caption_breakPointArr[i];
					if(w > point.width){
						size = point.size;
					}
				}
				if(!size && settings.caption_breakPointArr[0]){
					//if no width defined for zero size take first available
					size = settings.caption_breakPointArr[0].size
				}
				subtitleHolderInner.style.fontSize = size+'px';
			}
		}

		//settings menu

		if(settingsHolder){
			if(settingsHolder.offsetHeight > playerHolder.offsetHeight-60){//doesnt fit in player
				settingsHolder.style.maxHeight = playerHolder.offsetHeight-60 + 'px'
				settingsHolder.classList.add('vpl-settings-holder-scrollable');
			}
		}
	}

	function resizeYtIframe(){
		if(settings.aspectRatio == 2 && currMediaData.width && currMediaData.height){
			youtubeIframe.sw = currMediaData.width;
			youtubeIframe.sh = currMediaData.height;
			VPLAspectRatio.resizeMedia('iframe', settings.aspectRatio, youtubeHolder, youtubeIframe);
		}else{
			youtubeIframe.style.width = '100%';
			youtubeIframe.style.height = '100%';
		}
	}

			
	//############################################//
	/* api */
	//############################################//

	this.playMedia = function() {
		if(!componentInited) return false;
		if(mediaPlaying) return false;

		if(mediaType == 'audio'){
			if(audioUp2Js){

				var promise = audioUp2Js.play();
				if (promise !== undefined) {
					promise.then(function(){
				    	
				    }).catch(function(error){
				    	if(bigPlay)bigPlay.style.display = 'block';
				    	if(playerLoader)playerLoader.style.display = 'none';
				    });
				}

			}
			else if(!mediaStarted){
				//autoplay viewport with no poster
				if(bigPlay)bigPlay.style.display = 'block';
				setMedia();
			}


		}else if(mediaType == 'video'){

			if(poster && !mediaStarted){//NOTE: we might want to use better check here (add class to posterHolder) because playMedia might get called multiple times before mediaStarted=true in playHandler and this block gets triggered again

				if(settings.showPosterOnPause){
					posterHolder.style.display = 'none';
				}else{
					posterHolder.style.display = 'none';
					posterHolder.innerHTML = ''
					poster = null;
				}
				if(bigPlay)bigPlay.style.display = 'block';
				setMedia();

			}else if(videoUp2Js){

				var promise = videoUp2Js.play();
				if (promise !== undefined) {
					promise.then(function(){
						    	
				    }).catch(function(error){
				    	if(bigPlay)bigPlay.style.display = 'block';
				    	if(playerLoader)playerLoader.style.display = 'none';
				    });
				}

			}
			else if(!mediaStarted){
				//autoplay viewport with no poster
				if(bigPlay)bigPlay.style.display = 'block';
				setMedia();
			}

		}else if(mediaType == 'youtube'){

			if(poster && !mediaStarted){

				if(settings.showPosterOnPause){
					posterHolder.style.display = 'none';
				}else{
					posterHolder.style.display = 'none';
					posterHolder.innerHTML = ''
					poster = null;
				}
				setMedia();
			}
			else if(!mediaStarted){
				//autoplay viewport with no poster
				if(bigPlay)bigPlay.style.display = 'block';
				setMedia();
			}
			else{
				if(youtubePlayer)youtubePlayer.playVideo();
			}
		    
		}else if(mediaType == 'vimeo'){

		    if(poster && !mediaStarted){

				if(settings.showPosterOnPause){
					posterHolder.style.display = 'none';
				}else{
					posterHolder.style.display = 'none';
					posterHolder.innerHTML = ''
					poster = null;
				}
				setMedia();
			}
			else if(!mediaStarted){
				//autoplay viewport with no poster
				if(bigPlay)bigPlay.style.display = 'block';
				setMedia();
			}
			else{
				if(activeVimeoPlayer)activeVimeoPlayer.play();
			}
		}

		mediaPlaying=true;
	}
	this.pauseMedia = function() {	
		if(!componentInited) return false;
		if(!mediaPlaying) return false;
		
		if(mediaType == 'audio'){
			if(audioUp2Js)audioUp2Js.pause();
		}else if(mediaType == 'video'){
			if(videoUp2Js)videoUp2Js.pause();
		}else if(mediaType == 'youtube'){
		    youtubePlayer.pauseVideo();
		}else if(mediaType == 'vimeo'){
		    activeVimeoPlayer.pause();
		}

		mediaPlaying=false;
	}	
	this.togglePlayback = function(){
		if(!componentInited) return false;
		if(!currMediaData) return false;
		if(settings.displayPosterOnMobile && currMediaData.poster)return;

		window.removeEventListener('scroll', handleAutoplayScroll);//kill autoplay in viewport

		if(mediaType == 'audio'){
			
			if(poster && !mediaStarted){

				if(bigPlay)bigPlay.style.display = 'none';
				if(playerLoader)playerLoader.style.display = 'block';
				setMedia();

			}else{

				if(audioUp2Js){
				    if(audioUp2Js.paused) {

					    var promise = audioUp2Js.play();
						if (promise !== undefined) {
						    promise.then(function(){

						    }).catch(function(error){
						    	if(bigPlay)bigPlay.style.display = 'block';
				    			if(playerLoader)playerLoader.style.display = 'none';
						    });
						}

				    }else{
					    audioUp2Js.pause();
				    }
				}

			}

		}else if(mediaType == 'video'){
			
			if(poster && !mediaStarted){

				if(settings.showPosterOnPause){
					posterHolder.style.display = 'none';
				}else{
					posterHolder.style.display = 'none';
					posterHolder.innerHTML = ''
					poster = null;
				}
				if(bigPlay)bigPlay.style.display = 'none';
				if(playerLoader)playerLoader.style.display = 'block';
				setMedia();

			}else{
				if(videoUp2Js){
				    if(videoUp2Js.paused) {

					    var promise = videoUp2Js.play();
						if (promise !== undefined) {
							promise.then(function(){
						    	
						    }).catch(function(error){
						    	if(bigPlay)bigPlay.style.display = 'block';
				    			if(playerLoader)playerLoader.style.display = 'none';
						    });
						}

				    }else{
					    videoUp2Js.pause();
				    }
				}
			}

		}else if(mediaType == 'youtube'){

			if(poster && !mediaStarted){

				if(posterHolder)posterHolder.style.display = 'none';
				if(bigPlay)bigPlay.style.display = 'none';
				setMedia();

			}else{

				if(youtubePlayer){

				    var player_state = youtubePlayer.getPlayerState();
					if(player_state == 1){//playing
						youtubePlayer.pauseVideo();
					}else if(player_state == 2){//paused
						youtubePlayer.playVideo();
					}else if(player_state == -1 || player_state == 5 || player_state == 0){//unstarted, cued, ended
						youtubePlayer.playVideo();
					}

				}

			}


		}else if(mediaType == 'vimeo'){

			if(poster && !mediaStarted){

				if(posterHolder)posterHolder.style.display = 'none';
				if(bigPlay)bigPlay.style.display = 'none';
				setMedia();

			}else{

				if(activeVimeoPlayer){
				    activeVimeoPlayer.getPaused().then(function(paused) {
					    if(paused){
					    	activeVimeoPlayer.play();
					    }else{
					    	activeVimeoPlayer.pause();
					    }
					});
				}

			}
		}

	}
	this.nextMedia = function(){
		if(!componentInited) return false;
		if(playlistLength == 0)return false;
		if(_VPLPlaylistManager)_VPLPlaylistManager.advanceHandler(1, true);
	}
	this.previousMedia = function(){
		if(!componentInited) return false;
		if(playlistLength == 0)return false;
		if(_VPLPlaylistManager)_VPLPlaylistManager.advanceHandler(-1, true);
	}
	this.setVolume = function(v){
		if(!componentInited) return false;
		if(v<0) v=0;
		else if(v>1) v=1;
		setVolume(v);
	}
	this.toggleMute = function(){
		if(!componentInited) return false;
		toggleMute();
	}
	this.seek = function(v){
		if(!componentInited) return false;

		if(mediaType == 'audio'){
			if(audioUp2Js)audioUp2Js.currentTime = v;
		}else if(mediaType == 'video'){
			if(videoUp2Js)videoUp2Js.currentTime = v;
		}else if(mediaType == 'youtube'){
			if(youtubePlayer)youtubePlayer.seekTo(v);
		}else if(mediaType == 'vimeo'){
			if(activeVimeoPlayer)activeVimeoPlayer.setCurrentTime(v);
		}
	}
	this.seekBackward = function(val) {
		if(!componentInited) return false;
		if(!mediaType) return false;

		if(mediaType == 'audio'){
			if(audioUp2Js){
		    	try{
					audioUp2Js.currentTime = Math.max(audioUp2Js.currentTime - (val || Number(settings.seekTime)), 0);
				}catch(er){console.log(er)}
		    }
		}else if(mediaType == 'video'){
			if(videoUp2Js){
		    	try{
					videoUp2Js.currentTime = Math.max(videoUp2Js.currentTime - (val || Number(settings.seekTime)), 0);
				}catch(er){console.log(er)}
		    }
		}else if(mediaType == 'youtube'){
			var pos = Math.max(youtubePlayer.getCurrentTime() - (val || Number(settings.seekTime)), 0);
		    youtubePlayer.seekTo(pos);
		}else if(mediaType == 'vimeo'){
			activeVimeoPlayer.getCurrentTime().then(function(seconds) {
			    var pos = Math.max(seconds - (val || Number(settings.seekTime)), 0);
		    	activeVimeoPlayer.setCurrentTime(pos);
			});
		}
	}
	this.seekForward = function(val) {
		if(!componentInited) return false;
		if(!mediaType) return false;

		if(mediaType == 'audio'){
			if(audioUp2Js){
		    	try{
					audioUp2Js.currentTime = Math.min(audioUp2Js.currentTime + (val || Number(settings.seekTime)), audioUp2Js.duration);
				}catch(er){console.log(er)}
		    }
		}else if(mediaType == 'video'){
			if(videoUp2Js){
		    	try{
					videoUp2Js.currentTime = Math.min(videoUp2Js.currentTime + (val || Number(settings.seekTime)), videoUp2Js.duration);
				}catch(er){console.log(er)}
		    }
		}else if(mediaType == 'youtube'){
			var pos = Math.min(youtubePlayer.getCurrentTime() + (val || Number(settings.seekTime)), youtubePlayer.getDuration());
		    youtubePlayer.seekTo(pos);	
		}else if(mediaType == 'vimeo'){
			if(vimeoDuration != 0 && vimeoCurrentTime != 0){
				var pos = Math.min(vimeoCurrentTime + (val || Number(settings.seekTime)), vimeoDuration);
		    	activeVimeoPlayer.setCurrentTime(pos);
			}else{
				activeVimeoPlayer.getCurrentTime().then(function(seconds) {
					activeVimeoPlayer.getDuration().then(function(duration) {
					    var pos = Math.min(seconds + (val || Number(settings.seekTime)), duration);
			    		activeVimeoPlayer.setCurrentTime(pos);
					})
				});
			}
		}
	}
	this.getCurrentMediaUrl = function(){
		if(!componentInited) return false;
		if(!mediaType) return '';

		var current_url = window.location.href, separator;//check if url contains other parameters 

		//clear existing parameters from url
		if(current_url.indexOf('vpl-') > -1){
			var params = current_url.substring(current_url.indexOf('?'));
            params = params.split('&');
            var i, len = params.length;
            for(i = len - 1; i > -1; i--){
                if(params[i].indexOf('vpl-') > -1){
                    params.splice(i, 1);
                }
            }

            var base_url = current_url.substring(0, current_url.indexOf('?')+1);//get with ?
            if(params.length){//append rest params which are not ours
                var i, len = params.length, p;
                for(i = 0; i < len; i++){
                    p = params[i];
                    if(p.charAt(0) == '?'){
                        base_url += p.substring(1);//remove ?
                    }else{
                        if(i > 0){
                            base_url += '&'+p;
                        }else{
                            base_url += p;//after ? 
                        }
                    }
                }
            }

            current_url = base_url;
		}

		if(current_url.indexOf('?') == -1){
			separator = '?'
		}else{
			if(current_url.charAt(current_url.length-1) == '?' || current_url.charAt(current_url.length-1) == '&')separator = '';	
			else separator = '&';	
		} 


		//share url to specific media
		if(currMediaData.mediaId != undefined)var activeItem = 'vpl-media-id='+currMediaData.mediaId;
		else var activeItem = 'vpl-active-item='+mediaCounter;
		var start = (mediaStarted && mediaType != 'image') ? '&vpl-playback-position-time='+Math.floor(self.getCurrentTime()) : '';

		return current_url + separator + activeItem + start;
		
	}
	this.getCurrentTime = function(){
		if(!componentInited) return false;
		var v = '0';
		if(mediaType == 'audio'){
			if(audioUp2Js)v = audioUp2Js.currentTime;
		}else if(mediaType == 'video'){
			if(videoUp2Js)v = videoUp2Js.currentTime;
		}else if(mediaType == 'youtube'){
			if(youtubePlayer)v = youtubePlayer.getCurrentTime();
		}else if(mediaType == 'vimeo'){
			return vimeoCurrentTime;
		}
		return v;
	}
	this.getDuration = function(){
		if(!componentInited) return false;
		var v;
		if(mediaType == 'audio'){
			if(audioUp2Js)v = audioUp2Js.duration;
		}else if(mediaType == 'video'){
			if(videoUp2Js)v = videoUp2Js.duration;
		}else if(mediaType == 'youtube'){
			if(youtubePlayer)v = youtubePlayer.getDuration();
		}else if(mediaType == 'vimeo'){
			return vimeoDuration;
		}
		return v;
	}
	this.getLoadProgress = function(){
		if(!componentInited) return false;
		if(!mediaType) return false;

		var v;
		if(mediaType == 'audio'){
			if (typeof audioUp2Js.buffered !== 'undefined' && audioUp2Js.buffered.length != 0) {
				try{
					var bufferedEnd = audioUp2Js.buffered.end(audioUp2Js.buffered.length - 1);
				}catch(error){}
				if(!isNaN(bufferedEnd)){
					v = bufferedEnd / Math.floor(audioUp2Js.duration);
				}
			}
		}else if(mediaType == 'video'){
			if (typeof videoUp2Js.buffered !== 'undefined' && videoUp2Js.buffered.length != 0) {
				try{
					var bufferedEnd = videoUp2Js.buffered.end(videoUp2Js.buffered.length - 1);
				}catch(error){}
				if(!isNaN(bufferedEnd)){
					v = bufferedEnd / Math.floor(videoUp2Js.duration);
				}
			}
		}else if(mediaType == 'youtube'){
			v = youtubePlayer.getVideoLoadedFraction();
		}else if(mediaType == 'vimeo'){
			v = vimeoProgress;
		}
		return v;
	}
	this.setPlaybackRate = function(v){
		if(!componentInited) return false;

		currMediaData.playbackRate = v;//update playback rate so it doesnt reset on quality change

		if(mediaType == 'audio'){
			if(audioUp2Js)audioUp2Js.playbackRate = Number(v);
		}else if(mediaType == 'video'){
			if(videoUp2Js)videoUp2Js.playbackRate = Number(v);
		}else if(mediaType == 'youtube'){
			if(youtubePlayer)youtubePlayer.setPlaybackRate(Number(v));
		}else if(mediaType == 'vimeo'){
			if(activeVimeoPlayer)activeVimeoPlayer.setPlaybackRate(v).then(function(playbackRate) {
			    // playback rate was set
			}).catch(function(error) {
			    //console.log(error.name)
			});
		}
	}
	this.setRandom = function(v) {
		if(!componentInited) return false;
		if(typeof _VPLPlaylistManager !== 'undefined')_VPLPlaylistManager.setRandom(v);
	}
	this.setLooping = function(v) {
		if(!componentInited) return false;
		if(typeof _VPLPlaylistManager !== 'undefined')_VPLPlaylistManager.setLooping(v);
	}
	this.cleanMedia = function(){
		if(!componentInited) return false;
		cleanMedia();
	}
	this.loadMedia = function(format, value){
		if(!componentInited) return false;
		if(typeof _VPLPlaylistManager === 'undefined')return false;

		if(format == 'counter'){

			if(value < 0)value = 0;
			else if(value > playlistLength - 1)value = playlistLength - 1;
			_VPLPlaylistManager.processPlaylistRequest(value);

		}else if(format == 'id'){//mediaId

			var i;
			for(i = 0; i < playlistLength; i++){
				if(value == playlistDataArr[i].mediaId){
					mediaCounter = i;
					break;
				}
			}

			if(typeof i != undefined){
				_VPLPlaylistManager.setCounter(mediaCounter, false);
			}else{
				alert('No media with ID to load!');
				return false;
			}

		}else if(format == 'data'){

			playlistDataArr = value;

			playlistLength = playlistDataArr.length;
			_VPLPlaylistManager.setPlaylistItems(playlistLength);

			autoPlay = true;
			_VPLPlaylistManager.setCounter(insertPosition, false);

		}

	}
	this.addMedia = function(value, playit, insertPosition){
		if(!componentInited) return false;

		//position
		if(typeof insertPosition !== 'undefined'){
			if(insertPosition < 0){
				insertPosition = 0;
				playlistDataArr.unshift(value);
			}
			else if(insertPosition => playlistLength || insertPosition == 'end'){
				insertPosition = playlistLength;
				playlistDataArr.push(value);//add media to end	
			}
		}else{
			insertPosition = playlistLength;
			playlistDataArr.push(value);
		}
			
		playlistLength = playlistDataArr.length;
		_VPLPlaylistManager.setPlaylistItems(playlistLength);

		//play it
		if(playit){
			autoPlay = true;
			_VPLPlaylistManager.setCounter(insertPosition, false);
		}
	
	}

	this.loadPlaylist = function(value){
		if(!componentInited) return false;
		
		self.destroyPlaylist()

		settings.media = value;
		playlistDataArr = settings.media;
		playlistLength = playlistDataArr.length;
		_VPLPlaylistManager.setPlaylistItems(playlistLength);

		if(playlistDataArr.length){
			if(typeof settings.activeItem != 'undefined')mediaCounter = settings.activeItem;
			if(mediaCounter != -1){
				if(mediaCounter > playlistLength - 1) mediaCounter = playlistLength - 1;
				_VPLPlaylistManager.setCounter(mediaCounter, false);
			}
		}
	
	}

	this.getMediaPlaying = function(){
		if(!componentInited) return false;
		return mediaPlaying;
	}
	this.setPlaybackQuality = function(v){
		if(!componentInited) return false;
		if(!mediaType) return false;

		if(mediaType == 'audio' || mediaType == 'video'){

			if(subMediaType == 'hls'){
				var q = parseInt(v,10);
				//https://github.com/video-dev/hls.js/issues/478
				if(!currMediaData.quality){
					hls.currentLevel = q;	
					hls.nextLevel = q;	
					hls.loadLevel = q;	
				}else{
					hls.currentLevel = q;	
				}
			}
			else if(subMediaType == 'dash'){
				var q = parseInt(v,10);
				dash.setQualityFor('video', q);
			}
			else{
				currMediaData.quality = v;//update quality 
				setQualityActiveMenuItem(v);
				qualityChange = true;
				cleanMediaQuality();
				getQuality();
				setMedia();
			}

		}else if(mediaType == 'youtube') {
			youtubePlayer.setPlaybackQuality(v);
		}
	}
	this.toggleInfo = function(){
		if(!componentInited) return false;
		if(!mediaType) return false;
		toggleInfo();
	}
	this.toggleShare = function(){
		if(!componentInited) return false;
		if(!mediaType) return false;
		toggleShare();
	}
	this.toggleFullscreen = function(){
		if(!componentInited) return false;
		toggleFullscreen();
	}
	this.destroyMedia = function(){
		if(!componentInited) return false;
		destroyMedia();
	}
	this.resize = function(){
		if(!componentInited) return false;
		doneResizing();
	}

	this.resume = function(){
		if(!componentInited) return false;
		if(mediaCounter != -1){
			_VPLPlaylistManager.setCounter(mediaCounter, false);
		}
	}

	if(settings.autoResumeAfterAdd != undefined)return;


	//############################################//
	/* viewport autoplay */
	//############################################//

	//continuosly monitor viewport
	function checkViewport(){

		if(VPLUtils.isScrolledIntoView(wrapper)){
			//console.log('in view',  settings.instanceName)
			autoPlayInViewportDone = true;
			autoPlay = true;
			settings.autoPlay = true;

			self.playMedia();
		}else{
			window.addEventListener('scroll', handleAutoplayScroll);
		}

	}

	function handleAutoplayScroll(){
        if(isScrollTimeout)return;
        isScrollTimeout = true;
        
        if(VPLUtils.isScrolledIntoView(wrapper)){

    		autoPlayInViewportDone = true;
    		autoPlay = true;
    		settings.autoPlay = true;
    		self.playMedia();
    	}else{
    		//console.log('out', instanceName: settings.instanceName)
    		self.pauseMedia();
    	}

        setTimeout(function() {
            isScrollTimeout = false;
        }, 250);
    }


	//############################################//
	/* minimize on scroll */
	//############################################//

	function minimizeScrollHander() {
        if(isScrollTimeout)return;
        isScrollTimeout = true;

        if(settings.minimizeOnScrollOnlyIfPlaying){
        	if(mediaPlaying)checkPlayerMinimize();
        }else{
        	checkPlayerMinimize();
        }
        setTimeout(function() {
            isScrollTimeout = false;
        }, 100);
    }

	function checkPlayerMinimize() {

		var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if(scrollTop > playerOffsetTop){
       		if(!wrapper.classList.contains(settings.minimizeClass)){
       			wrapper.classList.add(settings.minimizeClass);
       		}
        }else{
        	if(wrapper.classList.contains(settings.minimizeClass)){
	        	wrapper.classList.remove(settings.minimizeClass);
	        }
        }
        doneResizing();
    }

	//############################################//
	/* init */
	//############################################//

	componentInited = true;


	if(settings.minimizeOnScroll){

		playerOffsetTop = VPLUtils.getElementOffsetTop(wrapper);

		window.addEventListener('scroll', minimizeScrollHander);
        
    }


    setTimeout(function(){
    	self.fireEvent('setupDone', [{instance:self, instanceName:settings.instanceName}]);
    },50);
	

    if(playerControlsMain){
		['mousemove', 'mousedown', 'keypress', 'DOMMouseScroll', 'mousewheel', 'touchmove', 'MSPointerMove'].forEach(function(event) { 
			playerHolder.addEventListener(event, resetIdleTimer);
		});
	}
	

	doneResizing();

	wrapper.style.opacity = 1;

	if(playbackToggle)playbackToggle.querySelector('.vpl-btn-play').style.display = 'block';


	//init

	if(queryPlaylist.length){
		//proces playlist from query params
		settings.media = queryPlaylist;
	}

	if(settings.media){
		playlistDataArr = settings.media;
		playlistLength = playlistDataArr.length;
		_VPLPlaylistManager.setPlaylistItems(playlistLength);
	}




	//remember playback position
	if(playbackPositionKey && hasLocalStorage){
		if(!rememberPlaybackPosition){
			localStorage.removeItem(playbackPositionKey);
		}else{
			if(hasLocalStorage){
				if(localStorage.getItem(playbackPositionKey)){
					var d = JSON.parse(localStorage.getItem(playbackPositionKey));

					settings.playbackPositionTime = d.resumeTime;
					volume = d.volume;
					settings.activeItem = d.activeItem;

					localStorage.removeItem(playbackPositionKey);
				}
			}
		}
	}



console.log(settings)

	if(playlistDataArr.length){
		if(typeof settings.activeItem != 'undefined')mediaCounter = settings.activeItem;
		if(mediaCounter != -1){
			if(mediaCounter > playlistLength - 1) mediaCounter = playlistLength - 1;
			_VPLPlaylistManager.setCounter(mediaCounter, false);
		}
	}
	

	
	return this;

	}

	window.vpl = vpl;
	
})();

