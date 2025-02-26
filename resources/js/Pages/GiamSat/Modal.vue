<script setup>
import { useForm } from '@inertiajs/vue3'
import Select from '@/Components/Select.vue'
import { watch, ref } from 'vue'
import { MucDo, TinhTrang } from '@/Constants/constants.js'
import Input from '@/Components/Input.vue'
import MapModal from '@/Components/MapModal.vue'
const props = defineProps({
  giam_sat: Object,
  tuyen_duong: Object,
  don_vi: Object,
  giam_sat_cha: Object,
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
  hu_hong: '',
  vi_tri: '',
  muc_do: '',
  tinh_trang_khac_phuc: '',
  giam_sat_id: '',
  route_geometry: null
})

const submit = () => {
  form.post(route('giam-sat.store'), {
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
    if (props.giam_sat) {
      Object.assign(form, props.giam_sat)
      if (props.giam_sat?.toa_do?.length > 0) {
        routeCoords.value = {
          start: `${props.giam_sat.toa_do[0]?.lng || ''}, ${props.giam_sat.toa_do[0]?.lat || ''}`,
          end: `${props.giam_sat.toa_do[props.giam_sat.toa_do.length - 1]?.lng || ''}, ${
            props.giam_sat.toa_do[props.giam_sat.toa_do.length - 1]?.lat || ''
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
      form.hu_hong = ''
      form.vi_tri = ''
      form.muc_do = ''
      form.tinh_trang_khac_phuc = ''
      form.giam_sat_id = ''
      form.route_geometry = null

      routeCoords.value = null
    }
    console.log(props.giam_sat_cha)
    if (props.giam_sat_cha) {
      form.tuyen_duong_id = props.giam_sat_cha.tuyen_duong_id
      form.giam_sat_id = props.giam_sat_cha.id
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
              <div class="form-group" :class="[!props.flag ? '' : 'disable_tr']">
                <label for="loai_tuyen_duong">Tuyến đường</label>
                <Select
                  v-model="form.tuyen_duong_id"
                  :errors="form.errors.tuyen_duong_id"
                  :options="tuyen_duong"
                  id="tuyen_duong_id"
                  option-default="Chọn tuyến đường"
                />
              </div>
              <div :class="[!props.flag ? 'disable_tr' : '']">
                <div class="form-group">
                  <label for="hu_hong">Hư hỏng bất cập</label>
                  <Input id="hu_hong" v-model="form.hu_hong" :errors="form.errors.hu_hong" />
                </div>
                <div class="form-group" style="padding-top: 10px">
                  <label for="vi_tri">Vị trí</label>
                  <Input id="vi_tri" v-model="form.vi_tri" :errors="form.errors.vi_tri" />
                </div>
                <div class="form-group">
                  <label for="ma_phan_cap">Mức độ</label>
                  <Select
                    v-model="form.muc_do"
                    :errors="form.errors.muc_do"
                    :options="MucDo"
                    id="muc_do"
                    option-default="Chọn mức độ"
                  />
                </div>
                <div class="form-group">
                  <label for="ma_phan_cap">Tình trạng khắc phục</label>
                  <Select
                    v-model="form.tinh_trang_khac_phuc"
                    :errors="form.errors.tinh_trang_khac_phuc"
                    :options="TinhTrang"
                    id="tinh_trang_khac_phuc"
                    option-default="Chọn tình trạng"
                  />
                </div>
              </div>

              <div class="form-group" :class="[!props.flag ? '' : 'disable_tr']">
                <label for="ma_phan_cap">Đơn vị giám sát</label>
                <Select
                  v-model="form.don_vi_id"
                  :errors="form.errors.don_vi_id"
                  :options="don_vi"
                  id="don_vi_id"
                  option-default="Chọn đơn vị"
                />
              </div>

              <div class="form-group" :class="[props.flag ? '' : 'disable_tr']">
                <label for="tu_km_id">Chọn tuyến đường</label>
                <button class="btn btn-info btn-sm mb-3 w-1/2" @click="openMapModal">📍 Chọn tuyến đường</button>
              </div>

              <div v-if="routeCoords" class="form-group" :class="[props.flag ? '' : 'disable_tr']">
                <label for="tu_km_id">Tọa độ đã chọn:</label>
                <div class="flex gap-3">
                  <input type="text" class="form-control mb-3" v-model="routeCoords.start" />
                  <input type="text" class="form-control" v-model="routeCoords.end" />
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
    :existingRoute="props.giam_sat?.toa_do?.length > 0 ? props.giam_sat.toa_do : null"
    @close="showMap = false"
    @selectRoute="handleRouteSelect"
  />
</template>
