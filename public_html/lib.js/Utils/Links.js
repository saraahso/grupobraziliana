// JavaScript Document

var Links = new Class({
					 
    abrirJavascript: function(funcao){
		
		funcao;
		
	}, 
	
	confirmar: function(msg, url){
	    
		var alerta = new SexyAlert();
		
		function _onOK(valor){
			
			if(valor)
			   document.location.href = url;
			
		}
		
		alerta.confirm(msg, {onComplete: _onOK});
		
	}

});