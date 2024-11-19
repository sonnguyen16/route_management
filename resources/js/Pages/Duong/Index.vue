<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/Duong/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {debounce} from "lodash";
import {
    _TIME_DEBOUNCE,
    loaiOptions,
    loaiTuanTraOptions,
    maPhanCapOptions
} from "@/Constants/constants.js";


const props = defineProps({
    tuyen_duong: Object,
    huyen: Object,
    don_vi: Object,
    loai_tuyen_duong: Object,
    phan_cap: Object,
    loai_tuan_tra: Object,
})

const tuyen_duong_selected = ref(null);
const keyModal = ref(0);
const key = ref(0);
const changePage = (page) => {
    router.visit(route('tuyen-duong.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'STT'},
    {field: 'ten', label: 'Tên'},
    {field: 'loai_tuyen_duong.ten', label: 'Loại đường', }, // enums: loaiOptions
    {field: 'phan_cap.ten', label: 'Mã phân cấp'}, // enums: maPhanCapOptions},
   // {field: 'diem_dau_xa.name', label: 'Điểm đầu'},
   // {field: 'diem_cuoi_xa.name', label: 'Điểm cuối'},
    {field: 'chieu_dai', label: 'Chiều dài', align: 'center'},
   // {field: 'chieu_rong', label: 'Chiều rộng', align: 'center'},
   // {field: 'dien_tich', label: 'Diện tích'},
    {field: 'loai_tuan_tra.ten', label: 'Loại tuần tra'},// enums: loaiTuanTraOptions},
    {field: 'don_vi.ten', label: 'Đơn vị quản lý'},
    // {field: 'xi_nghiep', label: 'Xí nghiệp'},
    {field: 'huyen.name', label: 'Huyện quản lý'},
    {field: 'action', label: ''},
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
    if(tuyen_duong_selected.value){
        tuyen_duong_selected.value = props.tuyen_duong.data.
        find(item => item.id === tuyen_duong_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        tuyen_duong_selected.value = props.tuyen_duong.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++;
        modal.showModal();
    });
}

const openModal = () => {
    tuyen_duong_selected.value = null;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('tuyen-duong.index', {ten_duong: value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}, _TIME_DEBOUNCE)

const isEdit = ref(false);

</script>

<template>
 <MainLayout>
     <div class="py-3 px-3"> <!--class="py-3 px-4"-->
         <div class="mb-3 flex justify-between">
             <button @click.prevent="openModal" class="btn btn-success">Thêm tuyến đường</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm tuyến đường">
         </div>
         <div class="table-responsive">
             <table :key="key"
                    class="table table-striped text-2xl"
                    v-data="{ data: tuyen_duong.data, columns: columns }">
             </table>
         </div>
         <Pagination
             :all-data="tuyen_duong"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :huyen="huyen"
         :key-modal="keyModal"
         :loai_tuyen_duong="loai_tuyen_duong"
         :loai_tuan_tra="loai_tuan_tra"
         :phan_cap="phan_cap"
         :tuyen_duong="tuyen_duong_selected"
         :don_vi="don_vi"
         :is-edit="isEdit"
         
     />
</MainLayout>
</template>
