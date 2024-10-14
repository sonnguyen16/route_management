<script setup>
import Input from "@/Components/Input.vue";
import {useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import { watch} from "vue";
import {loaiCongVanOptions, trangThaiCongVan} from "@/Constants/constants.js";

const props = defineProps({
    cong_van: Object,
    don_vi: Object,
    nguoi_xu_ly: Object,
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
    don_vi_id: '',
    ngay_gui: '',
    ngay_nhan: '',
    nguoi_xu_ly_id: '',
    trang_thai: '',
    ghi_chu: '',
    tai_lieu: '',
})

watch(() => props.cong_van, (value) => {
    if(value) {
        Object.assign(form, value);
    }else{
        form.reset();
    }
})

const submit = () => {
    form.post(route('cong-van.store'), {
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
                            <label for="ten">Tên công văn</label>
                            <Input :errors="form.errors.ten" v-model="form.ten" id="ten" type="text" />
                        </div>

                        <div class="form-group">
                            <label for="loai">Loại công văn</label>
                            <Select :errors="form.errors.loai" v-model="form.loai" option-default="Chọn loại" :options="loaiCongVanOptions"  />
                        </div>

                        <div class="form-group">
                            <label for="don_vi_id">Đơn vị gửi</label>
                            <Select :errors="form.errors.don_vi_id" v-model="form.don_vi_id" option-default="Chọn đơn vị" id="don_vi_id" :options="props.don_vi"  />
                        </div>

                        <div class="form-group">
                            <label for="ngay_gui">Ngày gửi</label>
                            <Input :errors="form.errors.ngay_gui" v-model="form.ngay_gui" id="ngay_gui" type="date"  />
                        </div>

                        <div class="form-group">
                            <label for="ngay_nhan">Ngày nhận</label>
                            <Input :errors="form.errors.ngay_nhan" v-model="form.ngay_nhan" id="ngay_nhan" type="date"  />
                        </div>

                        <div class="form-group">
                            <label for="nguoi_xu_ly">Người xử lý</label>
                            <Select :errors="form.errors.nguoi_xu_ly_id" v-model="form.nguoi_xu_ly_id" option-default="Chọn người xử lý" :options="props.nguoi_xu_ly"  />
                        </div>

                        <div class="form-group">
                            <label for="trang_thai">Trạng thái</label>
                            <Select :errors="form.errors.trang_thai" v-model="form.trang_thai" option-default="Chọn trạng thái" :options="trangThaiCongVan"  />
                        </div>

                        <div class="form-group">
                            <label for="ghi_chu">Ghi chú</label>
                            <Input :errors="form.errors.ghi_chu" v-model="form.ghi_chu" id="ghi_chu" type="text" />
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
