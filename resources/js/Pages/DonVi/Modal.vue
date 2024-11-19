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
            closeModal()
            emits('refresh')
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
             :class="[isEdit ? 'modal-xl' : 'modal-lg']"
        >
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row">
                        <!-- Main Content -->
                        <div :class="['px-4 pt-4', isEdit ? 'col-md-8' : 'col-md-12']">
                            <div class="form-group">
                                <label for="name">Mã số thuế</label>
                                <Input
                                    v-model="form.ma_so_thue"
                                    :errors="form.errors.ma_so_thue"
                                />
                            </div>
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
                         <!-- Sidebar Actions -->
                         <div v-if="isEdit" class="col-md-4 bg-gray-100 p-3 flex flex-col">
                            <div class="mb-3">
                                <div class="flex items-center space-x-2">
                                    <img src="@/assets/img/avatar.jpg"
                                         alt="Avatar" class="rounded-full w-12 h-12">
                                    <div>
                                        <div class="font-semibold">Admin</div>
                                        <div class="text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ formatDate(don_vi.created_at?.toString()) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <Upload
                                @file-change="uploadFiles"
                                @submit="submit"
                                @close="closeModal"
                            />
                            <div class="font-bold mt-4 ">Tệp đã tải lên</div>
                            <div class="flex-1 overflow-y-auto max-h-[550px] mt-2">
                                <File v-for="file in uploadedFiles"
                                      :key="file.id"
                                      :file="file"
                                      @remove-file-upload="removeFileUploaded"
                                      @refresh="emits('refresh')"
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