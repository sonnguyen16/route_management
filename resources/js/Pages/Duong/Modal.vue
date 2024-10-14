<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {computed, watch} from "vue";
import { loaiOptions, loaiTuanTraOptions, maPhanCapOptions } from "@/Constants/constants.js";

const props = defineProps({
    huyen: Object,
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
    ten: '',
    loai: '',
    ma_phan_cap: '',
    diem_dau_huyen_id: '',
    diem_cuoi_huyen_id: '',
    diem_dau_lat: '',
    diem_dau_lng: '',
    diem_dau_xa_id: '',
    diem_cuoi_xa_id: '',
    diem_cuoi_lat: '',
    diem_cuoi_lng: '',
    chieu_dai: '',
    chieu_rong: '',
    dien_tich: '',
    loai_tuan_tra: '',
    don_vi_id: '',
    xi_nghiep: '',
    huyen_id: '',
})

watch(() => props.tuyen_duong, (value) => {
    if(value) {
        Object.assign(form, value);
    }else{
        form.reset();
    }
})

const dau_xa = computed(() => {
    if(form.diem_dau_huyen_id) {
        return props.huyen.find(h => h.id === form.diem_dau_huyen_id).xa
    }
})

const cuoi_xa = computed(() => {
    if(form.diem_cuoi_huyen_id) {
        return props.huyen.find(h => h.id === form.diem_cuoi_huyen_id).xa
    }
})

const submit = () => {
    form.post(route('tuyen-duong.store'), {
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
                            <label for="ten_tuyen_duong">Tên đường</label>
                            <Input v-model="form.ten" :errors="form.errors.ten" />
                        </div>
                        <div class="form-group">
                            <label for="loai_tuyen_duong">Loại</label>
                            <Select v-model="form.loai"
                                    :errors="form.errors.loai"
                                    :options="loaiOptions"
                                    option-default="Chọn loại"/>
                        </div>
                        <div class="form-group">
                            <label for="ma_phan_cap">Mã phân cấp</label>
                            <Select v-model="form.ma_phan_cap"
                                    :errors="form.errors.ma_phan_cap"
                                    :options="maPhanCapOptions"
                                    option-default="Chọn mã phân cấp"/>
                        </div>

                        <div class="form-group">
                            <label for="diem_dau_huyen_id">Điểm đầu</label>
                            <div class="grid grid-cols-2 gap-x-3">
                                <Select v-model="form.diem_dau_huyen_id"
                                        :errors="form.errors.diem_dau_huyen_id"
                                        :options="huyen"
                                        option-default="Chọn huyện"/>
                                <Select v-model="form.diem_dau_xa_id"
                                        :errors="form.errors.diem_dau_xa_id"
                                        :options="dau_xa"
                                        option-default="Chọn xã"/>
                                <Input v-model="form.diem_dau_lat" :errors="form.errors.diem_dau_lat" />
                                <Input v-model="form.diem_dau_lng" :errors="form.errors.diem_dau_lng" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="diem_cuoi_huyen_id">Điểm cuối</label>
                            <div class="grid grid-cols-2 gap-x-3">
                                <Select v-model="form.diem_cuoi_huyen_id"
                                        :errors="form.errors.diem_cuoi_huyen_id"
                                        :options="huyen"
                                        option-default="Chọn huyện"/>
                                <Select v-model="form.diem_cuoi_xa_id"
                                        :errors="form.errors.diem_cuoi_xa_id"
                                        :options="cuoi_xa"
                                        option-default="Chọn xã"/>
                                <Input v-model="form.diem_cuoi_lat" :errors="form.errors.diem_cuoi_lat" />
                                <Input v-model="form.diem_cuoi_lng" :errors="form.errors.diem_cuoi_lng" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="chieu_dai">Chiều dài</label>
                            <Input v-model="form.chieu_dai" :errors="form.errors.chieu_dai" />
                        </div>

                        <div class="form-group">
                            <label for="chieu_rong">Chiều rộng</label>
                            <Input v-model="form.chieu_rong" :errors="form.errors.chieu_rong" />
                        </div>

                        <div class="form-group">
                            <label for="dien_tich">Diện tích</label>
                            <Input v-model="form.dien_tich" :errors="form.errors.dien_tich" />
                        </div>

                        <div class="form-group">
                            <label for="loai_tuan_tra">Loại tuần tra</label>
                            <Select v-model="form.loai_tuan_tra"
                                    :errors="form.errors.loai_tuan_tra"
                                    :options="loaiTuanTraOptions"
                                    option-default="Chọn loại tuần tra"/>
                        </div>

                        <div class="form-group">
                            <label for="don_vi_quan_ly">Đơn vị quản lý</label>
                            <Select v-model="form.don_vi_id"
                                    :errors="form.errors.don_vi_id"
                                    :options="props.don_vi"
                                    option-default="Chọn đơn vị"/>
                        </div>

                        <div class="form-group">
                            <label for="xi_nghiep">Xí nghiệp</label>
                            <Input v-model="form.xi_nghiep" :errors="form.errors.xi_nghiep" />
                        </div>

                        <div class="form-group">
                            <label for="huyen_id">Huyện</label>
                            <Select v-model="form.huyen_id"
                                    :errors="form.errors.huyen_id"
                                    :options="huyen"
                                    option-default="Chọn huyện"/>
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
