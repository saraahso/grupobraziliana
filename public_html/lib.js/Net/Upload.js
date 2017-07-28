// JavaScript Document

var Upload = new Class({
	 Implements: Options,
	 options:{
		 
		 classe: 'titulo',
		 imagemBegin: 'linha.jpg',
		 imagemEnd: 'linhav.jpg'
		 
	 },
	
	 initialize: function(objeto, destino, options){
		 
		 this.setOptions(options);
		 
		 if(Browser.Engine.gecko){
			 
			 this.objeto = $(objeto);
			 
		 }else if(Browser.Engine.webkit){
			 
			 this.objeto = $(objeto+"2");
			 
		 }else if(Browser.Engine.trident){
			 
			 this.objeto = $(objeto);
		 
		 }
		 
		 this.destino = $(destino);
		 		 
		 
	 },
	 
	 url: '',
	 
	 detalhes: function (nome, tamanho){
         
	     tab = '<table style="width: 100%">';
	
	     var texto = new String();
	     
	     for(i = 0; i < nome.length; i++){
	         
		     if((i+1)%2 == 0){
		        bg = '';	
		     }else{
		        bg = '';
		     }
		
		     texto = nome[i];
		     
		     tab += '<tr bgcolor="'+bg+'" class="'+this.options.classe+'"><td style="width: 30%;"><b>'+texto.substr(0, 30)+'</b><input type="hidden" name="imagem['+i+']" value="'+texto+'"></td>';
		     tab += '<td><img id="carimg'+i+'" src="'+this.options.imagemBegin+'" width="0" height="15"></td>';
		     tab += '</tr>';
	
	      }
	
	      tab += '</table>';

		  this.destino.innerHTML = tab;
	      
      },
	  
	  progresso: function(int, car){
	
	      $('carimg'+int).width = car;
	      $('carimg'+int).alt = car+'%';
	
	      if(car == 100){
		     
			 $('carimg'+int).src = this.options.imagemEnd;
	      
		  }
	  
	  },
	  
	  setURL: function(){
	   
		 this.objeto.URL(this.url);
      
	  },
	  
	  final: function(){}
	 
});