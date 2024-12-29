
<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch } from "vue";
const props = defineProps({
  show: Boolean,
  cap_phep_diem: Object,
  cap_phep: Object,
  keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);
let form = useForm({
    id: '',
    cap_phep_id: '',
    ten: '',
    tu_km: '',
    den_km: '',
    tu_ngay: '',
    den_ngay: '',
    noi_dung: '',
})

watch(() => props.keyModal, () => {
  if(props.cap_phep) {
        form.cap_phep_id = props.cap_phep.id;
        if(props.cap_phep_diem) {
         Object.assign(form, props.cap_phep_diem);
        } else {
          form.ten = "";
          form.tu_km = "";
          form.den_km = "";
          form.tu_ngay = "";
          form.den_ngay = "";
          form.noi_dung = "";
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
    console.log(form);
    // console.log(cap_phep);
    form.post(route('cap-phep.storeDiemCapPhep'), {
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
  <div class="modal fade" id="modelDiemCapPhep">
      <div
          class="modal-dialog modal-dialog-centered modal-lg"  style="max-width: 600px;"
     >
    
      <div class="modal-content">
              <div class="modal-body p-0">
                  <div class="row">
                      <!-- Main Content -->
                     
                      <div :class="['px-4 pt-4','col-md-12']">
                          <div class="form-group">
                              <label for="ten">Tên cấp phép</label>
                              <Input
                                v-model="form.ten"
                                type="text"
                                :errors="form.errors.ten"
                                placeholder="Tên"
                                id="toc_do"
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
                          <div class="form-group">
                                <label for="tu_ngay">Từ ngày</label>
                                <Input
                                    v-model="form.tu_ngay"
                                    type="date"
                                    :errors="form.errors.tu_ngay"
                                />
                            </div>

                            <div class="form-group">
                                <label for="den_ngay">Đến ngày</label>
                                <Input
                                    v-model="form.den_ngay"
                                    type="date"
                                    :errors="form.errors.den_ngay"
                                />
                            </div>
                            <div class="form-group">
                              <label for="toc_do">Nội dung</label>
                              <Input
                                v-model="form.noi_dung"
                                type="text"
                                :errors="form.errors.noi_dung"
                                placeholder="Nội dung"
                                id="toc_do"
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
<style scoped>
.form-group {
    display: grid
;
    grid-template-columns: 3fr 10fr!important;
    margin-bottom: 0;
}
</style>