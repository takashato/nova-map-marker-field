<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <div :id="mapId" class="base-map"></div>
            <div v-if="richValue.latitude && richValue.longitude" class="mt-2">
                Longitude: {{ richValue.longitude }}<br/>
                Latitude: {{ richValue.latitude }}
            </div>
        </template>
    </DefaultField>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import mapboxgl from "mapbox-gl";

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            map: null,
            marker: null,
            richValue: {
                longitude: null,
                latitude: null,
            },
        }
    },

    mounted() {
        mapboxgl.accessToken = this.field.accessToken;

        const mapOptions = {
            container: this.mapId,
            style: "mapbox://styles/mapbox/streets-v11",
            center: [this.field.longitude, this.field.latitude],
            zoom: this.field.zoom || 10,
        }
        this.map = new mapboxgl.Map(mapOptions);

        this.marker = new mapboxgl.Marker()
            .setLngLat([this.field.longitude, this.field.latitude])
            .setDraggable(true)
            .on('dragend', (e) => {
                this.setValue({
                    longitude: e.target.getLngLat().lng,
                    latitude: e.target.getLngLat().lat,
                });
            })
            .addTo(this.map);

        this.map.on('click', (e) => {
            this.marker.setLngLat(e.lngLat);
            this.setValue({
                longitude: e.lngLat.lng,
                latitude: e.lngLat.lat,
            });
        });

        this.map.addControl(new mapboxgl.NavigationControl());

        // Add geolocate control to the map.
        this.map.addControl(
            new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
// When active the map will receive updates to the device's location as it changes.
                trackUserLocation: true,
// Draw an arrow next to the location dot to indicate which direction the device is heading.
                showUserHeading: true
            })
        );
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.richValue = {
                longitude: this.field.longitude,
                latitude: this.field.latitude,
            };
            this.value = JSON.stringify(this.richValue);
        },

        setValue(value) {
            this.richValue = value;
            this.value = JSON.stringify(this.richValue);
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },
    },

    computed: {
        mapId() {
            return 'map-' + (this.field.mapId || 'default');
        },
    },
}
</script>

<style>
.base-map {
    min-height: 500px;
}
</style>
