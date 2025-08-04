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

    // Tạo layer hiển thị vị trí người dùng
    map.addLayer({
      id: 'user-location-point',
      type: 'circle',
      source: 'user-location',
      paint: {
        'circle-radius': 8,
        'circle-color': '#3887be',
        'circle-stroke-width': 2,
        'circle-stroke-color': '#ffffff'
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
  })

  // Check if mobile device
  checkIfMobile()
  window.addEventListener('resize', checkIfMobile)
})

// Responsive states
const isInfoExpanded = ref(true)
const isMobile = ref(false)

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
.btn-warning {
  @apply px-3 py-2 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2;
}

@media (max-width: 640px) {
  .btn-primary,
  .btn-secondary,
  .btn-warning {
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
</style>
