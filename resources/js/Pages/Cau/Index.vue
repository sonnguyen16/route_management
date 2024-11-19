<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/Cau/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import { debounce } from 'lodash';
import {_TIME_DEBOUNCE, maPhanCapOptions} from "@/Constants/constants.js";

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
        console.log(id);
        cau_selected.value = props.cau.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
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

</script>

<template>
 <MainLayout>
     <div class="py-3 px-4">
         <div class="mb-3 flex justify-between">
             <button @click.prevent="openModal" class="btn btn-success">Thêm cầu</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm nút giao">
         </div>
         <div class="table-responsive">
             <table :key="key"
                    class="table table-striped text-2xl"
                    v-data="{ data: cau.data, columns: columns }">
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