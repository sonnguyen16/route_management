<script setup>
import {useForm} from "@inertiajs/vue3";
import {watch} from "vue";
import Input from "@/Components/Input.vue";

const props = defineProps({
    cau_hinh: Object,
    keyModal: Number,
    loai: String
})

const emits = defineEmits(['closeModal', 'refresh']);

const form = useForm({
    id: '',
    ten: '',
    loai: props.loai,
})

const submit = () => {
    form.post(route('cau-hinh.store'), {
        onSuccess: () => {
            closeModal()
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    })
}

watch(() => props.keyModal, () => {
    if(props.cau_hinh) {
        Object.assign(form, props.cau_hinh);
    }else{
        form.reset();
    }
})
const closeModal = () => {
    emits('closeModal');
    form.reset();
    form.clearErrors();
}

</script>

<template>
    <div class="modal fade" id="modal">
        <div
            class="modal-dialog modal-dialog-centered"
            :class="['modal-lg']"
        >
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row">
                        <!-- Main Content -->
                        <div :class="['col-md-12 px-4 pt-4']">
                            <div class="form-group">
                                <label for="name">Tên</label>
                                <Input
                                    v-model="form.ten"
                                    :errors="form.errors.ten"
                                />
                            </div>

                            <div class="form-group" style="display: none;">
                                <label for="loai">Loại</label>
                               <Input
                                   v-model="form.loai"
                                   :errors="form.errors.loai"
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
<!--test-->