<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import { watch} from "vue";
import { loaiSuaChuaOptions } from "@/Constants/constants.js";

const props = defineProps({
    duong_cam: Object,
    tuyen_duong: Object,
    don_vi: Object,
})

const emits = defineEmits(['closeModal', 'refresh']);
const closeModal = () => {
    emits('closeModal');
    form.reset();
    form.clearErrors();
}

let form = useForm({
    id: '',
    tuyen_duong_id: '',
    don_vi_quyet_dinh_id: '',
    don_vi_thuc_hien_id: '',
    tu_ngay: '',
    den_ngay: '',
    ly_do: '',
    tai_lieu: '',
})

watch(() => props.duong_cam, (value) => {
    if(value) {
        Object.assign(form, value);
    }else{
        form.reset();
    }
})

const submit = () => {
    form.post(route('duong-cam.store'), {
        onSuccess: () => {
            closeModal()
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    })
}

const onFileChange = (e) => {
    form.tai_lieu = e.target.files;
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
                            <label for="tuyen_duong_id">Tuyến đường</label>
                            <Select
                                v-model="form.tuyen_duong_id"
                                :options="tuyen_duong"
                                :errors="form.errors.tuyen_duong_id"
                                option-default="Chọn tuyến đường"
                            />
                        </div>

                        <div class="form-group">
                            <label for="tu_ngay">Từ ngày</label>
                            <Input
                                v-model="form.tu_ngay"
                                type="datetime-local"
                                :errors="form.errors.tu_ngay"
                            />
                        </div>

                        <div class="form-group">
                            <label for="den_ngay">Đến ngày</label>
                            <Input
                                v-model="form.den_ngay"
                                type="datetime-local"
                                :errors="form.errors.den_ngay"
                            />
                        </div>

                        <div class="form-group">
                            <label for="don_vi_quyet_dinh_id">Đơn vị quyết định</label>
                            <Select
                                v-model="form.don_vi_quyet_dinh_id"
                                :options="don_vi"
                                :errors="form.errors.don_vi_quyet_dinh_id"
                                option-default="Chọn đơn vị quyết định"
                            />
                        </div>

                        <div class="form-group">
                            <label for="don_vi_thuc_hien_id">Đơn vị thực hiện</label>
                            <Select
                                v-model="form.don_vi_thuc_hien_id"
                                :options="don_vi"
                                :errors="form.errors.don_vi_thuc_hien_id"
                                option-default="Chọn đơn vị thực hiện"
                            />
                        </div>

                        <div class="form-group">
                            <label for="ly_do">Lý do</label>
                            <Input
                                v-model="form.ly_do"
                                :errors="form.errors.ly_do"
                            />
                        </div>

                        <div class="form-group">
                            <label for="tai_lieu">Tài liệu</label>
                            <input type="file" @change="onFileChange" multiple>
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
