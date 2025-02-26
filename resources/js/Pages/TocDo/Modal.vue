<script setup>
import Input from '@/Components/Input.vue'
import { router, useForm } from '@inertiajs/vue3'
import Select from '@/Components/Select.vue'
import { ref, watch } from 'vue'
import MapModal from '@/Components/MapModal.vue'

const props = defineProps({
  gioi_han_toc_do: Object,
  gioi_han_toc_do_cha: Object,
  tuyen_duong: Object,
  don_vi: Object,
  nguoi_duyet: Object,
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
  don_vi_id: '',
  tu_ngay: '',
  den_ngay: '',
  noi_dung: '',
  tu_km: '',
  den_km: '',
  gioi_han_toc_do_id: '',
  don_vi_thuc_hien_id: '',
  tai_lieu: [],
  route_geometry: null
})

const submit = () => {
  form.post(route('gioi-han-toc-do.store'), {
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
    if (props.gioi_han_toc_do) {
      Object.assign(form, props.gioi_han_toc_do)
      if (props.gioi_han_toc_do?.toa_do?.length > 0) {
        routeCoords.value = {
          start: `${props.gioi_han_toc_do.toa_do[0]?.lng || ''}, ${props.gioi_han_toc_do.toa_do[0]?.lat || ''}`,
          end: `${props.gioi_han_toc_do.toa_do[props.gioi_han_toc_do.toa_do.length - 1]?.lng || ''}, ${
            props.gioi_han_toc_do.toa_do[props.gioi_han_toc_do.toa_do.length - 1]?.lat || ''
          }`
        }
      } else {
        routeCoords.value = null
      }
    } else {
      form.reset()
      form.id = ''
      form.tuyen_duong_id = ''
      form.don_vi_id = ''
      form.tu_ngay = ''
      form.den_ngay = ''
      form.noi_dung = ''
      form.tu_km = ''
      form.den_km = ''
      form.gioi_han_toc_do_id = ''
      form.don_vi_thuc_hien_id = ''
      form.route_geometry = null

      routeCoords.value = null
    }
    console.log(props.gioi_han_toc_do_cha)
    if (props.gioi_han_toc_do_cha) {
      form.gioi_han_toc_do_id = props.gioi_han_toc_do_cha.id
      form.tuyen_duong_id = props.gioi_han_toc_do_cha.tuyen_duong_id
    }
  }
)
const closeModal = () => {
  emits('closeModal')
  form.reset()
  formFile.reset()
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
                  <label for="toc_do">Nội dung</label>
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
                  <label for="tu_ngay">Từ ngày</label>
                  <Input v-model="form.tu_ngay" type="date" :errors="form.errors.tu_ngay" />
                </div>

                <div class="form-group">
                  <label for="den_ngay">Đến ngày</label>
                  <Input v-model="form.den_ngay" type="date" :errors="form.errors.den_ngay" />
                </div>
                <div class="form-group">
                  <label for="don_vi_id">Đơn vị quyết định</label>
                  <Select
                    v-model="form.don_vi_id"
                    :options="don_vi"
                    :errors="form.errors.don_vi_id"
                    id="don_vi_id"
                    option-default="Chọn đơn vị thi công"
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
    :existingRoute="props.gioi_han_toc_do?.toa_do?.length > 0 ? props.gioi_han_toc_do.toa_do : null"
    @close="showMap = false"
    @selectRoute="handleRouteSelect"
  />
</template>
