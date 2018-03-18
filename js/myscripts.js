

$(document).ready(function () {
	
	$('[data-toggle="tooltip"]').tooltip();

	var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: {lat: 44.545817, lng: 6.275324},
          mapTypeControl: false,
          streetViewControl: false,
          rotateControl: false,
          fullscreenControl: false
    });

	var markers = [];

	$('.service-container').each(function(){
		var lat = $(this).data('lat');
		var lng = $(this).data('lng');
		var title = $(this).data('title');
		var content = $(this).data('content');
		var link = $(this).data('link');
		var infoWindowContent = '<h5>'+title+'</h4><p>'+content+'</p>'+'<a class="btn btn-sm btn-outline-secondary" href="'+link+'">Voir plus</a>';
		var myLatLng = new google.maps.LatLng(lat, lng);
		
		var infowindow = new google.maps.InfoWindow({
			position: myLatLng,
			content: infoWindowContent,
			maxWidth: 300
		})

		var marker = new google.maps.Marker({
    		map: map,
    		position: myLatLng,
    		icon: '../img/customMarker.svg',
    		animation: google.maps.Animation.DROP,
    		infowindow: infowindow
		});

		markers.push(marker);

		marker.addListener('click', function(){
			hideAllInfoWindows(map);
			this.infowindow.open(map, this);
		})
	});

	function hideAllInfoWindows(map){
		markers.forEach(function(marker) {
     		marker.infowindow.close();
  		}); 
	}

	map.addListener('click', function(){
		hideAllInfoWindows(this);
	})

		
});


