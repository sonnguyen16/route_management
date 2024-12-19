
<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch } from "vue";
const props = defineProps({
  show: Boolean,
  sua_chua_diem: Object,
  loai_sua_chua: Object,
  sua_chua: Object,
  keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);
let form = useForm({
    id: '',
    sua_chua_id: '',
    loai_sua_chua_id: '',
    tu_km: '',
    den_km: '',
})

watch(() => props.keyModal, () => {
  if(props.sua_chua) {        
        if(props.sua_chua_diem) {
         Object.assign(form, props.sua_chua_diem);
         form.errors.loai_sua_chua_id = "";
         
        } else {
          form.loai_sua_chua_id = null;
          form.tu_km = "";
          form.den_km = "";
        }
        form.sua_chua_id = props.sua_chua.id;
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
  //  console.log(props.sua_chua);
    form.sua_chua_id = props.sua_chua.id;
    form.post(route('sua-chua.storeDiem'), {
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
  <div class="modal fade" id="ModelHangMuc">
      <div
          class="modal-dialog modal-dialog-centered modal-lg"  style="max-width: 600px;"
     >
      <div class="modal-content">
              <div class="modal-body p-0">
                  <div class="row">
                      <!-- Main Content -->
                      
                      <div :class="['px-4 pt-4', isEdit ? 'col-md-8' : 'col-md-12']">
                          <div class="form-group">
                              <label for="tuyen_duong_id">Loại sửa chữa</label>
                              <Select
                              v-model="form.loai_sua_chua_id"            
                              :errors="form.errors.loai_sua_chua_id"                                        
                              :options="loai_sua_chua"
                              id="loai_sua_chua_id"
                              option-default="Chọn loại sửa chữa"
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
                              <Input v-model="form.den_km"
                                  type="text"
                                  :errors="form.errors.tu_km"
                                  placeholder="Đến km"
                                  id="den_km_id"
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