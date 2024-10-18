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
import Input from "@/Components/Input.vue";

const props = defineProps({
    sua_chua: Object,
    tuyen_duong: Object,
    don_vi: Object,
    nguoi_duyet: Object,
})

const sua_chua_selected = ref(null);
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const ngay_khoi_cong = ref('');
const ngay_hoan_thanh = ref('');

const changePage = (page) => {
    router.visit(route('sua-chua.index', {
            page: page,
            ten_duong: search.value,
            ngay_hoan_thanh: ngay_hoan_thanh.value,
            ngay_khoi_cong: ngay_khoi_cong.value
        }), {
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
    if (sua_chua_selected.value) {
        sua_chua_selected.value = props.sua_chua.data.find(item => item.id === sua_chua_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        sua_chua_selected.value = props.sua_chua.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    sua_chua_selected.value = null;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

watch(ngay_khoi_cong, (value) => {
    ngay_hoan_thanh.value = '';
    router.visit(route('sua-chua.index', {
        ngay_khoi_cong: value,
        ngay_hoan_thanh: ngay_hoan_thanh.value,
        ten_duong: search.value
    }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
})

watch(ngay_hoan_thanh, (value) => {
    router.visit(route('sua-chua.index', {
        ngay_khoi_cong: ngay_khoi_cong.value,
        ngay_hoan_thanh: value,
        ten_duong: search.value
    }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
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
             <div class="flex gap-5 items-center">
                 <button @click.prevent="openModal" class="btn btn-success w-[200px]">Thêm sửa chữa</button>
                 <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_khoi_cong">Ngày khởi công</label>
                     <input v-model="ngay_khoi_cong" type="date" class="form-control flex-1" placeholder="Ngày khởi công"/>
                 </div>
                 <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_hoan_thanh">Ngày hoàn thành</label>
                     <input v-model="ngay_hoan_thanh" type="date" class="form-control flex-1" placeholder="Ngày hoàn thành"/>
                 </div>
             </div>
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
         :is-edit="isEdit"
         :key-modal="keyModal"
         :tuyen_duong="tuyen_duong"
         :sua_chua="sua_chua_selected"
         :don_vi="don_vi"
         :nguoi_duyet="nguoi_duyet"
     />
</MainLayout>
</template>
