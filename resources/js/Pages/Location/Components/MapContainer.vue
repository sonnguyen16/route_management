<template>
  <div ref="mapContainer" class="map-container w-full h-full relative">
    <!-- Popup danh sách đèn giao thông -->
    <div v-if="showDenGiaoThongList" class="den-giao-thong-list">
      <div class="list-header">
        <h3 class="list-title">🚦 Danh sách đèn giao thông</h3>
        <button @click="closeDenGiaoThongList" class="close-btn">&times;</button>
      </div>
      <div class="list-content">
        <div v-if="props.denGiaoThong.length === 0" class="no-data">Không có dữ liệu đèn giao thông</div>
        <div v-else class="den-list">
          <div v-for="den in props.denGiaoThong" :key="den.id" class="den-item" @click="flyToDen(den)">
            <div class="den-icon">🚦</div>
            <div class="den-info">
              <div class="den-name">{{ den.nut_giao || 'N/A' }}</div>
              <div class="den-details">
                <span class="den-phase">{{ den.so_pha_den || 0 }} pha</span>
                <span class="den-time">{{ den.thoi_gian_pha_den || 0 }}s</span>
              </div>
            </div>
            <div class="den-action">
              <button class="view-btn" @click.stop="flyToDen(den)">👁️ Xem</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Nút mở danh sách đèn giao thông -->
    <button
      v-if="props.denGiaoThong.length > 0"
      @click="openDenGiaoThongList"
      class="den-list-toggle"
      title="Danh sách đèn giao thông"
    >
      🚦
    </button>
  </div>
</template>

<script setup>
import mapboxgl from 'mapbox-gl'
import { onMounted, ref, watch } from 'vue'
import 'mapbox-gl/dist/mapbox-gl.css'

const props = defineProps({
  center: {
    type: Array,
    default: () => [107.242997, 10.495088]
  },
  zoom: {
    type: Number,
    default: 14
  },
  locations: {
    type: Array,
    default: () => []
  },
  plans: {
    type: Array,
    default: () => []
  },
  userRole: {
    type: String,
    default: 'captain'
  },
  denGiaoThong: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['map-click', 'map-ready'])

mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN

const mapContainer = ref()
const showDenGiaoThongList = ref(false)
let map = null
let locationHistory = []

onMounted(() => {
  initializeMap()
})

const initializeMap = () => {
  map = new mapboxgl.Map({
    container: mapContainer.value,
    style: 'mapbox://styles/mapbox/streets-v12',
    center: props.center,
    zoom: props.zoom
  })

  // Thêm điều khiển zoom và xoay
  map.addControl(new mapboxgl.NavigationControl(), 'bottom-right')

  // Khi map đã load xong
  map.on('load', () => {
    setupMapSources()
    setupMapLayers()
    setupMapEvents()

    // Load initial plans nếu có
    if (props.plans && props.plans.length > 0) {
      updatePlans(props.plans)
    }

    if (props.denGiaoThong && props.denGiaoThong.length > 0) {
      updateDenGiaoThong(props.denGiaoThong)
    }

    emit('map-ready', map)
  })
}

const setupMapSources = () => {
  // Load icon CSGT cho vị trí người dùng
  map.loadImage('/csgt.png', (error, image) => {
    if (error) {
      console.error('Lỗi load csgt.png:', error)
    } else if (!map.hasImage('csgt-icon')) {
      map.addImage('csgt-icon', image)
    }
  })

  // Load icon task cho plans
  map.loadImage('/Km3.png', (error, image) => {
    if (error) {
      console.error('Lỗi load task.png:', error)
    } else if (!map.hasImage('task-icon')) {
      map.addImage('task-icon', image)
    }
  })

  // Load icon đèn giao thông
  map.loadImage('/traffic-light.png', (error, image) => {
    if (error) {
      console.error('Lỗi load traffic-light.png:', error)
    } else if (!map.hasImage('traffic-light-icon')) {
      map.addImage('traffic-light-icon', image)
    }
  })

  // Source cho vị trí người dùng
  map.addSource('user-location', {
    type: 'geojson',
    data: {
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [0, 0]
      },
      properties: {}
    }
  })

  // Source cho lịch sử di chuyển
  map.addSource('location-history', {
    type: 'geojson',
    data: {
      type: 'Feature',
      geometry: {
        type: 'LineString',
        coordinates: []
      },
      properties: {}
    }
  })

  // Source cho các đội trưởng (chỉ cho lãnh đạo)
  if (props.userRole === 'leader') {
    // Source cho vị trí hiện tại của đội trưởng (icon CSGT)
    map.addSource('captains-locations', {
      type: 'geojson',
      data: {
        type: 'FeatureCollection',
        features: []
      }
    })

    // Source cho đường tracking của đội trưởng
    map.addSource('captains-tracks', {
      type: 'geojson',
      data: {
        type: 'FeatureCollection',
        features: []
      }
    })
  }

  // Source cho plans
  map.addSource('plans', {
    type: 'geojson',
    data: {
      type: 'FeatureCollection',
      features: []
    }
  })

  // Source cho đèn giao thông
  map.addSource('den-giao-thong', {
    type: 'geojson',
    data: {
      type: 'FeatureCollection',
      features: []
    }
  })
}

const setupMapLayers = () => {
  // Layer hiển thị vị trí người dùng với icon CSGT
  map.addLayer({
    id: 'user-location-point',
    type: 'symbol',
    source: 'user-location',
    layout: {
      'icon-image': 'csgt-icon',
      'icon-size': 0.2,
      'icon-allow-overlap': true,
      'icon-anchor': 'bottom'
    }
  })

  // Layer hiển thị lịch sử di chuyển
  map.addLayer({
    id: 'location-history-line',
    type: 'line',
    source: 'location-history',
    layout: {
      'line-join': 'round',
      'line-cap': 'round'
    },
    paint: {
      'line-color': '#3887be',
      'line-width': 3,
      'line-opacity': 0.8
    }
  })

  // Layer cho các đội trưởng (chỉ cho lãnh đạo) - Dùng icon CSGT thay vì chấm đỏ
  if (props.userRole === 'leader') {
    map.addLayer({
      id: 'captains-locations-points',
      type: 'symbol',
      source: 'captains-locations',
      layout: {
        'icon-image': 'csgt-icon',
        'icon-size': 0.2,
        'icon-allow-overlap': true,
        'icon-anchor': 'bottom'
      }
    })

    // Thêm đường tracking cho từng đội trưởng
    map.addLayer({
      id: 'captains-tracks',
      type: 'line',
      source: 'captains-tracks',
      paint: {
        'line-color': ['get', 'color'],
        'line-width': 3,
        'line-opacity': 0.8
      }
    })

    // Labels cho đội trưởng
    map.addLayer({
      id: 'captains-locations-labels',
      type: 'symbol',
      source: 'captains-locations',
      layout: {
        'text-field': ['get', 'name'],
        'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold'],
        'text-size': 12,
        'text-offset': [0, -2]
      },
      paint: {
        'text-color': '#ffffff',
        'text-halo-color': '#000000',
        'text-halo-width': 1
      }
    })
  }

  // Layer cho plans
  map.addLayer({
    id: 'plans-points',
    type: 'symbol',
    source: 'plans',
    layout: {
      'icon-image': 'task-icon',
      'icon-size': 0.4,
      'icon-allow-overlap': true,
      'icon-anchor': 'bottom'
    }
  })

  // Layer cho đèn giao thông
  map.addLayer({
    id: 'den-giao-thong-points',
    type: 'symbol',
    source: 'den-giao-thong',
    layout: {
      'icon-image': 'traffic-light-icon',
      'icon-size': 0.4,
      'icon-allow-overlap': true,
      'icon-anchor': 'bottom'
    }
  })
}

const setupMapEvents = () => {
  // Click event cho map
  map.on('click', (e) => {
    emit('map-click', {
      lng: e.lngLat.lng,
      lat: e.lngLat.lat
    })
  })

  // Click event cho plans
  map.on('click', 'plans-points', (e) => {
    const plan = e.features[0].properties
    const formattedDate = formatDate(plan.date)
    const statusText = getStatusText(plan.status)

    new mapboxgl.Popup()
      .setLngLat(e.lngLat)
      .setHTML(
        `
        <div class="p-3 min-w-[200px]">
          <h4 class="font-bold text-lg mb-2">${plan.description}</h4>
          <p class="mb-1"><strong>📅 Ngày:</strong> ${formattedDate}</p>
          <p class="mb-1"><strong>⏰ Thời gian:</strong> ${plan.start_time} - ${plan.end_time}</p>
          <p><strong>📋 Trạng thái:</strong> <span class="px-2 py-1 rounded text-sm ${getStatusClass(
            plan.status
          )}">${statusText}</span></p>
        </div>
      `
      )
      .addTo(map)
  })

  // Click event cho đèn giao thông
  map.on('click', 'den-giao-thong-points', (e) => {
    const den = e.features[0].properties

    new mapboxgl.Popup()
      .setLngLat(e.lngLat)
      .setHTML(
        `
        <div class="p-3 min-w-[250px]">
          <h4 class="font-bold text-lg mb-2">🚦 Đèn giao thông</h4>
          <p class="mb-1"><strong>📍 Nút giao:</strong> ${den.nut_giao || 'N/A'}</p>
          <p class="mb-1"><strong>🔴 Số pha đèn:</strong> ${den.so_pha_den || 'N/A'}</p>
          <p class="mb-1"><strong>⏱️ Thời gian pha đèn:</strong> ${den.thoi_gian_pha_den || 'N/A'}s</p>
          <p class="mb-1"><strong>🕐 Thời gian hoạt động:</strong> ${den.thoi_gian_hoat_dong || 'N/A'}</p>
          <p class="mb-1"><strong>📍 Vị trí:</strong> ${den.vi_tri || 'N/A'}</p>
          <p class="mb-1"><strong>📅 Ngày lắp đặt:</strong> ${
            den.ngay_lap_dat ? new Date(den.ngay_lap_dat).toLocaleDateString('vi-VN') : 'N/A'
          }</p>
        </div>
      `
      )
      .addTo(map)
  })

  // Click event cho captains (nếu là lãnh đạo)
  if (props.userRole === 'leader') {
    map.on('click', 'captains-locations-points', (e) => {
      const captain = e.features[0].properties

      // Format thời gian cập nhật
      const lastUpdateDate = new Date(captain.last_update)
      const formattedTime = lastUpdateDate.toLocaleTimeString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      })
      const formattedDate = lastUpdateDate.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      })

      new mapboxgl.Popup()
        .setLngLat(e.lngLat)
        .setHTML(
          `
          <div class="p-3 min-w-[200px]">
            <h4 class="font-bold text-lg mb-2">${captain.name}</h4>
            <p class="mb-1"><strong>📅 Ngày:</strong> ${formattedDate}</p>
            <p class="mb-1"><strong>⏰ Cập nhật:</strong> ${formattedTime}</p>
            <p class="mb-1"><strong>📍 Độ chính xác:</strong> ${captain.accuracy}m</p>
            <div class="mt-2 pt-2 border-t border-gray-200">
              <div class="flex items-center">
                <span class="w-3 h-3 rounded-full mr-2" style="background-color: ${captain.color}"></span>
                <span class="text-xs text-gray-600">Đường tracking</span>
              </div>
            </div>
          </div>
        `
        )
        .addTo(map)
    })
  }
}

// Utility functions
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('vi-VN', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusText = (status) => {
  switch (status) {
    case 'pending':
      return 'Chờ thực hiện'
    case 'active':
      return 'Đang thực hiện'
    case 'completed':
      return 'Hoàn thành'
    default:
      return status
  }
}

const getStatusClass = (status) => {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'active':
      return 'bg-green-100 text-green-800'
    case 'completed':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

// Update user location on map
const updateUserLocation = (longitude, latitude) => {
  if (map && map.getSource('user-location')) {
    map.getSource('user-location').setData({
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [longitude, latitude]
      },
      properties: {}
    })

    // Update location history
    locationHistory.push([longitude, latitude])
    if (locationHistory.length > 1) {
      map.getSource('location-history').setData({
        type: 'Feature',
        geometry: {
          type: 'LineString',
          coordinates: locationHistory
        },
        properties: {}
      })
    }
  }
}

// Update captains locations (for leaders)
// Khai báo global ở ngoài hàm để lưu lịch sử tracking
let captainTracks = {} // { user_id: [[lng, lat], ...] }
let captainColors = {} // { user_id: '#RRGGBB' }
const colors = ['#FF5733', '#33FF57', '#3357FF', '#F033FF', '#FF33A8', '#33FFF6']

const updateCaptainsLocations = (captains) => {
  if (props.userRole !== 'leader' || !map) return
  const locationsSource = map.getSource('captains-locations')
  const tracksSource = map.getSource('captains-tracks')
  if (!locationsSource || !tracksSource) return

  // Cập nhật vị trí và track cho từng captain
  captains.forEach((captain, index) => {
    // 1️⃣ Gán màu cố định cho captain nếu chưa có
    if (!captainColors[captain.user_id]) {
      captainColors[captain.user_id] = colors[index % colors.length]
    }

    // 2️⃣ Cập nhật track
    const currentCoord = [captain.location.lng, captain.location.lat]
    const track = captainTracks[captain.user_id] || []

    // Thêm điểm mới nếu khác điểm cuối cùng
    const last = track[track.length - 1]
    if (!last || last[0] !== currentCoord[0] || last[1] !== currentCoord[1]) {
      track.push(currentCoord)
      // Giới hạn số điểm để tránh quá tải
      //   if (track.length > 100) track.shift()
      captainTracks[captain.user_id] = track
    }
  })

  // 3️⃣ Tạo FeatureCollection cho locations
  const locationFeatures = captains.map((captain) => ({
    type: 'Feature',
    geometry: {
      type: 'Point',
      coordinates: [captain.location.lng, captain.location.lat]
    },
    properties: {
      user_id: captain.user_id,
      name: captain.user_name,
      last_update: captain.location.recorded_at,
      accuracy: captain.location.accuracy,
      color: captainColors[captain.user_id]
    }
  }))

  // 4️⃣ Tạo FeatureCollection cho tracks
  const trackFeatures = Object.entries(captainTracks)
    .filter(([_, coords]) => coords.length > 1)
    .map(([userId, coords]) => ({
      type: 'Feature',
      geometry: {
        type: 'LineString',
        coordinates: coords
      },
      properties: {
        user_id: userId,
        color: captainColors[userId]
      }
    }))

  // 5️⃣ Cập nhật dữ liệu cho Mapbox source
  locationsSource.setData({
    type: 'FeatureCollection',
    features: locationFeatures
  })
  tracksSource.setData({
    type: 'FeatureCollection',
    features: trackFeatures
  })
}

// Update plans on map
const updatePlans = (plans) => {
  console.log('Updating plans on map:', plans)
  if (map && map.getSource('plans')) {
    const features = plans.map((plan) => ({
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [parseFloat(plan.lng), parseFloat(plan.lat)]
      },
      properties: {
        id: plan.id,
        description: plan.description,
        date: plan.date,
        start_time: plan.start_time,
        end_time: plan.end_time,
        status: plan.status
      }
    }))

    console.log('Plan features:', features)
    map.getSource('plans').setData({
      type: 'FeatureCollection',
      features
    })
  }
}

// Update den giao thong on map
const updateDenGiaoThong = (denGiaoThong) => {
  console.log('Updating den giao thong on map:', denGiaoThong)
  if (map && map.getSource('den-giao-thong')) {
    const features = denGiaoThong.map((den) => ({
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [parseFloat(den.lng), parseFloat(den.lat)]
      },
      properties: {
        id: den.id,
        nut_giao: den.nut_giao,
        so_pha_den: den.so_pha_den,
        thoi_gian_pha_den: den.thoi_gian_pha_den,
        thoi_gian_hoat_dong: den.thoi_gian_hoat_dong,
        vi_tri: den.vi_tri,
        ngay_lap_dat: den.ngay_lap_dat
      }
    }))

    console.log('Den giao thong features:', features)
    map.getSource('den-giao-thong').setData({
      type: 'FeatureCollection',
      features
    })
  }
}

// Fly to location
const flyTo = (coordinates, zoom = 16) => {
  if (map) {
    map.flyTo({
      center: coordinates,
      zoom
    })
  }
}

// Fly to den giao thong
const flyToDen = (den) => {
  if (map && den.lat && den.lng) {
    map.flyTo({
      center: [parseFloat(den.lng), parseFloat(den.lat)],
      zoom: 16
    })
    closeDenGiaoThongList()
  }
}

// Open den giao thong list
const openDenGiaoThongList = () => {
  showDenGiaoThongList.value = true
}

// Close den giao thong list
const closeDenGiaoThongList = () => {
  showDenGiaoThongList.value = false
}

// Watch for prop changes
watch(() => props.locations, updateCaptainsLocations, { deep: true })
watch(() => props.plans, updatePlans, { deep: true })
watch(() => props.denGiaoThong, updateDenGiaoThong, { deep: true })

// Expose methods
defineExpose({
  updateUserLocation,
  updateCaptainsLocations,
  updatePlans,
  updateDenGiaoThong,
  flyTo,
  map: () => map
})
</script>

<style scoped>
.map-container {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Den giao thong list popup */
.den-giao-thong-list {
  position: absolute;
  top: 20px;
  right: 20px;
  width: 350px;
  max-height: 500px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 1000;
  overflow: hidden;
}

.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background: #f8f9fa;
  border-bottom: 1px solid #e9ecef;
}

.list-title {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #333;
}

.close-btn {
  background: none;
  border: none;
  font-size: 20px;
  color: #666;
  cursor: pointer;
  padding: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.close-btn:hover {
  background-color: #e9ecef;
  color: #333;
}

.list-content {
  max-height: 400px;
  overflow-y: auto;
}

.no-data {
  padding: 20px;
  text-align: center;
  color: #666;
  font-style: italic;
}

.den-list {
  padding: 0;
}

.den-item {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  border-bottom: 1px solid #f1f3f4;
  cursor: pointer;
  transition: background-color 0.2s;
}

.den-item:hover {
  background-color: #f8f9fa;
}

.den-item:last-child {
  border-bottom: none;
}

.den-icon {
  font-size: 20px;
  margin-right: 12px;
  flex-shrink: 0;
}

.den-info {
  flex: 1;
  min-width: 0;
}

.den-name {
  font-weight: 500;
  color: #333;
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.den-details {
  display: flex;
  gap: 12px;
  font-size: 12px;
  color: #666;
}

.den-phase,
.den-time {
  background: #e9ecef;
  padding: 2px 6px;
  border-radius: 4px;
}

.den-action {
  margin-left: 8px;
}

.view-btn {
  background: #007bff;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.view-btn:hover {
  background: #0056b3;
}

/* Toggle button */
.den-list-toggle {
  position: absolute;
  top: 20px;
  right: 20px;
  width: 50px;
  height: 50px;
  background: white;
  border: 2px solid #007bff;
  border-radius: 50%;
  font-size: 20px;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  transition: all 0.2s;
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.den-list-toggle:hover {
  background: #007bff;
  color: white;
  transform: scale(1.05);
}

.den-list-toggle:active {
  transform: scale(0.95);
}
</style>
