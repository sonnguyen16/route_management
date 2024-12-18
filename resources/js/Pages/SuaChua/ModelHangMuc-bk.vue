
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
const showModal = ref(false)
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
        form.sua_chua_id = props.sua_chua.id;
        if(props.sua_chua_diem) {
         Object.assign(form, props.sua_chua_diem);
         form.errors.loai_sua_chua_id = "";
        } else {
          form.loai_sua_chua_id = null;
          form.tu_km = "";
          form.den_km = "";
        }
       
    }else{
        form.reset();
    }
})
const closeModal = () => {
    emits('close');
    form.reset();
    form.clearErrors();
}
const submit = () => {
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
   
  <Transition name="modal" id="modal" >
    <div v-if="show" class="modal-mask">
      <div class="modal-container">
        <div class="modal-header">
          <slot name="header" style="font-size: 15px;font-weight: 600;">ĐIỂM SỬA CHỮA</slot>
        </div>
        <div class="modal-body">
          <Select
            v-model="form.loai_sua_chua_id"            
            :errors="form.errors.loai_sua_chua_id"                                        
            :options="loai_sua_chua"
            id="loai_sua_chua_id"
            option-default="Chọn loại sửa chữa"
             />
             <Input
                v-model="form.tu_km"
                type="text"
                :errors="form.errors.tu_km"
                placeholder="Từ km"
              />
              <Input
                v-model="form.den_km"
                  type="text"
                  :errors="form.errors.tu_km"
                  placeholder="Đến km"
              />
        </div>
        <div class="modal-footer">
          <slot name="footer">
            <button @click.prevent="submit" type="submit" class="btn btn-success" style="width: 100%;">Lưu</button>          
            <button @click.prevent="closeModal" type="reset" class="btn btn-secondary" style="width: 100%;">Hủy</button>           
          </slot>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  transition: opacity 0.3s ease;
}

.modal-container {
  width: 300px;
  margin: auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter-from {
  opacity: 0;
}

.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>