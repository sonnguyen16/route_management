<template>
    <MainLayout>
        <div class="cont">
            <div ref="mapContainer" class="map-container"></div>
            <Popup :selectedRoute="selectedRoute"
                   :is-sheet-open="isSheetOpen"
                   @close-sheet="closeSheet"
            />
        </div>
    </MainLayout>
</template>

<script setup>
import mapboxgl from "mapbox-gl";
import { onMounted, ref, watch } from "vue";
import "mapbox-gl/dist/mapbox-gl.css";
import MainLayout from "@/Layouts/MainLayout.vue";
import Popup from "@/Pages/Map/Popup.vue";

const props = defineProps({
    tuyen_duong: Object
})

const selectedRoute = ref(null);
const isSheetOpen = ref(false);
const selectedRouteId = ref(null);

mapboxgl.accessToken =
    "pk.eyJ1Ijoic29udmgwNjEwIiwiYSI6ImNsd243Y2JqYzF5Zm0yanFtdnFldGViZGYifQ.IQv7f-DlQ8p_yWkwaa2UYA";

const mapContainer = ref();
let map = null;

onMounted(() => {
    map = new mapboxgl.Map({
        container: mapContainer.value,
        style: "mapbox://styles/mapbox/streets-v12",
        center: [
            props.tuyen_duong[0].toa_do[0].lng,
            props.tuyen_duong[0].toa_do[0].lat
        ],
        zoom: 17,
    });

    map.on("load", () => {
        addAllRoutes();
    });

    map.on("click",  (e) => {
        const features = map.queryRenderedFeatures(e.point, {
            layers: props.tuyen_duong.map((_) => `${_.id}`)
        });

        if (features.length > 0) {
            const clickedRoute = features[0];
            const routeId = clickedRoute.properties.id;
            selectedRouteId.value = routeId;

            const route = props.tuyen_duong.find(r => String(r.id) === routeId);

            if (!isSheetOpen.value) {
                map.setPaintProperty(clickedRoute.layer.id, "line-color", "#ff0000");
                isSheetOpen.value = true;
                selectedRoute.value = route;
            } else {
                map.setPaintProperty(clickedRoute.layer.id, "line-color", "#0000ff");
                isSheetOpen.value = false;
                selectedRoute.value = null;
            }
        }
    });

});

watch(props.tuyen_duong, () => {
    if (map && map.isStyleLoaded()) {
        addAllRoutes();
    }
});

const closeSheet = () => {
    isSheetOpen.value = false;
    map.setPaintProperty(selectedRoute.value.id, "line-color", "#0000ff");
};

const addAllRoutes = () => {
    if (!props.tuyen_duong || props.tuyen_duong.length === 0) return;

    props.tuyen_duong.forEach((route) => {
        const coordinates = route.toa_do.map((point) => [point.lng, point.lat]);

        const routeId = `${route.id}`;
        const routeNameId = `route-name-${route.id}`;

        const geojson = {
            type: "Feature",
            geometry: {
                type: "LineString",
                coordinates: coordinates
            },
            properties: {
                id: routeId,
                ten_duong: route?.ten || `Đường ${route.id}`
            }
        };


        if (map.getSource(routeId)) {
            map.getSource(routeId).setData(geojson);
        } else {
            map.addSource(routeId, {
                type: "geojson",
                data: geojson
            });

            map.addLayer({
                id: routeId,
                type: "line",
                source: routeId,
                layout: {
                    "line-join": "round",
                    "line-cap": "round"
                },
                paint: {
                    "line-color": "#0000ff",
                    "line-width": 5
                }
            });

            map.addLayer({
                id: routeNameId,
                type: "symbol",
                source: routeId,
                layout: {
                    "symbol-placement": "line",
                    "text-field": ["get", "ten_duong"],
                    "text-size": 12,
                    "text-offset": [0, 0.5],
                    "text-rotation-alignment": "map"
                },
                paint: {
                    "text-color": "#000000"
                }
            });
        }
    });
};

</script>

<style scoped>
.map-container {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

.cont {
    position: relative;
    width: 100%;
    height: 100vh;
}
</style>
