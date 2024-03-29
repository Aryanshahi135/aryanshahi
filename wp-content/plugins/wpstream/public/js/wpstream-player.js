/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * player,'.$live_event_uri_final.','.$live_conect_views.'
 */

window.WebSocket = window.WebSocket || window.MozWebSocket;
if (!window.WebSocket) {
  console.log("Sorry, but your browser does not support WebSockets");
}

function wpstream_player_initialize(id,hlsUri,statsUri,autoplay){
    
    var is_autoplay=true;
    if(autoplay !== 'autoplay'){
        is_autoplay=false;
    }
        
  
    let player = new WpstreamPlayer(id, hlsUri, statsUri, is_autoplay);
}

class WpstreamPlayer {
    // id;
    // hlsUri;
    // statsUri;
    // autoplay;
    // ruler = 0; //0 - basic; 1 - ajax; 2 - ws
    // state = -1; //-1 - unknown; 0 - idle; 1 - starting; 2 - started; 5 - paused; 7 - live
    // liveConnect;
    // wrapper;
    // counter;
    // chat;

    constructor(id, hlsUri, statsUri, autoplay){
        console.log("[]WpstreamPlayer: ", id, hlsUri, statsUri, autoplay);
        this.id = id;
        this.hlsUri = hlsUri;
        this.statsUri = statsUri;
        this.autoplay = autoplay;
        this.liveConnect = new LiveConnect(this);
        this.wrapper = jQuery('#wpstream_live_player_wrapper' + id);
        console.log("wrapper: ", this.wrapper)
        this.channelId = this.wrapper.attr('data-product-id');
        console.log("channelId: ", this.channelId)
        this.playback = new WpstreamPlayback(this, id, autoplay);
        this.counter = new LiveCounter(this.wrapper, id);
        this.liveMessage = new WpstreamLiveMessage(this.wrapper, id);
        this.chat = new WpstreamChat();
        this.setRuler(1);
    }

    setRuler(ruler){
        console.log("setRuler: " + ruler);
        let oldRuler = this.ruler;
        this.ruler = ruler;
        switch (ruler){
            case 1:
                if (oldRuler != 1){
                    this.getDynamicSettings();
                }
                clearTimeout(this.retrieveDynamicSettingsTimeout);
                let self = this;
                this.retrieveDynamicSettingsTimeout = setTimeout(() => self.getDynamicSettings(), 30 * 1000)
                break;
            case 2:
                clearTimeout(this.retrieveDynamicSettingsTimeout);
                break;
        }
    }

    getDynamicSettings(){
        console.log("getDynamicSettings()");
        let ajaxurl = wpstream_player_vars.admin_url + 'admin-ajax.php';
        let owner = this;
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action'                    :   'wpstream_player_check_status',
                'channel_id'                  :   this.channelId
            },
            success: function (data) {     
              
                console.log("dynamicSettings: ", data);
                if (data == 0){
                    owner.setState(0);
                }
                else if (data.started == "no"){
                    owner.setState(1);
                    owner.chat.disconnect();

                }
                else if (data.started == "yes"){
                    let liveConnectUri = data.live_conect_views;
                    owner.liveConnect.setup(liveConnectUri);
                    let hlsUri = data.event_uri;
                    owner.setSrc(hlsUri);
                    owner.setState(2);
                    owner.chat.connect(data.chat_url);
                }
            },
            error: function (error) { 
                console.log("dynamicSettingsError: ", error)  
            }
        });
        if (this.ruler <= 1){
            this.setRuler(1);
        }
    }

    setSrc(uri){
        this.playback.setSrc(uri);
    }

    setState(state){
        console.log("setState: ", state);
        this.state = state;
        switch(state){
            case 0:
            case 1:
                this.liveMessage.showOriginalMessage();
                break;
            case 2:
                this.liveMessage.hide();
                break;
            case 5:
                this.liveMessage.showPausedMessage();
                this.playback.pause();
                break;
            case 7:
                this.liveMessage.hide();
                this.playback.play();
                break;
        }
    }

    onLiveConnectActive(isActive){
        console.log("onLiveConnectActive: ", isActive);
        this.setRuler(isActive ? 2 : 1);
        if (!isActive){
            this.counter.hide();
        }
    }

    updateViewerCount(count){
        console.log("updateViewerCount: ", count)
        this.counter.show();
        this.counter.setCount(count);
    }
}

class WpstreamPlayback {
    // player;
    // timeQueue = [];
    // master;
    // paused = false;
    // played = false;


    constructor(master, id, autoplay){
        this.timeQueue = [];
        this.paused = false;
        this.played = false;

        this.master = master;
        this.setupBasePlayer(id, autoplay);
        this.runWatchdog();
    } 

    setupBasePlayer(id, autoplay){
        console.log("setupBasePlayer: ", id, autoplay);
        let hlsUri = this.master.hlsUri;
        console.log("hlsUri: ", hlsUri);
        let llhls = /ll[a-z]+\.m3u8/.test(hlsUri)
        console.log("llhls: ", llhls);
        this.player = videojs('wpstream-video' + id, {
            html5: {
                vhs: {
                    useBandwidthFromLocalStorage: true,
                    limitRenditionByPlayerDimensions: false,
                    useDevicePixelRatio: true,
                    overrideNative: !videojs.browser.IS_SAFARI,
                    cacheEncryptionKeys: true,
                    llhls
                }
            },
            errorDisplay: false,
            autoplay:autoplay,
            preload:"auto",
            // muted    : true
        });
        let owner = this;
        this.player.on('play', function(event) {
            console.log("Play");
            owner.played = true;
        });
        this.player.on('pause', function(event) {
            console.log("Pause");
        });
        console.log("src: ", this.player.currentSrc());
    }

    

    setSrc(src, force){
        console.log("setSrc: ", src, force);
        console.log("currentSrc: ", this.player.currentSrc());
        console.log("paused: ", this.player.paused());
        console.log("currentTime: ", this.player.currentTime());
        if (src != this.player.currentSrc() || force){
            console.log("setting src...")
            this.player.src({
                src:  src != null ? src : this.player.currentSrc(),
                type: "application/x-mpegURL"
            });
        }
        this.player.controlBar.show();
        this.player.loadingSpinner.show();
    }

    play(forced){
        console.log("play() ", forced);
        this.paused = false;
        console.log("player.paused: ", this.player.paused());
        console.log("currentTime: ", this.player.currentTime());

        if (this.player.paused() || forced || this.player.currentTime() === 0){
            this.player.src({
                src:  this.player.currentSrc(),
                type: "application/x-mpegURL"
            });
            console.log("autoplay: ", this.player.autoplay());
            // this.player.currentTime(0);
            console.log("played: ", this.played);
            if (this.played){
                var promise = this.player.play();
                // console.log("promise: ", promise);
                let player = this.player;
                if (promise !== undefined) {
                    promise.then(function() {
                      console.log("Autoplay started ;)");
                    }).catch(function(error) {
                      console.log("Autoplay did not work ", error);
                    });
                }
                console.log("no promise")
            }
        }
        this.player.controlBar.show();
        this.player.loadingSpinner.show();
    }

    pause(){
        console.log("pause()");
        this.paused = true;
        this.player.pause();
        console.log("paused: ", this.player.paused());
        console.log("currentTime: ", this.player.currentTime());
        this.player.controlBar.hide();
        this.player.loadingSpinner.hide();
    }

    runWatchdog(){
        //console.log("runWatchdog()");
        this.timeQueue.push(this.player.currentTime());
        if (this.timeQueue.length > 25){
            this.timeQueue.shift();
            if (this.timeQueue[0] === this.timeQueue[this.timeQueue.length -1]){
                console.log("queue: ", this.timeQueue[0], this.timeQueue[this.timeQueue.length -1]);
                console.log("paused: ", this.paused);
                console.log("ruler: ", this.master.ruler);
                console.log("state: ", this.master.state);
                console.log("player paused: ", this.player.paused());
                console.log("currentTime: ", this.player.currentTime());

                if (this.master.ruler == 2){
                    if (!this.player.paused()){
                        this.play(true);    
                    }
                }
                else if (this.master.state > 1) {
                    if (!this.player.paused() || this.player.currentTime() === 0){
                        this.play(true);
                    }
                }
                this.timeQueue = [];
            }
        }
        let self = this;
        setTimeout(() => self.runWatchdog(), 1 * 1000)
    }
}

class WpstreamChat {
    // connected = '';

    constructor(){
        this.connected = '';
    }

    connect(url){
        this.connected = 'yes';
        if(typeof(connect)==='function' ){               
            connect(url);
        }
    }

    disconnect(){
        if( typeof(showChat) === 'function' && this.connected === 'yes' ){
            showChat('info', null, wpstream_player_vars.chat_not_connected);
            this.connected='no';
        }
    }
}

class WpstreamLiveMessage {
    // element;
    // msg;
    // originalMessage;
    // customMessage; 
    // state = -1; // -1 - unknown; 0 - hidden; 1 - showing original msg; 3 - showing paused msg; 5 - showing custom msg

    constructor(wrapper, id){
        this.state = -1; 
        this.element = wrapper.find('.wpstream_not_live_mess');
        this.msg = wrapper.find('.wpstream_not_live_mess_mess');
        this.originalMessage = this.msg.text();
        console.log("originalMessage: ", this.originalMessage);
        var playerElement = jQuery('#wpstream-video' + id);
        this.element.appendTo(playerElement);
    }

    setCustomMessage(msg){
        this.customMessage = msg;
        if (this.state == 3 || this.state == 5){
            this.showPausedMessage();
        }
    }

    showPausedMessage(){
        if (this.customMessage != null){
            this.msg.text(this.customMessage);    
            this.state = 5;
        }
        else {
            this.msg.text(wpstream_player_vars.server_up);
            this.state = 3;
        }
        this.show();
    }

    showOriginalMessage(){
        this.msg.text(this.originalMessage)
        this.show();
        this.state = 1;
    }

    //public
    hide(){
        this.element.hide();
        this.state = 0;
    }

    //private
    show(){
        this.element.show();
    }

    
}

class LiveCounter {
    // element;
    constructor(wrapper, id){
        console.log("[]LiveCounter: ", wrapper, id);
        this.element = wrapper.find('.wpestream_live_counting');
        this.element.css("background-color","rgb(174 69 69 / 90%)");
        //var playerElement = wrapper.find('.wpstream-video' + id);
        var playerElement = jQuery('#wpstream-video' + id);
        console.log("playerElement: ", playerElement);
        this.element.appendTo(playerElement);
        this.hide();
    }
    
    show(){
        this.element.show();
    }
    
    hide(){
        this.element.hide();
    }
    setCount(count){
        this.element.html( count + " Viewers");
    }   
}

class LiveConnect {
    // master;
    // wsUri;
    // ws;
    // connectCount = 0;
    // connected = false;
    // pendingConnect = false;

    constructor(master){
        this.connectCount = 0;
        this.connected = false;
        this.pendingConnect = false;
        this.master = master; 
    }

    setup(wsUri){
        console.log("setup: ", wsUri);
        this.close();
        this.wsUri = wsUri;
        this.connect();
    }

    close(){
        if (this.ws != null){
            this.ws.close();
        }
        this.ws = null;
    }

    connect(){
        let connectAttempt = ++ this.connectCount;
        console.log("connect() ", connectAttempt);
        this.pendingConnect = true;
        this.ws = new WebSocket(this.wsUri);
        let owner = this;
        this.ws.onopen = function () {
            console.log("connected. ", connectAttempt);
            owner.pendingConnect = false;
            owner.master.onLiveConnectActive(true);
            //socket_connection.send(`{"type":"register","data":"${now}"}`);
        };
        this.ws.onclose = function(){
            console.log("onclose.. ", connectAttempt);
            owner.master.onLiveConnectActive(false);
        }
        this.ws.onerror = function (error) {
            console.log("onerror: ", connectAttempt, error);
            owner.master.onLiveConnectActive(false);   
        };
        this.ws.onmessage = function (message) {
            console.log("onmessage: ", connectAttempt, message.data); 
            owner.processMessage(message.data);
        }
    }

    processMessage(msg){
        console.log("processMessage: ", msg);
        var json;
        try {
            json = JSON.parse(msg);
        } catch (e) {
            console.log("Invalid JSON: ", msg);
            return;
        }
        if (json.type){
            switch(json.type){
                case "viewerCount":
                    this.master.updateViewerCount(json.data); 
                    break;
                case "onair":
                    this.master.setState(json.data ? 7 : 5);
                    break;
                case "status":
                    this.master.liveMessage.setCustomMessage(json.data);
                    break;
                default:
                    console.log("invalid type: ", json.type);
            }   
        }
    }
}

function wpstream_read_websocket_info(event_id,player, player_wrapper, socket_wss_live_conect_views_uri, event_uri){
    console.log("wpstream_read_websocket_info: ", event_id, player, player_wrapper, socket_wss_live_conect_views_uri, event_uri);
    console.log("sldpPlayer: ", sldpPlayer);
    if (sldpPlayer != null){
        var chat = new WpstreamChat();
        chat.connect(socket_wss_live_conect_views_uri);
    }
}

jQuery(document).ready(function ($) {
    console.log("ready!")
    var event_id;  
    var player_wrapper;
    jQuery('.wpstream_live_player_wrapper').each(function(){
        console.log("wrapper: ", this, $(this));
        if($(this).hasClass('wpstream_low_latency')){
            return;
        }
        event_id          =   jQuery(this).attr('data-product-id');
        player_wrapper    =   jQuery(this);

        //wpstream_check_player_status_ticker(player_wrapper,event_id);
    });
    
});



var sldpPlayer;

function initPlayer(playerID,low_latency_uri,muted,autoplay){
    console.log("initPlayer: ", low_latency_uri)
    var is_muted    =   false;
    var is_autoplay =   true;
    if(muted === 'muted'){
        is_muted=true;
    }
    
    if(autoplay !== 'autoplay'){
        is_autoplay=false;
    }
    
    console.log('is_muted '+is_muted + '/ '+is_autoplay);
    
    let player = OvenPlayer.create(playerID, {
        "autoStart": is_autoplay,
        "autoFallback": false,
        "mute": is_muted,
        "sources": [{
            "type": "webrtc",
            "file": low_latency_uri
        }],
        "hlsConfig": {
            "liveSyncDuration": 1.5,
            "liveMaxLatencyDuration": 3,
            "maxLiveSyncPlaybackRate": 1.5
        },
        "webrtcConfig": {
           "timeoutMaxRetry": 100,
           "connectionTimeout": 10000
        }
    });


};

function removePlayer(){
  sldpPlayer.destroy();
}
