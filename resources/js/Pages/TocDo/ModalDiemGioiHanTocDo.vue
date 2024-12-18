
<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch } from "vue";
const props = defineProps({
  show: Boolean,
  diem_gioi_han_toc_do: Object,
  gioi_han_toc_do: Object,
  keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);
let form = useForm({
    id: '',
    gioi_han_toc_do_id: '',
    toc_do: '',
    tu_km: '',
    den_km: '',
})

watch(() => props.keyModal, () => {
  if(props.gioi_han_toc_do) {
        form.gioi_han_toc_do_id = props.gioi_han_toc_do.id;
        if(props.diem_gioi_han_toc_do) {
         Object.assign(form, props.diem_gioi_han_toc_do);
        } else {
          form.toc_do = "";
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
    form.post(route('gioi-han-toc-do.storeDiemGioiHanTocDo'), {
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
  <div class="modal fade" id="ModalDiemGioiHanTocDo">
      <div
          class="modal-dialog modal-dialog-centered modal-lg"  style="max-width: 600px;"
     >
      <div class="modal-content">
              <div class="modal-body p-0">
                  <div class="row">
                      <!-- Main Content -->
                      
                      <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
                          <div class="form-group">
                              <label for="toc_do">Điểm giới hạn tốc độ</label>
                              <Input
                                v-model="form.toc_do"
                                type="text"
                                :errors="form.errors.toc_do"
                                placeholder="Điểm giới hạn tốc độ"
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