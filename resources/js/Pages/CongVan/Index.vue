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
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const ngay_gui = ref('');
const ngay_nhan = ref('');
const changePage = (page) => {
    router.visit(route('cong-van.index', {
        page: page,
        ten: search.value,
        ngay_gui: ngay_gui.value,
        ngay_nhan: ngay_nhan.value
    }), {
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
    keyModal.value++
    nextTick(() => {
        eventForEditBtn()
    })
    if(cong_van_selected.value) {
        cong_van_selected.value = props.cong_van.data.find(item => item.id === cong_van_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        cong_van_selected.value = props.cong_van.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++;
        modal.showModal();
    });
}

const openModal = () => {
    cong_van_selected.value = null;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

watch(ngay_gui, (value) => {
    ngay_nhan.value = '';
    router.visit(route('cong-van.index', {
        ngay_gui: value,
        ngay_nhan: ngay_nhan.value,
        ten: search.value
    }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
})

watch(ngay_nhan, (value) => {
    router.visit(route('cong-van.index', {
        ngay_nhan: value,
        ngay_gui: ngay_gui.value,
        ten: search.value
    }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
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
             <div class="flex gap-5 items-center">
                 <button @click.prevent="openModal" class="btn btn-success w-[200px]">Thêm công văn</button>
                 <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_khoi_cong">Từ ngày</label>
                     <input v-model="ngay_gui" type="date" class="form-control flex-1" placeholder="Ngày khởi công"/>
                 </div>
                 <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_hoan_thanh">Đến ngày</label>
                     <input v-model="ngay_nhan" type="date" class="form-control flex-1" placeholder="Ngày hoàn thành"/>
                 </div>
             </div>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm công văn">
         </div>
         <div class="table-responsive">
             <table :key="key"
                    class="table table-striped text-2xl"
                    v-data="{ data: cong_van.data, columns: columns }">
             </table>
         </div>
         <Pagination
             :all-data="cong_van"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :key-modal="keyModal"
         :is-edit="isEdit"
         :cong_van="cong_van_selected"
         :don_vi="don_vi"
         :nguoi_xu_ly="nguoi_xu_ly"
     />
</MainLayout>
</template>
