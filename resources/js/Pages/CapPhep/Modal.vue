<script setup>
import { router, useForm } from '@inertiajs/vue3'
import Select from '@/Components/Select.vue'
import { ref, watch } from 'vue'
import Input from '@/Components/Input.vue'
import MapModal from '@/Components/MapModal.vue'

const props = defineProps({
  cap_phep: Object,
  tuyen_duong: Object,
  cap_phep_cha: Object,
  don_vi: Object,
  isEdit: {
    type: Boolean,
    default: false
  },
  flag: {
    type: Boolean,
    default: false
  },
  keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange'])

const form = useForm({
  id: '',
  don_vi_id: '',
  tuyen_duong_id: '',
  so_cap_phep: '',
  ngay_cap_phep: '',
  ngay_het_han: '',
  noi_dung: '',
  tu_km: '',
  den_km: '',
  cap_phep_id: '',
  route_geometry: null
})

const submit = () => {
  form.post(route('cap-phep.store'), {
    onSuccess: () => {
      closeModal()
      emits('refresh')
    },
    onError: (err) => {
      console.log(err)
    }
  })
}

let formFile = useForm({
  tuyen_duong_id: '',
  danh_muc: null,
  file: []
})

watch(
  () => props.keyModal,
  () => {
    if (props.cap_phep) {
      Object.assign(form, props.cap_phep)
      if (props.cap_phep?.toa_do?.length > 0) {
        routeCoords.value = {
          start: `${props.cap_phep.toa_do[0]?.lng || ''}, ${props.cap_phep.toa_do[0]?.lat || ''}`,
          end: `${props.cap_phep.toa_do[props.cap_phep.toa_do.length - 1]?.lng || ''}, ${
            props.cap_phep.toa_do[props.cap_phep.toa_do.length - 1]?.lat || ''
          }`
        }
      } else {
        routeCoords.value = null
      }
    } else {
      form.reset()
      form.id = ''
      form.don_vi_id = ''
      form.tuyen_duong_id = ''
      form.so_cap_phep = ''
      form.ngay_cap_phep = ''
      form.ngay_het_han = ''
      form.noi_dung = ''
      form.tu_km = ''
      form.den_km = ''
      form.cap_phep_id = ''
      form.route_geometry = null

      routeCoords.value = null
    }
    if (props.cap_phep_cha) {
      form.cap_phep_id = props.cap_phep_cha.id
      form.tuyen_duong_id = props.cap_phep_cha.tuyen_duong_id
    }
  }
)
const closeModal = () => {
  emits('closeModal')
  form.reset()
  form.clearErrors()
}

const uploadedFiles = ref([])
const removeFileUploaded = (id) => {
  uploadedFiles.value = uploadedFiles.value.filter((file) => file.id !== id)
  router.delete(route('tai-lieu.delete', { id: id }), {
    onSuccess: () => {
      emits('refresh')
    }
  })
}

const uploadFiles = (files) => {
  formFile.file = files
  formFile.post(route('tai-lieu.store'), {
    onSuccess: () => {
      emits('refresh')
    },
    onError: (err) => {
      console.log(err)
    }
  })
}

const showMap = ref(false)
const routeCoords = ref(null)

const openMapModal = () => (showMap.value = true)

const handleRouteSelect = (route) => {
  routeCoords.value = route
  form.route_geometry = route.route
  showMap.value = false
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
              <div :class="[props.flag ? 'disable_tr' : '']">
                <div class="form-group">
                  <label for="loai_tuyen_duong">Tuyến đường</label>
                  <Select
                    v-model="form.tuyen_duong_id"
                    :errors="form.errors.tuyen_duong_id"
                    :options="tuyen_duong"
                    id="tuyen_duong_id"
                    option-default="Chọn tuyến đường"
                  />
                </div>
              </div>
              <div :class="[props.flag ? '' : 'disable_tr']">
                <div class="form-group">
                  <label for="so_cap_phep">Nội dung</label>
                  <Input v-model="form.noi_dung" :errors="form.errors.noi_dung" />
                </div>
                <div class="form-group">
                  <label for="tu_km">Từ km</label>
                  <Input v-model="form.tu_km" type="text" :errors="form.errors.tu_km" placeholder="Từ km" id="tu_km" />
                </div>
                <div class="form-group">
                  <label for="den_km">Đến km</label>
                  <Input
                    v-model="form.den_km"
                    type="text"
                    :errors="form.errors.den_km"
                    placeholder="Đến km"
                    id="den_km"
                  />
                </div>

                <div class="form-group">
                  <label for="tu_km_id">Chọn tuyến đường</label>
                  <button class="btn btn-info btn-sm mb-3 w-1/2" @click="openMapModal">📍 Chọn tuyến đường</button>
                </div>

                <div v-if="routeCoords" class="form-group">
                  <label for="tu_km_id">Tọa độ đã chọn:</label>
                  <div class="flex gap-3">
                    <input type="text" class="form-control mb-3" v-model="routeCoords.start" />
                    <input type="text" class="form-control" v-model="routeCoords.end" />
                  </div>
                </div>

                <div class="form-group">
                  <label for="ngay_cap_phep">Ngày cấp phép</label>
                  <Input v-model="form.ngay_cap_phep" type="date" :errors="form.errors.ngay_cap_phep" />
                </div>
                <div class="form-group">
                  <label for="ngay_cap_phep">Ngày hết hạn</label>
                  <Input v-model="form.ngay_het_han" type="date" :errors="form.errors.ngay_het_han" />
                </div>
                <div class="form-group">
                  <label for="so_cap_phep">Số cấp phép</label>
                  <Input v-model="form.so_cap_phep" :errors="form.errors.so_cap_phep" />
                </div>
                <div class="form-group">
                  <label for="ma_phan_cap">Đơn vị cấp phép</label>
                  <Select
                    v-model="form.don_vi_id"
                    :errors="form.errors.don_vi_id"
                    :options="don_vi"
                    id="don_vi_id"
                    option-default="Chọn đơn vị"
                  />
                </div>
                <div class="form-group">
                  <label for="so_cap_phep">Vi phạm</label>
                  <Input v-model="form.so_cap_phep" :errors="form.errors.so_cap_phep" />
                </div>
              </div>
            </div>
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
    :existingRoute="props.cap_phep?.toa_do?.length > 0 ? props.cap_phep.toa_do : null"
    @close="showMap = false"
    @selectRoute="handleRouteSelect"
  />
</template>
