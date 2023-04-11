<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <div :id="mapId" class="base-map"></div>
        </template>
    </PanelItem>
</template>

<script>
import mapboxgl from 'mapbox-gl';
export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
    data() {
        return {
            map: null
        };
    },
    mounted() {
        mapboxgl.accessToken = this.field.accessToken;

        const mapOptions = {
            container: this.mapId,
            style: "mapbox://styles/mapbox/streets-v11",
            center: [this.field.longitude, this.field.latitude],
            zoom: this.field.zoom,
        }
        this.map = new mapboxgl.Map(mapOptions);

        const marker = new mapboxgl.Marker()
            .setLngLat([this.field.longitude, this.field.latitude])
            .addTo(this.map);

        this.map.addControl(new mapboxgl.NavigationControl());
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
