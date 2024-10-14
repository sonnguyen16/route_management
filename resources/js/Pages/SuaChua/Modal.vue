<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import { watch} from "vue";
import { loaiSuaChuaOptions } from "@/Constants/constants.js";

const props = defineProps({
    sua_chua: Object,
    tuyen_duong: Object,
    don_vi: Object,
    nguoi_duyet: Object,
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
    loai_sua_chua: '',
    tu_km: '',
    den_km: '',
    ngay_duyet: '',
    nguoi_duyet_id: '',
    ngay_khoi_cong: '',
    ngay_hoan_thanh: '',
    don_vi_id: '',
    tai_lieu: '',
})

watch(() => props.sua_chua, (value) => {
    if(value) {
        Object.assign(form, value);
    }else{
        form.reset();
    }
})

const submit = () => {
    form.post(route('sua-chua.store'), {
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
                            <label for="loai_sua_chua">Loại sửa chữa</label>
                            <Select
                                v-model="form.loai_sua_chua"
                                :options="loaiSuaChuaOptions"
                                :errors="form.errors.loai_sua_chua"
                                option-default="Chọn loại sửa chữa"
                            />
                        </div>

                        <div class="form-group">
                            <label for="tu_km">Từ km</label>
                            <Input
                                v-model="form.tu_km"
                                type="number"
                                :errors="form.errors.tu_km"
                            />
                        </div>

                        <div class="form-group">
                            <label for="den_km">Đến km</label>
                            <Input
                                v-model="form.den_km"
                                type="number"
                                :errors="form.errors.den_km"
                            />
                        </div>

                        <div class="form-group">
                            <label for="ngay_duyet">Ngày duyệt</label>
                            <Input
                                v-model="form.ngay_duyet"
                                type="date"
                                :errors="form.errors.ngay_duyet"
                            />
                        </div>

                        <div class="form-group">
                            <label for="nguoi_duyet_id">Người duyệt</label>
                            <Select
                                v-model="form.nguoi_duyet_id"
                                :options="nguoi_duyet"
                                :errors="form.errors.nguoi_duyet_id"
                                option-default="Chọn người duyệt"
                            />
                        </div>

                        <div class="form-group">
                            <label for="ngay_khoi_cong">Ngày khởi công</label>
                            <Input
                                v-model="form.ngay_khoi_cong"
                                type="date"
                                :errors="form.errors.ngay_khoi_cong"
                            />
                        </div>

                        <div class="form-group">
                            <label for="ngay_hoan_thanh">Ngày hoàn thành</label>
                            <Input
                                v-model="form.ngay_hoan_thanh"
                                type="date"
                                :errors="form.errors.ngay_hoan_thanh"
                            />
                        </div>

                        <div class="form-group">
                            <label for="don_vi_id">Đơn vị thi công</label>
                            <Select
                                v-model="form.don_vi_id"
                                :options="don_vi"
                                :errors="form.errors.don_vi_id"
                                option-default="Chọn đơn vị thi công"
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
