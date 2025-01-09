
<script setup>
import {ref, watch } from "vue";
const props = defineProps({
    obj: Object,
    msg: String,
    keyModal: Number
})

const emits = defineEmits(['closeModal','drop']);

const drop = () => {
    emits('drop', props.obj)
}
const closeModal = () => {
    emits('closeModal');
}
</script>

<template>
  <div class="modal fade" id="question">
      <div
          class="modal-dialog modal-dialog-centered modal-lg"  style="max-width: 600px;"
     >
      <div class="modal-content">
        <div class="modal-header backgroud-navbar color-navbar">
            <h5 class="modal-title ">Thông báo</h5>
          </div>
              <div class="modal-body p-0">
                  <p style="padding-left: 20px;padding-top: 15px;font-size: 15px;">{{ props.msg }}</p>
              </div>
              <div class="modal-footer">
                  <button @click.prevent="drop" type="submit" class="btn btn-success">Đồng ý</button>
                  <button @click.prevent="closeModal" type="reset"
                          class="btn btn-secondary ml-2">
                      Hủy
                  </button>
              </div>
          </div>
      </div>
  </div>
</template>