/**
 *  Gera google maps
 *  Font: http://gmap3.net/en/catalog/14-services/directionsrenderer-48 
 *  <Valmir Barbosa dos Santos> valmir.php@gmail.com
 **/

var nav = null;
var MAP_ADDRESS = 'marktronic, ciudad del este, py';
var MAKER = 'Marktronic';
var MAP_ROTA = 1; // lista roteiro ate o caminho

function ComoChegarGps() {

    if (nav === null) {
        nav = window.navigator;
    }

    var geoloc = nav.geolocation;
    if (geoloc !== null) {

        return geoloc.getCurrentPosition(successGmap3, errorGmap3);
    }

}

function ComoChegarEndereco() {
    renderRota($('#my_map_andress').val());
}

$(document).ready(function() {

$("#my_map").gmap3({
   marker:{
      address: MAP_ADDRESS,
      data: MAKER,
      events: {
                mouseover: function(marker, event, context) {
                    var map = $(this).gmap3("get"),
                            infowindow = $(this).gmap3({get: {name: "infowindow"}});
                    if (infowindow) {
                        infowindow.open(map, marker);
                        infowindow.setContent(context.data);
                    } else {
                        $(this).gmap3({
                            infowindow: {
                                anchor: marker,
                                options: {content: context.data}
                            }
                        });
                    }
                },
                mouseout: function() {
                    var infowindow = $(this).gmap3({get: {name: "infowindow"}});
                    if (infowindow) {
                        infowindow.close();
                    }
                }
            }//events
    },
    map:{
      options:{
        mapTypeControl: false,
        zoom: 15
      }
    }
});

});//load

function ReloadMap() {
    var f = document.getElementById("imapa");
    if (f != null)
        f.src = f.src;
}

function successGmap3(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    andress = latitude + ',' + longitude;
    renderRota(andress);

}

function renderRota(andress) {
    $("#my_map").gmap3({
        getroute: {
            options: {
                origin: andress,
                destination: MAP_ADDRESS, 
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            },
            callback: function(results) {
                if (!results)
                    return;
                if (MAP_ROTA === false) {
                    
                    $(this).gmap3({
                        directionsrenderer: {
                            options: {
                                directions: results
                            }
                        }
                    });
                    
                } else {

                    $(this).gmap3({
                        directionsrenderer: {
                            container: $(document.createElement("div")).addClass("googlemap").insertAfter($("#my_map")),
                            options: {
                                directions: results
                            }
                        }
                    });

                }
            }
        }

    });
}


function errorGmap3(error) {
    alert("Houve algum erro!");
    var strMessage = "";

    // Check for known errors
    switch (error.code) {
        case error.PERMISSION_DENIED:
            strMessage = "Access to your location is turned off. " +
                    "Change your settings to turn it back on.";
            break;
        case error.POSITION_UNAVAILABLE:
            strMessage = "Data from location services is " +
                    "currently unavailable.";
            break;
        case error.TIMEOUT:
            strMessage = "Location could not be determined " +
                    "within a specified timeout period.";
            break;
        default:
            break;
    }

    $(document.createElement("div")).addClass("googlemap").insertAfter( strMessage );
    //document.getElementById("status").innerHTML = strMessage;
}
