<template>
  <div class="location-info-panel">
    <div class="location-info-card">
      <!-- Header có thể thu gọn trên mobile -->
      <div class="location-header">
        <h3 class="location-title">{{ title }}</h3>
        <button @click="togglePanel" class="toggle-button sm:hidden" :class="{ rotated: !isExpanded }">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
      </div>

      <!-- Nội dung có thể ẩn/hiện trên mobile -->
      <div class="location-content" :class="{ hidden: !isExpanded && isMobile }">
        <div class="location-grid">
          <div class="location-item">
            <span class="location-label">Kinh độ:</span>
            <span class="location-value">{{ formatCoordinate(location.lng) }}</span>
          </div>
          <div class="location-item">
            <span class="location-label">Vĩ độ:</span>
            <span class="location-value">{{ formatCoordinate(location.lat) }}</span>
          </div>
          <div class="location-item">
            <span class="location-label">Độ chính xác:</span>
            <span class="location-value" :class="getAccuracyColor(location.accuracy)">
              {{ location.accuracy || 'Không xác định' }}
            </span>
          </div>
          <div class="location-item">
            <span class="location-label">Cập nhật:</span>
            <span class="location-value">{{ location.time || 'Chưa có dữ liệu' }}</span>
          </div>
        </div>

        <!-- Buttons responsive -->
        <div class="location-buttons">
          <button
            @click="$emit('toggle-tracking')"
            class="btn-primary"
            :class="isTracking ? 'btn-danger' : 'btn-success'"
          >
            <span class="btn-text-mobile">{{ isTracking ? 'Dừng' : 'Bắt đầu' }}</span>
            <span class="btn-text-desktop">{{ isTracking ? 'Dừng theo dõi' : 'Bắt đầu theo dõi' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
  location: {
    type: Object,
    default: () => ({
      lat: null,
      lng: null,
      time: null,
      accuracy: null,
      altitude: null,
      heading: null,
      speed: null
    })
  },
  isTracking: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Vị trí hiện tại'
  }
})

const emit = defineEmits(['toggle-tracking', 'toggle-panel'])

// Responsive states
const isExpanded = ref(true)
const isMobile = ref(false)

// Format tọa độ để hiển thị ngắn gọn
const formatCoordinate = (coord) => {
  if (!coord) return 'Đang xác định...'
  return parseFloat(coord).toFixed(6)
}

// Hàm xác định màu sắc dựa trên độ chính xác
const getAccuracyColor = (accuracy) => {
  if (!accuracy) return 'text-gray-500'

  const numericAccuracy = parseFloat(accuracy.toString().replace(' mét', ''))

  if (numericAccuracy <= 5) return 'text-green-600 font-bold'
  if (numericAccuracy <= 10) return 'text-green-500'
  if (numericAccuracy <= 20) return 'text-yellow-500'
  if (numericAccuracy <= 50) return 'text-orange-500'
  return 'text-red-500'
}

// Toggle panel thông tin trên mobile
const togglePanel = () => {
  isExpanded.value = !isExpanded.value
  emit('toggle-panel')
}

// Check if device is mobile
const checkIfMobile = () => {
  isMobile.value = window.innerWidth < 640
  if (!isMobile.value) {
    isExpanded.value = true
  }
}

onMounted(() => {
  checkIfMobile()
  window.addEventListener('resize', checkIfMobile)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkIfMobile)
})
</script>

<style scoped>
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

.btn-primary {
  @apply px-3 py-2 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 w-full;
}

@media (max-width: 640px) {
  .btn-primary {
    @apply px-2 py-1.5 text-xs flex-1 min-w-0;
  }
}

.btn-primary.btn-success {
  @apply bg-green-600 hover:bg-green-700 text-white focus:ring-green-500;
}

.btn-primary.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white focus:ring-red-500;
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
