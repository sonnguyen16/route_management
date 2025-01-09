<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import {nextTick, onMounted, ref, watch} from "vue";
import {debounce} from "lodash";
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import {useModal} from "@/Hooks/useModal.js";
import Modal from "@/Pages/DonVi/Modal.vue";
import Upload from "@/Components/UploadFile.vue";
import Question from "@/Components/Question.vue";

const props = defineProps({
    obj: Object,
    loai: String,
    ten: String,
    stt: Number,
})
const key = ref(0);
const keyModal = ref(0);
const don_vi_selected = ref(null);
const isEdit = ref(false);
const files = ref([]);
const key1 = ref(1);
const key2 = ref(2);
const changePage = (page) => {
    router.visit(route('don-vi.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    //{field: 'ma_so_thue', label: 'Mã số thuế'},
    {field: 'ten', label: 'Tên'},
    {field: 'dia_chi', label: 'Địa chỉ'},
    {field: 'dien_thoai', label: 'Điện thoại'},
    {field: 'fax', label: 'Fax'},
    {field: 'email', label: 'Email'},
    {field: 'website', label: 'Website'},
    {field: 'lien_he', label: 'Liên hệ'},
    {field: 'action', label: 'Hành động'},
]

const modal = useModal('modal');

onMounted(() => {
    files[1] = 1;
    files[2] = 2;
    files[3] = 3;
   // eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
   //     eventForEditBtn()
    })
    if (don_vi_selected.value) {
        don_vi_selected.value = props.obj.data.
        find(item => item.id === don_vi_selected.value.id);
    }
}
/*
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        don_vi_selected.value = props.obj.data.find(item => item.id === id);
        keyModal.value++
        isEdit.value = true;
        modal.showModal();
    });
}
*/
const editModal = (value) => {   
     don_vi_selected.value = value;
        keyModal.value++
        isEdit.value = true;
        modal.showModal();
}
const openModal = () => {
    don_vi_selected.value = null;
    isEdit.value = false;
    modal.showModal();

}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {

    router.visit(route('don-vi.index', {search: value, loai: props.loai}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}, _TIME_DEBOUNCE)


// upload hình ảnh
let formFile = useForm({
    type: '',
    danh_muc: '',
    file: []
})
var input = document.createElement('input');
input.type = 'file';
input.multiple = true;
input.onchange = e => { 
   formFile.file = e.target.files;
   formFile.type = 'don_vi';
   formFile.post(route('tai-lieu.store'), {
        onSuccess: () => {
            onRefresh();
        },
        onError: (err) => {
            console.log(err)
        }
    })
}
const chooseFile = (id) => {
formFile.danh_muc = id;
keyModal
  input.click();
}
// ket thuc



// ket thuc
const msg = ref(null);
const question = useModal('question');
const remove = (item) => {    
    msg.value ="Bạn có muốn xóa: "+item.ten +"?";
    don_vi_selected.value =  item;
    question.showModal();
}
const drop = (item) => {
    question.hideModal();
    router.visit(route('don-vi.delete', {id: item.id}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

</script>

<template>
    <MainLayout>
        <div class="py-3 px-4">
            <div class="mb-3 flex justify-between">
                <button @click.prevent="openModal" class="btn btn-success">Thêm đơn vị</button>
                <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm">
            </div>
            <div class="table-responsive">
                <table class="table table-striped text-2xl">
                <thead>
                    <tr>
                    <th class="text-center">STT</th>
                    <th class="text-left">Tên đơn vị</th>
                    <th class="text-left">Địa chỉ</th>
                    <th class="text-left">Điện thoại</th>
                    <th class="text-left">Fax</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Website</th>
                    <th class="text-center">Liên hệ</th>
                    <th class="text-center">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in obj.data" :key="i">
                    <td class="text-center" scope="row">{{ props.stt + i }}</td>
                    <td>{{ item.ten }}</td>
                    <td>{{ item.dia_chi }}</td>
                    <td>{{ item.dien_thoai }}</td>
                    <td>{{ item.fax }}</td>
                    <td>{{ item.email }}</td>
                    <td>{{ item.website }}</td>
                    <td>{{ item.lien_he }}</td>
                    <td style="vertical-align: unset !important;">
                        <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                        </label>
                        <Upload
                            :listFile ="item.tai_lieu"
                            @refresh="onRefresh"
                        />
                    </td>
                    <td class="text-center">
                        <!--
                        <a :data-id=item.id class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a>
                        -->
                        <a @click.prevent="editModal(item)" class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a>
                            
                        <a @click.prevent="remove(item)"  class="edit cursor-pointer" title="Xóa"><i class="fas fa-times-circle mr-1"></i></a>
                    </td>
                </tr>
                </tbody>
                </table>
                <!--
                <table :key="key" v-data="{ data: obj.data, columns: columns }"
                class="table table-striped text-2xl">
                </table>
                -->
            </div>
            <Pagination
                :all-data="obj"
                @changePage="changePage"
            />
        </div>
        <Modal
            :keyModal="keyModal"
            :don_vi="don_vi_selected"
            :loai="1"
            @closeModal="modal.hideModal"
            @refresh="onRefresh"
            :is-edit="isEdit"
            />
        <Question
        @close-modal="question.hideModal"
        @drop="drop"
        :obj="don_vi_selected"
        :msg="msg"
    />
    </MainLayout>
</template>
