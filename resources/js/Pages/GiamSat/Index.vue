<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import Modal from "@/Pages/GiamSat/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import { debounce } from 'lodash';
import {_TIME_DEBOUNCE, maPhanCapOptions} from "@/Constants/constants.js";
import Upload from "@/Components/UploadFile.vue";
import Question from "@/Components/Question.vue";

const props = defineProps({
    giam_sat: Object,
    tuyen_duong: Object,
    don_vi: Object,
    stt: Number,
})

const giam_sat_selected = ref(null);
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const giam_sat_cha_selected = ref(null);
const flag = ref(false);

const changePage = (page) => {
    router.visit(route('giam-sat.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'tuyen_duong.ten', label: 'Tên đường'},
    {field: 'tuyen_duong.ma_phan_cap', label: 'Mã phân cấp', enums: maPhanCapOptions},
    {field: 'tuyen_duong.diem_dau_xa.name', label: 'Điểm đầu'},
    {field: 'tuyen_duong.diem_cuoi_xa.name', label: 'Điểm cuối'},
    {field: 'tuyen_duong.chieu_dai', label: 'Chiều dài'},
    {field: 'tuyen_duong.chieu_rong', label: 'Chiều rộng'},
    {field: 'tuyen_duong.dien_tich', label: 'Chiều rộng'},
    {field: 'don_vi.ten', label: 'Đơn vị giám sát'},
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
    if (giam_sat_selected.value) {
        giam_sat_selected.value = props.giam_sat.data.
        find(item => item.id === giam_sat_selected.value.id);
    }
}
const eventForEditBtn = () => {
   /*  $('.edit').click(function () {
        const id = $(this).data('id');
        giam_sat_selected.value = props.giam_sat.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++
        modal.showModal();
    }); */
}

const openModal = (item) => {
    keyModal.value++
    giam_sat_selected.value = null;
    giam_sat_cha_selected.value = item;
    if(item && item.id) {
        flag.value = true;
    } else {
        flag.value = false;
    }
    isEdit.value = false;
    modal.showModal();
}

const editModal = (item) => {
    console.log(item);
    keyModal.value++
    giam_sat_selected.value = item;
    if (item.giam_sat_id) {
        giam_sat_cha_selected.value = props.giam_sat.data.find(item => item.id === item.giam_sat_id);
        flag.value = true;
    } else {
        giam_sat_cha_selected.value = null;
        flag.value = false;
    }
    isEdit.value = true;
    modal.showModal();
}
const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('giam-sat.index', {ten_duong: value}), {
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
   formFile.type = 'giam_sat';
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

// ket thuc
const msg = ref(null);
const question = useModal('question');
const remove = (item) => {    
    msg.value ="Bạn có muốn xóa: "+( item.hu_hong ? item.hu_hong : item.tuyen_duong.ten) +"?";
    giam_sat_selected.value =  item;
    question.showModal();
}
const drop = (item) => {
    question.hideModal();
    router.visit(route('giam-sat.delete', {id: item.id}), {
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
             <button @click.prevent="openModal(null)" class="btn btn-success">Thêm giám sát</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm giám sát">
         </div>
         <div class="table-responsive">
            <table class="table table-striped text-2xl table-line">
                <thead>
                    <tr>
                    <th class="text-center">STT</th>
                    <th class="text-left">Tên tuyến đường</th>
                    <th class="text-left">Hư hỏng bất cập</th>
                    <th class="text-left">Vị trí</th>
                    <th class="text-left">Mức độ</th>
                    <th class="text-left">Tình trạng khắc phục</th>   
                    <th class="text-left">Đơn vị giám sát</th>                 
                    <th class="text-left">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(it, i) in giam_sat.data" :key="i">
                    <tr v-if="!it.giam_sat_id">
                        <td class="text-center" scope="row">{{ props.stt + i }}</td>
                        <td>{{ it.tuyen_duong ? it.tuyen_duong.ten : '' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><a href="#">{{ it.don_vi ? it.don_vi.ten : ''}}</a></td>
                        <td>
                            <Upload
                            :listFile ="it.tai_lieu"
                            @refresh="onRefresh"
                        />
                        <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(it.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                        </label>
                        </td>
                        <td class="text-center">
                            <a @click.prevent="editModal(it)" class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a>
                            <a @click.prevent="remove(it)"  class="edit cursor-pointer" title="Xóa"><i class="fas fa-times-circle mr-1"></i></a>
                        </td>
                    </tr>
                   
                    <tr v-for="(item,i) in it.doan_duong" :key="i">
                    <td class="text-center" scope="row"></td>
                    <td></td>
                    <td>{{ item.hu_hong }}</td>
                    <td>{{ item.vi_tri }}</td>
                    <td><span v-if="item.muc_do !== null"></span>{{ item.muc_do == 0 ? 'Nghiêm trọng' : 'X Nghiêm trọng' }}</td>
                    <td><span v-if="item.tinh_trang_khac_phuc !== null">{{ item.tinh_trang_khac_phuc == 0 ? 'Khắc phục' : 'Chưa khắc phục' }}</span></td>
                    <td></td>
                    <td style="vertical-align: unset !important;">
                        <Upload
                            :listFile ="item.tai_lieu"
                            @refresh="onRefresh"
                        />
                        <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                        </label>
                    </td>
                    <td class="text-center">
                        <a @click.prevent="editModal(item)" class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a>
                        <a @click.prevent="remove(item)"  class="edit cursor-pointer" title="Xóa"><i class="fas fa-times-circle mr-1"></i></a>
                    </td>
                    </tr>
                    <tr v-if="!it.giam_sat_id">
                        <td class="text-center" scope="row"></td>
                        <td><a @click.prevent="openModal(it)" class="edit cursor-pointer" title="Thêm đoạn đường"><i class="fas fa-plus mr-2"></i></a>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </template>
                </tbody>
                </table>
         </div>
         <Pagination
             :all-data="giam_sat"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :is-edit="isEdit"
         :key-modal="keyModal"
         :giam_sat="giam_sat_selected"
         :tuyen_duong="tuyen_duong"
         :giam_sat_cha="giam_sat_cha_selected"
         :don_vi="don_vi"
         :flag="flag"
     />
     <Question
        @close-modal="question.hideModal"
        @drop="drop"
        :obj="giam_sat_selected"
        :msg="msg"
    />
</MainLayout>
</template>
