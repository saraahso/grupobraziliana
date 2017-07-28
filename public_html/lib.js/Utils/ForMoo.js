/*---------------------------------------------------------------------------------------------------------------
Ag. Dotes - Otimizando seus dons - http://www.dotes.com.br - +55(62)3514.1227 | url: http://formoo.dotes.com.br
Criado por : Luiz Jr. Fernandes | email: contato@dotes.com.br / luizbox@msn.com | Update: 22/05/2007
---------------------------------------------------------------------------------------------------------------*/
var ForMoo = new Class({
        options: {
			form:'',				//Form in question
			onSubmit : true,		//onSubmit handler
			errorFields:[],			//Array Error Fields 
			errorMsgs:[],			//Array Error messages
			dateFormat: 'EURO', 	//ISO , EURO , ANSI
			timeFormat: '24',		//12 | 24
						
			/*Plugins*/
			showMsgs: true,			//Show or hidden error msgs
			scrollOnError:false,	//Scroll for error field onSubmit
			tips: false,			//Turn on or off tips for fields 		
			foc: false,				//Turn on or off focus on fields
			
			/*Classes*/
			original:'',			//Original fields class |required for focus|
			valid:'',				//Valid fields class
			invalid:'',				//Invalid fields class
			msg:'',					//Error Msgs class
			tipClass:'',			//Tips Class
			focClass:''				//Focus Class

        },
        initialize: function(options){
                this.setOptions(options);
				this.options.tipClass=this.options.tipClass.replace(".","")
				this.options.msg=this.options.msg.replace(".","")
				if (this.options.tips){ this.showTips(); }
				if (this.options.foc){ this.onFocus(); }
				this.form=$(this.options.form);
				this.form.reset();
				if(this.options.onSubmit){
				this.form.addEvents({
					"submit": this.onSubmit.bind(this),
					"reset": this.onReset.bind(this)
				});}
        },

		onSubmit :  function(ev){// onSubmit tests with validate if has errors. If yes it stops submit
			//if(!this.validate()) new Event(ev).stop();
		},
		onReset: function(ev){ // onReset turn back original field state
			var invalid=this.options.invalid;
			var valid = this.options.valid;
			var original = this.options.original;
			this.options.errorFields.each(function(field){
						var morpher = new Fx.Morph(field, {wait: false});
						morpher.start(original);
						var span=field+'_span'
						var tween = new Fx.Tween(span, 'opacity');
						if (!$(span)){}else{if($(span).getStyle("opacity")!=0){tween.start(1,0);}}
			});							   
		},
		validate:function(){// Check if error array has itens
			if (!this.options.errorFields.getLast())
			{
				return true;	//Post the form
			}
			else 			//Show errors for any field
			{
				var invalid=this.options.invalid;
				var labels=$$(this.options.form+" label");			var select_label;
				this.options.errorFields.each(function(field){
					var morpher = new Fx.Morph($(field), {wait: false});
					morpher.start(invalid);								   
				});
				this.options.errorMsgs.each(function(field,i){
					if (i==0){ff=field}
					var tmp=field.split("||")
					var field=tmp[0];
					var msg=tmp[1];
					labels.each(function(item){if ($(item).getProperty('for')==field){select_label=item}});
					if (!$(field+'_span')){
						var span =	new Element('span', {'styles': {'opacity': '0'},	'class': 'msg',	'id': field+'_span'}).set('html', msg).inject(select_label, 'after');
						var tween = new Fx.Tween(span, 'opacity');
						tween.start(0,1);
					}else{
						var span=field+'_span';
						var tween = new Fx.Tween(span, 'opacity');
						tween.start(0,1);
					}
				});				
				if (this.options.scrollOnError){
					ff = ff.toString()
					var ffs = ff.split("||")
					new Fx.Scroll(window).start(0,($(ffs[0]).getCoordinates().top - 30 ))
				}
				return false;
			}
		},
		
		aggErrors:function(field,msg){
			this.options.errorFields.include(field);
			this.options.errorMsgs.include(field +"||"+ msg);
		},
		
		clearErrors:function(field,msg){
			this.options.errorFields.remove(field)
			this.options.errorMsgs.remove(field +"||"+ msg);
			this.hideErrors(field,msg);
		},
		
		showErrors:function(field,msg){
				var morpher = new Fx.Morph(field, {wait: false});
				morpher.start(this.options.invalid);
				var labels=$$(this.options.form+" label");			var select_label;
				labels.each(function(item){if ($(item).getProperty('for')==field){select_label=item}});
				if (!$(field+'_span')){
					if (this.options.showMsgs){ var span = new Element('span', {'styles': {'opacity': '0'},	'class': this.options.msg,	'id': field+'_span'}).set('html', msg).inject(select_label, 'after'); }else{ var span=new Element('span', {'styles': {'opacity': '0'},	'class': 'msg',	'id': field+'_span'}).inject(select_label, 'after'); }
					var tween = new Fx.Tween(span, 'opacity');
					tween.start(0,1);
				}else{
					var span=field+'_span';
					var tween = new Fx.Tween(span, 'opacity');
					tween.start(0,1);
				}
				
		},
		hideErrors:function(field,msg){
			var morpher = new Fx.Morph(field, {wait: false});
			morpher.start(this.options.valid);
			var span=field+'_span'
			var tween = new Fx.Tween(span, 'opacity');
			if (!$(span)){}else{if($(span).getStyle("opacity")!=0){tween.start(1,0);}}
		},
		showTips:function(){ //Mostar tips
			var tipClass=this.options.tipClass;
			var errorFields=this.options.errorFields;
			$$(this.options.form +" *").each(function(field){
					var enter,leave;
					switch($(field).type){
						case 'text':
						case 'password':
						case 'textarea':
							enter="focus";leave="blur";
						break;
						case 'select-one':
							enter="focus";leave="blur";
							$(field).addEvent("change", function () {if ($(field).getProperty("title") && errorFields.contains($(field).id)){var tip=$(field).id+'_tip';var tween = new Fx.Tween(tip, 'opacity');tween.start(1,0)}}.bind(this) );	
						break;
						/*case 'checkbox':
						case 'radio':				
							enter="focus";leave="blur";				
						break;*/	
						case 'reset':
						case 'submit':
							$(field).addEvent("mouseenter", function () {if ( $(field).getAttribute("title")){var div=new Element('div', {'styles': {'opacity': '0','position':'absolute'},	'id': $(field).id+'_tip','class':tipClass}).set('html', $(field).getAttribute("title")).inject(field, 'bottom');var tween = new Fx.Tween(div, 'opacity');tween.start(0,1);}}.bind(this) );
							$(field).addEvent("mouseleave", function () {if ($(field).getProperty("title")){	var tip=$(field).id+'_tip';var tween = new Fx.Tween(tip, 'opacity');tween.start(1,0);(function(){$($(field).id+'_tip').destroy()}).delay(500, this);}}.bind(this) );	
						break;
					};
					$(field).addEvent(enter, function () { //Start tips for focus field
						if ( $(field).getAttribute("title") && errorFields.contains($(field).id)  ){
							var div=new Element('div', {'styles': {'opacity': '0','position':'absolute'},	'id': $(field).id+'_tip','class':tipClass}).set('html', $(field).getAttribute("title")).inject($(field).id, 'after');
							var tween = new Fx.Tween(div, 'opacity');
							tween.start(0,1);
							
						}
					}.bind(this) );			
					$(field).addEvent(leave, function () { //Hide tips for focus field
						if ( ($(field).getProperty("title")) && (errorFields.contains($(field).id)) ){
							var tip=$(field).id+'_tip';
							var tween = new Fx.Tween(tip, 'opacity');
							tween.start(1,0);
							(function(){$($(field).id+'_tip').remove()}).delay(500, this);
						}
					}.bind(this) );	
			});
		},
		onFocus:function(){
			var enter; var leave; var original = this.options.original; var focusClass= this.options.focClass;var errorFields=this.options.errorFields;
			$$(this.options.form + " *").each(function(field){
				switch($(field).type){
						case 'text':
						case 'password':
						case 'textarea':
						case 'submit':
						case 'reset':
						case 'select-one':
						case 'file':
							enter="focus";leave="blur";
						break;
						default:
							enter="never";leave="land";
						break;
				}
				$(field).addEvent(enter, function () { 	var morpher = new Fx.Morph(field, {wait: false});morpher.start(focusClass);}.bind(this) );	
				$(field).addEvent(leave, function () { if (!errorFields.contains($(field).id)){var morpher = new Fx.Morph(field, {wait: false});							morpher.start(original);	}				}.bind(this) );	
			});
		},
		Required: function(field,msg){//Validation for required fields
			switch($(field).type)
			{
				case 'text':
				case 'password':
				case 'file':
				case 'textarea':
					$(field).addEvent('blur', function () { this.textRequired(field,msg);	}.bind(this) );				
					this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
					break;
				case 'checkbox':
				case 'radio':
					var status=false;
					var fields=document.getElementsByName($(field).name);
					for(i=0;i<fields.length;i++){$(fields[i].id).addEvent('click', function () {this.checkradioRequired(field,msg);}.bind(this) )	}
					this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
					break;
				case 'select-one':
					$(field).addEvent('change', function () { this.selectRequired(field,msg);	}.bind(this) );	
					this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
					break;
			}

		},
		textRequired: function(field,msg){//Text fields validation
			if ($(field).value.replace(/ /g, '') == ""){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		checkradioRequired: function(field,msg){//CHECKBOX and RADIO validation
			var status=false;
			var fields=document.getElementsByName($(field).name);
			for (var i = 0; i < fields.length; i++){ if(fields[i].checked==true){status=true;}}
			if (!status){			this.aggErrors(field,msg);this.showErrors(field,msg);
			}else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		selectRequired: function(field,msg){//SELECT fields validation
			if ($(field).value == ""){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		emailRequired: function(field,msg){//EMAIL fields validation
			$(field).addEvent('blur', function () { this._emailRequired(field,msg);	}.bind(this) );	
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_emailRequired:function(field,msg){
			if ($(field).value.search(/^\w+((-\w+)|(\.\w+))*\@\w+((\.|-)\w+)*\.\w+$/) == -1){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		urlRequired:function(field,msg){//URL fields validation
			$(field).addEvent('blur', function () { this._urlRequired(field,msg);	}.bind(this) );	
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_urlRequired:function(field,msg){
			if ($(field).value.search(/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i) == -1){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		integerRequired:function(field,msg){//INTEGER fields validation
			$(field).addEvent('blur', function () { this._integerRequired(field,msg);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_integerRequired:function(field,msg){
			if ( ! ( parseInt($(field).value) == $(field).value ) ){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		invalidRequired:function(field,msg,invalids){//INVALID fields validation
			$(field).addEvent('blur', function () { this._invalidRequired(field,msg,invalids);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_invalidRequired:function(field,msg,invalids){
			var i=0;var ret=true;
			while(i < invalids.length && ret){
				if($(field).value.toLowerCase().indexOf(invalids[i]) != -1) 
					ret = false;
				i++;
			}
			if(!ret){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		regexpRequired:function(field,msg,pattern){//Validation via REGEXP
			$(field).addEvent('blur', function () { this._regexpRequired(field,msg,pattern);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_regexpRequired:function(field,msg,pattern){
			var strmatch = $(field).value.match(pattern);
			if (strmatch == null){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		matchRequired:function(field1,field2,msg){ //Field COMPARASION
			$(field2).addEvent('blur', function () { this._matchRequired(field1,field2,msg);	}.bind(this) );
			this.options.errorFields.include(field2);this.options.errorMsgs.include(field2 +"||"+ msg);
			this.options.errorFields.include(field1);this.options.errorMsgs.include(field1 +"||"+ msg);
		},
		_matchRequired:function(field1,field2,msg){
			if($(field1).value != $(field2).value || $(field2).value.replace(/ /g, '') == ""){this.aggErrors(field1,msg);this.aggErrors(field2,msg);this.showErrors(field1,msg);this.showErrors(field2,msg);}
			else{this.clearErrors(field1,msg);this.clearErrors(field2,msg);this.hideErrors(field1,msg);this.hideErrors(field2,msg);}
		},
		fileRequired:function(field,msg,types,maxsize){ //FILE fields validation
			$(field).addEvent('blur', function () { this._fileRequired(field,msg,types,maxsize);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_fileRequired:function(field,msg,types){
			var ext="," + $(field).value.substr( $(field).value.length - 4 ).toLowerCase() + ",";
			
			if ($(field).value.replace(/ /g, '') == ""){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
			
			msg="Tipo de arquivo inv&aacute;lido. Utilize <strong>"+types+"</strong>";
			if (types.indexOf(ext)== -1 ){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}

		},
		dateRequired:function(field,msg){ //FORMATED DATE fields validation
			$(field).addEvent('blur', function () { this._dateRequired(field,msg);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_dateRequired:function(field,msg){
			var dtPt;
			var ret= true;
			switch(this.options.dateFormat) {
				case 'ISO':
					dtPt = /^(?:(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\/|-|\.)(?:0?2\1(?:29))$)|(?:(?:1[6-9]|[2-9]\d)?\d{2})(\/|-|\.)(?:(?:(?:0?[13578]|1[02])\2(?:31))|(?:(?:0?[1,3-9]|1[0-2])\2(29|30))|(?:(?:0?[1-9])|(?:1[0-2]))\2(?:0?[1-9]|1\d|2[0-8]))$/;					format="aaaa/mm/dd";
				break;
				case 'EURO': 
					dtPt="(((0[1-9]|[12][0-9]|3[01])([/])(0[13578]|10|12)([/])([1-2][0,9][0-9][0-9]))|(([0][1-9]|[12][0-9]|30)([/])(0[469]|11)([/])([1-2][0,9][0-9][0-9]))|((0[1-9]|1[0-9]|2[0-8])([/])(02)([/])([1-2][0,9][0-9][0-9]))|((29)(\.|-|\/)(02)([/])([02468][048]00))|((29)([/])(02)([/])([13579][26]00))|((29)([/])(02)([/])([0-9][0-9][0][48]))|((29)([/])(02)([/])([0-9][0-9][2468][048]))|((29)([/])(02)([/])([0-9][0-9][13579][26])))";					format = "dd/mm/aaaa";
				break;
				case 'ANSI':
					dtPt= /^((\d{2}(([02468][048])|([13579][26]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|([1-2][0-9])))))|(\d{2}(([02468][1235679])|([13579][01345789]))[\-\/\s]?((((0?[13578])|(1[02]))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\-\/\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\-\/\s]?((0?[1-9])|(1[0-9])|(2[0-8]))))))(\s(((0?[1-9])|(1[0-2]))\:([0-5][0-9])((\s)|(\:([0-5][0-9])\s))([AM|PM|am|pm]{2,2})))?$/;					format= "aaaa/mm/dd hh:mm:ss am/pm";
				break;
			}
			var matchDt = $(field).value.match(dtPt);
			if (matchDt == null){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		timeRequired:function(field,msg){	//FORMATED TIME fields validation
			$(field).addEvent('blur', function () { this._timeRequired(field,msg);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_timeRequired:function(field,msg){
			var timePat;
			switch(this.options.timeFormat){
				case '24':
					timePat = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;					format = "hh:mm";
				break;
				case '12':
					timePat = /^(1|01|2|02|3|03|4|04|5|05|6|06|7|07|8|08|9|09|10|11|12{1,2}):(([0-5]{1}[0-9]{1}\s{0,1})([AM|PM|am|pm]{2,2}))\W{0}$/;					format = "hh:mm am/pm";
				break;
			}
			var matchArray = $(field).value.match(timePat);
			if (matchArray == null){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		machValueRequired:function(field, operator, value, msg){ //VALUE COMPARASION fields validation
			$(field).addEvent('blur', function () { this._machValueRequired(field, operator, value, msg);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_machValueRequired:function(field, operator, value, msg){
			if (operator == "=") operator = "==";
			if ( !(eval("$(field).value "+operator+value)) ){this.clearErrors(field,msg);this.hideErrors(field,msg); }
			else{this.aggErrors(field,msg);this.showErrors(field,msg);}
		},
		amountRequired:function(field,msg){//CURRENCY FORMATED field validation 50,000,000.00
			$(field).addEvent('blur', function () { this._amountRequired(field,msg);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_amountRequired:function(field,msg){
			if ($(field).value.search(/^\$?\-?([1-9]{1}[0-9]{0,2}(\,[0-9]{3})*(\.[0-9]{0,2})?|[1-9]{1}\d*(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|(\.[0-9]{1,2})?)$/) == -1){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		lengthRequired:function(field,msg,lengthmax,lengthmin){//LENGTH FIELD validation
			$(field).addEvent('blur', function () { this._lengthRequired(field,msg,lengthmax,lengthmin);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_lengthRequired:function(field,msg,lengthmax,lengthmin){
			if (!lengthmin){if( ($(field).value.length<lengthmax) || ($(field).value.length>lengthmax) ){this.aggErrors(field,msg);this.showErrors(field,msg);}else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
			}else{if( ($(field).value.length<lengthmin) || ($(field).value.length>lengthmax) ){this.aggErrors(field,msg);this.showErrors(field,msg);}else{this.clearErrors(field,msg);this.hideErrors(field,msg);}}
		},
		cpfRequired:function(field,msg){//BRAZILIAN CPF validation
			$(field).addEvent('blur', function () { this._cpfRequired(field,msg);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_cpfRequired:function(field,msg){
			 cpf=$(field).value;  exp = /\.|\-/g;  cpf = cpf.toString().replace( exp, "" );  var numeros, digitos, soma, i, resultado, digitos_iguais;			  digitos_iguais = 1;
			  if (cpf.length != 11){this.aggErrors(field,msg);this.showErrors(field,msg);}
			  else{
				  for (i = 0; i < cpf.length - 1; i++)
						if (cpf.charAt(i) != cpf.charAt(i + 1)){digitos_iguais = 0; break;}
				  if (!digitos_iguais)
						{
						numeros = cpf.substring(0,9);digitos = cpf.substring(9);soma = 0;
						for (i = 10; i > 1; i--)
							  soma += numeros.charAt(10 - i) * i;
						resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
						if (resultado != digitos.charAt(0)){this.aggErrors(field,msg);this.showErrors(field,msg);}
						numeros = cpf.substring(0,10);	soma = 0;
						for (i = 11; i > 1; i--)
							  soma += numeros.charAt(11 - i) * i;
						resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
						if (resultado != digitos.charAt(1)){this.aggErrors(field,msg);this.showErrors(field,msg);}
						else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
						}
				  else{this.aggErrors(field,msg);this.showErrors(field,msg);}
			  }
		},
		cnpjRequired:function(field,msg){//BRAZILIAN CNPJ validation
			$(field).addEvent('blur', function () { this._cnpjRequired(field,msg);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_cnpjRequired:function(field,msg){
			  cnpj=$(field).value
			  exp = /\.|\-|\//g
			  cnpj = cnpj.toString().replace( exp, "" ); 
			  var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
			  digitos_iguais = 1;
			  if (cnpj.length != 14 && cnpj.length != 15){this.aggErrors(field,msg);this.showErrors(field,msg);}
			  for (i = 0; i < cnpj.length - 1; i++)
					if (cnpj.charAt(i) != cnpj.charAt(i + 1)){digitos_iguais = 0; break;}
			  if (!digitos_iguais)
					{
						tamanho = cnpj.length - 2;numeros = cnpj.substring(0,tamanho);digitos = cnpj.substring(tamanho);soma = 0;pos = tamanho - 7;
						for (i = tamanho; i >= 1; i--){soma += numeros.charAt(tamanho - i) * pos--; if (pos < 2){pos = 9;}}
						resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
						if (resultado != digitos.charAt(0)){this.aggErrors(field,msg);this.showErrors(field,msg);}
						tamanho = tamanho + 1;numeros = cnpj.substring(0,tamanho);	soma = 0;pos = tamanho - 7;
						for (i = tamanho; i >= 1; i--) { soma += numeros.charAt(tamanho - i) * pos--;  if (pos < 2){pos = 9;} }
						resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
						if (resultado != digitos.charAt(1)){this.aggErrors(field,msg);this.showErrors(field,msg);}
						else{this.clearErrors(field,msg);this.hideErrors(field,msg); }
					}
			  else{this.aggErrors(field,msg);this.showErrors(field,msg);}
		},
		creditcardRequired:function(field,msg){		//Credit Card Validation
			$(field).addEvent('blur', function () { this._creditcardRequired(field,msg);	}.bind(this) );
			this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
		},
		_creditcardRequired:function(field,msg){
			credit_card_regexp = /^((4\d{3})|(5[1-5]\d{2})|(6011))([- ])?\d{4}([- ])?\d{4}([- ])?\d{4}|3[4,7]\d{13}$/			
			var matchArray = $(field).value.match(credit_card_regexp);
			if (matchArray == null){this.aggErrors(field,msg);this.showErrors(field,msg);}
			else{this.clearErrors(field,msg);this.hideErrors(field,msg);}
		},
		maskIt:function(field, mask, msg, event){  // MASKIT - field masks for numeric fields |--> date, time, phone, ids, serial numbers etc.
			
			
				$(field).addEvent('keypress', function (event) { this._maskIt(field, mask, msg, event);	}.bind(this) );
				$(field).addEvent('blur', function (event) { this._maskIt(field, mask, msg, event);	}.bind(this) );
				//this.options.errorFields.include(field);this.options.errorMsgs.include(field +"||"+ msg);
			
		},
		_maskIt:function(field, mask, msg, event){ 
			var sMask = mask;	var objeto = $(field);	var evtKeyPress = event;var i, nCount, sValue, fldLen, mskLen,bolMask, sCod, nTecla;
			
			if(evtKeyPress.key != 'undefined' && evtKeyPress.key != 'backspace'){
				
				if(document.all){nTecla = evtKeyPress.key;} 
				else if(document.layers) {nTecla = evtKeyPress.which;} 
				sValue = objeto.value;sValue = sValue.toString().replace( "-", "" );sValue = sValue.toString().replace( "-", "" );sValue = sValue.toString().replace( ".", "" );sValue = sValue.toString().replace( ".", "" );sValue = sValue.toString().replace( "/", "" );sValue = sValue.toString().replace( "/", "" );sValue = sValue.toString().replace( ":", "" );sValue = sValue.toString().replace( ":", "" );sValue = sValue.toString().replace( "(", "" );sValue = sValue.toString().replace( "(", "" );sValue = sValue.toString().replace( ")", "" );sValue = sValue.toString().replace( ")", "" );sValue = sValue.toString().replace( " ", "" );sValue = sValue.toString().replace( " ", "" );fldLen = sValue.length; mskLen = sMask.length;
				i = 0;	nCount = 0;	sCod = "";	mskLen = fldLen;
				while (i <= mskLen) {
				  bolMask = ((sMask.charAt(i) == "-") || (sMask.charAt(i) == ".") || (sMask.charAt(i) == "/") || (sMask.charAt(i) == ":"))
				  bolMask = bolMask || ((sMask.charAt(i) == "(") || (sMask.charAt(i) == ")") || (sMask.charAt(i) == " "))
				  if (bolMask) {sCod += sMask.charAt(i); mskLen++; }
				  else {sCod += sValue.charAt(nCount);nCount++;}
				  i++;
				}
				objeto.value = sCod;
				if (nTecla != 8) {
					if (nTecla==0){/* return true; this.clearErrors(field,msg);this.hideErrors(field,msg);*/}
					if (sMask.charAt(i-1) == "9") { return ((nTecla > 47) && (nTecla < 58));
					}else {/* return true; this.clearErrors(field,msg);this.hideErrors(field,msg);*/ }
				}else { /* return true; this.clearErrors(field,msg);this.hideErrors(field,msg);*/}
			
			}
		
		}	
});
ForMoo.implement(new Options, new Events);