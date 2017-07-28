// JavaScript Document

var Celula = new Class({
	
	Implements: Options,
	
	initialize: function(objeto){
		
		this.objeto = objeto;
		
	},
	
	conteudo: function(html){
		
		this.objeto.innerHTML = html;
		
	},
	
	pegarCelula: function(){
		
		return $(this.objeto);
		
	}
	
});

var Linha = new Class({
	
	Implements: Options,
	
	initialize: function(objeto){
		
		this.objeto = objeto;
		this.celulaAtual = '';
		
	},
	
	novaCelula: function(n){
		
		if(n != 'undefined') n = this.totalCelulas();
		
		var celula = this.objeto.insertCell(n);
		this.celulaAtual =new Celula($(celula));
		return this.celulaAtual;
		
	},
	
	removerCelula: function(celula){
		
		if(celula == '') celula = this.celulaAtual;
		if(celula != '') this.objeto.deleteCell(celula.pegarCelula().cellIndex);
		
	},
	
	totalCelulas: function(){
		
		return this.objeto.cells.length;
		
	},
	
	pegarCelula: function(n){
		
		return new Celula(this.objeto.cells[n]);
		
	},
	
	pegarLinha: function(){
		
		return $(this.objeto);
		
	}
	
});

var Tabela = new Class({	   
	
	Implements: Options,
	
	/*options:{
		 
	    classe: 'titulo',
	    imagemBegin: 'linha.jpg',
	    imagemEnd: 'linhav.jpg'
		 
	},*/
	
	
	initialize: function(objeto){
		
		this.objeto = objeto;
		this.linhaAtual = '';
		
	},
	
	novaLinha: function(n){
    
	    if(n != 'undefined') n = this.totalLinhas();
	
        var linha = this.objeto.insertRow(n);
	    this.linhaAtual = new Linha($(linha));
		return this.linhaAtual;
		   
	       
	    /*celula.innerHTML = '<img src="../vob.img/del.gif" style="cursor:pointer;" onclick="if(document.getElementById(\'etapas\').rows.length == 2){ document.getElementById(\'etapas\').style.display = \'none\'; document.getElementById(\'etapas\').deleteRow(this.parentNode.parentNode.rowIndex);}else{ document.getElementById(\'etapas\').deleteRow(this.parentNode.parentNode.rowIndex);}" />';
*/

	
	},
	
	removerLinha: function(linha){
		
		if(linha == '') linha = this.linhaAtual;
		if(linha != '') this.objeto.deleteRow(linha.pegarLinha().rowIndex);
		
	},
	
	totalLinhas: function(){
		
		return this.objeto.rows.length;
	
	},
	
	pegarLinha: function(n){
		
		return new Linha($(this.objeto.rows[n]));
		
	},
	
	pegarTabela: function(){
		
		return $(this.objeto);
		
	}

});