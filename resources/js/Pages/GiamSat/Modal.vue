<script setup>
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {watch} from "vue";

const props = defineProps({
    giam_sat: Object,
    tuyen_duong: Object,
    don_vi: Object,
})

const emits = defineEmits(['closeModal', 'refresh']);
const closeModal = () => {
    emits('closeModal');
    form.reset();
    form.clearErrors();
}

const form = useForm({
    id: '',
    don_vi_id: '',
    tuyen_duong_id: '',
})

watch(() => props.giam_sat, (value) => {
    if(value) {
        Object.assign(form, value);
    }else{
        form.reset();
    }
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

</script>

<template>
    <div id="modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pt-2">
                    <h5 class="modal-title text-primary">
                        <span v-if="form.id">Chỉnh sửa</span>
                        <span v-else>Thêm mới</span>
                    </h5>
                    <button type="button" class="btn-close" @click.prevent="closeModal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="submit">
                    <div class="modal-body mt-3">
                        <div class="form-group">
                            <label for="loai_tuyen_duong">Tuyến đường</label>
                            <Select v-model="form.tuyen_duong_id"
                                    :errors="form.errors.tuyen_duong_id"
                                    :options="tuyen_duong"
                                    option-default="Chọn tuyến đường"/>
                        </div>
                        <div class="form-group">
                            <label for="ma_phan_cap">Đơn vị giám sát</label>
                            <Select v-model="form.don_vi_id"
                                    :errors="form.errors.don_vi_id"
                                    :options="don_vi"
                                    option-default="Chọn đơn vị"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Lưu</button>
                            <button type="reset"
                                    @click.prevent="closeModal"
                                    class="btn btn-secondary ml-2">
                                Hủy
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
