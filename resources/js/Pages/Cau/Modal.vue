<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch, ref } from 'vue'
import Input from '@/Components/Input.vue'
import Select from '@/Components/Select.vue'
import MapModal from '@/Components/MapModal.vue'

const props = defineProps({
  cau: Object,
  keyModal: Number,
  loai_ket_cau_nhip: Object,
  loai_cau: Object,
  tuyen_duong: Object,
  isEdit: {
    type: Boolean,
    default: false
  }
})

const emits = defineEmits(['closeModal', 'refresh'])

const form = useForm({
  id: '',
  ten: '',
  ly_trinh: '',
  chieu_rong: '',
  chieu_dai: '',
  loai_ket_cau_nhip_id: '',
  nam_khai_thac: '',
  tai_trong: '',
  trong_tai_khai_thac: '',
  kiem_dinh: '',
  tuyen_duong_id: '',
  loai_cau_id: '',
  vi_tri: '',
  lat: '',
  lng: ''
})

const submit = () => {
  form.post(route('cau.store'), {
    onSuccess: () => {
      closeModal()
      emits('refresh')
    },
    onError: (err) => {
      console.log(err)
    }
  })
}

watch(
  () => props.keyModal,
  () => {
    if (props.cau) {
      Object.assign(form, props.cau)
    } else {
      form.reset()
      form.id = ''
      form.ten = ''
      form.ly_trinh = ''
      form.chieu_rong = ''
      form.chieu_dai = ''
      form.loai_ket_cau_nhip_id = ''
      form.nam_khai_thac = ''
      form.tai_trong = ''
      form.trong_tai_khai_thac = ''
      form.kiem_dinh = ''
      form.tuyen_duong_id = ''
      form.loai_cau_id = ''
      form.vi_tri = ''
      form.lat = ''
      form.lng = ''
    }
  }
)
const closeModal = () => {
  emits('closeModal')
  form.reset()
  form.clearErrors()
}

const showMap = ref(false)

const openMapModal = () => (showMap.value = true)

const handleRouteSelect = (point) => {
  showMap.value = false
  form.lng = point.point[0]
  form.lat = point.point[1]
}
</script>

<template>
  <div class="modal fade" id="modal">
    <div class="modal-dialog modal-dialog-centered" :class="[isEdit ? 'modal-lg' : 'modal-lg']">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="row">
            <!-- Main Content -->
            <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
              <div class="form-group">
                <label for="name">Tên cầu</label>
                <Input v-model="form.ten" :errors="form.errors.ten" />
              </div>
              <div class="form-group">
                <label for="loai_cau_id">Loại cầu</label>
                <Select
                  v-model="form.loai_cau_id"
                  :options="loai_cau"
                  :errors="form.errors.loai_cau_id"
                  id="loai_cau_id"
                  option-default="Chọn loại cầu"
                />
              </div>
              <div class="form-group">
                <label for="loai_ket_cau_nhip_id">Loại kết cấu nhịp</label>
                <Select
                  v-model="form.loai_ket_cau_nhip_id"
                  :options="loai_ket_cau_nhip"
                  :errors="form.errors.loai_ket_cau_nhip_id"
                  id="loai_ket_cau_nhip_id"
                  option-default="Chọn loại kết cấu nhịp"
                />
              </div>
              <div class="form-group">
                <label for="name">Lý trình</label>
                <Input v-model="form.ly_trinh" :errors="form.errors.ly_trinh" />
              </div>
              <div class="form-group">
                <label for="name">Chiều rộng</label>
                <Input v-model="form.chieu_rong" :errors="form.errors.chieu_rong" />
              </div>
              <div class="form-group">
                <label for="name">Chiều dài</label>
                <Input v-model="form.chieu_dai" :errors="form.errors.chieu_dai" />
              </div>

              <div class="form-group">
                <label for="name">Năm khai thác</label>
                <Input v-model="form.nam_khai_thac" :errors="form.errors.nam_khai_thac" />
              </div>
              <div class="form-group">
                <label for="name">Tải trọng thiết kế</label>
                <Input v-model="form.tai_trong" :errors="form.errors.tai_trong" />
              </div>
              <div class="form-group">
                <label for="name">Tải trọng khai thác</label>
                <Input v-model="form.trong_tai_khai_thac" :errors="form.errors.trong_tai_khai_thac" />
              </div>
              <div class="form-group">
                <label for="name">Năm kiểm định</label>
                <Input v-model="form.kiem_dinh" :errors="form.errors.kiem_dinh" />
              </div>
              <div class="form-group">
                <label for="tuyen_duong_id">Tuyến đường</label>
                <Select
                  v-model="form.tuyen_duong_id"
                  :options="tuyen_duong"
                  :errors="form.errors.tuyen_duong_id"
                  id="tuyen_duong_id"
                  option-default="Chọn tuyến đường"
                />
              </div>

              <div class="form-group">
                <label for="name">Vị trí</label>
                <Input v-model="form.vi_tri" :errors="form.errors.vi_tri" />
              </div>
              <div class="form-group">
                <label for="tu_km_id">Chọn vị trí</label>
                <button class="btn btn-info btn-sm mb-3 w-1/2" @click="openMapModal">📍 Chọn vị trí</button>
              </div>

              <div class="form-group">
                <label for="tu_km_id">Vị trí đã chọn:</label>
                <div class="flex gap-3">
                  <input type="text" class="form-control mb-3" v-model="form.lng" />
                  <input type="text" class="form-control" v-model="form.lat" />
                </div>
              </div>
            </div>
            <!-- Sidebar Actions -->
          </div>
        </div>
        <div class="modal-footer">
          <button @click.prevent="submit" type="submit" class="btn btn-success">Lưu</button>
          <button @click.prevent="closeModal" type="reset" class="btn btn-secondary ml-2">Hủy</button>
        </div>
      </div>
    </div>
  </div>

  <MapModal
    v-if="showMap"
    :show="showMap"
    :existingMarker="[form.lng, form.lat]"
    :mode="'single'"
    @close="showMap = false"
    @selectRoute="handleRouteSelect"
  />
</template>
<!--test-->
