<template>
  <MainLayout>
    <div class="cont position-relative h-screen w-full overflow-hidden">
      <div ref="mapContainer" class="map-container w-full h-full"></div>
      <Popup :locationData="currentLocation" :is-sheet-open="isSheetOpen" @close-sheet="closeSheet" />

      <!-- Panel thông tin vị trí responsive -->
      <div class="location-info-panel">
        <div class="location-info-card">
          <!-- Header có thể thu gọn trên mobile -->
          <div class="location-header">
            <h3 class="location-title">Vị trí hiện tại</h3>
            <button @click="toggleInfoPanel" class="toggle-button sm:hidden" :class="{ rotated: !isInfoExpanded }">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>

          <!-- Nội dung có thể ẩn/hiện trên mobile -->
          <div class="location-content" :class="{ hidden: !isInfoExpanded && isMobile }">
            <div class="location-grid">
              <div class="location-item">
                <span class="location-label">Kinh độ:</span>
                <span class="location-value">{{ formatCoordinate(currentLocation.lng) }}</span>
              </div>
              <div class="location-item">
                <span class="location-label">Vĩ độ:</span>
                <span class="location-value">{{ formatCoordinate(currentLocation.lat) }}</span>
              </div>
              <div class="location-item">
                <span class="location-label">Độ chính xác:</span>
                <span class="location-value" :class="getAccuracyColor(currentLocation.accuracy)">
                  {{ currentLocation.accuracy || 'Không xác định' }}
                </span>
              </div>
              <div class="location-item">
                <span class="location-label">Cập nhật:</span>
                <span class="location-value">{{ currentLocation.time || 'Chưa có dữ liệu' }}</span>
              </div>
            </div>

            <!-- Buttons responsive -->
            <div class="location-buttons">
              <button @click="toggleTracking" class="btn-primary" :class="isTracking ? 'btn-danger' : 'btn-success'">
                <span class="btn-text-mobile">{{ isTracking ? 'Dừng' : 'Bắt đầu' }}</span>
                <span class="btn-text-desktop">{{ isTracking ? 'Dừng theo dõi' : 'Bắt đầu theo dõi' }}</span>
              </button>
              <button @click="showDetails" class="btn-secondary">
                <span class="btn-text-mobile">Chi tiết</span>
                <span class="btn-text-desktop">Chi tiết</span>
              </button>
              <button @click="getCurrentLocation" class="btn-warning">
                <span class="btn-text-mobile">Làm mới</span>
                <span class="btn-text-desktop">Làm mới</span>
              </button>
              <button @click="toggleTrafficList" class="btn-info">
                <span class="btn-text-mobile">Đèn</span>
                <span class="btn-text-desktop">Đèn giao thông</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Danh sách đèn giao thông -->
      <div class="traffic-lights-panel" :class="{ open: isTrafficListOpen }">
        <div class="traffic-lights-card">
          <div class="traffic-lights-header">
            <h3 class="traffic-lights-title">Đèn giao thông Vũng Tàu</h3>
            <button @click="toggleTrafficList" class="close-traffic-btn">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div class="traffic-lights-content">
            <div class="traffic-lights-list">
              <div
                v-for="light in trafficLights"
                :key="light.id"
                @click="flyToTrafficLight(light)"
                class="traffic-light-item"
                :class="{ maintenance: light.status === 'maintenance' }"
              >
                <div class="traffic-light-info">
                  <div class="traffic-light-number">#{{ light.id }}</div>
                  <div class="traffic-light-address">{{ light.address }}</div>
                  <div class="traffic-light-status">
                    <span
                      class="status-badge"
                      :class="light.status === 'active' ? 'status-active' : 'status-maintenance'"
                    >
                      {{ light.status === 'active' ? 'Hoạt động bình thường' : 'Đang sửa chữa' }}
                    </span>
                  </div>
                </div>
                <div class="traffic-light-icon">
                  <svg
                    v-if="light.status === 'active'"
                    class="w-6 h-6 text-green-600"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <circle cx="10" cy="6" r="2" />
                    <circle cx="10" cy="10" r="2" />
                    <circle cx="10" cy="14" r="2" />
                  </svg>
                  <svg v-else class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <circle cx="10" cy="6" r="2" />
                    <circle cx="10" cy="10" r="2" />
                    <circle cx="10" cy="14" r="2" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Popup thông tin đèn giao thông -->
      <div v-if="selectedTrafficLight" class="traffic-light-popup">
        <div class="traffic-light-popup-card">
          <div class="traffic-light-popup-header">
            <h4 class="traffic-light-popup-title">Đèn giao thông #{{ selectedTrafficLight.id }}</h4>
            <button @click="closeTrafficLightPopup" class="close-popup-btn">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <div class="traffic-light-popup-content">
            <div class="popup-info-item">
              <span class="popup-label">Địa chỉ:</span>
              <span class="popup-value">{{ selectedTrafficLight.address }}</span>
            </div>
            <div class="popup-info-item">
              <span class="popup-label">Tọa độ:</span>
              <span class="popup-value"
                >{{ selectedTrafficLight.lat.toFixed(6) }}, {{ selectedTrafficLight.lng.toFixed(6) }}</span
              >
            </div>
            <div class="popup-info-item">
              <span class="popup-label">Trạng thái:</span>
              <span
                class="popup-status"
                :class="selectedTrafficLight.status === 'active' ? 'status-active' : 'status-maintenance'"
              >
                {{ selectedTrafficLight.status === 'active' ? 'Hoạt động bình thường' : 'Đang sửa chữa' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import mapboxgl from 'mapbox-gl'
import { onMounted, ref, onBeforeUnmount } from 'vue'
import 'mapbox-gl/dist/mapbox-gl.css'
import MainLayout from '@/Layouts/MainLayout.vue'
import Popup from '@/Pages/Location/Popup.vue'

// Khai báo biến
mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN

const mapContainer = ref()
let map = null
const currentLocation = ref({
  lat: null,
  lng: null,
  time: null,
  accuracy: null,
  altitude: null,
  heading: null,
  speed: null
})
const isTracking = ref(false)
const isSheetOpen = ref(false)
let trackingInterval = null
let userMarker = null
let locationHistory = []

// Hàm khởi tạo map
onMounted(() => {
  // Khởi tạo map
  map = new mapboxgl.Map({
    container: mapContainer.value,
    style: 'mapbox://styles/mapbox/streets-v12',
    center: [107.242997, 10.495088], // Vị trí mặc định
    zoom: 14
  })

  // Thêm điều khiển zoom và xoay
  map.addControl(new mapboxgl.NavigationControl(), 'bottom-right')

  // Khi map đã load xong
  map.on('load', () => {
    // Load icon CSGT cho vị trí người dùng
    map.loadImage('/csgt.png', (error, image) => {
      if (error) {
        console.error('Lỗi load csgt.png:', error)
      } else if (!map.hasImage('csgt-icon')) {
        map.addImage('csgt-icon', image)
      }
    })

    // Tạo source cho vị trí người dùng
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

    // Tạo layer hiển thị vị trí người dùng với icon CSGT
    map.addLayer({
      id: 'user-location-point',
      type: 'symbol',
      source: 'user-location',
      layout: {
        'icon-image': 'csgt-icon',
        'icon-size': 0.5, // Kích thước nhỏ như đèn giao thông
        'icon-allow-overlap': true,
        'icon-anchor': 'bottom'
      }
    })

    // Tạo source cho lịch sử di chuyển
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

    // Tạo layer hiển thị lịch sử di chuyển
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

    // Lấy vị trí hiện tại ban đầu
    getCurrentLocation()

    // Load traffic light icon và hiển thị đèn giao thông
    loadTrafficLightIcon()
  })

  // Check if mobile device
  checkIfMobile()
  window.addEventListener('resize', checkIfMobile)
})

// Responsive states
const isInfoExpanded = ref(true)
const isMobile = ref(false)

// Danh sách đèn giao thông
const trafficLights = ref([
  {
    id: 1,
    lat: 10.359295,
    lng: 107.07919,
    address: 'Lê Hồng Phong - Nguyễn Thái Học',
    status: 'maintenance' // Đang sửa chữa
  },
  {
    id: 2,
    lat: 10.353411,
    lng: 107.086643,
    address: 'Lê Hồng Phong - Thống Nhất',
    status: 'active' // Hoạt động bình thường
  },
  {
    id: 3,
    lat: 10.355041,
    lng: 107.084942,
    address: 'Trương Công Định - Lê Hồng Phong',
    status: 'active'
  },
  {
    id: 4,
    lat: 10.364294,
    lng: 107.091785,
    address: 'Nguyễn An Ninh - Đội Cấn',
    status: 'active'
  },
  {
    id: 5,
    lat: 10.365312,
    lng: 107.089966,
    address: 'Nguyễn An Ninh - Trương Công Định',
    status: 'active'
  }
])

// Hiển thị danh sách đèn
const isTrafficListOpen = ref(false)

// Đèn giao thông được chọn
const selectedTrafficLight = ref(null)

// Hàm lấy vị trí hiện tại
const getCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        handlePositionSuccess(position)
      },
      (error) => {
        console.error('Lỗi khi lấy vị trí:', error.message)
        alert(`Không thể xác định vị trí: ${error.message}. Vui lòng kiểm tra quyền truy cập vị trí trong trình duyệt.`)
      },
      {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 0
      }
    )
  } else {
    console.error('Trình duyệt không hỗ trợ Geolocation')
    alert('Trình duyệt của bạn không hỗ trợ định vị. Vui lòng sử dụng trình duyệt khác.')
  }
}

// Xử lý khi lấy được vị trí thành công
const handlePositionSuccess = (position) => {
  const { latitude, longitude, accuracy, altitude, heading, speed } = position.coords
  const timestamp = new Date()

  // Cập nhật vị trí hiện tại
  currentLocation.value = {
    lat: latitude,
    lng: longitude,
    time: timestamp.toLocaleTimeString(),
    accuracy: accuracy ? `${accuracy.toFixed(2)} mét` : 'Không xác định',
    altitude: altitude ? `${altitude ? altitude.toFixed(2) : 0} mét` : 'Không xác định',
    heading: heading ? `${heading.toFixed(2)}°` : 'Không xác định',
    speed: speed ? `${(speed * 3.6).toFixed(2)} km/h` : 'Không xác định'
  }

  console.log('Vị trí hiện tại:', currentLocation.value)

  // Thêm vị trí vào lịch sử
  locationHistory.push([longitude, latitude])

  // Cập nhật vị trí trên bản đồ
  updateLocationOnMap(longitude, latitude)

  // Di chuyển bản đồ đến vị trí hiện tại
  map.flyTo({
    center: [longitude, latitude],
    zoom: 16
  })
}

// Hàm cập nhật vị trí trên bản đồ
const updateLocationOnMap = (longitude, latitude) => {
  if (map && map.getSource('user-location')) {
    // Cập nhật vị trí người dùng
    map.getSource('user-location').setData({
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: [longitude, latitude]
      },
      properties: {}
    })

    // Cập nhật lịch sử di chuyển
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

// Hàm bắt đầu/dừng theo dõi vị trí
const toggleTracking = () => {
  isTracking.value = !isTracking.value

  if (isTracking.value) {
    // Bắt đầu theo dõi vị trí mỗi phút
    trackingInterval = setInterval(() => {
      getCurrentLocation()
    }, 60000) // 60000ms = 1 phút

    // Lấy vị trí hiện tại ngay lập tức
    getCurrentLocation()
  } else {
    // Dừng theo dõi vị trí
    if (trackingInterval) {
      clearInterval(trackingInterval)
      trackingInterval = null
    }
  }
}

// Hiển thị chi tiết vị trí
const showDetails = () => {
  isSheetOpen.value = true
}

// Đóng popup chi tiết
const closeSheet = () => {
  isSheetOpen.value = false
}

// Hàm xác định màu sắc dựa trên độ chính xác
const getAccuracyColor = (accuracy) => {
  if (!accuracy) return 'text-gray-500'

  const numericAccuracy = parseFloat(accuracy.toString().replace(' mét', ''))

  if (numericAccuracy <= 5) return 'text-green-600 font-bold' // Rất tốt
  if (numericAccuracy <= 10) return 'text-green-500' // Tốt
  if (numericAccuracy <= 20) return 'text-yellow-500' // Khá tốt
  if (numericAccuracy <= 50) return 'text-orange-500' // Trung bình
  return 'text-red-500' // Kém
}

// Format tọa độ để hiển thị ngắn gọn
const formatCoordinate = (coord) => {
  if (!coord) return 'Đang xác định...'
  return parseFloat(coord).toFixed(6)
}

// Toggle panel thông tin trên mobile
const toggleInfoPanel = () => {
  isInfoExpanded.value = !isInfoExpanded.value
}

// Check if device is mobile
const checkIfMobile = () => {
  isMobile.value = window.innerWidth < 640 // Tailwind's sm breakpoint
  if (!isMobile.value) {
    isInfoExpanded.value = true // Always expanded on desktop
  }
}

// Load traffic light icon và hiển thị đèn giao thông
const loadTrafficLightIcon = () => {
  // Load icon đèn giao thông bình thường
  map.loadImage('/traffic-light.png', (error, image) => {
    if (error) {
      console.error('Lỗi load traffic-light.png:', error)
      return
    }

    if (!map.hasImage('traffic-light-icon')) {
      map.addImage('traffic-light-icon', image)
    }

    // Hiển thị đèn giao thông
    displayTrafficLights()
  })
}

// Hiển thị đèn giao thông trên bản đồ
const displayTrafficLights = () => {
  // Tạo GeoJSON data cho đèn giao thông
  const trafficLightFeatures = trafficLights.value.map((light) => ({
    type: 'Feature',
    geometry: {
      type: 'Point',
      coordinates: [light.lng, light.lat]
    },
    properties: {
      id: light.id,
      address: light.address,
      status: light.status
    }
  }))

  // Xóa layers và source cũ nếu tồn tại
  if (map.getLayer('traffic-lights-active')) {
    map.removeLayer('traffic-lights-active')
  }
  if (map.getLayer('traffic-lights-maintenance')) {
    map.removeLayer('traffic-lights-maintenance')
  }
  if (map.getSource('traffic-lights')) {
    map.removeSource('traffic-lights')
  }

  // Thêm source cho đèn giao thông
  map.addSource('traffic-lights', {
    type: 'geojson',
    data: {
      type: 'FeatureCollection',
      features: trafficLightFeatures
    }
  })

  // Thêm layer cho đèn giao thông hoạt động bình thường
  map.addLayer({
    id: 'traffic-lights-active',
    type: 'symbol',
    source: 'traffic-lights',
    filter: ['==', ['get', 'status'], 'active'],
    layout: {
      'icon-image': 'traffic-light-icon',
      'icon-size': 0.8,
      'icon-allow-overlap': true
    }
  })

  // Thêm layer cho đèn giao thông đang sửa chữa (sử dụng cùng icon nhưng sẽ có opacity thấp hơn)
  map.addLayer({
    id: 'traffic-lights-maintenance',
    type: 'symbol',
    source: 'traffic-lights',
    filter: ['==', ['get', 'status'], 'maintenance'],
    layout: {
      'icon-image': 'traffic-light-icon',
      'icon-size': 0.8,
      'icon-allow-overlap': true
    },
    paint: {
      'icon-opacity': 0.4 // Làm mờ icon đèn đang sửa chữa
    }
  })

  // Thêm sự kiện click cho đèn giao thông
  map.on('click', 'traffic-lights-active', handleTrafficLightClick)
  map.on('click', 'traffic-lights-maintenance', handleTrafficLightClick)

  // Thêm sự kiện hover
  map.on('mouseenter', 'traffic-lights-active', () => {
    map.getCanvas().style.cursor = 'pointer'
  })
  map.on('mouseleave', 'traffic-lights-active', () => {
    map.getCanvas().style.cursor = ''
  })
  map.on('mouseenter', 'traffic-lights-maintenance', () => {
    map.getCanvas().style.cursor = 'pointer'
  })
  map.on('mouseleave', 'traffic-lights-maintenance', () => {
    map.getCanvas().style.cursor = ''
  })
}

// Xử lý click vào đèn giao thông
const handleTrafficLightClick = (e) => {
  const lightId = e.features[0].properties.id
  const light = trafficLights.value.find((l) => l.id === lightId)

  if (light) {
    // Bay đến vị trí đèn giao thông
    map.flyTo({
      center: [light.lng, light.lat],
      zoom: 18,
      duration: 2000
    })

    // Hiển thị popup bên phải
    selectedTrafficLight.value = light
  }
}

// Toggle danh sách đèn giao thông
const toggleTrafficList = () => {
  isTrafficListOpen.value = !isTrafficListOpen.value
}

// Bay đến đèn giao thông từ danh sách
const flyToTrafficLight = (light) => {
  map.flyTo({
    center: [light.lng, light.lat],
    zoom: 18,
    duration: 2000
  })

  // Hiển thị popup bên phải
  selectedTrafficLight.value = light

  // Đóng danh sách trên mobile
  if (isMobile.value) {
    isTrafficListOpen.value = false
  }
}

// Đóng popup đèn giao thông
const closeTrafficLightPopup = () => {
  selectedTrafficLight.value = null
}

// Dọn dẹp khi component bị hủy
onBeforeUnmount(() => {
  if (trackingInterval) {
    clearInterval(trackingInterval)
  }

  window.removeEventListener('resize', checkIfMobile)
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

.cont {
  position: relative;
  width: 100%;
  height: 100vh;
}

/* Location Info Panel Styles */
.location-info-panel {
  @apply absolute top-2 right-2 z-20 max-w-sm w-full;
}

@media (max-width: 640px) {
  .location-info-panel {
    @apply top-2 left-2 right-2 max-w-none;
  }
}

.location-info-card {
  @apply bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden;
  backdrop-filter: blur(10px);
  background-color: rgba(255, 255, 255, 0.95);
}

.location-header {
  @apply flex items-center justify-between p-3 border-b border-gray-100;
}

.location-title {
  @apply text-base font-semibold text-gray-800;
}

@media (max-width: 640px) {
  .location-title {
    @apply text-sm;
  }
}

.toggle-button {
  @apply p-1 rounded-md hover:bg-gray-100 transition-transform duration-200;
}

.toggle-button.rotated {
  transform: rotate(180deg);
}

.location-content {
  @apply p-3;
}

.location-grid {
  @apply space-y-2;
}

.location-item {
  @apply flex justify-between items-center text-sm;
}

@media (max-width: 640px) {
  .location-item {
    @apply text-xs;
  }
}

.location-label {
  @apply text-gray-600 font-medium;
}

.location-value {
  @apply text-gray-900 font-mono text-right;
  max-width: 120px;
  word-break: break-all;
}

@media (max-width: 640px) {
  .location-value {
    max-width: 100px;
  }
}

/* Button Styles */
.location-buttons {
  @apply flex flex-wrap gap-2 mt-3;
}

.btn-primary,
.btn-secondary,
.btn-warning,
.btn-info {
  @apply px-3 py-2 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2;
}

@media (max-width: 640px) {
  .btn-primary,
  .btn-secondary,
  .btn-warning,
  .btn-info {
    @apply px-2 py-1.5 text-xs flex-1 min-w-0;
  }
}

.btn-primary.btn-success {
  @apply bg-green-600 hover:bg-green-700 text-white focus:ring-green-500;
}

.btn-primary.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white focus:ring-red-500;
}

.btn-secondary {
  @apply bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500;
}

.btn-warning {
  @apply bg-yellow-600 hover:bg-yellow-700 text-white focus:ring-yellow-500;
}

.btn-warning:disabled {
  @apply bg-gray-400 cursor-not-allowed hover:bg-gray-400;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white focus:ring-cyan-500;
}

/* Button Text Responsive */
.btn-text-mobile {
  @apply sm:hidden;
}

.btn-text-desktop {
  @apply hidden sm:inline;
}

/* Custom scrollbar for mobile */
@media (max-width: 640px) {
  .location-content {
    max-height: calc(100vh - 150px);
    overflow-y: auto;
  }

  .location-content::-webkit-scrollbar {
    width: 4px;
  }

  .location-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
  }

  .location-content::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 2px;
  }

  .location-content::-webkit-scrollbar-thumb:hover {
    background: #555;
  }
}

/* Animation for expand/collapse */
.location-content {
  transition: all 0.3s ease-in-out;
}

.location-content.hidden {
  @apply opacity-0 h-0 overflow-hidden;
}

/* Traffic Lights Panel Styles */
.traffic-lights-panel {
  @apply fixed top-1/2 transform -translate-y-1/2 z-30 w-80 max-w-sm;
  transform: translateX(-100%) translateY(-50%);
  transition: transform 0.3s ease-in-out;
}

.traffic-lights-panel.open {
  transform: translateX(0) translateY(-50%);
}

@media (max-width: 640px) {
  .traffic-lights-panel {
    @apply left-2 right-2 top-20 w-auto max-w-none;
    transform: translateY(-110%);
  }

  .traffic-lights-panel.open {
    transform: translateY(0);
  }
}

.traffic-lights-card {
  @apply bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden;
  backdrop-filter: blur(10px);
  background-color: rgba(255, 255, 255, 0.95);
  max-height: calc(100vh - 200px);
}

.traffic-lights-header {
  @apply flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50;
}

.traffic-lights-title {
  @apply text-lg font-semibold text-gray-800;
}

@media (max-width: 640px) {
  .traffic-lights-title {
    @apply text-base;
  }
}

.close-traffic-btn {
  @apply p-1 rounded-md hover:bg-gray-200 transition-colors;
}

.traffic-lights-content {
  @apply overflow-y-auto;
  max-height: calc(100vh - 300px);
}

.traffic-lights-list {
  @apply p-2;
}

.traffic-light-item {
  @apply flex items-center justify-between p-3 mb-2 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors;
}

.traffic-light-item:hover {
  @apply shadow-md border-gray-300;
}

.traffic-light-item.maintenance {
  @apply bg-orange-50 border-orange-200;
}

.traffic-light-info {
  @apply flex-1;
}

.traffic-light-number {
  @apply text-sm font-bold text-gray-800 mb-1;
}

.traffic-light-address {
  @apply text-sm text-gray-600 mb-2;
}

@media (max-width: 640px) {
  .traffic-light-address {
    @apply text-xs;
  }
}

.traffic-light-status {
  @apply flex items-center;
}

.status-badge {
  @apply px-2 py-1 rounded-full text-xs font-medium;
}

.status-active {
  @apply bg-green-100 text-green-800;
}

.status-maintenance {
  @apply bg-orange-100 text-orange-800;
}

.traffic-light-icon {
  @apply ml-3 flex-shrink-0;
}

/* Custom scrollbar for traffic lights list */
.traffic-lights-content::-webkit-scrollbar {
  width: 6px;
}

.traffic-lights-content::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.traffic-lights-content::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.traffic-lights-content::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}

/* Traffic Light Popup Styles */
.traffic-light-popup {
  @apply absolute top-2 right-2 w-80 max-w-sm;
  margin-top: 320px; /* Dưới popup vị trí */
}

@media (max-width: 640px) {
  .traffic-light-popup {
    @apply left-2 right-2 top-auto bottom-[5.5rem] w-auto max-w-none;
    margin-top: 0;
  }
}

.traffic-light-popup-card {
  @apply bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden;
  backdrop-filter: blur(10px);
  background-color: rgba(255, 255, 255, 0.95);
}

.traffic-light-popup-header {
  @apply flex items-center justify-between p-3 border-b border-gray-200 bg-blue-50;
}

.traffic-light-popup-title {
  @apply text-base font-semibold text-blue-800;
}

@media (max-width: 640px) {
  .traffic-light-popup-title {
    @apply text-sm;
  }
}

.close-popup-btn {
  @apply p-1 rounded-md hover:bg-blue-200 transition-colors text-blue-600;
}

.traffic-light-popup-content {
  @apply p-3;
}

.popup-info-item {
  @apply flex justify-between items-start mb-3 last:mb-0;
}

.popup-label {
  @apply text-sm font-medium text-gray-600 mr-2 flex-shrink-0;
}

.popup-value {
  @apply text-sm text-gray-900 text-right flex-1;
  word-break: break-word;
}

@media (max-width: 640px) {
  .popup-label,
  .popup-value {
    @apply text-xs;
  }
}

.popup-status {
  @apply px-2 py-1 rounded-full text-xs font-medium text-right;
}

.popup-status.status-active {
  @apply bg-green-100 text-green-800;
}

.popup-status.status-maintenance {
  @apply bg-orange-100 text-orange-800;
}
</style>
