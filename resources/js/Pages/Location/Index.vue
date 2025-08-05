<template>
  <MainLayout>
    <div class="cont position-relative h-screen w-full overflow-hidden">
      <!-- Map Container -->
      <MapContainer
        ref="mapRef"
        :center="[107.242997, 10.495088]"
        :zoom="14"
        :locations="activeCaptains"
        :plans="todayPlans"
        :user-role="$page.props.auth.user.role"
        @map-click="handleMapClick"
        @map-ready="handleMapReady"
      />

      <!-- Leader Dashboard -->
      <LeaderDashboard
        v-if="$page.props.auth.user.role === 'leader'"
        ref="leaderDashboard"
        :active-captains="activeCaptains"
        :today-plans="todayPlans"
        @captain-selected="handleCaptainSelected"
        @focus-captain="handleFocusCaptain"
        @focus-plan="handleFocusPlan"
        @refresh-data="refreshData"
        @select-location="enableLocationSelection"
      />

      <!-- Captain Tracker -->
      <CaptainTracker
        v-if="$page.props.auth.user.role === 'captain'"
        :user="$page.props.auth.user"
        @location-updated="handleLocationUpdated"
      />

      <!-- Location selection indicator -->
      <div v-if="selectingLocation" class="location-selector">
        <div class="selector-content">
          <div class="selector-icon">📍</div>
          <div class="selector-text">Click vào bản đồ để chọn vị trí cho kế hoạch</div>
          <button @click="cancelLocationSelection" class="selector-cancel">Hủy</button>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { usePage } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import MapContainer from './Components/MapContainer.vue'
import LeaderDashboard from './Components/LeaderDashboard.vue'
import CaptainTracker from './Components/CaptainTracker.vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

// Refs
const mapRef = ref()
const leaderDashboard = ref()

// State
const activeCaptains = ref([])
const todayPlans = ref([])
const selectingLocation = ref(false)
const selectedCaptain = ref(null)

let echo = null

onMounted(() => {
  initializeWebSocket()
  loadInitialData()
})

onBeforeUnmount(() => {
  if (echo) {
    echo.disconnect()
  }
})

const initializeWebSocket = () => {
  // Only for leaders to receive real-time location updates
  if (usePage().props.auth.user.role === 'leader') {
    window.Pusher = Pusher

    const port = import.meta.env.VITE_REVERB_PORT ?? (
      (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https' ? 443 : 8080
    )

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

    // Listen for location updates
    echo.private('location-updates').listen('.location.updated', (e) => {
      updateCaptainLocation(e)
    })
  }
}

const loadInitialData = async () => {
  try {
    if (usePage().props.auth.user.role === 'leader') {
      // Load active captains and plans for leaders
      const [captainsResponse, plansResponse] = await Promise.all([
        axios.get('/api/location/active-captains'),
        axios.get('/api/plans/today')
      ])

      activeCaptains.value = captainsResponse.data.data
      todayPlans.value = plansResponse.data.data
    } else {
      // Load only user's plans for captains
      const plansResponse = await axios.get('/api/plans/today')
      todayPlans.value = plansResponse.data.data
    }
  } catch (error) {
    console.error('Error loading initial data:', error)
  }
}

const refreshData = async () => {
  await loadInitialData()
}

const updateCaptainLocation = (locationData) => {
  const captainIndex = activeCaptains.value.findIndex((captain) => captain.user_id === locationData.user_id)

  if (captainIndex !== -1) {
    // Update existing captain
    activeCaptains.value[captainIndex] = {
      ...activeCaptains.value[captainIndex],
      location: locationData.location
    }
  } else {
    // Add new captain
    activeCaptains.value.push({
      user_id: locationData.user_id,
      user_name: locationData.user_name,
      location: locationData.location
    })
  }

  // Update map
  if (mapRef.value) {
    mapRef.value.updateCaptainsLocations(activeCaptains.value)
  }
}

const handleLocationUpdated = (locationData) => {
  // For captains, update their own location on map
  if (mapRef.value) {
    mapRef.value.updateUserLocation(locationData.lng, locationData.lat)
    mapRef.value.flyTo([locationData.lng, locationData.lat])
  }
}

const handleMapClick = (coordinates) => {
  if (selectingLocation.value && leaderDashboard.value) {
    leaderDashboard.value.setLocationFromMap(coordinates)
    selectingLocation.value = false
  }
}

const handleMapReady = (map) => {
  console.log('Map is ready:', map)
}

const handleCaptainSelected = (captain) => {
  selectedCaptain.value = captain
}

const handleFocusCaptain = (captain) => {
  if (mapRef.value && captain.location) {
    mapRef.value.flyTo([captain.location.lng, captain.location.lat], 16)
  }
}

const handleFocusPlan = (plan) => {
  if (mapRef.value) {
    mapRef.value.flyTo([plan.lng, plan.lat], 16)
  }
}

const enableLocationSelection = () => {
  selectingLocation.value = true
}

const cancelLocationSelection = () => {
  selectingLocation.value = false
}
</script>

<style scoped>
.cont {
  position: relative;
  width: 100%;
  height: 100vh;
}

.location-selector {
  @apply absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50;
}

.selector-content {
  @apply bg-white rounded-lg shadow-xl p-6 text-center border-2 border-dashed border-blue-500;
  min-width: 300px;
}

.selector-icon {
  @apply text-4xl mb-2;
}

.selector-text {
  @apply text-gray-700 mb-4;
}

.selector-cancel {
  @apply px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md text-sm transition-colors;
}

@keyframes pulse {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.location-selector {
  animation: pulse 2s infinite;
}
</style>
