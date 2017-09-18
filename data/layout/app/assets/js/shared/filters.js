
/**
 * Filtros
 */
application
    .filter("moeda", ['numberFilter', function (numberFilter){
        
        function isNumeric(value){
            return (!isNaN(parseFloat(value)) && isFinite(value));
        }
        
        return function (inputNumber, currencySymbol, decimalSeparator, thousandsSeparator, decimalDigits) {
            if (isNumeric(inputNumber)){
                // Default values for the optional arguments
                currencySymbol = (typeof currencySymbol === "undefined") ? "" : currencySymbol;
                decimalSeparator = (typeof decimalSeparator === "undefined") ? "," : decimalSeparator;
                thousandsSeparator = (typeof thousandsSeparator === "undefined") ? "." : thousandsSeparator;
                decimalDigits = (typeof decimalDigits === "undefined" || !isNumeric(decimalDigits)) ? 2 : decimalDigits;

                if (decimalDigits < 0) decimalDigits = 0;

                // Format the input number through the number filter
                // The resulting number will have "," as a thousands separator
                // and "." as a decimal separator.
                var formattedNumber = numberFilter(inputNumber, decimalDigits);

                // Extract the integral and the decimal parts
                var numberParts = formattedNumber.split(".");

                // Replace the "," symbol in the integral part
                // with the specified thousands separator.
                numberParts[0] = numberParts[0].split(",").join(thousandsSeparator);

                // Compose the final result
                var result = currencySymbol + numberParts[0];

                if (numberParts.length === 2){
                    result += decimalSeparator + numberParts[1];
                }

                return result;
            }else{
                return inputNumber;
            }
        };
    }]);

application
    .filter('telephone', function(){
        return function(input){
            
            if(angular.isObject(input)){
                var number  = String(input.number),
                    tel     = '';
                if(input.ddi !== '')
                    tel = '+'+input.ddi+' ';
                if(input.ddd !== '')
                    tel += '('+input.ddd+') ';
                if(input.number !== '')
                    tel += (number.length === 9 ? (number.slice(0, 5)+"-"+number.slice(5, 9)) : (number.slice(0, 4)+"-"+number.slice(4, 8)));
                return tel;
            }else if(Number(input) !== NaN){
                var input = String(input);
                if(input.length === 11)
                    return "("+input.slice(0, 2)+") "+input.slice(2, 7)+"-"+input.slice(7, 11);
                else if(input.length === 10)
                    return "("+input.slice(0, 2)+") "+input.slice(2, 6)+"-"+input.slice(6, 10);
            }
            
            return input;
            
        };
    });

application
    .filter('toDate', function(){
        return function(input){
            var localDate = new Date(input);
            if(localDate.toString() === 'Invalid Date'){
                if(input.length === 8){
                    var ano = input.slice(0, 4),
                        mes = input.slice(4, 6),
                        dia = input.slice(6, 8);
                    localDate = new Date(ano+'-'+mes+'-'+dia);
                }
            }
            var localTime = localDate.getTime();
            var localOffset = localDate.getTimezoneOffset() * 60000;
            return new Date(localTime + localOffset);
        };
    });

application
	.filter('resizeImage', function(){
		return function(value, width, height){
			var separate = String(value).split(".");
			if(value)
				return separate[0]+"size-"+width+"-"+height+"."+separate[1];
			return "";
		};
	});