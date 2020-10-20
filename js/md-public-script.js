(function (window, document, $, td, te, tf, tg, ts, tt, config, dbug) {
    delete tribe_ev.fn.map_add_marker;

    jQuery.extend(tribe_ev.fn, {
        map_add_marker: function (lat, lng, title, address, link) {

            var myLatlng = new google.maps.LatLng(lat, lng);
            var title = title.replace(/<(?:.|\n)*?>/gm, '');

            var marker = {
                position: myLatlng,
                map: tg.map,
                title: title
            };

            // If we have a Map Pin set, we use it
            if ('undefined' !== GeoLoc.pin_url && GeoLoc.pin_url) {
                marker.icon = GeoLoc.pin_url;
            }

            // Overwrite with an actual object
            marker = new google.maps.Marker(marker);

            var infoWindow = new google.maps.InfoWindow();

            var content_title = title;
            if (link) {
                content_title = $('<div/>').append($("<strong/>").text(title)).html();
            }

            var content = TribeEventsPro.map_tooltip_event + content_title;

            if (address) {
                content = content + "<br/>" + TribeEventsPro.map_tooltip_address + address;
            }

            infoWindow.setContent(content);

            google.maps.event.addListener(marker, 'click', function (event) {
                infoWindow.open(tg.map, marker);
            });

            tg.markers.push(marker);

            if (tg.refine) {
                marker.setVisible(false);
            }
            tg.bounds.extend(myLatlng);
        }
    });
})(window, document, jQuery, tribe_ev.data, tribe_ev.events, tribe_ev.fn, tribe_ev.geoloc, tribe_ev.state, tribe_ev.tests, tribe_js_config, tribe_debug);