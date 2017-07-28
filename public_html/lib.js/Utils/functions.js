// JavaScript Document

String.prototype.isValidCPF = function () {
	var i,
		cpf = this; 
	cpf = cpf.replace(".", "");
	cpf = cpf.replace(".", "");
	cpf = cpf.replace("_", "");
	cpf = cpf.replace("-", "");
	s = cpf;
	
	var c = s.substr(0,9); 
	var dv = s.substr(9,2); 
	
	var d1 = 0; 
	
	if(cpf == '99999999999' || cpf == '88888888888' || cpf == '77777777777' || cpf == '66666666666' || cpf == '55555555555' || cpf == '44444444444' || cpf == '33333333333' || cpf == '22222222222' || cpf == '11111111111')
		return false; 
	
	for (i = 0; i < 9; i++){ 
		d1 += c.charAt(i)*(10-i); 
	} 
	
	if (d1 == 0){ 
		return false; 
	} 
	
	d1 = 11 - (d1 % 11); 
	
	if (d1 > 9) d1 = 0; 
	
	if (dv.charAt(0) != d1){ 						  
		return false; 
	} 
	
	d1 *= 2; 
	
	for (i = 0; i < 9; i++){ 
		d1 += c.charAt(i)*(11-i); 
	} 
	
	d1 = 11 - (d1 % 11); 
	
	if (d1 > 9) d1 = 0; 
	
	if (dv.charAt(1) != d1){ 
		return false;  
	} 
	
	return cpf; 
};

String.prototype.isValidCNPJ = function () {
	var i = 0;
	var l = 0;
	var cnpj = this;
	var strNum = "";
	var strMul = "6543298765432";
	var character = "";
	var iValido = 1;
	var iSoma = 0;
	var strNum_base = "";
	var iLenNum_base = 0;
	var iLenMul = 0;
	var iSoma = 0;
	var strNum_base = 0;
	var iLenNum_base = 0;
					
	if(cnpj == '00.000.000/0000-00' || cnpj == '00000000000000')
		return false;
	
	cnpj = cnpj.replace(".", "");
	cnpj = cnpj.replace(".", "");
	cnpj = cnpj.replace("_", "");
	cnpj = cnpj.replace("-", "");
	cnpj = cnpj.replace("/", "");
	
	l = cnpj.length;
	for (i = 0; i < l; i++) {
		caracter = cnpj.substring(i,i+1)
		if ((caracter >= '0') && (caracter <= '9'))
			strNum = strNum + caracter;
	};
	
	strNum_base = strNum.substring(0,12);
	iLenNum_base = strNum_base.length - 1;
	iLenMul = strMul.length - 1;
	for(i = 0;i < 12; i++)
		iSoma = iSoma +
	parseInt(strNum_base.substring((iLenNum_base-i),(iLenNum_base-i)+1),10) *
	parseInt(strMul.substring((iLenMul-i),(iLenMul-i)+1),10);
	
	iSoma = 11 - (iSoma - Math.floor(iSoma/11) * 11);
	if(iSoma == 11 || iSoma == 10)
		iSoma = 0;
	
	strNum_base = strNum_base + iSoma;
	iSoma = 0;
	iLenNum_base = strNum_base.length - 1
	for(i = 0; i < 13; i++)
		iSoma = iSoma +
	parseInt(strNum_base.substring((iLenNum_base-i),(iLenNum_base-i)+1),10) *
	parseInt(strMul.substring((iLenMul-i),(iLenMul-i)+1),10)
	
	iSoma = 11 - (iSoma - Math.floor(iSoma/11) * 11);
	if(iSoma == 11 || iSoma == 10)
		iSoma = 0;
	strNum_base = strNum_base + iSoma;
	if(strNum != strNum_base)
		return false;
	
	return cnpj;
};

String.prototype.isValidEmail = function () {
	var email = this,
		div = null;
	if(email.indexOf('@') > 0){
		div = email.split('@');
		if(div[1].indexOf('.') > 0){
			div = div[1].split('.');
			if(div[1] != '')
				return true;
			else
				return false;
		}else
			return false; 
	}else
		return false;
};


function getQueryString(sParam){
	var sPageURL = window.location.pathname,
		sURLVariables = sPageURL.split('&');
	for(var i = 0; i < sURLVariables.length; i++){
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam){
			return sParameterName[1];
		}
	}
}