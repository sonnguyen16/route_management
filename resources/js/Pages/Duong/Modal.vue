<script setup>
import { useForm } from '@inertiajs/vue3'
import Select from '@/Components/Select.vue'
import Input from '@/Components/Input.vue'
import { computed, watch, ref } from 'vue'
import MapModal from '@/Components/MapModal.vue'

const props = defineProps({
  huyen: Object,
  tuyen_duong: Object,
  don_vi: Object,
  loai_tuyen_duong: Object,
  phan_cap: Object,
  loai_tuan_tra: Object,
  tuyen_duong_cha: Object,
  flag: {
    type: Boolean,
    default: false
  },
  isEdit: {
    type: Boolean,
    default: false
  },
  keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange'])

let form = useForm({
  id: '',
  ten: '',
  loai_tuyen_duong_id: '',
  phan_cap_id: '',
  diem_dau_huyen_id: '',
  diem_cuoi_huyen_id: '',
  diem_dau_lat: '',
  diem_dau_lng: '',
  diem_dau_xa_id: '',
  diem_cuoi_xa_id: '',
  diem_cuoi_lat: '',
  diem_cuoi_lng: '',
  chieu_dai: '',
  chieu_rong: '',
  don_vi_id: '',
  lo_gioi: '',
  tuyen_duong_id: '',
  diem_dau: '',
  diem_cuoi: '',
  route_geometry: null
})

const dau_xa = computed(() => {
  if (form.diem_dau_huyen_id) {
    return props.huyen.find((h) => h.id == form.diem_dau_huyen_id).xa
  }
})

const cuoi_xa = computed(() => {
  if (form.diem_cuoi_huyen_id) {
    return props.huyen.find((h) => h.id == form.diem_cuoi_huyen_id).xa
  }
})

const submit = () => {
  form.post(route('tuyen-duong.store'), {
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
    if (props.tuyen_duong) {
      Object.assign(form, props.tuyen_duong)
      form.don_vi_id = props.tuyen_duong.don_vi_id
      if (props.tuyen_duong.toa_do?.length > 0) {
        routeCoords.value = {
          lng_start: props.tuyen_duong.toa_do[0]?.lng,
          lat_start: props.tuyen_duong.toa_do[0]?.lat,
          lng_end: props.tuyen_duong.toa_do[1]?.lng,
          lat_end: props.tuyen_duong.toa_do[1]?.lat
        }
      } else {
        routeCoords.value = null
      }
    } else {
      form.reset()
      form.id = ''
      form.ten = ''
      form.loai_tuyen_duong_id = ''
      form.phan_cap_id = ''
      form.diem_dau_huyen_id = ''
      form.diem_cuoi_huyen_id = ''
      form.diem_dau_lat = ''
      form.diem_dau_lng = ''
      form.diem_dau_xa_id = ''
      form.diem_cuoi_xa_id = ''
      form.diem_cuoi_lat = ''
      form.diem_cuoi_lng = ''
      form.chieu_dai = ''
      form.chieu_rong = ''
      form.don_vi_id = ''
      form.lo_gioi = ''
      form.diem_dau = ''
      form.diem_cuoi = ''
      form.route_geometry = null

      routeCoords.value = null
    }
    if (props.tuyen_duong_cha) {
      form.ten = props.tuyen_duong_cha.ten
      form.loai_tuyen_duong_id = props.tuyen_duong_cha.loai_tuyen_duong_id
      form.phan_cap_id = props.tuyen_duong_cha.phan_cap_id
      form.tuyen_duong_id = props.tuyen_duong_cha.id
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
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="row">
            <!-- Main Content -->
            <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
              <div v-if="!props.flag">
                <div class="form-group">
                  <label for="ten_tuyen_duong">Tên tuyến đường</label>
                  <Input v-model="form.ten" :errors="form.errors.ten" />
                </div>
                <div class="form-group">
                  <label for="loai_tuyen_duong_id">Loại đường</label>
                  <Select
                    v-model="form.loai_tuyen_duong_id"
                    :errors="form.errors.loai_tuyen_duong_id"
                    :options="loai_tuyen_duong"
                    id="loai_tuyen_duong_id"
                    option-default="Chọn loại"
                    :disabled="flag"
                  />
                </div>
                <div class="form-group">
                  <label for="phan_cap_id">Mã quản lý</label>
                  <Select
                    v-model="form.phan_cap_id"
                    :errors="form.errors.phan_cap_id"
                    :options="phan_cap"
                    id="phan_cap_id"
                    option-default="Chọn mã phân cấp"
                    :disabled="flag"
                  />
                </div>
                <div class="form-group">
                  <label for="don_vi_quan_ly">Đơn vị quản lý</label>
                  <Select
                    v-model="form.don_vi_id"
                    :errors="form.errors.don_vi_id"
                    :options="props.don_vi"
                    id="don_vi_id"
                    option-default="Chọn đơn vị"
                  />
                </div>

                <div class="form-group" style="display: none">
                  <label for="diem_dau_huyen_id">Điểm đầu</label>
                  <div class="grid grid-cols-2 gap-x-3">
                    <Select
                      v-model="form.diem_dau_huyen_id"
                      :errors="form.errors.diem_dau_huyen_id"
                      :options="huyen"
                      id="diem_dau_huyen_id"
                      option-default="Chọn huyện"
                    />
                    <Select
                      v-model="form.diem_dau_xa_id"
                      :errors="form.errors.diem_dau_xa_id"
                      :options="dau_xa"
                      id="diem_dau_xa_id"
                      option-default="Chọn xã"
                    />
                  </div>
                </div>

                <div class="form-group" style="display: none">
                  <label for="diem_cuoi_huyen_id">Điểm cuối</label>
                  <div class="grid grid-cols-2 gap-x-3">
                    <Select
                      v-model="form.diem_cuoi_huyen_id"
                      :errors="form.errors.diem_cuoi_huyen_id"
                      :options="huyen"
                      id="diem_cuoi_huyen_id"
                      option-default="Chọn huyện"
                    />
                    <Select
                      v-model="form.diem_cuoi_xa_id"
                      :errors="form.errors.diem_cuoi_xa_id"
                      :options="cuoi_xa"
                      id="diem_cuoi_xa_id"
                      option-default="Chọn xã"
                    />
                  </div>
                </div>
                <div class="form-group">
                  <label for="chieu_dai">Điểm đầu</label>
                  <Input v-model="form.diem_dau" :errors="form.errors.diem_dau" />
                </div>
                <div class="form-group">
                  <label for="chieu_dai">Điểm cuối</label>
                  <Input v-model="form.diem_cuoi" :errors="form.errors.diem_cuoi" />
                </div>
                <div class="form-group">
                  <label for="chieu_dai">Chiều dài (km)</label>
                  <Input v-model="form.chieu_dai" :errors="form.errors.chieu_dai" />
                </div>
                <div class="form-group">
                  <label for="chieu_rong">Chiều rộng (m)</label>
                  <Input v-model="form.chieu_rong" :errors="form.errors.chieu_rong" />
                </div>

                <div class="form-group">
                  <label for="dien_tich">Lộ giới (m)</label>
                  <Input v-model="form.lo_gioi" :errors="form.errors.lo_gioi" />
                </div>
              </div>
              <div v-else>
                <div class="form-group" style="display: none">
                  <label for="diem_dau_huyen_id">Điểm đầu</label>
                  <div class="grid grid-cols-2 gap-x-3">
                    <Select
                      v-model="form.diem_dau_huyen_id"
                      :errors="form.errors.diem_dau_huyen_id"
                      :options="huyen"
                      id="diem_dau_huyen_id"
                      option-default="Chọn huyện"
                    />
                    <Select
                      v-model="form.diem_dau_xa_id"
                      :errors="form.errors.diem_dau_xa_id"
                      :options="dau_xa"
                      id="diem_dau_xa_id"
                      option-default="Chọn xã"
                    />
                  </div>
                </div>

                <div class="form-group" style="display: none">
                  <label for="diem_cuoi_huyen_id">Điểm cuối</label>
                  <div class="grid grid-cols-2 gap-x-3">
                    <Select
                      v-model="form.diem_cuoi_huyen_id"
                      :errors="form.errors.diem_cuoi_huyen_id"
                      :options="huyen"
                      id="diem_cuoi_huyen_id"
                      option-default="Chọn huyện"
                    />
                    <Select
                      v-model="form.diem_cuoi_xa_id"
                      :errors="form.errors.diem_cuoi_xa_id"
                      :options="cuoi_xa"
                      id="diem_cuoi_xa_id"
                      option-default="Chọn xã"
                    />
                  </div>
                </div>
                <div class="form-group">
                  <label for="chieu_dai">Điểm đầu</label>
                  <Input v-model="form.diem_dau" :errors="form.errors.diem_dau" />
                </div>
                <div class="form-group">
                  <label for="chieu_dai">Điểm cuối</label>
                  <Input v-model="form.diem_cuoi" :errors="form.errors.diem_cuoi" />
                </div>
                <div class="form-group">
                  <label for="chieu_dai">Chiều dài (km)</label>
                  <Input v-model="form.chieu_dai" :errors="form.errors.chieu_dai" />
                </div>
                <div class="form-group">
                  <label for="chieu_rong">Chiều rộng (m)</label>
                  <Input v-model="form.chieu_rong" :errors="form.errors.chieu_rong" />
                </div>

                <div class="form-group">
                  <label for="dien_tich">Lộ giới (m)</label>
                  <Input v-model="form.lo_gioi" :errors="form.errors.lo_gioi" />
                </div>
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
    :existingRoute="props.tuyen_duong?.toa_do?.length > 0 ? props.tuyen_duong.toa_do : null"
    @close="showMap = false"
    @selectRoute="handleRouteSelect"
  />
</template>
