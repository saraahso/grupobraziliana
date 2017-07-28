/************************************************
*   mooquee v.1.1                               *
*   Http: WwW.developer.ps/moo/mooquee          *
*   Dirar Abu Kteish dirar@zanstudio.com        *
*   2009-01-30                                  *
*************************************************
*   Extend By www.Sod.hu                        *
*   new directions: top, bottom                 *
*   2008-04-30                                  *
/***********************************************/
/* This program is free software. It comes without any warranty, to
* the extent permitted by applicable law. You can redistribute it
* and/or modify it under the terms of the Do What The Fuck You Want
* To Public License, Version 2, as published by Sam Hocevar. See
* http://sam.zoy.org/wtfpl/COPYING for more details. */ 

var mooquee = new Class({
    initialize: function(element, options) {
		this.setOptions({
			marHeight: 40,
			marWidth: 550,
			steps: 1,
			speed: 40,
			direction: 'bottom',
			pauseOnOver: true,
			pauseOnContainerOver: true
	    }, options);
	    this.timer = null;
	    this.textElement = null;
	    this.mooqueeElement = element;
	    this.constructMooquee();
	},
	constructMooquee: function() {
		var el = this.mooqueeElement;
		el.setStyles({
		    'width' : this.options.marWidth
		    ,'height' : this.options.marHeight		    
		});
        this.textElement = new Element('div',{
		    'class' : 'mooquee-text'
		    ,'id' : 'mooquee-text'
		}).set('html', el.innerHTML);
		el.set('html', '');//clear mooqueeElement inner html
		this.textElement.inject(el);
		//this.textElement = $('mooquee-text');
        if(!this.setStartPos()){return;}
        if(this.options.pauseOnOver){this.addMouseEvents();}
		//start marquee
		this.timer = this.startMooquee.delay(this.options.speed, this);
	},
	setStartPos: function(){
	    /* sod.hu Ext */
		if( this.options.direction == 'bottom' )
            this.textElement.setStyle('bottom', ( -1 * this.textElement.getCoordinates().height.toInt()));
        else if( this.options.direction == 'top' )
            this.textElement.setStyle( 'bottom', this.options.marHeight );
        else if( this.options.direction == 'left' )
            this.textElement.setStyle('left', 40);
        else if( this.options.direction == 'right' )
            this.textElement.setStyle( 'left', this.options.marWidth );
        else{
            alert( 'direction config error: ' + this.options.direction );
            return false;
        }
        return true;
	},
	addMouseEvents : function(){
	    if(!this.options.pauseOnContainerOver){
	        this.textElement.addEvents({
	            'mouseenter' : function(me){
	                this.clearTimer();
	            }.bind(this),
	            'mouseleave' : function(me){
	                this.timer = this.startMooquee.delay(this.options.speed, this);
	            }.bind(this)
	        });
	    }else{
	        this.mooqueeElement.addEvents({
	            'mouseenter' : function(me){
	                this.clearTimer();
	            }.bind(this),
	            'mouseleave' : function(me){
	                this.timer = this.startMooquee.delay(this.options.speed, this);
	            }.bind(this)
	        });
	    }
	},
    startMooquee: function(){
        /* sod.hu Ext */
        if(this.options.direction == 'bottom' || this.options.direction == 'top')
            var pos = this.textElement.getStyle('bottom').toInt();
        else if(this.options.direction == 'left' || this.options.direction == 'right')
            var pos = this.textElement.getStyle('left').toInt();
        if(this.options.direction == 'bottom')
            this.textElement.setStyle( 'bottom', ( pos + -1 ) + 'px' );
        else if(this.options.direction == 'top')
            this.textElement.setStyle( 'bottom', ( pos + 1 ) + 'px' );
        else if(this.options.direction == 'left'){
            this.textElement.setStyle( 'left', ( pos + -1 ) + 'px' );
        }
        else if(this.options.direction == 'right')
            this.textElement.setStyle( 'left', ( pos + 1 ) + 'px' );
        /* sod.hu Ext end */
        this.checkEnd(pos);
        this.timer = this.startMooquee.delay(this.options.speed, this);
    },
    resumeMooquee: function(){
        this.stopMooquee();
        if(this.options.pauseOnOver){this.addMouseEvents();}
        this.timer = this.startMooquee.delay(this.options.speed, this);
    },
    stopMooquee: function(){
        this.clearTimer();
        this.textElement.removeEvents();
    },
    clearTimer: function(){
        $clear(this.timer);
    },
    checkEnd: function(pos){
        /* sod.hu Ext */
        if(this.options.direction == 'bottom'){
            if(pos < -1 * (this.textElement.getCoordinates().height.toInt()))
                this.textElement.setStyle('bottom', this.options.marHeight);
        } else if(this.options.direction == 'top'){
            if(pos > this.options.marHeight.toInt())
                this.textElement.setStyle('bottom', -1 * (this.textElement.getCoordinates().height.toInt()) );
        } else if(this.options.direction == 'left'){
            if(pos < -1 * (this.textElement.getCoordinates().width.toInt()))
                this.textElement.setStyle('left', this.options.marWidth);
        } else if(this.options.direction == 'right'){
            if(pos > this.options.marWidth.toInt())
                this.textElement.setStyle('left', -1 * (this.textElement.getCoordinates().width.toInt()) );
        }
        /* sod.hu Ext end */
    },
    setDirection: function(dir){
        this.options.direction = dir;
        this.setStartPos();
    }
});
mooquee.implement(new Options);