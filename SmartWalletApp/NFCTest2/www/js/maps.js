function initialize() {


    jQuery.ajax({
        type: "GET",
        dataType: 'jsonp',
        url: 'http://titansmora.org/logs/last',
        success: function (obj, textstatus) {

            var myCenter = new google.maps.LatLng(obj.lat, obj.lan);
            var mapProp = {
                center: myCenter,
                zoom: 18,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(obj.lat, obj.lan),
                animation: google.maps.Animation.BOUNCE,
                icon: 'icon/purse-icon.png'
            });

            marker.setMap(map);
        }
    });

}
google.maps.event.addDomListener(window, 'load', initialize);