<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {computed, watch} from "vue";

const props = defineProps({
    huyen: Object,
    tuyen_duong: Object,
})

const emits = defineEmits(['closeModal', 'refresh']);
const closeModal = () => {
    emits('closeModal');
    form.reset();
    form.clearErrors();
}

const form = useForm({
    id: '',
    ten: '',
    loai: '',
    ma_phan_cap: '',
    diem_dau_huyen_id: '',
    diem_cuoi_huyen_id: '',
    diem_dau_xa_id: '',
    diem_cuoi_xa_id: '',
    chieu_dai: '',
    chieu_rong: '',
    dien_tich: '',
    loai_tuan_tra: '',
    don_vi_quan_ly: '',
    xi_nghiep: '',
    huyen_id: '',
    tai_lieu: [],
})

watch(() => props.tuyen_duong, (value) => {
    if(value) {
        form.id = value.id;
        form.ten = value.ten;
        form.loai = value.loai;
        form.ma_phan_cap = value.ma_phan_cap;
        form.diem_dau_huyen_id = value.diem_dau_huyen_id;
        form.diem_cuoi_huyen_id = value.diem_cuoi_huyen_id;
        form.diem_dau_xa_id = value.diem_dau_xa_id;
        form.diem_cuoi_xa_id = value.diem_cuoi_xa_id;
        form.chieu_dai = value.chieu_dai;
        form.chieu_rong = value.chieu_rong;
        form.dien_tich = value.dien_tich;
        form.loai_tuan_tra = value.loai_tuan_tra;
        form.don_vi_quan_ly = value.don_vi_quan_ly;
        form.xi_nghiep = value.xi_nghiep;
        form.huyen_id = value.huyen_id;
    }
})

const loaiOptions = [
    {id: 1, name: 'Loại 1'},
    {id: 2, name: 'Loại 2'},
    {id: 3, name: 'Loại 3'},
]

const maPhanCapOptions = [
    {id: 1, name: 'Cấp 1'},
    {id: 2, name: 'Cấp 2'},
    {id: 3, name: 'Cấp 3'},
]

const loaiTuanTraOptions = [
    {id: 1, name: 'Loại 1'},
    {id: 2, name: 'Loại 2'},
    {id: 3, name: 'Loại 3'},
]

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

const onFileChange = (e) => {
    form.tai_lieu = e.target.files;
}

</script>

<template>
    <div id="modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pt-2">
                    <h5 class="modal-title text-primary">Thêm tuyến đường</h5>
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
                            <div class="grid grid-cols-2 gap-3">
                                <Select v-model="form.diem_dau_huyen_id"
                                        :errors="form.errors.diem_dau_huyen_id"
                                        :options="huyen"
                                        option-default="Chọn huyện"/>
                                <Select v-model="form.diem_dau_xa_id"
                                        :errors="form.errors.diem_dau_xa_id"
                                        :options="dau_xa"
                                        option-default="Chọn xã"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="diem_cuoi_huyen_id">Điểm cuối</label>
                            <div class="grid grid-cols-2 gap-3">
                                <Select v-model="form.diem_cuoi_huyen_id"
                                        :errors="form.errors.diem_cuoi_huyen_id"
                                        :options="huyen"
                                        option-default="Chọn huyện"/>
                                <Select v-model="form.diem_cuoi_xa_id"
                                        :errors="form.errors.diem_cuoi_xa_id"
                                        :options="cuoi_xa"
                                        option-default="Chọn xã"/>
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
                            <Input v-model="form.don_vi_quan_ly" :errors="form.errors.don_vi_quan_ly" />
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

                        <div class="form-group">
                            <label for="tai_lieu">Tài liệu</label>
                            <input type="file" @change="onFileChange"  multiple>
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

<style scoped>

</style>
