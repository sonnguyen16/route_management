<template>
  <MainLayout>
    <div class="cont position-relative">
      <div ref="mapContainer" class="map-container"></div>
      <Popup :selectedRoute="selectedRoute" :is-sheet-open="isSheetOpen" @close-sheet="closeSheet" />
      <!-- list checkbox filter tyoe -->
      <div class="absolute top-0 left-0 p-3 z-10">
        <div class="bg-white p-3 rounded-lg shadow-lg">
          <div class="">
            <h3 class="text-lg font-semibold">Bộ lọc</h3>
          </div>
          <div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="">Đường chính</label>
              </div>
              <div class="w-6 h-6 rounded-sm" style="background-color: #65c0ed"></div>
            </div>
            <div class="grid grid-cols-2 gap-3" v-for="t in type" :key="t.type">
              <div>
                <input type="checkbox" class="rounded-sm" :id="t.type" :value="t.type" v-model="filterType" />
                <label :for="t.type" class="ml-2">{{ t.name }}</label>
              </div>
              <div class="w-6 h-6 rounded-sm" :style="{ backgroundColor: t.color }"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import mapboxgl from 'mapbox-gl'
import { onMounted, ref, watch } from 'vue'
import 'mapbox-gl/dist/mapbox-gl.css'
import MainLayout from '@/Layouts/MainLayout.vue'
import Popup from '@/Pages/Map/Popup.vue'
import { danhMucTaiLieuOptions, iconFileTypes } from '@/Constants/constants.js'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  tuyen_duong: Object,
  sua_chua: Object,
  duong_cam: Object,
  giam_sat: Object,
  toc_do: Object,
  cap_phep: Object,
  cau: Object,
  den_giao_thong: Object
})

const type = [
  {
    type: 'sua_chua',
    name: 'Sửa chữa',
    color: '#00ff00'
  },
  {
    type: 'duong_cam',
    name: 'Đường cấm',
    color: '#ff0000'
  },
  {
    type: 'giam_sat',
    name: 'Giám sát',
    color: '#0000ff'
  },
  {
    type: 'toc_do',
    name: 'Tốc độ',
    color: '#ed9e4d'
  },
  {
    type: 'cap_phep',
    name: 'Cấp phép',
    color: '#ffff00'
  }
]

const filterType = ref(['sua_chua'])

watch(filterType, (newValue) => {
  type.forEach((t) => {
    if (!newValue.includes(t.type)) {
      removeAllRoutes(t.type)
    }
  })
  newValue.forEach((type) => {
    addAllRoutes(type)
  })
})

const selectedRoute = ref(null)
const isSheetOpen = ref(false)

mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN

const mapContainer = ref()
let map = null

onMounted(() => {
  // Khởi tạo map
  map = new mapboxgl.Map({
    container: mapContainer.value,
    style: 'mapbox://styles/mapbox/streets-v12',
    center: [107.242997, 10.495088],
    zoom: 12
  })

  // Thêm đường các các điểm km, cầu vào map
  map.on('load', () => {
    map.loadImage('/bridge.png', (error, image) => {
      if (!error && !map.hasImage('bridge-marker-icon')) {
        map.addImage('bridge-marker-icon', image)
      }
    })

    map.loadImage('/traffic-light.png', (error, image) => {
      if (!error && !map.hasImage('traffic-light-marker-icon')) {
        map.addImage('traffic-light-marker-icon', image)
      }
    })

    filterType.value.forEach((type) => {
      addAllRoutes(type)
    })

    addAllRoutes('tuyen_duong')

    props.cau?.forEach((item) => {
      addMarker(item.lng, item.lat, item.ten, '/bridge.png', `bridge-marker-icon`, item.id)
    })

    props.den_giao_thong?.forEach((item) => {
      addMarker(item.lng, item.lat, item.nut_giao, '/traffic-light.png', `traffic-light-marker-icon`, item.id)
    })
  })
})

const closeSheet = () => {
  isSheetOpen.value = false
}

const getColor = (type) => {
  switch (type) {
    case 'tuyen_duong':
      return '#65c0ed'
    case 'sua_chua':
      return '#00ff00'
    case 'duong_cam':
      return '#ff0000'
    case 'giam_sat':
      return '#0000ff'
    case 'toc_do':
      return '#ed9e4d'
    case 'cap_phep':
      return '#ffff00'
    default:
      return '#000000'
  }
}

// Hàm vẽ đường và tên đường
const addAllRoutes = (type) => {
  if (!route || route.length === 0) return

  if (type === 'tuyen_duong') {
    props[type].forEach((route) => {
      const coordinates = route.toa_do.map((point) => [point.lng, point.lat])
      if (coordinates.length === 0) return

      const routeId = `${type}-${route.id}`
      const routeNameId = `route-name-${route.id}`

      const geojson = {
        type: 'Feature',
        geometry: {
          type: 'LineString',
          coordinates: coordinates
        },
        properties: {
          id: routeId,
          ten_duong: `Đường ${route.ten}` || `Đường ${route.id}`,
          tuyen_duong: JSON.stringify(route)
        }
      }

      if (map.getSource(routeId)) {
        map.getSource(routeId).setData(geojson)
      } else {
        map.addSource(routeId, {
          type: 'geojson',
          data: geojson
        })

        map.addLayer({
          id: routeId,
          type: 'line',
          source: routeId,
          layout: {
            'line-join': 'round',
            'line-cap': 'round'
          },
          paint: {
            'line-color': getColor(type),
            'line-width': 5
          }
        })

        map.addLayer({
          id: routeNameId,
          type: 'symbol',
          source: routeId,
          layout: {
            'icon-image': 'demo',
            'icon-size': 1,
            'icon-allow-overlap': true,
            'symbol-placement': 'line', // line-center, line, point // hiển thị nhiều lần hay ít lần
            'text-field': ['get', 'ten_duong'],
            'text-size': 12,
            'text-max-width': 10,
            'symbol-spacing': 2,
            'text-rotation-alignment': 'map',
            'text-keep-upright': false
          },
          paint: {
            'text-color': 'blue', // màu của chữ
            'text-halo-color': 'white', // đường viền của chữ
            'text-halo-width': 1 // độ đậm của đường viền
          }
        })
      }
    })
  }

  props[type].forEach((item) => {
    item.doan_duong?.forEach((route) => {
      const coordinates = route.toa_do.map((point) => [point.lng, point.lat])
      if (coordinates.length === 0) return

      const routeId = `${type}-${route.id}`
      const routeNameId = `route-name-${route.id}`

      const geojson = {
        type: 'Feature',
        geometry: {
          type: 'LineString',
          coordinates: coordinates
        },
        properties: {
          id: routeId,
          ten_duong: `Đường ${item.tuyen_duong ? item.tuyen_duong.ten : route.tuyen_duong.ten}` || `Đường ${route.id}`,
          tuyen_duong: JSON.stringify(item.tuyen_duong ? item.tuyen_duong : route.tuyen_duong)
        }
      }

      if (map.getSource(routeId)) {
        map.getSource(routeId).setData(geojson)
      } else {
        map.addSource(routeId, {
          type: 'geojson',
          data: geojson
        })

        map.addLayer({
          id: routeId,
          type: 'line',
          source: routeId,
          layout: {
            'line-join': 'round',
            'line-cap': 'round'
          },
          paint: {
            'line-color': getColor(type),
            'line-width': 5
          }
        })

        map.addLayer({
          id: routeNameId,
          type: 'symbol',
          source: routeId,
          layout: {
            'icon-image': 'demo',
            'icon-size': 1,
            'icon-allow-overlap': true,
            'symbol-placement': 'line', // line-center, line, point // hiển thị nhiều lần hay ít lần
            'text-field': ['get', 'ten_duong'],
            'text-size': 12,
            'text-max-width': 10,
            'symbol-spacing': 2,
            'text-rotation-alignment': 'map',
            'text-keep-upright': false
          },
          paint: {
            'text-color': 'blue', // màu của chữ
            'text-halo-color': 'white', // đường viền của chữ
            'text-halo-width': 1 // độ đậm của đường viền
          }
        })
      }
      //   addRouteClickEvent(routeId)
    })
  })

  moveMarkersToTop()
}

const addRouteClickEvent = (routeId) => {
  console.log(routeId)
  map.on('click', routeId, (e) => {
    const properties = e.features[0].properties
    const tuyenDuong = JSON.parse(properties.tuyen_duong) // Chuyển từ string về object
    selectedRoute.value = tuyenDuong
    isSheetOpen.value = true
  })
}

const removeAllRoutes = (type) => {
  props[type].forEach((item) => {
    item.doan_duong?.forEach((route) => {
      const routeId = `${type}-${route.id}`
      const routeNameId = `route-name-${route.id}`

      if (map.getSource(routeId)) {
        map.removeLayer(routeId)
        map.removeLayer(routeNameId)
        map.removeSource(routeId)
      }
    })
  })
}

const moveMarkersToTop = () => {
  // Di chuyển các marker của cầu và đèn giao thông lên trên các tuyến đường
  props.cau?.forEach((item) => {
    const markerId = `bridge-marker-icon-${item.id}`
    if (map.getLayer(markerId)) {
      map.moveLayer(markerId)
    }
  })

  props.den_giao_thong?.forEach((item) => {
    const markerId = `traffic-light-marker-icon-${item.id}`
    if (map.getLayer(markerId)) {
      map.moveLayer(markerId)
    }
  })
}

const formFile = useForm({
  file: null,
  toa_do_id: '',
  danh_muc: danhMucTaiLieuOptions.toa_do
})

const formMota = useForm({
  mo_ta: '',
  toa_do_id: ''
})
// Hàm thêm marker
const addMarker = (lng, lat, name, iconUrl, iconId, id) => {
  // Tạo geojson
  const markerGeojson = {
    type: 'Feature',
    geometry: {
      type: 'Point',
      coordinates: [lng, lat]
    },
    properties: {
      name: name,
      image: iconUrl
    }
  }

  if (!map.getSource(`${iconId}-${id}`)) {
    // Thêm source marker
    map.addSource(`${iconId}-${id}`, {
      type: 'geojson',
      data: markerGeojson
    })

    // Thêm layer marker
    map.addLayer({
      id: `${iconId}-${id}`,
      type: 'symbol',
      source: `${iconId}-${id}`,
      layout: {
        'icon-image': iconId,
        'icon-size': 0.4,
        'text-field': name,
        'text-size': 10,
        'text-offset': [0, 1.25],
        'text-anchor': 'top'
      },
      paint: {
        'text-color': 'blue', // màu của chữ
        'text-halo-color': 'white', // đường viền của chữ
        'text-halo-width': 1 // độ đậm của đường viền
      }
    })

    // Thêm sự kiện click vào marker
    map.on('click', `${iconId}-${id}`, (e) => {
      const coordinates = e.lngLat
      formFile.toa_do_id = point.id
      formMota.toa_do_id = point.id
      const tai_lieu = props.toa_do_khac.find((item) => item.id === point.id).tai_lieu
      const mo_ta = props.toa_do_khac.find((item) => item.id === point.id).mo_ta

      // Hiện popup
      new mapboxgl.Popup({ offset: 50, closeButton: false })
        .setLngLat(coordinates)
        .setMaxWidth('300px')
        .setHTML(
          `
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
                            <textarea class="hidden w-100 border-gray-300 rounded" id="mota-input-${point.id}">${
            mo_ta || ''
          }</textarea>
                            <button id="luuMoTa-${point.id}" class=" hidden btn-sm btn-success">Lưu</button>
                        </div>
                        <label class="btn-success w-100 btn btn-sm mb-0 mt-3"
                               for="fileUpload-${point.id}">
                            <span class="fw-normal">Chọn file upload</span>
                        </label>
                        <input type="file" class="w-100 hidden" multiple id="fileUpload-${point.id}"/>
                        <div class="mt-2" id="listFile-${point.id}">
                            ${
                              tai_lieu?.length > 0
                                ? tai_lieu
                                    .map((file) => {
                                      return `
                                    <div class="">
                                      <a class="d-block" href="/storage/${file.file}" target="_blank">
                                        <i class="fas ${iconFileTypes[file.loai]}"></i>
                                        ${file.ten}
                                      </a>
                                    </div>
                                `
                                    })
                                    .join('')
                                : '<p>Không có tài liệu nào.</p>'
                            }
                        </div>
                    </div>
                `
        )
        .addTo(map)

      const moTaElement = document.getElementById(`mota-${point.id}`)
      if (moTaElement) {
        moTaElement.addEventListener('click', () => {
          const inputElement = document.getElementById(`mota-input-${point.id}`)
          const luuMoTaElement = document.getElementById(`luuMoTa-${point.id}`)
          inputElement.classList.remove('hidden')
          luuMoTaElement.classList.remove('hidden')
          moTaElement.classList.add('hidden')
        })
      }

      // Lưu mô tả và upload file
      const luuMoTaElement = document.getElementById(`luuMoTa-${point.id}`)
      if (luuMoTaElement) {
        luuMoTaElement.addEventListener('click', () => {
          const moTaElement = document.getElementById(`mota-input-${point.id}`)
          formMota.mo_ta = moTaElement.value
          formMota.post(route('map.update-mota'), {
            onSuccess: () => {
              const moTaElement = document.getElementById(`mota-${point.id}`)
              moTaElement.innerHTML = `${formMota.mo_ta}
                                    <i class="fas fa-edit"></i>`
              moTaElement.classList.remove('hidden')
              moTaElement.nextElementSibling.classList.add('hidden')
              moTaElement.nextElementSibling.nextElementSibling.classList.add('hidden')
            }
          })
        })
      }

      const inputElement = document.getElementById(`fileUpload-${point.id}`)
      if (inputElement) {
        inputElement.addEventListener('change', (event) => {
          formFile.file = event.target.files
          formFile.post(route('tai-lieu.store'), {
            onSuccess: () => {
              const listFileElement = document.getElementById(`listFile-${point.id}`)
              listFileElement.innerHTML = ''
              props.toa_do_khac
                .find((item) => item.id === point.id)
                .tai_lieu.forEach((file) => {
                  listFileElement.innerHTML += `<div class="">
                                      <a class="d-block" href="/storage/${file.file}" target="_blank">
                                        <i class="fas ${iconFileTypes[file.loai]}"></i>
                                        ${file.ten}
                                      </a>
                                    </div>`
                })
            }
          })
        })
      }
    })
  }
}
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
