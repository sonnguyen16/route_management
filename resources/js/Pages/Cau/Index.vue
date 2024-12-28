<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import Modal from "@/Pages/Cau/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import { debounce } from 'lodash';
import {_TIME_DEBOUNCE, maPhanCapOptions} from "@/Constants/constants.js";
import Upload from "@/Components/UploadFile.vue";

const props = defineProps({
    cau: Object,
    loai_ket_cau_nhip: Object,
    loai_cau: Object,
    tuyen_duong: Object
})

const cau_selected = ref(null);
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const changePage = (page) => {
    router.visit(route('cau.index', {page: page, ten: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'ten', label: 'Tên cầu'},
    {field: 'ly_trinh', label: 'Lý trình'},
    {field: 'chieu_rong', label: 'Chiều rộng'},
    {field: 'chieu_dai', label: 'Chiều dài'},
   // {field: 'loai_ket_cau_nhip.ten', label: 'Loại kết cấu nhịp'},
    {field: 'nam_khai_thac', label: 'Năm khai thác'},
    {field: 'kiem_dinh', label: 'Kiểm định'},
   // {field: 'tuyen_duong_id', label: 'Tuyến đường'},
   // {field: 'loai_cau_id', label: 'Loại cầu'},
    {field: 'action', label: 'Hành động'},
]
const modal = useModal('modal');

onMounted(() => {
    eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
        eventForEditBtn()
    })
    if (cau_selected.value) {
        cau_selected.value = props.cau.data.
        find(item => item.id === cau_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        
        cau_selected.value = props.cau.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    keyModal.value++
    cau_selected.value = null;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('cau.index', {search: value}), {
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
   formFile.type = 'cau';
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
    input.click();
}
// ket thuc
</script>

<template>
 <MainLayout>
     <div class="py-3 px-4">
         <div class="mb-3 flex justify-between">
            <div>
             <button @click.prevent="openModal" class="btn btn-success">Thêm cầu</button>
             <span style="padding-left: 10px;"><a :href="route('cau-hinh.index',{loai: 'loai-ket-cau-nhip'})" class="btn btn-success">Kết cấu nhịp</a></span>
             <span style="padding-left: 10px;"><a :href="route('cau-hinh.index',{loai: 'loai-cau'})" class="btn btn-success">Loại cầu</a></span>
            </div>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm nút giao">
         </div>
         <div class="table-responsive">
            <!-- <table :key="key"
                    class="table table-striped text-2xl"
                    v-data="{ data: cau.data, columns: columns }">
             </table> -->
             <table class="table table-striped text-2xl">
                <thead>
                    <tr style="line-height: 1.5;">
                    <th class="text-center">STT</th>
                    <th class="text-left">Tên cầu</th>
                    <th class="text-left">Loại cầu</th>
                    <th class="text-left">Loại kết<br>cấu nhịp</th>
                    <th class="text-left">Lý trình</th>                    
                    <th class="text-center">Chiều dài</th>
                    <th class="text-left">Chiều rộng</th>
                    <th class="text-center">Năm<br>khai thác</th>
                    <th class="text-center">Tải trọng thiết kế</th>
                    <th class="text-center">Tải trọng khai thác</th>
                    <th class="text-center">Năm<br>kiểm định</th>
                    <th class="text-center">Tuyến đường</th>
                    <th class="text-center">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in cau.data" :key="i">
                    <td class="text-center" scope="row">{{ i+1 }}</td>  
                    <td><a :data-id=item.id class="edit cursor-pointer" title="Sửa">{{ item.ten }}</a></td> 
                    <td>{{ item.loai_cau ? item.loai_cau.ten : ''}}</td>  
                    <td>{{ item.loai_ket_cau_nhip ? item.loai_ket_cau_nhip.ten : ''}}</td>  
                    <td class="text-left">{{ item.ly_trinh }}</td> 
                    <td class="text-center">{{ item.chieu_dai }} m</td> 
                    <td class="text-center">{{ item.chieu_rong }} m</td>
                    <td class="text-center">{{ item.nam_khai_thac }}</td>
                    <td class="text-center">{{ item.tai_trong }} tấn</td>
                    <td class="text-center">{{ item.trong_tai_khai_thac }} tấn</td>
                    <td class="text-center">{{ item.kiem_dinh }}</td>
                    <td>{{ item.tuyen_duong ? item.tuyen_duong.ten : ''}}</td>
                    <td style="vertical-align: unset !important;">
                        <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                            Tải lên tệp
                        </label>
                        <Upload
                            :listFile ="item.tai_lieu"
                            @refresh="onRefresh"
                        />
                    </td>
                    <td class="text-center"><a :data-id=item.id class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                </tr>
                </tbody>
                </table>
         </div>
         <Pagination
             :all-data="obj"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :is-edit="isEdit"
         :key-modal="keyModal"
         :cau="cau_selected"
         :loai_ket_cau_nhip="loai_ket_cau_nhip"
         :loai_cau="loai_cau"
         :tuyen_duong="tuyen_duong"
     />
</MainLayout>
</template>