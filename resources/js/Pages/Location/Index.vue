<template>
  <MainLayout>
    <div class="cont position-relative">
      <div ref="mapContainer" class="map-container"></div>
      <Popup :locationData="currentLocation" :is-sheet-open="isSheetOpen" @close-sheet="closeSheet" />
      <!-- Thông tin vị trí hiện tại -->
      <div class="absolute top-0 right-0 p-3 z-10">
        <div class="bg-white p-3 rounded-lg shadow-lg">
          <div class="">
            <h3 class="text-lg font-semibold">Vị trí hiện tại</h3>
          </div>
          <div class="mt-2">
            <div class="grid grid-cols-2 gap-3">
              <div>Kinh độ:</div>
              <div>{{ currentLocation.lng || 'Đang xác định...' }}</div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>Vĩ độ:</div>
              <div>{{ currentLocation.lat || 'Đang xác định...' }}</div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>Cập nhật lúc:</div>
              <div>{{ currentLocation.time || 'Chưa có dữ liệu' }}</div>
            </div>
            <div class="mt-2">
              <button @click="toggleTracking" class="btn" :class="isTracking ? 'btn-danger' : 'btn-success'">
                {{ isTracking ? 'Dừng theo dõi' : 'Bắt đầu theo dõi' }}
              </button>
              <button @click="showDetails" class="btn btn-info ml-2">Chi tiết</button>
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
})

// Hàm lấy vị trí hiện tại
const getCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude, accuracy, altitude, heading, speed } = position.coords
        const timestamp = new Date()

        // Cập nhật vị trí hiện tại
        currentLocation.value = {
          lat: latitude,
          lng: longitude,
          time: timestamp.toLocaleTimeString(),
          accuracy: accuracy,
          altitude: altitude,
          heading: heading,
          speed: speed
        }

        // Thêm vị trí vào lịch sử
        locationHistory.push([longitude, latitude])

        // Cập nhật vị trí trên bản đồ
        updateLocationOnMap(longitude, latitude)

        // Di chuyển bản đồ đến vị trí hiện tại
        map.flyTo({
          center: [longitude, latitude],
          zoom: 15
        })
      },
      (error) => {
        console.error('Lỗi khi lấy vị trí:', error.message)
      },
      {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      }
    )
  } else {
    console.error('Trình duyệt không hỗ trợ Geolocation')
  }
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

// Dọn dẹp khi component bị hủy
onBeforeUnmount(() => {
  if (trackingInterval) {
    clearInterval(trackingInterval)
  }
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
</style>
