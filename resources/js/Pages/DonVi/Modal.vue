<script setup>
import {useForm} from "@inertiajs/vue3";
import {watch} from "vue";
import Input from "@/Components/Input.vue";
import {formatDate} from "@/assets/js/script.js";
import File from "@/Components/File.vue";
import Upload from "@/Components/Upload.vue";

const props = defineProps({
    don_vi: Object,
    keyModal: Number,
    loai: String,
    isEdit: {
        type: Boolean,
        default: false
    },
})

const emits = defineEmits(['closeModal', 'refresh']);

const form = useForm({
    id: '',
    ten: '',
    dia_chi: '',
    dien_thoai: '',
    fax: '',
    email: '',
    website: '',
    ma_so_thue: '',
    lien_he: '',
    loai: props.loai,
})

const submit = () => {
    form.post(route('don-vi.store'), {
        onSuccess: () => {
            closeModal();
            emits('refresh');
            form.reset();
        },
        onError: (err) => {
            console.log(err)
        }
    })
}

watch(() => props.keyModal, () => {
    if(props.don_vi) {
       Object.assign(form, props.don_vi);
    }else{
        form.reset();
        form.id = "";
        form.ten = "";
        form.dia_chi = "";
        form.dien_thoai = "";
        form.fax = "";
        form.email = "";
        form.website = "";
        form.ma_so_thue = "";
        form.lien_he = "";
        
    }
})
const closeModal = () => {
    form.id = "";
        form.ten = "";
        form.dia_chi = "";
        form.dien_thoai = "";
        form.fax = "";
        form.email = "";
        form.website = "";
        form.ma_so_thue = "";
        form.lien_he = "";
    emits('closeModal');
    
    form.reset();
    form.clearErrors();
    
}

</script>

<template>
    <div class="modal fade" id="modal">
        <div
            class="modal-dialog modal-dialog-centered"
             :class="[isEdit ? 'modal-lg' : 'modal-lg']"
        >
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row">
                        <!-- Main Content -->
                        <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
                           <!-- <div class="form-group">
                                <label for="name">Mã số thuế</label>
                                <Input
                                    v-model="form.ma_so_thue"
                                    :errors="form.errors.ma_so_thue"
                                />
                            </div> -->
                            <div class="form-group">
                                <label for="name">Tên đơn vị</label>
                                <Input
                                    v-model="form.ten"
                                    :errors="form.errors.ten"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Địa chỉ</label>
                                <Input
                                    v-model="form.dia_chi"
                                    :errors="form.errors.dia_chi"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Điện thoại</label>
                                <Input
                                    v-model="form.dien_thoai"
                                    :errors="form.errors.dien_thoai"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Fax</label>
                                <Input
                                    v-model="form.fax"
                                    :errors="form.errors.fax"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Email</label>
                                <Input
                                    v-model="form.email"
                                    :errors="form.errors.email"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Website</label>
                                <Input
                                    v-model="form.website"
                                    :errors="form.errors.website"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Liên hệ</label>
                                <Input
                                    v-model="form.lien_he"
                                    :errors="form.errors.lien_he"
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
