// JavaScript Document

var Navegador = new Class({
					 
    width: function(){
		
		/*if(this.verificarNavegador() == -1){
			return window.outerWidth;
		}else{
			return window.document.body.clientWidth;
		}*/
		
		return window.getWidth();
		
		
	},
	
	verificarNavegador: function(tipo){
		
		if(tipo == 1)
			return Browser.Engine.trident;
		else if(tipo == 2)
			return Browser.Engine.gecko;
		else if(tipo == 3)
			return Browser.Engine.webkit;
		
	}

});