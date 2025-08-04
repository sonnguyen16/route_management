<template>
  <div
    class="fixed bottom-0 left-0 right-0 bg-white rounded-t-lg shadow-lg transform transition-transform duration-300 ease-in-out z-20"
    :class="isSheetOpen ? 'translate-y-0' : 'translate-y-full'"
    style="max-height: 80vh; overflow-y: auto"
  >
    <div class="p-4">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Thông tin vị trí</h3>
        <button @click="closeSheet" class="text-gray-500 hover:text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div v-if="locationData">
        <div class="grid grid-cols-2 gap-2 mb-2">
          <div class="font-medium">Thời gian:</div>
          <div>{{ locationData.time }}</div>
        </div>
        <div class="grid grid-cols-2 gap-2 mb-2">
          <div class="font-medium">Kinh độ:</div>
          <div>{{ locationData.lng }}</div>
        </div>
        <div class="grid grid-cols-2 gap-2 mb-2">
          <div class="font-medium">Vĩ độ:</div>
          <div>{{ locationData.lat }}</div>
        </div>
        <div class="grid grid-cols-2 gap-2 mb-2">
          <div class="font-medium">Độ chính xác:</div>
          <div>{{ locationData.accuracy ? `${locationData.accuracy} mét` : 'Không có dữ liệu' }}</div>
        </div>
        <div class="grid grid-cols-2 gap-2 mb-2">
          <div class="font-medium">Độ cao:</div>
          <div>{{ locationData.altitude ? `${locationData.altitude} mét` : 'Không có dữ liệu' }}</div>
        </div>
        <div class="grid grid-cols-2 gap-2 mb-2">
          <div class="font-medium">Hướng di chuyển:</div>
          <div>{{ locationData.heading ? `${locationData.heading}°` : 'Không có dữ liệu' }}</div>
        </div>
        <div class="grid grid-cols-2 gap-2 mb-2">
          <div class="font-medium">Tốc độ:</div>
          <div>{{ locationData.speed ? `${(locationData.speed * 3.6).toFixed(1)} km/h` : 'Không có dữ liệu' }}</div>
        </div>
      </div>
      <div v-else class="text-center py-4">
        <p>Không có dữ liệu vị trí</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  locationData: Object,
  isSheetOpen: Boolean
})

const emit = defineEmits(['close-sheet'])

const closeSheet = () => {
  emit('close-sheet')
}
</script>

<style scoped>
/* Thêm CSS nếu cần thiết */
</style>
