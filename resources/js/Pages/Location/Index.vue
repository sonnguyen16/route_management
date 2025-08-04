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
              <div>Độ chính xác:</div>
              <div :class="getAccuracyColor(currentLocation.accuracy)">
                {{ currentLocation.accuracy || 'Không xác định' }}
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>Cập nhật lúc:</div>
              <div>{{ currentLocation.time || 'Chưa có dữ liệu' }}</div>
            </div>
            <div class="grid grid-cols-2 gap-3" v-if="positionSamples.length > 0">
              <div>Mẫu thu thập:</div>
              <div>{{ positionSamples.length }}/{{ maxSamples }}</div>
            </div>
            <div class="mt-2">
              <button @click="toggleTracking" class="btn" :class="isTracking ? 'btn-danger' : 'btn-success'">
                {{ isTracking ? 'Dừng theo dõi' : 'Bắt đầu theo dõi' }}
              </button>
              <button @click="showDetails" class="btn btn-info ml-2">Chi tiết</button>
              <button @click="getCurrentLocation" class="btn btn-warning ml-2" :disabled="positionSamples.length > 0">
                {{ positionSamples.length > 0 ? 'Đang lấy mẫu...' : 'Làm mới' }}
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
})

// Lưu trữ các vị trí để tính toán trung bình
const positionSamples = ref([])
const maxSamples = 5 // Số mẫu tối đa để tính trung bình

// Hàm lấy vị trí hiện tại với độ chính xác cao nhất
const getCurrentLocation = () => {
  if (navigator.geolocation) {
    // Reset mẫu vị trí
    positionSamples.value = []
    
    // Lấy nhiều mẫu vị trí để tính trung bình
    collectPositionSamples()
  } else {
    console.error('Trình duyệt không hỗ trợ Geolocation')
    alert('Trình duyệt của bạn không hỗ trợ định vị. Vui lòng sử dụng trình duyệt khác.')
  }
}

// Thu thập nhiều mẫu vị trí để tính toán chính xác hơn
const collectPositionSamples = () => {
  const sampleCount = ref(0)
  
  const getSample = () => {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude, accuracy } = position.coords
        
        // Chỉ chấp nhận vị trí có độ chính xác dưới 100m
        if (accuracy <= 100) {
          positionSamples.value.push({
            lat: latitude,
            lng: longitude,
            accuracy: accuracy,
            timestamp: Date.now(),
            coords: position.coords
          })
          
          console.log(`Mẫu ${positionSamples.value.length}/${maxSamples}: Accuracy ${accuracy.toFixed(2)}m`)
          
          // Nếu đã có đủ mẫu hoặc có mẫu rất chính xác (<10m), xử lý kết quả
          if (positionSamples.value.length >= maxSamples || accuracy < 10) {
            processPositionSamples()
          } else if (positionSamples.value.length < maxSamples) {
            // Tiếp tục lấy mẫu sau 1 giây
            setTimeout(getSample, 1000)
          }
        } else {
          console.log(`Bỏ qua mẫu có độ chính xác thấp: ${accuracy.toFixed(2)}m`)
          sampleCount.value++
          
          // Nếu đã thử quá nhiều lần mà không có mẫu tốt, sử dụng mẫu hiện tại
          if (sampleCount.value > 10) {
            if (positionSamples.value.length > 0) {
              processPositionSamples()
            } else {
              // Fallback với mẫu hiện tại dù độ chính xác thấp
              positionSamples.value.push({
                lat: latitude,
                lng: longitude,
                accuracy: accuracy,
                timestamp: Date.now(),
                coords: position.coords
              })
              processPositionSamples()
            }
          } else {
            // Thử lại sau 2 giây
            setTimeout(getSample, 2000)
          }
        }
      },
      (error) => {
        console.error('Lỗi khi lấy mẫu vị trí:', error.message)
        sampleCount.value++
        
        // Nếu có ít nhất 1 mẫu, sử dụng nó
        if (positionSamples.value.length > 0) {
          processPositionSamples()
        } else if (sampleCount.value <= 3) {
          // Thử lại với cài đặt khác
          setTimeout(() => {
            navigator.geolocation.getCurrentPosition(
              (position) => {
                positionSamples.value.push({
                  lat: position.coords.latitude,
                  lng: position.coords.longitude,
                  accuracy: position.coords.accuracy,
                  timestamp: Date.now(),
                  coords: position.coords
                })
                processPositionSamples()
              },
              (err) => {
                alert(`Không thể xác định vị trí: ${err.message}`)
              },
              {
                enableHighAccuracy: false,
                timeout: 10000,
                maximumAge: 0
              }
            )
          }, 1000)
        } else {
          alert(`Không thể xác định vị trí: ${error.message}. Vui lòng kiểm tra quyền truy cập vị trí.`)
        }
      },
      {
        enableHighAccuracy: true,
        timeout: 15000, // Tăng timeout để GPS có thời gian khởi động
        maximumAge: 0
      }
    )
  }
  
  // Bắt đầu thu thập mẫu
  getSample()
}

// Xử lý các mẫu vị trí để tính toán vị trí chính xác nhất
const processPositionSamples = () => {
  if (positionSamples.value.length === 0) return
  
  console.log('Xử lý', positionSamples.value.length, 'mẫu vị trí')
  
  // Sắp xếp theo độ chính xác (accuracy thấp = chính xác hơn)
  const sortedSamples = positionSamples.value.sort((a, b) => a.accuracy - b.accuracy)
  
  let finalPosition
  
  if (sortedSamples.length === 1) {
    // Chỉ có 1 mẫu
    finalPosition = sortedSamples[0]
  } else {
    // Sử dụng weighted average dựa trên độ chính xác
    const weights = sortedSamples.map(sample => 1 / (sample.accuracy + 1)) // +1 để tránh chia cho 0
    const totalWeight = weights.reduce((sum, weight) => sum + weight, 0)
    
    const weightedLat = sortedSamples.reduce((sum, sample, index) => 
      sum + (sample.lat * weights[index]), 0) / totalWeight
    const weightedLng = sortedSamples.reduce((sum, sample, index) => 
      sum + (sample.lng * weights[index]), 0) / totalWeight
    
    // Sử dụng độ chính xác tốt nhất
    const bestAccuracy = sortedSamples[0].accuracy
    const bestCoords = sortedSamples[0].coords
    
    finalPosition = {
      lat: weightedLat,
      lng: weightedLng,
      accuracy: bestAccuracy,
      coords: {
        ...bestCoords,
        latitude: weightedLat,
        longitude: weightedLng,
        accuracy: bestAccuracy
      }
    }
  }
  
  console.log('Vị trí cuối cùng:', finalPosition)
  handlePositionSuccess({ coords: finalPosition.coords })
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
    }, 30000) // 30000ms = 30 giây (tăng tần suất cập nhật)

    // Lấy vị trí hiện tại ngay lập tức
    getCurrentLocation()

    // Thêm sự kiện lắng nghe khi người dùng di chuyển (nếu trình duyệt hỗ trợ watchPosition)
    if (navigator.geolocation && !userMarker) {
      // Theo dõi vị trí với độ chính xác thấp để tiết kiệm pin và tránh timeout
      userMarker = navigator.geolocation.watchPosition(
        (position) => {
          // Sử dụng hàm xử lý chung để cập nhật vị trí
          handlePositionSuccess(position)

          // Chỉ thêm vào lịch sử nếu vị trí thay đổi đáng kể (> 10m)
          const { latitude, longitude } = position.coords
          const lastLocation = locationHistory.length > 0 ? locationHistory[locationHistory.length - 1] : null
          if (!lastLocation || calculateDistance(lastLocation[1], lastLocation[0], latitude, longitude) > 10) {
            locationHistory.push([longitude, latitude])

            // Cập nhật vị trí trên bản đồ
            updateLocationOnMap(longitude, latitude)
          }
        },
        (error) => {
          console.error('Lỗi khi theo dõi vị trí:', error.message)

          // Nếu lỗi là do timeout, thử lại với độ chính xác thấp hơn
          if (error.code === error.TIMEOUT && userMarker) {
            navigator.geolocation.clearWatch(userMarker)
            userMarker = navigator.geolocation.watchPosition(
              handlePositionSuccess,
              (err) => console.error('Không thể theo dõi vị trí:', err.message),
              {
                enableHighAccuracy: false,
                timeout: 15000,
                maximumAge: 60000
              }
            )
          }
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 5000 // Cho phép sử dụng cache trong 5 giây
        }
      )
    }
  } else {
    // Dừng theo dõi vị trí
    if (trackingInterval) {
      clearInterval(trackingInterval)
      trackingInterval = null
    }

    // Dừng theo dõi vị trí thời gian thực
    if (userMarker) {
      navigator.geolocation.clearWatch(userMarker)
      userMarker = null
    }
  }
}

// Tính khoảng cách giữa 2 điểm (theo công thức Haversine)
const calculateDistance = (lat1, lon1, lat2, lon2) => {
  const R = 6371e3 // Bán kính trái đất tính bằng mét
  const φ1 = (lat1 * Math.PI) / 180 // φ, λ in radians
  const φ2 = (lat2 * Math.PI) / 180
  const Δφ = ((lat2 - lat1) * Math.PI) / 180
  const Δλ = ((lon2 - lon1) * Math.PI) / 180

  const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2)
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))

  return R * c // khoảng cách tính bằng mét
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
