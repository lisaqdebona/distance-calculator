/* Google Map */
let map, infoWindow;
let msgContainer = document.getElementById("msg");
let triggerBtn = document.getElementById("checkBtn");
var useCurrentLocBtn = document.getElementById("useCurrentLocBtn");
var btnTxt = document.getElementById("btnTxt");
btnTxt.innerHTML = "Detecting your location...";


useCurrentLocBtn.addEventListener("click", (e) => {
  e.preventDefault();
  let lat = useCurrentLocBtn.getAttribute("data-lat");
  let long = useCurrentLocBtn.getAttribute("data-long");
}); 

function initialize() {
  var input = document.getElementById('userloc');
  var autocomplete = new google.maps.places.Autocomplete(input);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        var lat = place.geometry.location.lat();
        var lng = place.geometry.location.lng();
        document.getElementById('userLat').value = lat;
        document.getElementById('userLong').value = lng;
        document.getElementById('form-msg').innerHTML = '';
    });
}
google.maps.event.addDomListener(window, 'load', initialize);

useCurrentLocation();
function useCurrentLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position){
      var lat = position.coords.latitude;
      var lng = position.coords.longitude;
      useCurrentLocBtn.setAttribute("data-lat", lat);
      useCurrentLocBtn.setAttribute("data-long", lng);
      useCurrentLocBtn.classList.remove('disabled');
      useCurrentLocBtn.classList.add('enabled');
      btnTxt.innerHTML = "Use Current Location";

      //document.getElementById('userLat').value = lat;
      //document.getElementById('userLong').value = lng;
    });
  } else { 
    msgContainer.innerHTML = "Geolocation is not supported by this browser.";
  }
}


var destinationAddress = document.getElementById("dest2").value;
codeAddress(destinationAddress);
function codeAddress(addressVal) {
  geocoder = new google.maps.Geocoder();
  geocoder.geocode( { 'address':addressVal }, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      var lat2 = results[0].geometry.location.lat();
      var long2 = results[0].geometry.location.lng();
      document.getElementById('lat2').value = lat2;
      document.getElementById('long2').value = long2;
    } 
    else {
      alert("Geocode was not successful for the following reason: " + status);
    }
  });
}

jQuery(document).ready(function($){
  $("#checkBtn").on("click",function(e){
    e.preventDefault();
    var button = $(this);
    var userlat = $("#userLat").val();
    var userlong = $("#userLong").val();

    if( userlat && userlong ) {
      $.ajax({
        url:mypluginAjax.jsonUrl,
        data:$("#distanceform").serialize(),
        type: 'GET',
        dataType: "JSON",
        beforeSend: function(){
          
        },
        success: function(data){
          if(data) {
            setTimeout(function(){
              $("#msg").html(data);
            },500);
          }
        },
        complete:function(){
          
        },
        error: function(xhr) {
          
        }
      });
    } else {
      var message = '<div class="error"><i class="fa fa-exclamation-triangle"></i> Type your location or click "User Current Location" button.</div>';
      $("#form-msg").html(message);
    }
  });

  /* Use current location */
  $("#useCurrentLocBtn").on("click",function(e){
    e.preventDefault();
    var userlat = $(this).attr("data-lat");
    var userlong = $(this).attr("data-long");

    $("#userLat").val(userlat);
    $("#userLong").val(userlong);
    var api = mypluginAjax.mapAPI;

    var jsonURL = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+userlat+','+userlong+'&sensor=true/false&key='+api;
    $.ajax({
      url:jsonURL,
      type: 'GET',
      dataType: "JSON",
      beforeSend: function(){
        
      },
      success: function(data){
        if( typeof data.results!="undefined" && data.results.length>0 ) {
          var result = data.results[0];
          var userAddress = result.formatted_address;
          $("#userloc").val(userAddress);
          $("#form-msg").html("");
        }
      },
      error: function(xhr) {
        
      }
    });

  });

});

