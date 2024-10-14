<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/Duong/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref} from "vue";

const props = defineProps({
    tuyen_duong: Object,
    huyen: Object,
    don_vi: Object,
})

const td = ref(null);

const key = ref(0);
const changePage = (page) => {
    router.visit(route('tuyen-duong.index', {page: page}));
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'ten', label: 'Tên'},
    {field: 'loai', label: 'Loại'},
    {field: 'ma_phan_cap', label: 'Mã phân cấp'},
    {field: 'diem_dau_xa.name', label: 'Điểm đầu'},
    {field: 'diem_cuoi_xa.name', label: 'Điểm cuối'},
    {field: 'chieu_dai', label: 'Chiều dài'},
    {field: 'chieu_rong', label: 'Chiều rộng'},
    {field: 'dien_tich', label: 'Diện tích'},
    {field: 'loai_tuan_tra', label: 'Loại tuần tra'},
    {field: 'don_vi.ten', label: 'Đơn vị quản lý'},
    {field: 'xi_nghiep', label: 'Xí nghiệp'},
    {field: 'huyen.name', label: 'Huyện quản lý'},
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
        td.value = props.tuyen_duong.data.find(item => item.id === id);
        modal.showModal();
    });
}

</script>

<template>
 <MainLayout>
     <div class="py-3 px-4">
         <div class="mb-3 flex justify-between">
             <button @click.prevent="modal.showModal()" class="btn btn-success">Thêm tuyến đường</button>
             <input class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm tuyến đường">
         </div>
         <table :key="key"
                class="table table-striped text-2xl"
                v-data="{ data: tuyen_duong.data, columns: columns }">
         </table>
         <Pagination
             :all-data="tuyen_duong"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :huyen="huyen"
         :tuyen_duong="td"
         :don_vi="don_vi"
     />
</MainLayout>
</template>
