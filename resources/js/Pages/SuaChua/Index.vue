<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/SuaChua/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {_TIME_DEBOUNCE, loaiSuaChuaOptions} from "@/Constants/constants.js";
import { debounce } from 'lodash';

const props = defineProps({
    sua_chua: Object,
    tuyen_duong: Object,
    don_vi: Object,
    nguoi_duyet: Object,
})

const sua_chua_selected = ref(null);

const key = ref(0);
const changePage = (page) => {
    router.visit(route('sua-chua.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'tuyen_duong.ten', label: 'Tên đường'},
    {field: 'loai_sua_chua', label: 'Loại sửa chữa', enums: loaiSuaChuaOptions},
    {field: 'tu_km', label: 'Từ km'},
    {field: 'den_km', label: 'Đến km'},
    {field: 'ngay_duyet', label: 'Ngày duyệt'},
    {field: 'nguoi_duyet.name', label: 'Người duyệt'},
    {field: 'ngay_khoi_cong', label: 'Ngày khởi công'},
    {field: 'ngay_hoan_thanh', label: 'Ngày hoàn thành'},
    {field: 'don_vi.ten', label: 'Đơn vị thi công'},
    {field: 'tai_lieu', label: 'Tài liệu'},
    {field: 'action', label: 'Hành động'},
]

const modal = useModal('modal');

onMounted(() => {
    eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    nextTick(() => {
        eventForEditBtn()
    })
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        sua_chua_selected.value = props.sua_chua.data.find(item => item.id === id);
        modal.showModal();
    });
}

const openModal = () => {
    sua_chua_selected.value = null;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('sua-chua.index', {ten_duong: value}), {
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
             <button @click.prevent="openModal" class="btn btn-success">Thêm sửa chữa</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm sửa chữa">
         </div>
         <table :key="key"
                class="table table-striped text-2xl"
                v-data="{ data: sua_chua.data, columns: columns }">
         </table>
         <Pagination
             :all-data="sua_chua"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :tuyen_duong="tuyen_duong"
         :sua_chua="sua_chua_selected"
         :don_vi="don_vi"
         :nguoi_duyet="nguoi_duyet"
     />
</MainLayout>
</template>
