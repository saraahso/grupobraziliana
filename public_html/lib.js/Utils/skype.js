
var activex=((navigator.userAgent.indexOf("Win")!=-1)&&(navigator.userAgent.indexOf("MSIE")!=-1)&&(parseInt(navigator.appVersion)>=4));
var CantDetect=((navigator.userAgent.indexOf("Safari")!=-1)||(navigator.userAgent.indexOf("Opera")!=-1));

function oopsPopup(){
	if((navigator.language&&navigator.language.indexOf("ja")!=-1)||(navigator.systemLanguage&&navigator.systemLanguage.indexOf("ja")!=-1)||(navigator.userLanguage&&navigator.userLanguage.indexOf("ja")!=-1)){
		var _1="http://download.skype.com/share/skypebuttons/oops/oops_ja.html";
	}else{
		var _2="http://download.skype.com/share/skypebuttons/oops/oops.html";
	}
	var _3="oops";
	var _4=540,popH=305;
	var _5="no";
	w=screen.availWidth;
	h=screen.availHeight;
	var _6=(w-_4)/2,topPos=(h-popH)/2;
	oopswindow=window.open(_2,_3,"width="+_4+",height="+popH+",scrollbars="+_5+",screenx="+_6+",screeny="+topPos+",top="+topPos+",left="+_6);
	return false;
}

function skypeCheck(){
	if(CantDetect){
		return true;
	}else{
		if(!activex){
			var _7=navigator.mimeTypes["application/x-skype"];
			detected=true;
			if(typeof (_7)=="object"){
				return true;
			}else{
				return oopsPopup();
			}
		}else{
			if(isSkypeInstalled()){
				detected=true;
				return true;
			}
		}
	}
	detected=true;
	return oopsPopup();
}

if(typeof(detected) == 'undefined' &&activex){
	document.write(['<script language="VBscript">','Function isSkypeInstalled()','on error resume next','Set oSkype = CreateObject("Skype.Detection")','isSkypeInstalled = IsObject(oSkype)','Set oSkype = nothing','End Function','</script>'].join('\n'));
}