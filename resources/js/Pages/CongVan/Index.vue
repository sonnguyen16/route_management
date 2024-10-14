<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/CongVan/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {_TIME_DEBOUNCE, loaiCongVanOptions, trangThaiCongVan} from "@/Constants/constants.js";
import { debounce } from 'lodash';

const props = defineProps({
    cong_van: Object,
    don_vi: Object,
    nguoi_xu_ly: Object,
})

const cong_van_selected = ref(null);

const key = ref(0);
const changePage = (page) => {
    router.visit(route('cong-van.index', {page: page, ten: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'ten', label: 'Tên công văn'},
    {field: 'loai', label: 'Loại công văn', enums: loaiCongVanOptions},
    {field: 'don_vi.ten', label: 'Đơn vị gửi'},
    {field: 'ngay_gui', label: 'Ngày gửi'},
    {field: 'ngay_nhan', label: 'Ngày nhận'},
    {field: 'nguoi_xu_ly.name', label: 'Người xử lý'},
    {field: 'trang_thai', label: 'Trạng thái', enums: trangThaiCongVan},
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
        cong_van_selected.value = props.cong_van.data.find(item => item.id === id);
        modal.showModal();
    });
}

const openModal = () => {
    cong_van_selected.value = null;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('cong-van.index', {ten: value}), {
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
             <button @click.prevent="openModal" class="btn btn-success">Thêm công văn</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm công văn">
         </div>
         <table :key="key"
                class="table table-striped text-2xl"
                v-data="{ data: cong_van.data, columns: columns }">
         </table>
         <Pagination
             :all-data="cong_van"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :cong_van="cong_van_selected"
         :don_vi="don_vi"
         :nguoi_xu_ly="nguoi_xu_ly"
     />
</MainLayout>
</template>
