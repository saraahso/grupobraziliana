// JavaScript Document
/*
	Marquee using mootools 
	Abdelkader Elkalidi contact[at]updel.com
	http://updel.com
*/
var marquee = new Class({
    initialize: function(options) {
		this.setOptions({
			marqueeIn	: 'marqueeIn',
			marqueeMe	: 'marqueeMe',
			speed		: 10,
			//direction	: 'left', Not yet
			hoverpause	: true
	    }, options);
	    this._construct();
	},
	_construct: function() {
		this.startFrom		=	$(this.options.marqueeMe).getStyle('width').toInt();
		this.restartLimit	=	$(this.options.marqueeIn).getStyle('width').toInt();
		this.elTomarquee	=	$(this.options.marqueeMe);
		//alert($(this.options.marqueeMe).getWidth()+" - "+$(this.options.marqueeIn).getWidth());
		if($(this.options.marqueeMe).getWidth() > $(this.options.marqueeIn).getWidth())
			this.elTomarquee.setStyle('right', -$(this.options.marqueeMe).getWidth()+$(this.options.marqueeIn).getWidth()+'px');
		else
			this.elTomarquee.setStyle('right', $(this.options.marqueeIn).getWidth()-$(this.options.marqueeMe).getWidth()+'px');
		//this.elTomarquee.setStyle('right',-this.startFrom+'px');
		this.marquee(); //start marquee
		this.mouseEvents(); //start marquee
	},
	marquee: function() {

		if(this.options.direction == 'left' && this.elTomarquee.getStyle('right').toInt() < 0){
			var addPix = this.elTomarquee.getStyle('right').toInt();
			this.elTomarquee.setStyle('right',(addPix+10)+'px');
			if(addPix > this.restartLimit){
				this.elTomarquee.setStyle('right', 0+'px');
			}
		}else if(this.options.direction == 'right' && this.elTomarquee.getStyle('right').toInt() > -$(this.options.marqueeMe).getWidth()+$(this.options.marqueeIn).getWidth()){
			var addPix = this.elTomarquee.getStyle('right').toInt();
			this.elTomarquee.setStyle('right',(addPix-10)+'px');
			if(addPix < -$(this.options.marqueeMe).getWidth()){
				this.elTomarquee.setStyle('right', this.startFrom+'px');
			}
		}
		
		this.timer = this.marquee.delay(this.options.speed, this);
		
	},
	mouseEvents : function(){
	    this.elTomarquee.addEvents({
	        'mouseover' : function(){
	            //$clear(this.timer);
	        }.bind(this),
	        'mouseout' : function(){
	            //this.timer = this.marquee.delay(this.options.speed, this);
	        }.bind(this)
	    });
	},
	stop : function(){
		$clear(this.timer);
	},
	play : function (){
		this.timer = this.marquee.delay(this.options.speed, this);
	},
	direction : function(dir){
		this.options.direction	= dir;
	}
});
marquee.implement(new Options);