
<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch } from "vue";
const props = defineProps({
  show: Boolean,
  duong_cam: Object,
  diem_cam: Object,
  keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);
let form = useForm({
    id: '',
    duong_cam_id: '',
    noi_dung: '',
    tu_km: '',
    den_km: '',
})

watch(() => props.keyModal, () => {
  if(props.duong_cam) {
        form.duong_cam_id = props.duong_cam.id;
        if(props.diem_cam) {
         Object.assign(form, props.diem_cam);
        } else {
          form.noi_dung = "";
          form.vi_tri = "";
          form.tu_km = "";
          form.den_km = "";
        }
       
    }else{
        form.reset();
    }
})
const closeModal = () => {
  emits('closeModal');
    form.reset();
    form.clearErrors();
}
const submit = () => {
    form.post(route('duong-cam.storeDiemCam'), {
        onSuccess: () => {
            closeModal()
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    })
}
</script>

<template>
  <div class="modal fade" id="ModelDiemCam">
      <div
          class="modal-dialog modal-dialog-centered modal-lg"  style="max-width: 600px;"
     >
      <div class="modal-content">
              <div class="modal-body p-0">
                  <div class="row">
                      <!-- Main Content -->
                      
                      <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
                          <div class="form-group">
                              <label for="noi_dung">Điểm cấm</label>
                              <Input
                                v-model="form.noi_dung"
                                type="text"
                                :errors="form.errors.noi_dung"
                                placeholder="Điểm cấm"
                                id="noi_dung"
                              />
                          </div>
                          <div class="form-group">
                              <label for="tu_km">Từ km</label>
                              <Input
                                v-model="form.tu_km"
                                type="text"
                                :errors="form.errors.tu_km"
                                placeholder="Từ km"
                                id="tu_km"
                              />
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
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button @click.prevent="submit" type="submit" class="btn btn-success">Lưu</button>
                  <button @click.prevent="closeModal" type="reset"
                          class="btn btn-secondary ml-2">
                      Hủy
                  </button>
              </div>
          </div>
      </div>
  </div>
</template>