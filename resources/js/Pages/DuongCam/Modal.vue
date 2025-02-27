<script setup>
import Input from '@/Components/Input.vue'
import { useForm } from '@inertiajs/vue3'
import Select from '@/Components/Select.vue'
import { ref, watch } from 'vue'
import MapModal from '@/Components/MapModal.vue'

const props = defineProps({
  duong_cam: Object,
  duong_cam_cha: Object,
  tuyen_duong: Object,
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

let form = useForm({
  id: '',
  tuyen_duong_id: '',
  don_vi_quyet_dinh_id: '',
  don_vi_thuc_hien_id: '',
  tu_ngay: '',
  den_ngay: '',
  tu_km: '',
  den_km: '',
  noi_dung: '',
  duong_cam_id: '',
  route_geometry: null
})

const submit = () => {
  form.post(route('duong-cam.store'), {
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
    if (props.duong_cam) {
      Object.assign(form, props.duong_cam)
      if (props.duong_cam?.toa_do?.length > 0) {
        routeCoords.value = {
          lng_start: props.duong_cam.toa_do[0]?.lng,
          lat_start: props.duong_cam.toa_do[0]?.lat,
          lng_end: props.duong_cam.toa_do[1]?.lng,
          lat_end: props.duong_cam.toa_do[1]?.lat
        }
      } else {
        routeCoords.value = null
      }
    } else {
      form.reset()
      form.id = ''
      form.tuyen_duong_id = ''
      form.don_vi_quyet_dinh_id = ''
      form.don_vi_thuc_hien_id = ''
      form.tu_ngay = ''
      form.den_ngay = ''
      form.noi_dung = ''
      form.tu_km = ''
      form.den_km = ''
      form.duong_cam_id = ''
      form.route_geometry = null

      routeCoords.value = null
    }
    if (props.duong_cam_cha) {
      form.duong_cam_id = props.duong_cam_cha.id
      form.tuyen_duong_id = props.duong_cam_cha.tuyen_duong_id
    }
  }
)
const closeModal = () => {
  emits('closeModal')
  form.reset()
  form.clearErrors()
}

const showMap = ref(false)
const routeCoords = ref(null)

const openMapModal = () => (showMap.value = true)

const handleRouteSelect = (route) => {
  routeCoords.value = {
    lng_start: route.start[0],
    lat_start: route.start[1],
    lng_end: route.end[0],
    lat_end: route.end[1]
  }
  form.route_geometry = route.route
  showMap.value = false
}
</script>

<template>
  <div class="modal fade" id="modal">
    <div class="modal-dialog modal-dialog-centered" :class="[isEdit ? 'modal-lg' : 'modal-lg']">
      <!--'modal-xl'-->
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="row">
            <!-- Main Content -->
            <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
              <div :class="[props.flag ? 'disable_tr' : '']">
                <div class="form-group">
                  <label for="tuyen_duong_id">Tên tuyến đường</label>

                  <Select
                    v-model="form.tuyen_duong_id"
                    :options="tuyen_duong"
                    :errors="form.errors.tuyen_duong_id"
                    id="tuyen_duong_id"
                    option-default="Chọn tuyến đường"
                  />
                </div>
              </div>
              <div :class="[props.flag ? '' : 'disable_tr']">
                <div class="form-group">
                  <label for="ly_do">Nội dung cấm</label>
                  <Input v-model="form.noi_dung" :errors="form.errors.noi_dung" />
                </div>
                <div class="form-group">
                  <label for="tu_km_id">Từ km</label>
                  <Input
                    v-model="form.tu_km"
                    type="text"
                    :errors="form.errors.tu_km"
                    placeholder="Từ km"
                    id="tu_km_id"
                  />
                </div>
                <div class="form-group">
                  <label for="den_km_id">Đến km</label>
                  <Input
                    v-model="form.den_km"
                    type="text"
                    :errors="form.errors.tu_km"
                    placeholder="Đến km"
                    id="den_km_id"
                  />
                </div>

                <div class="form-group">
                  <label for="tu_km_id">Chọn tuyến đường</label>
                  <button class="btn btn-info btn-sm mb-3 w-1/2" @click="openMapModal">📍 Chọn tuyến đường</button>
                </div>

                <div v-if="routeCoords" class="form-group">
                  <label for="tu_km_id">Tọa độ điểm đầu:</label>
                  <div class="flex gap-3">
                    <input readonly="readonly" type="text" class="form-control mb-3" v-model="routeCoords.lng_start" />
                    <input readonly="readonly" type="text" class="form-control" v-model="routeCoords.lat_start" />
                  </div>
                </div>

                <div v-if="routeCoords" class="form-group">
                  <label for="tu_km_id">Tọa độ điểm cuối:</label>
                  <div class="flex gap-3">
                    <input readonly="readonly" type="text" class="form-control mb-3" v-model="routeCoords.lng_end" />
                    <input readonly="readonly" type="text" class="form-control" v-model="routeCoords.lat_end" />
                  </div>
                </div>

                <div class="form-group">
                  <label for="tu_ngay">Từ ngày</label>
                  <Input v-model="form.tu_ngay" type="date" :errors="form.errors.tu_ngay" />
                </div>

                <div class="form-group">
                  <label for="den_ngay">Đến ngày</label>
                  <Input v-model="form.den_ngay" type="date" :errors="form.errors.den_ngay" />
                </div>
                <div class="form-group">
                  <label for="don_vi_quyet_dinh_id">Đơn vị quyết định</label>
                  <Select
                    v-model="form.don_vi_quyet_dinh_id"
                    :options="don_vi"
                    :errors="form.errors.don_vi_quyet_dinh_id"
                    id="don_vi_quyet_dinh_id"
                    option-default="Chọn đơn vị quyết định"
                  />
                </div>
                <div class="form-group">
                  <label for="don_vi_thuc_hien_id">Đơn vị thực hiện</label>
                  <Select
                    v-model="form.don_vi_thuc_hien_id"
                    :options="don_vi"
                    :errors="form.errors.don_vi_thuc_hien_id"
                    id="don_vi_thuc_hien_id"
                    option-default="Chọn đơn vị thực hiện"
                  />
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
    :existingRoute="props.duong_cam?.toa_do?.length > 0 ? props.duong_cam.toa_do : null"
    @close="showMap = false"
    @selectRoute="handleRouteSelect"
  />
</template>
