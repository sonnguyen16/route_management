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
import {danhMucTaiLieuOptions, iconFileTypes} from "@/Constants/constants.js";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    tuyen_duong: Object,
    toa_do_khac: Object
})

const selectedRoute = ref(null);
const isSheetOpen = ref(false);
const selectedRouteId = ref(null);

mapboxgl.accessToken =
    "pk.eyJ1Ijoic29udmgwNjEwIiwiYSI6ImNsd243Y2JqYzF5Zm0yanFtdnFldGViZGYifQ.IQv7f-DlQ8p_yWkwaa2UYA";

const mapContainer = ref();
let map = null;

onMounted(() => {
    console.log(props.tuyen_duong);
    // Khởi tao map
    map = new mapboxgl.Map({
        container: mapContainer.value,
        style: "mapbox://styles/mapbox/streets-v12",
        center: [
            props.tuyen_duong[0].toa_do[0].lng,
            props.tuyen_duong[0].toa_do[0].lat
        ],
        zoom: 17,
    });

    // Thêm đường các các điểm km, cầu vào map
    map.on("load", () => {
       // addAllRoutes();

        for (let i = 0; i < props.toa_do_khac?.length ; i++) {
            const img_url = props.toa_do_khac[i]?.loai === 2 ? '/bridge3.png' : '/Km33.png';
            const iconId = props.toa_do_khac[i]?.loai === 2 ? 'bridge-marker-icon' : 'km-marker-icon';
            addMarker(props.toa_do_khac[i], props.toa_do_khac[i]?.ten, img_url, iconId);
        }
    });

    // Thêm sự kiện click vào map hiện thông tin đường
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

    // Load các icon km và cầu
    map.loadImage('/place.png', (error, image) => {
        if (error) throw error;
        map.addImage('km-marker-icon', image);
    });

    map.loadImage('/place.png', (error, image) => {
        if (error) throw error;
        map.addImage('bridge-marker-icon', image);
    });

});

watch(props.tuyen_duong, () => {
    if (map && map.isStyleLoaded()) {
       // addAllRoutes();
    }
});

const closeSheet = () => {
    isSheetOpen.value = false;
    map.setPaintProperty(String(selectedRoute.value.id), "line-color", "#0000ff");
    selectedRoute.value = null;
};

const formFile = useForm({
    file: null,
    toa_do_id: '',
    danh_muc: danhMucTaiLieuOptions.toa_do
})

const formMota = useForm({
    mo_ta: '',
    toa_do_id: '',
})
// Hàm thêm marker
const addMarker = (point, name, iconUrl, iconId) => {
    // Tạo geojson
    const markerGeojson = {
        type: "Feature",
        geometry: {
            type: "Point",
            coordinates: [point?.lng, point?.lat]
        },
        properties: {
            name: name,
            image: iconUrl
        }
    };

    if (!map.getSource(`marker-${point.id}`)) {
        // Thêm source marker
        map.addSource(`marker-${point.id}`, {
            type: "geojson",
            data: markerGeojson
        });

        // Thêm layer marker
        map.addLayer({
            id: `marker-${point.id}`,
            type: "symbol",
            source: `marker-${point.id}`,
            layout: {
                "icon-image": iconId,
                "icon-size": .4,
                "text-field": name,
                "text-size": 10,
                "text-color": "blue",
                "text-offset": [0, 1.25],
                "text-anchor": "top"
            },
            paint: {
                   // "text-color": "#000000",
                    "text-color": "blue", // màu của chữ
                    "text-halo-color": "white", // đường viền của chữ
                    "text-halo-width": 1, // độ đậm của đường viền
                    'line-opacity':     0.5,
                    'line-dasharray':   [0, 2],
                }
        });

        // Thêm sự kiện click vào marker
        map.on('click', `marker-${point.id}`, (e) => {
            const coordinates = e.lngLat;
            formFile.toa_do_id = point.id;
            formMota.toa_do_id = point.id;
            const tai_lieu = props.toa_do_khac.find((item) => item.id === point.id).tai_lieu;
            const mo_ta = props.toa_do_khac.find((item) => item.id === point.id).mo_ta;

            // Hiện popup
            new mapboxgl.Popup({offset: 50, closeButton: false})
                .setLngLat(coordinates)
                .setMaxWidth("300px")
                .setHTML(`
                    <div style="min-width: 250px">
                        <div class="d-flex gap-3 items-center">
                            <img src="${iconUrl}" alt="${name}" style="width: 30px; height: 30px;"/>
                            <h5>${name}</h5>
                        </div>
                        <div class="d-flex align-items-start mt-2 gap-2">
                            <a tabindex="-1" class="flex-1 cursor-pointer text-gray-500" id="mota-${point.id}">
                                ${mo_ta || 'Nhập mô tả...'}
                                <i class="fas fa-edit"></i>
                            </a>
                            <textarea class="hidden w-100 border-gray-300 rounded" id="mota-input-${point.id}">${mo_ta || ''}</textarea>
                            <button id="luuMoTa-${point.id}" class=" hidden btn-sm btn-success">Lưu</button>
                        </div>
                        <label class="btn-success w-100 btn btn-sm mb-0 mt-3"
                               for="fileUpload-${point.id}">
                            <span class="fw-normal">Chọn file upload</span>
                        </label>
                        <input type="file" class="w-100 hidden" multiple id="fileUpload-${point.id}"/>
                        <div class="mt-2" id="listFile-${point.id}">
                            ${tai_lieu?.length > 0 ? tai_lieu.map((file) => {
                                 return `
                                    <div class="">
                                      <a class="d-block" href="/storage/${file.file}" target="_blank">
                                        <i class="fas ${iconFileTypes[file.loai]}"></i>
                                        ${file.ten}
                                      </a>
                                    </div>
                                `
                                }).join('') : '<p>Không có tài liệu nào.</p>'}
                        </div>
                    </div>
                `)
                .addTo(map);

                const moTaElement = document.getElementById(`mota-${point.id}`);
                if (moTaElement) {
                    moTaElement.addEventListener('click', () => {
                        const inputElement = document.getElementById(`mota-input-${point.id}`);
                        const luuMoTaElement = document.getElementById(`luuMoTa-${point.id}`);
                        inputElement.classList.remove('hidden');
                        luuMoTaElement.classList.remove('hidden');
                        moTaElement.classList.add('hidden');
                    });
                }

                // Lưu mô tả và upload file
                const luuMoTaElement = document.getElementById(`luuMoTa-${point.id}`);
                if (luuMoTaElement) {
                    luuMoTaElement.addEventListener('click', () => {
                        const moTaElement = document.getElementById(`mota-input-${point.id}`);
                        formMota.mo_ta = moTaElement.value;
                        formMota.post(route('map.update-mota'), {
                            onSuccess: () => {
                                const moTaElement = document.getElementById(`mota-${point.id}`);
                                moTaElement.innerHTML =
                                    `${formMota.mo_ta}
                                    <i class="fas fa-edit"></i>`;
                                moTaElement.classList.remove('hidden');
                                moTaElement.nextElementSibling.classList.add('hidden');
                                moTaElement.nextElementSibling.nextElementSibling.classList.add('hidden');
                            },
                        })
                    });
                }

                const inputElement = document.getElementById(`fileUpload-${point.id}`);
                if (inputElement) {
                    inputElement.addEventListener('change', (event) => {
                        formFile.file = event.target.files;
                        formFile.post(route('tai-lieu.store'), {
                            onSuccess: () => {
                                const listFileElement = document.getElementById(`listFile-${point.id}`);
                                listFileElement.innerHTML = '';
                                props.toa_do_khac.find((item) => item.id === point.id).tai_lieu.forEach((file) => {
                                    listFileElement.innerHTML +=
                                    `<div class="">
                                      <a class="d-block" href="/storage/${file.file}" target="_blank">
                                        <i class="fas ${iconFileTypes[file.loai]}"></i>
                                        ${file.ten}
                                      </a>
                                    </div>`;
                                });
                            },
                        });
                    });
                }
        });

    }
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
