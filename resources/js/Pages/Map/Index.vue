<template>
    <MainLayout>
        <div ref="mapContainer" class="map-container"></div>
    </MainLayout>
</template>

<script setup lang="ts">
import mapboxgl from "mapbox-gl";
import { onMounted, ref } from "vue";
import "mapbox-gl/dist/mapbox-gl.css";
import MainLayout from "@/Layouts/MainLayout.vue";

mapboxgl.accessToken =
    "pk.eyJ1Ijoic29udmgwNjEwIiwiYSI6ImNsd243Y2JqYzF5Zm0yanFtdnFldGViZGYifQ.IQv7f-DlQ8p_yWkwaa2UYA";
const mapContainer = ref();
let map: any = null;

const coordStreet = [
    [106.65656967668332, 10.788345590010692],
    [106.65633981621085, 10.787891601865596],
    [106.65627062925398, 10.78783981961],
    [106.65616849612803, 10.787800982912955],
    [106.65611248763867, 10.787775091779537],
    [106.65607624685282, 10.787619744926772],
    [106.65604330068174, 10.787506471130058],
    [106.65601364912965, 10.787389960893677],
    [106.65601035451272, 10.78711163070352],
    [106.6560498899155, 10.786885082683582],
];

const geojsonLine = {
    type: "Feature",
    properties: {},
    geometry: {
        type: "LineString",
        coordinates: coordStreet,
    },
};
const geojsonText = {
    type: "Feature",
    properties: {
        title: "label",
    },
    geometry: {
        type: "Point",
        coordinates: [106.65611248763867, 10.787775091779537],
    },
};
const geojsonImage = {
    type: "FeatureCollection",
    features: [
        {
            type: "Feature",
            geometry: {
                type: "Point",
                coordinates: [106.65716, 10.78729],
            },
        },
    ],
};

onMounted(() => {
    map = new mapboxgl.Map({
        container: mapContainer.value,
        style: "mapbox://styles/mapbox/streets-v12",
        center: [106.65656967668332, 10.788345590010692],
        zoom: 16,
    });

    map.on("style.load", function () {
        map.addSource("route", {
            type: "geojson",
            data: geojsonLine,
            generateId: true,
        });
        map.addSource("label", {
            type: "geojson",
            data: geojsonText,
            generateId: true,
        });

        map.addLayer({
            id: "duong-van-coi",
            type: "line",
            source: "route",
            layout: {
                "line-join": "round",
                "line-cap": "round",
            },
            paint: {
                "line-color": [
                    "case",
                    ["boolean", ["feature-state", "click"], false],
                    "#64bdbb",
                    "#888888",
                ],
                "line-width": 8,
            },
        });
        map.addLayer({
            id: "label-style",
            type: "symbol",
            source: "label",
            layout: {
                "text-field": "Custom tên đường",
            },
            paint: {
                "text-color": "blue",
            },
        });

        let streetId: any = null;
        map.on("click", function () {
            if (streetId !== null) {
                map.setFeatureState(
                    { source: "route", id: streetId },
                    { click: false },
                );
                streetId = null;
            }
        });
        // Click vào đường
        map.on("click", "duong-van-coi", function (e: any) {
            new mapboxgl.Popup()
                .setLngLat(e.lngLat)
                .setHTML("Đường Vân Côi")
                .addTo(map);

            if (e.features.length > 0) {
                streetId = e.features[0].id;
                map.setFeatureState({ source: "route", id: streetId }, { click: true });
            }
        });

        // Load hình
        map.loadImage("/public/cat.png", (error: any, image: any) => {
            console.log(error, image);
            if (error) throw error;
            map.addImage("cat", image);
            map.addSource("point", { type: "geojson", data: geojsonImage });
            map.addLayer({
                id: "hinh-anh",
                type: "symbol",
                source: "point",
                layout: {
                    "icon-image": "cat",
                    "icon-size": 0.25,
                },
            });

            const popup = new mapboxgl.Popup({
                closeButton: false,
                closeOnClick: false,
            });

            // Click vào hình
            map.on("mousemove", "hinh-anh", function (e: any) {
                map.getCanvas().style.cursor = "pointer";

                const coordinates = e.features[0].geometry.coordinates.slice();

                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                }

                popup.setLngLat(coordinates).setHTML("Con mèo").addTo(map);
            });

            map.on("mouseleave", "hinh-anh", () => {
                map.getCanvas().style.cursor = "";
                popup.remove();
            });
        });
    });
});
</script>

<style scoped>
.map-container {
    flex: 1;
    width: 100vw;
    height: 100vh;
}
</style>
