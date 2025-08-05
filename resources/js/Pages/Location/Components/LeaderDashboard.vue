<template>
  <div class="leader-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
      <h2 class="dashboard-title">Dashboard Lãnh Đạo</h2>
      <div class="header-actions">
        <button @click="togglePlansPanel" class="btn-secondary">
          <span v-if="showPlansPanel">Ẩn</span>
          <span v-else>Hiện</span> Kế hoạch
        </button>
        <button @click="refreshData" class="btn-primary" :disabled="loading">
          <svg v-if="loading" class="animate-spin w-4 h-4 mr-2" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
          Làm mới
        </button>
      </div>
    </div>

    <!-- Captains List -->
    <div class="captains-panel">
      <h3 class="panel-title">Đội trưởng đang hoạt động</h3>
      <div v-if="activeCaptains.length === 0" class="empty-state">Không có đội trưởng nào đang hoạt động</div>
      <div v-else class="captains-grid">
        <div
          v-for="captain in activeCaptains"
          :key="captain.user_id"
          class="captain-card"
          :class="{ selected: selectedCaptain?.user_id === captain.user_id }"
          @click="selectCaptain(captain)"
        >
          <div class="captain-info">
            <div class="captain-name">{{ captain.user_name }}</div>
            <div class="captain-status">
              <div class="status-indicator active"></div>
              <span class="status-text">Đang hoạt động</span>
            </div>
          </div>
          <div class="captain-details">
            <div class="detail-item">
              <span class="detail-label">Cập nhật:</span>
              <span class="detail-value">{{ formatTime(captain.location.recorded_at) }}</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Độ chính xác:</span>
              <span class="detail-value">{{ captain.location.accuracy }}m</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Tốc độ:</span>
              <span class="detail-value">{{ captain.location.speed || 0 }}km/h</span>
            </div>
          </div>
          <div class="captain-actions">
            <!-- <button @click.stop="viewCaptainHistory(captain)" class="btn-sm">Xem lịch sử</button> -->
            <button @click.stop="focusOnCaptain(captain)" class="btn-sm btn-primary">Định vị</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Plans Panel -->
    <div v-if="showPlansPanel" class="plans-panel">
      <div class="plans-header">
        <h3 class="panel-title">Kế hoạch hôm nay</h3>
        <button @click="openPlanForm" class="btn-success btn-sm">+ Thêm kế hoạch</button>
      </div>

      <div v-if="todayPlans.length === 0" class="empty-state">Chưa có kế hoạch nào cho hôm nay</div>
      <div v-else class="plans-list">
        <div v-for="plan in todayPlans" :key="plan.id" class="plan-item" :class="`status-${plan.status}`">
          <div class="plan-info">
            <div class="plan-description">{{ plan.description }}</div>
            <div class="plan-time">{{ plan.start_time }} - {{ plan.end_time }}</div>
          </div>
          <div class="plan-actions">
            <button @click="editPlan(plan)" class="btn-sm">Sửa</button>
            <button @click="deletePlan(plan)" class="btn-sm btn-danger">Xóa</button>
            <button @click="focusOnPlan(plan)" class="btn-sm btn-primary">Xem</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Plan Form Modal - Moved outside dashboard -->
    <Teleport to="body">
      <div v-if="showPlanForm" class="modal-overlay" @click="closePlanForm">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h3>{{ editingPlan ? 'Sửa kế hoạch' : 'Thêm kế hoạch mới' }}</h3>
            <button @click="closePlanForm" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="savePlan" class="plan-form">
            <div class="form-group">
              <label>Mô tả:</label>
              <textarea class="mb-3" v-model="planForm.description" required rows="3"></textarea>
            </div>
            <div class="form-group">
              <label>Ngày:</label>
              <input v-model="planForm.date" type="date" class="mb-3" required />
            </div>

            <div class="form-group">
              <label>Bắt đầu:</label>
              <input v-model="planForm.start_time" type="time" class="mb-3" required />
            </div>
            <div class="form-group">
              <label>Kết thúc:</label>
              <input v-model="planForm.end_time" type="time" class="mb-3" required />
            </div>

            <div class="form-group">
              <label>Vị trí:</label>
              <div class="location-input">
                <input v-model="planForm.lat" type="number" step="any" placeholder="Vĩ độ" required />
                <input v-model="planForm.lng" type="number" step="any" placeholder="Kinh độ" required />
                <button type="button" @click="selectLocationOnMap" class="btn-secondary btn-sm">
                  Chọn trên bản đồ
                </button>
              </div>
            </div>
            <div class="form-actions">
              <button type="button" @click="closePlanForm" class="btn-secondary">Hủy</button>
              <button type="submit" class="btn-primary" :disabled="saving">
                {{ saving ? 'Đang lưu...' : editingPlan ? 'Cập nhật' : 'Tạo mới' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  activeCaptains: {
    type: Array,
    default: () => []
  },
  todayPlans: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['captain-selected', 'focus-captain', 'focus-plan', 'refresh-data', 'select-location'])

// State
const loading = ref(false)
const saving = ref(false)
const selectedCaptain = ref(null)
const showPlansPanel = ref(true)
const showPlanForm = ref(false)
const editingPlan = ref(null)
const selectingLocation = ref(false)

// Plan form
const planForm = reactive({
  description: '',
  date: new Date().toISOString().split('T')[0],
  start_time: '',
  end_time: '',
  lat: null,
  lng: null
})

// Methods
const selectCaptain = (captain) => {
  selectedCaptain.value = captain
  emit('captain-selected', captain)
}

const viewCaptainHistory = (captain) => {
  // TODO: Navigate to captain history page or show modal
  console.log('View history for:', captain)
}

const focusOnCaptain = (captain) => {
  emit('focus-captain', captain)
}

const focusOnPlan = (plan) => {
  emit('focus-plan', plan)
}

const togglePlansPanel = () => {
  showPlansPanel.value = !showPlansPanel.value
}

const refreshData = () => {
  loading.value = true
  emit('refresh-data')
  setTimeout(() => {
    loading.value = false
  }, 1000)
}

const openPlanForm = () => {
  resetPlanForm()
  showPlanForm.value = true
}

const editPlan = (plan) => {
  editingPlan.value = plan
  planForm.description = plan.description
  planForm.date = plan.date
  planForm.start_time = plan.start_time
  planForm.end_time = plan.end_time
  planForm.lat = plan.lat
  planForm.lng = plan.lng
  showPlanForm.value = true
}

const closePlanForm = () => {
  showPlanForm.value = false
  editingPlan.value = null
  selectingLocation.value = false
  resetPlanForm()
}

const resetPlanForm = () => {
  planForm.description = ''
  planForm.date = new Date().toISOString().split('T')[0]
  planForm.start_time = ''
  planForm.end_time = ''
  planForm.lat = null
  planForm.lng = null
}

const selectLocationOnMap = () => {
  selectingLocation.value = true
  showPlanForm.value = false // Ẩn modal để có thể click trên bản đồ
  emit('select-location')
}

const setLocationFromMap = (coordinates) => {
  planForm.lat = coordinates.lat
  planForm.lng = coordinates.lng
  selectingLocation.value = false
  showPlanForm.value = true // Hiện lại modal với tọa độ đã chọn
}

const savePlan = async () => {
  saving.value = true
  try {
    if (editingPlan.value) {
      // Update plan
      await axios.put(`/api/plans/${editingPlan.value.id}`, planForm)
    } else {
      // Create new plan
      await axios.post('/api/plans', planForm)
    }
    closePlanForm()
    emit('refresh-data')
  } catch (error) {
    console.error('Error saving plan:', error)
    alert('Có lỗi xảy ra khi lưu kế hoạch')
  } finally {
    saving.value = false
  }
}

const deletePlan = async (plan) => {
  if (confirm('Bạn có chắc chắn muốn xóa kế hoạch này?')) {
    try {
      await axios.delete(`/api/plans/${plan.id}`)
      emit('refresh-data')
    } catch (error) {
      console.error('Error deleting plan:', error)
      alert('Có lỗi xảy ra khi xóa kế hoạch')
    }
  }
}

const formatTime = (timestamp) => {
  return new Date(timestamp).toLocaleTimeString('vi-VN')
}

defineExpose({
  setLocationFromMap
})
</script>

<style scoped>
.leader-dashboard {
  @apply absolute top-4 left-4 z-20 w-80 max-h-[calc(100vh-2rem)] overflow-hidden flex flex-col;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}

.dashboard-header {
  @apply p-4 border-b border-gray-200 flex items-center justify-between;
}

.dashboard-title {
  @apply text-lg font-bold text-gray-800;
}

.header-actions {
  @apply flex gap-2;
}

.captains-panel,
.plans-panel {
  @apply p-4 border-b border-gray-200 overflow-y-auto;
}

.panel-title {
  @apply text-sm font-semibold text-gray-700 mb-3;
}

.empty-state {
  @apply text-center text-gray-500 text-sm py-4;
}

.captains-grid {
  @apply space-y-3;
}

.captain-card {
  @apply p-3 bg-white rounded-lg border border-gray-200 cursor-pointer transition-all hover:shadow-md;
}

.captain-card.selected {
  @apply border-blue-500 shadow-md;
}

.captain-info {
  @apply flex items-center justify-between mb-2;
}

.captain-name {
  @apply font-medium text-gray-800;
}

.captain-status {
  @apply flex items-center gap-1;
}

.status-indicator {
  @apply w-2 h-2 rounded-full;
}

.status-indicator.active {
  @apply bg-green-500;
}

.status-text {
  @apply text-xs text-gray-600;
}

.captain-details {
  @apply space-y-1 mb-3;
}

.detail-item {
  @apply flex justify-between text-xs;
}

.detail-label {
  @apply text-gray-500;
}

.detail-value {
  @apply text-gray-800 font-mono;
}

.captain-actions {
  @apply flex gap-2;
}

.plans-header {
  @apply flex items-center justify-between mb-3;
}

.plans-list {
  @apply space-y-2;
}

.plan-item {
  @apply p-3 bg-white rounded-lg border border-gray-200;
}

.plan-item.status-active {
  @apply border-green-500 bg-green-50;
}

.plan-item.status-completed {
  @apply border-gray-400 bg-gray-50;
}

.plan-info {
  @apply mb-2;
}

.plan-description {
  @apply font-medium text-gray-800 text-sm;
}

.plan-time {
  @apply text-xs text-gray-600;
}

.plan-actions {
  @apply flex gap-2;
}

/* Buttons */
.btn-primary,
.btn-secondary,
.btn-success,
.btn-danger,
.btn-sm {
  @apply px-3 py-1.5 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2;
}

.btn-sm {
  @apply px-2 py-1 text-xs;
}

.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 focus:ring-gray-500;
}

.btn-success {
  @apply bg-green-600 hover:bg-green-700 text-white focus:ring-green-500;
}

.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white focus:ring-red-500;
}

/* Modal */
.modal-overlay {
  @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999];
}

.modal-content {
  @apply bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto;
}

.modal-header {
  @apply flex items-center justify-between p-4 border-b border-gray-200;
}

.modal-close {
  @apply text-gray-400 hover:text-gray-600 text-2xl;
}

.plan-form {
  @apply p-4;
}

.form-group {
  @apply mb-4;
}

.form-row {
  @apply flex gap-4;
}

.form-group label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.form-group input,
.form-group textarea {
  @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent;
}

.location-input {
  @apply flex gap-2;
}

.location-input input {
  @apply flex-1;
}

.form-actions {
  @apply flex justify-end gap-2 mt-6;
}
</style>
