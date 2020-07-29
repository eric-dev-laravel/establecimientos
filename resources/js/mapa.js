
    import { OpenStreetMapProvider } from 'leaflet-geosearch';
    const provider = new OpenStreetMapProvider();

    document.addEventListener('DOMContentLoaded', () => {

        if(document.querySelector('#mapa')){
            const lat = document.querySelector('#lat').value === '' ? 20.666332695977 : document.querySelector('#lat').value;
            const lng = document.querySelector('#lng').value === '' ? -103.392177745699 : document.querySelector('#lng').value;

            const mapa = L.map('mapa').setView([lat, lng], 16);

            //Eliminar pines previos
            const markers = new L.FeatureGroup().addTo(mapa);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mapa);

            let marker;

            // agregar el pin
            marker = new L.marker([lat, lng], {
                draggable: true,
                autoPan: true
            }).addTo(mapa);

            //Agregar al contenedor de markers el nuevo pin
            markers.addLayer(marker);

            //Geocode Service
            const geocodeService = L.esri.Geocoding.geocodeService();

            //Variable del buscador
            const buscador = document.querySelector('#formbuscador');
            buscador.addEventListener('blur', buscarDireccion);

            reubicarPin(marker);

            function reubicarPin(marker) {
                //Detectar movimiento del marker
                marker.on('moveend' ,function (e) {
                    marker = e.target;

                    const posicion = marker.getLatLng();

                    //Centar mapa automatico
                    mapa.panTo( new L.LatLng(posicion.lat, posicion.lng) );

                    //Reverse Geocoding, cuando el usuario mueve el pin
                    geocodeService.reverse().latlng(posicion, 16).run(function (error, resultado) {
                        //console.log(error);

                        //console.log(resultado.address);

                        marker.bindPopup(resultado.address.LongLabel);
                        marker.openPopup();

                        //Llenado de campos
                        llenarInputs(resultado);
                    });

                });
            }

            function llenarInputs(resultado) {
                //console.log(resultado);
                document.querySelector('#direccion').value = resultado.address.Address || '';
                document.querySelector('#colonia').value = resultado.address.Neighborhood || '';

                document.querySelector('#lat').value = resultado.latlng.lat || '';
                document.querySelector('#lng').value = resultado.latlng.lng || '';
            }

            function buscarDireccion(e) {
                if (e.target.value.length > 1) {
                    provider.search({query: e.target.value + ' Guadalajara MX '})
                        .then(resultado => {
                            if( resultado ) {
                                //Limpiar pines previos
                                markers.clearLayers();

                                //Reverse Geocoding, cuando el usuario mueve el pin
                                geocodeService.reverse().latlng(resultado[0].bounds[0], 16).run(function (error, resultado) {
                                    //llenar los inputs
                                    llenarInputs(resultado);

                                    //Centar el mapa
                                    mapa.setView(resultado.latlng);

                                    //Agrgar el pin
                                    marker = new L.marker(resultado.latlng, {
                                        draggable: true,
                                        autoPan: true
                                    }).addTo(mapa);

                                    //Agregar al contenedor de markers el nuevo pin
                                    markers.addLayer(marker);

                                    //mover el pin
                                    reubicarPin(marker);
                                });
                            }
                        })
                        .catch(error => {
                            //console.log(error);
                        });
                }
            }

        }
    });
