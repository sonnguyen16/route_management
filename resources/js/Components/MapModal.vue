<template>
  <div class="modal fade show d-block" v-if="show">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Chọn Tọa Độ</h5>
          <button type="button" class="close" @click="close">&times;</button>
        </div>
        <div class="modal-body">
          <div ref="mapContainer" class="mapbox-map"></div>
        </div>
        <div class="modal-footer">
          <button style="z-index: 9999" @click="close" class="btn btn-secondary">Hủy</button>
          <button style="z-index: 9999" @click="confirmSelection" class="btn btn-primary">Xác nhận</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css'

const props = defineProps({
  show: Boolean,
  existingRoute: Array,
  existingMarker: Array,
  type: {
    type: String,
    default: 'bridge'
  },
  mode: {
    type: String,
    default: 'multiple'
  }
})

const emits = defineEmits(['close', 'selectRoute'])
const mapContainer = ref(null)
let map,
  startPoint = null,
  endPoint = null,
  routeLayer = null
const markers = []

onMounted(() => {
  mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN
  map = new mapboxgl.Map({
    container: mapContainer.value,
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [107.242997, 10.495088], // Bà Rịa - Vũng Tàu
    zoom: 12
  })

  // Khi bản đồ load, nếu có tuyến đường cũ thì vẽ lại
  map.on('style.load', () => {
    if (props.existingRoute && props.existingRoute.length > 0) {
      drawExistingRoute(props.existingRoute)
    }
    if (props.existingMarker[0] && props.existingMarker[1]) {
      addCustomMarker(props.existingMarker, props.type)
    }
  })

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
  })

  map.on('click', async (e) => {
    if (props.mode === 'single') {
      // Nếu ở chế độ 1 điểm (Cầu, Đèn giao thông)
      startPoint = [e.lngLat.lng, e.lngLat.lat]
      if (markers.length > 0) {
        resetMarkers()
      }
      addCustomMarker(startPoint, props.type)
    } else {
      if (!startPoint) {
        startPoint = [e.lngLat.lng, e.lngLat.lat]
        addMarker(startPoint, 'red')
      } else if (!endPoint) {
        endPoint = [e.lngLat.lng, e.lngLat.lat]
        addMarker(endPoint, 'blue')

        // Gọi API OSRM để lấy tuyến đường
        await fetchRoute(startPoint, endPoint)
      } else {
        resetMarkers()
      }
    }
  })
})

const addMarker = (coords, color) => {
  const marker = new mapboxgl.Marker({ color }).setLngLat(coords).addTo(map)
  markers.push(marker)
}

// Hàm thêm Marker vào Map
const addCustomMarker = (coords, type) => {
  if (!coords || !Array.isArray(coords) || coords.length !== 2) {
    console.error('Lỗi: Tọa độ không hợp lệ', coords)
    return
  }

  // Tạo thẻ HTML div chứa ảnh
  const markerElement = document.createElement('div')
  markerElement.className = 'custom-marker'
  markerElement.style.backgroundImage = `url(${type === 'bridge' ? '/bridge.png' : '/traffic-light.png'})`
  markerElement.style.width = '40px'
  markerElement.style.height = '40px'
  markerElement.style.backgroundSize = 'cover'

  // Tạo marker với phần tử HTML
  const marker = new mapboxgl.Marker(markerElement).setLngLat([parseFloat(coords[0]), parseFloat(coords[1])]).addTo(map)
  markers.push(marker)
}

// Hàm xóa Marker khi chọn lại điểm
const resetMarkers = () => {
  markers.forEach((marker) => marker.remove())
  markers.length = 0
  startPoint = null
  endPoint = null
  if (map.getSource('route')) {
    map.removeLayer('route')
    map.removeSource('route')
  }
}

// Hàm vẽ tuyến đường cũ nếu có
const drawExistingRoute = (coordinates) => {
  const geojsonRoute = {
    type: 'Feature',
    geometry: {
      type: 'LineString',
      coordinates: coordinates.map((coord) => [coord.lng, coord.lat])
    }
  }

  map.addSource('route', { type: 'geojson', data: geojsonRoute })

  map.addLayer({
    id: 'route',
    type: 'line',
    source: 'route',
    layout: { 'line-join': 'round', 'line-cap': 'round' },
    paint: { 'line-color': '#ff5733', 'line-width': 5 }
  })

  startPoint = [coordinates[0].lng, coordinates[0].lat]
  endPoint = [coordinates[coordinates.length - 1].lng, coordinates[coordinates.length - 1].lat]

  addMarker(startPoint, 'red')
  addMarker(endPoint, 'blue')
}

const fetchRoute = async (start, end) => {
  const url = `/proxy-osrm?start=${start.join(',')}&end=${end.join(',')}`

  try {
    const response = await fetch(url)
    const data = await response.json()
    const route = data.routes[0].geometry

    if (map.getSource('route')) {
      map.getSource('route').setData({ type: 'Feature', geometry: route })
    } else {
      map.addSource('route', { type: 'geojson', data: { type: 'Feature', geometry: route } })

      map.addLayer({
        id: 'route',
        type: 'line',
        source: 'route',
        layout: { 'line-join': 'round', 'line-cap': 'round' },
        paint: { 'line-color': '#ff5733', 'line-width': 5 }
      })
    }

    // Lưu tuyến đường vào state
    routeLayer = route
  } catch (error) {
    console.error('Lỗi khi lấy dữ liệu tuyến đường:', error)
  }
}

const close = () => emits('close')
const confirmSelection = () => {
  if (props.mode === 'single') {
    emits('selectRoute', { point: startPoint })
  } else {
    emits('selectRoute', { start: startPoint, end: endPoint, route: routeLayer })
  }
}
</script>

<style scoped>
.modal {
  background: rgba(0, 0, 0, 0.5);
}
.mapbox-map {
  width: 100%;
  height: 600px;
  position: relative;
}
</style>
