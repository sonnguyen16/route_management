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
  existingRoute: Array
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
  })

  map.on('click', async (e) => {
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
  })
})

// Hàm thêm Marker vào Map
const addMarker = (coords, color) => {
  const marker = new mapboxgl.Marker({ color }).setLngLat(coords).addTo(map)
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
  const url = `https://router.project-osrm.org/route/v1/driving/${start[0]},${start[1]};${end[0]},${end[1]}?overview=full&geometries=geojson`

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
const confirmSelection = () => emits('selectRoute', { start: startPoint, end: endPoint, route: routeLayer })
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
