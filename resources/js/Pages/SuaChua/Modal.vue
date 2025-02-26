<script setup>
import Input from '@/Components/Input.vue'
import { useForm } from '@inertiajs/vue3'
import Select from '@/Components/Select.vue'
import { ref, watch } from 'vue'
import MapModal from '@/Components/MapModal.vue'

const props = defineProps({
  sua_chua: Object,
  tuyen_duong: Object,
  don_vi: Object,
  nguoi_duyet: Object,
  loai_sua_chua: Object,
  sua_chua_cha: Object,
  keyModal: Number,
  flag: {
    type: Boolean,
    default: false
  },
  isEdit: {
    type: Boolean,
    default: false
  }
})
const emits = defineEmits(['closeModal', 'refresh', 'fileChange'])

let form = useForm({
  id: '',
  tuyen_duong_id: '',
  loai_sua_chua_id: '',
  tu_km: '',
  den_km: '',
  ngay_duyet: '',
  ngay_khoi_cong: '',
  ngay_hoan_thanh: '',
  don_vi_id: '',
  tai_lieu: [],
  noi_dung: '',
  sua_chua_id: '',
  route_geometry: null
})

const submit = () => {
  if (props.sua_chua_cha) {
    form.sua_chua_id = props.sua_chua_cha.id
  }
  form.post(route('sua-chua.store'), {
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
    if (props.sua_chua) {
      Object.assign(form, props.sua_chua)
      if (props.sua_chua.toa_do?.length > 0) {
        routeCoords.value = {
          start: `${props.sua_chua.toa_do[0]?.lng || ''}, ${props.sua_chua.toa_do[0]?.lat || ''}`,
          end: `${props.sua_chua.toa_do[props.sua_chua.toa_do.length - 1]?.lng || ''}, ${
            props.sua_chua.toa_do[props.sua_chua.toa_do.length - 1]?.lat || ''
          }`
        }
      } else {
        routeCoords.value = null
      }
    } else {
      form.reset()

      form.id = ''
      form.tuyen_duong_id = ''
      form.loai_sua_chua_id = ''
      form.tu_km = ''
      form.den_km = ''
      form.ngay_duyet = ''
      form.ngay_khoi_cong = ''
      form.ngay_hoan_thanh = ''
      form.don_vi_id = ''
      form.noi_dung = ''
      form.tai_lieu = []
      form.sua_chua_id = ''
      form.route_geometry = null

      routeCoords.value = null
    }
    if (props.sua_chua_cha) {
      form.tuyen_duong_id = props.sua_chua_cha.tuyen_duong_id
      form.sua_chua_id = props.sua_chua_cha.id
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
                  <label for="tuyen_duong_id">Tuyến đường</label>
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
                  <label for="noi_dung">Loại sửa chữa</label>
                  <Input
                    v-model="form.noi_dung"
                    type="text"
                    :errors="form.errors.noi_dung"
                    placeholder="Loại sửa chữa"
                    id="noi_dung"
                  />
                </div>
                <div class="form-group" style="display: none">
                  <label for="loai_sua_chua_id">Loại sửa chữa</label>
                  <Select
                    v-model="form.loai_sua_chua_id"
                    :options="loai_sua_chua"
                    :errors="form.errors.loai_sua_chua_id"
                    id="loai_sua_chua_id"
                    option-default="Chọn đơn vị thi công"
                  />
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
                  <label for="tu_km_id">Tọa độ đã chọn:</label>
                  <div class="flex gap-3">
                    <input type="text" class="form-control mb-3" v-model="routeCoords.start" />
                    <input type="text" class="form-control" v-model="routeCoords.end" />
                  </div>
                </div>

                <div class="form-group">
                  <label for="ngay_duyet">Ngày duyệt</label>
                  <Input v-model="form.ngay_duyet" type="date" :errors="form.errors.ngay_duyet" />
                </div>

                <div class="form-group">
                  <label for="ngay_khoi_cong">Ngày khởi công</label>
                  <Input v-model="form.ngay_khoi_cong" type="date" :errors="form.errors.ngay_khoi_cong" />
                </div>

                <div class="form-group">
                  <label for="ngay_hoan_thanh">Ngày hoàn thành</label>
                  <Input v-model="form.ngay_hoan_thanh" type="date" :errors="form.errors.ngay_hoan_thanh" />
                </div>

                <div class="form-group">
                  <label for="don_vi_id">Đơn vị thi công</label>
                  <Select
                    v-model="form.don_vi_id"
                    :options="don_vi"
                    :errors="form.errors.don_vi_id"
                    id="don_vi_id"
                    option-default="Chọn đơn vị thi công"
                  />
                </div>
              </div>
              <div class="form-group"></div>
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
    :existingRoute="props.sua_chua?.toa_do?.length > 0 ? props.sua_chua.toa_do : null"
    @close="showMap = false"
    @selectRoute="handleRouteSelect"
  />
</template>
