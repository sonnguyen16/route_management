<template>
  <div class="captain-tracker">
    <LocationInfoPanel
      :location="currentLocation"
      :is-tracking="isTracking"
      title="Vị trí đội trưởng"
      @toggle-tracking="toggleTracking"
    />

    <!-- Background tracking notification -->
    <div v-if="backgroundTracking" class="background-notification">
      <div class="notification-content">
        <div class="notification-icon">
          <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </div>
        <div class="notification-text">
          <div class="notification-title">Tracking nền đang hoạt động</div>
          <div class="notification-subtitle">Vị trí được gửi tự động mỗi 30 giây</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import LocationInfoPanel from './LocationInfoPanel.vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['location-updated'])

// State
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
const backgroundTracking = ref(false)
let watchId = null
let backgroundInterval = null
let echo = null

onMounted(() => {
  initializeWebSocket()
  getCurrentLocation()

  // Check if user navigates away and setup background tracking
  setupVisibilityChangeHandler()
})

onBeforeUnmount(() => {
  stopTracking()
  if (echo) {
    echo.disconnect()
  }
  clearInterval(backgroundInterval)
})

const initializeWebSocket = () => {
  // Initialize Laravel Echo for real-time communication
  window.Pusher = Pusher

  const port =
    import.meta.env.VITE_REVERB_PORT ?? ((import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https' ? 443 : 8080)

  echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST ?? 'localhost',
    wsPort: port,
    wssPort: port,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    auth: {
      headers: {
        Authorization: `Bearer ${document.querySelector('meta[name="csrf-token"]').content}`
      }
    }
  })

  // Listen for location updates from other captains (optional)
  echo.private(`captain.${props.user.id}`).listen('.location.updated', (e) => {
    console.log('Location update received:', e)
  })
}

const setupVisibilityChangeHandler = () => {
  document.addEventListener('visibilitychange', () => {
    if (isTracking.value) {
      if (document.hidden) {
        // Page is hidden, start background tracking
        startBackgroundTracking()
      } else {
        // Page is visible again, stop background tracking
        stopBackgroundTracking()
      }
    }
  })
}

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

const watchPosition = () => {
  if (navigator.geolocation) {
    watchId = navigator.geolocation.watchPosition(
      (position) => {
        handlePositionSuccess(position)
        sendLocationToServer(position)
      },
      (error) => {
        console.error('Lỗi khi theo dõi vị trí:', error.message)
        if (error.code === error.TIMEOUT) {
          alert('Quá thời gian chờ định vị. Đang thử lại với độ chính xác thấp hơn.')
          stopWatchingPosition()
          watchId = navigator.geolocation.watchPosition(
            (position) => {
              handlePositionSuccess(position)
              sendLocationToServer(position)
            },
            (fallbackError) => {
              console.error('Lỗi khi theo dõi vị trí (fallback):', fallbackError.message)
              alert(`Không thể theo dõi vị trí: ${fallbackError.message}`)
            },
            { enableHighAccuracy: false, timeout: 30000, maximumAge: 5000 }
          )
        } else {
          alert(
            `Không thể theo dõi vị trí: ${error.message}. Vui lòng kiểm tra quyền truy cập vị trí trong trình duyệt.`
          )
        }
      },
      {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 0
      }
    )
  }
}

const stopWatchingPosition = () => {
  if (watchId !== null) {
    navigator.geolocation.clearWatch(watchId)
    watchId = null
    console.log('Đã dừng theo dõi vị trí')
  }
}

const handlePositionSuccess = (position) => {
  const { latitude, longitude, accuracy, altitude, heading, speed } = position.coords
  const timestamp = new Date()

  currentLocation.value = {
    lat: latitude,
    lng: longitude,
    time: timestamp.toLocaleTimeString(),
    accuracy: accuracy ? `${accuracy.toFixed(2)} mét` : 'Không xác định',
    altitude: altitude ? `${altitude ? altitude.toFixed(2) : 0} mét` : 'Không xác định',
    heading: heading ? `${heading.toFixed(2)}°` : 'Không xác định',
    speed: speed ? `${(speed * 3.6).toFixed(2)} km/h` : 'Không xác định'
  }

  emit('location-updated', {
    lat: latitude,
    lng: longitude,
    accuracy,
    altitude,
    heading,
    speed,
    timestamp
  })
}

const sendLocationToServer = async (position) => {
  try {
    const { latitude, longitude, accuracy, altitude, heading, speed } = position.coords

    await axios.post('/api/location', {
      lat: latitude,
      lng: longitude,
      accuracy,
      altitude,
      heading,
      speed
    })
  } catch (error) {
    console.error('Error sending location to server:', error)
  }
}

const toggleTracking = () => {
  isTracking.value = !isTracking.value

  if (isTracking.value) {
    watchPosition()
  } else {
    stopTracking()
  }
}

const stopTracking = () => {
  stopWatchingPosition()
  stopBackgroundTracking()
  isTracking.value = false
}

const startBackgroundTracking = () => {
  if (backgroundInterval) return

  backgroundTracking.value = true

  backgroundInterval = setInterval(() => {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        sendLocationToServer(position)
        handlePositionSuccess(position)
      },
      (error) => {
        console.error('Background tracking error:', error)
      },
      {
        enableHighAccuracy: false,
        timeout: 10000,
        maximumAge: 30000
      }
    )
  }, 30000) // Send location every 30 seconds
}

const stopBackgroundTracking = () => {
  if (backgroundInterval) {
    clearInterval(backgroundInterval)
    backgroundInterval = null
    backgroundTracking.value = false
  }
}

// Expose methods for parent component
defineExpose({
  getCurrentLocation,
  currentLocation,
  isTracking
})
</script>

<style scoped>
.captain-tracker {
  @apply relative;
}

.background-notification {
  @apply absolute top-20 right-2 z-30 max-w-sm;
}

@media (max-width: 640px) {
  .background-notification {
    @apply top-20 left-2 right-2 max-w-none;
  }
}

.notification-content {
  @apply bg-blue-50 border border-blue-200 rounded-lg p-3 shadow-lg flex items-start gap-3;
  backdrop-filter: blur(10px);
}

.notification-icon {
  @apply flex-shrink-0 mt-0.5;
}

.notification-text {
  @apply flex-1 min-w-0;
}

.notification-title {
  @apply text-sm font-medium text-blue-800;
}

.notification-subtitle {
  @apply text-xs text-blue-600 mt-1;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.background-notification {
  animation: slideIn 0.3s ease-out;
}
</style>
