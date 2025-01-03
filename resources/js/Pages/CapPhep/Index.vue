<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import Modal from "@/Pages/CapPhep/Modal.vue";
import ModalDiemCapPhep from "@/Pages/CapPhep/ModalDiemCapPhep.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import { debounce } from 'lodash';
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import Upload from "@/Components/UploadFile.vue";
import moment from "moment";

const props = defineProps({
    cap_phep: Object,
    tuyen_duong: Object,
    don_vi: Object,

})

const cap_phep_selected = ref(null);
const cap_phep_cha_selected = ref(null);
const flag = ref(false);
const key = ref(0);
const keyModal = ref(0);
const isEdit = ref(false);
const changePage = (page) => {
    router.visit(route('cap-phep.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'tuyen_duong.ten', label: 'Tên đường'},
    {field: 'so_cap_phep', label: 'Số cấp phép'},
    {field: 'ngay_cap_phep', label: 'Ngày cấp phép'},
    {field: 'tuyen_duong.diem_dau_xa.name', label: 'Điểm đầu'},
    {field: 'tuyen_duong.diem_cuoi_xa.name', label: 'Điểm cuối'},
    {field: 'don_vi.ten', label: 'Đơn vị cấp phép'},
    {field: 'action', label: 'Hành động'},
]

const modal = useModal('modal');
onMounted(() => {
   // eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
       // eventForEditBtn()
    })
    if(cap_phep_selected.value) {
        cap_phep_selected.value = props.cap_phep.data.
        find(item => item.id === cap_phep_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        cap_phep_selected.value = props.cap_phep.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++;
        modal.showModal();
    });
}

const openModal = (item) => {
    keyModal.value++;
    cap_phep_selected.value = null;
    cap_phep_cha_selected.value = item;
    if(item && item.id) {
        flag.value = true;
    } else {
        flag.value = false;
    }
    isEdit.value = false;
    modal.showModal();
}
const editModal = (item) => {
   
    cap_phep_selected.value = item;    
    if (item.cap_phep_id) {
        flag.value = true;
        cap_phep_cha_selected.value = props.cap_phep.data.find(item => item.id === item.cap_phep_id);;
    } else {
        flag.value = false;
        cap_phep_cha_selected.value = null;
    }
    keyModal.value++;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('cap-phep.index', {ten_duong: value}), {
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
   formFile.type = 'cap_phep';
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


function deleteDiemCapPhep(value) {
    router.visit(route('cap-phep.deleteDiemCapPhep', {id: value}), {
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
             <button @click.prevent="openModal" class="btn btn-success">Thêm cấp phép</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm cấp phép">
         </div>
         <div class="table-responsive">
            <table class="table table-striped text-2xl table-line">
                <thead>
                    <tr>
                    <th class="text-center">STT</th>
                    <th class="text-left">Tên đường</th>
                   
                    <th class="text-left">Nội dung</th>
                    <th class="text-left">Từ km</th>
                    <th class="text-left">Đến km</th>
                    <th class="text-left">Từ ngày</th>
                    <th class="text-left">Đến ngày</th>
                    <th class="text-left">Số cấp phép</th>
                    <th class="text-left">Đơn vị cấp phép</th>
                    
                    <th class="text-left">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(it,i) in cap_phep.data" :key="i">
                    <tr v-if="!it.cap_phep_id">
                    <td class="text-center" scope="row">
                        <a @click.prevent="openModal(it)" class="cursor-pointer" title="Thêm đoạn đường"><i class="fas fa-plus mr-2"></i></a>
                    </td>
                    <td>{{ i+1 }}. {{ it.tuyen_duong.ten }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                   
                    <td style="vertical-align: unset !important;">
                        <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(it.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                        </label>
                        <Upload
                            :listFile ="it.tai_lieu"
                            @refresh="onRefresh"
                        />
                    </td>
                    <td class="text-center"><a @click.prevent="editModal(it)" class="cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                    </tr>
                    <tr v-for="(item,i) in it.doan_duong" :key="i">
                    <td class="text-center" scope="row">
                        <a @click.prevent="openModal(it)" class="cursor-pointer" title="Thêm đoạn đường"><i class="fas fa-plus mr-2"></i></a>
                    </td>
                    <td></td>
                   
                    <td>{{ item.noi_dung }}</td>
                    <td>{{ item.tu_km }}</td>
                    <td>{{ item.den_km }}</td>
                    <td><span v-if="item.ngay_cap_phep">{{ moment(item.ngay_cap_phep).format("DD/MM/YYYY") }}</span></td>
                    <td><span v-if="item.ngay_het_han">{{ moment(item.ngay_het_han).format("DD/MM/YYYY") }}</span></td>
                    <td>{{ item.so_cap_phep }}</td>
                    <td><span v-if="item.don_vi">{{ item.don_vi.ten }}</span></td>
                   
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
                    <td class="text-center"><a @click.prevent="editModal(item)" class="cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                    </tr>
                    </template>
                </tbody>
                </table>
         </div>
         <Pagination
             :all-data="cap_phep"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :is-edit="isEdit"
         :key-modal="keyModal"
         :cap_phep="cap_phep_selected"
         :tuyen_duong="tuyen_duong"
         :cap_phep_cha="cap_phep_cha_selected"
         :don_vi="don_vi"
         :flag="flag"
     />
</MainLayout>
</template>
