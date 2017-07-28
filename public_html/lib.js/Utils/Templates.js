// JavaScript Document

var Templates = new Class({
					 
	 initialize: function(objeto){
		 
		 this.objeto = objeto;
		 this.original = objeto.innerHTML;
		 this.template = '';
		 
	 },
	 
	 trocar: function(variavel, valor){
		 
		 var texto = this.template;
		 this.template = texto.replace('|'+variavel+'|', valor);
		 
		 
	 },
	 
	 criarTemplate: function(){
		 
		 this.template += this.original;
		 
	 },
	 
	 concluir: function(){
		 
		 return this.template;
		 
	 }

});