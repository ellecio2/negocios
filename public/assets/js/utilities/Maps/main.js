let marker;
let map;
const MARKER_IMAGE_URL =
    "https://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png";

function createMap(mapId, latlng, zoomLevel) {
    const mapOptions = {
        center: latlng,
        zoom: zoomLevel,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        panControl: true,
        panControlOptions: {
            position: google.maps.ControlPosition.TOP_RIGHT,
        },
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE,
            position: google.maps.ControlPosition.TOP_LEFT,
        },
    };

    return new google.maps.Map(document.getElementById(mapId), mapOptions);
}

function createMarker(map, latlng) {
    const marker = new google.maps.Marker({
        position: latlng,
        map: map,
        icon: MARKER_IMAGE_URL,
        draggable: true,
    });

    google.maps.event.addListener(marker, "dragend", function (event) {
        $("input[name='latitude']").val(event.latLng.lat());
        $("input[name='longitude']").val(event.latLng.lng());
    });

    return marker;
}

async function showMapnew(mapId) {
    try {
        const result = await navigator.permissions.query({
            name: "geolocation",
        });
        let latlng;
        if (result.state === "granted" || result.state === "prompt") {
            const position = await new Promise((resolve, reject) =>
                navigator.geolocation.getCurrentPosition(resolve, reject),
            );
            latlng = new google.maps.LatLng(
                position.coords.latitude,
                position.coords.longitude,
            );
        } else {
            latlng = new google.maps.LatLng(18.4801193, -69.9880795);
        }
        map = createMap(mapId, latlng, 10);
        marker = createMarker(map, latlng);
    } catch (error) {
        console.log("Error al obtener la ubicación: ", error);
    }

    const input = document.getElementById(
        mapId.replace("map_canvas", "searchTextField"),
    );
    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ["geocode"],
    });

    autocomplete.bindTo("bounds", map);

    const infowindow = new google.maps.InfoWindow();

    google.maps.event.addListener(
        autocomplete,
        "place_changed",
        function (event) {
            infowindow.close();
            const place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            moveMarker(place.name, place.geometry.location, infowindow);
            $(".MapLat").val(place.geometry.location.lat());
            $(".MapLon").val(place.geometry.location.lng());
            // Mostrar dirección, estado, país, ciudad y código postal
            $("input[name='address_2']").val(place.formatted_address);
            $("input[name='address']").val(place.formatted_address);
            $("input[name='country']").val(
                getAddressComponent(place, "country"),
            );
            $("input[name='state']").val(
                getAddressComponent(place, "administrative_area_level_1"),
            );
            $("input[name='city']").val(getAddressComponent(place, "locality"));
            $("input[name='postalCode']").val(
                getAddressComponent(place, "postal_code"),
            );
        },
    );

    google.maps.event.addListener(map, "click", function (event) {
        $(".MapLat").val(event.latLng.lat());
        $(".MapLon").val(event.latLng.lng());
        infowindow.close();
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode(
            {
                latLng: event.latLng,
            },
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat(),
                        lng = results[0].geometry.location.lng(),
                        placeName = results[0].address_components[0].long_name,
                        latlng = new google.maps.LatLng(lat, lng);
                    moveMarker(placeName, latlng, infowindow);
                    $("#searchTextField").val(results[0].formatted_address);
                    $("input[name='address_2']").val(
                        results[0].formatted_address,
                    );
                    $("input[name='address']").val(
                        results[0].formatted_address,
                    );
                    $("input[name='latitude']").val(latlng.lat());
                    $("input[name='longitude']").val(latlng.lng());
                    // Mostrar dirección, estado, país, ciudad y código postal
                    $("input[name='country']").val(
                        getAddressComponent(results[0], "country"),
                    );
                    $("input[name='state']").val(
                        getAddressComponent(
                            results[0],
                            "administrative_area_level_1",
                        ),
                    );
                    $("input[name='city']").val(
                        getAddressComponent(results[0], "locality"),
                    );
                    $("input[name='postalCode']").val(
                        getAddressComponent(results[0], "postal_code"),
                    );
                }
            },
        );
    });
}

function getAddressComponent(place, component) {
    for (let i = 0; i < place.address_components.length; i++) {
        const addressType = place.address_components[i].types[0];
        if (addressType === component) {
            return place.address_components[i].long_name;
        }
    }
    return "";
}

function moveMarker(placeName, latlng, infowindow) {
    marker.setIcon(MARKER_IMAGE_URL);
    marker.setPosition(latlng);
    infowindow.setContent(placeName);
}

function showMapedit(mapId, longitude, latitude) {
    console.log("edit", mapId, longitude, latitude);
    const latlng = new google.maps.LatLng(latitude, longitude);
    const MARKER_IMAGE_URL =
        "https://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png";
    const mapOptions = {
        center: latlng,
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        panControl: true,
        panControlOptions: {
            position: google.maps.ControlPosition.TOP_RIGHT,
        },
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE,
            position: google.maps.ControlPosition.TOP_LEFT,
        },
    };
    const map = new google.maps.Map(document.getElementById(mapId), mapOptions);
    const marker = new google.maps.Marker({
        position: latlng,
        map: map,
        icon: MARKER_IMAGE_URL,
        draggable: true,
    });
    google.maps.event.addListener(marker, "dragend", function (event) {
        $("input[name='latitude']").val(event.latLng.lat());
        $("input[name='longitude']").val(event.latLng.lng());
    });
    const input = document.getElementById(
        mapId.replace("map_canvas_", "searchTextField_"),
    );
    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ["geocode"],
    });
    autocomplete.bindTo("bounds", map);
    const infowindow = new google.maps.InfoWindow();
    console.log(infowindow, ' infowindow');
    google.maps.event.addListener(
        autocomplete,
        "place_changed",
        function (event) {
            infowindow.close();
            const place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            moveMarker(place.name, place.geometry.location);
            $(".MapLat").val(place.geometry.location.lat());
            $(".MapLon").val(place.geometry.location.lng());
            // Mostrar dirección, estado, país, ciudad y código postal
            $("input[name='address']").val(place.formatted_address);
            $("input[name='country']").val(
                getAddressComponent(place, "country"),
            );
            $("input[name='state']").val(
                getAddressComponent(place, "administrative_area_level_1"),
            );
            $("input[name='city']").val(getAddressComponent(place, "locality"));
            $("input[name='postalCode']").val(
                getAddressComponent(place, "postal_code"),
            );
        },
    );
    google.maps.event.addListener(map, "click", function (event) {
        $(".MapLat").val(event.latLng.lat());
        $(".MapLon").val(event.latLng.lng());
        infowindow.close();
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode(
            {
                latLng: event.latLng,
            },
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat(),
                        lng = results[0].geometry.location.lng(),
                        placeName = results[0].address_components[0].long_name,
                        latlng = new google.maps.LatLng(lat, lng);
                    moveMarker(placeName, latlng);
                    $("#searchTextField").val(results[0].formatted_address);
                    $("input[name='address']").val(
                        results[0].formatted_address,
                    );
                    $("input[name='latitude']").val(latlng.lat());
                    $("input[name='longitude']").val(latlng.lng());
                    // Mostrar dirección, estado, país, ciudad y código postal
                    $("input[name='country']").val(
                        getAddressComponent(results[0], "country"),
                    );
                    $("input[name='state']").val(
                        getAddressComponent(
                            results[0],
                            "administrative_area_level_1",
                        ),
                    );
                    $("input[name='city']").val(
                        getAddressComponent(results[0], "locality"),
                    );
                    $("input[name='postalCode']").val(
                        getAddressComponent(results[0], "postal_code"),
                    );
                }
            },
        );
    });

    // Función para obtener el valor de un componente de dirección específico
    function getAddressComponent(place, component) {
        for (let i = 0; i < place.address_components.length; i++) {
            const addressType = place.address_components[i].types[0];
            if (addressType === component) {
                return place.address_components[i].long_name;
            }
        }
        return "";
    }

    function moveMarker(placeName, latlng) {
        marker.setIcon(MARKER_IMAGE_URL);
        marker.setPosition(latlng);
        infowindow.setContent(placeName);
    }
}
