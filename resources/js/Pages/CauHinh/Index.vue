<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import {nextTick, onMounted, ref, watch} from "vue";
import {debounce} from "lodash";
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import {useModal} from "@/Hooks/useModal.js";
import Modal from "@/Pages/CauHinh/Modal.vue";

const props = defineProps({
    cau_hinh: Object,
    loai: String,
    ten: String,
    linkback: String,
})
const key = ref(0);
const keyModal = ref(0);
const cau_hinh_selected = ref(null);
const changePage = (page) => {
    router.visit(route('cau-hinh.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'ten', label: 'Tên'},
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
    if (cau_hinh_selected.value) {
        cau_hinh_selected.value = props.cau_hinh.data.
        find(item => item.id === cau_hinh_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        cau_hinh_selected.value = props.cau_hinh.data.find(item => item.id === id);
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    cau_hinh_selected.value = null;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
   router.visit(route('cau-hinh.index', {search: value, loai: props.loai}), {
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
                <div>
                    <button @click.prevent="openModal" style="padding-left:10px" class="btn btn-success">{{ props.ten }}</button>
                    <span style="padding-left:10px"><a :href="route(linkback)" class="btn btn-success">Trở lại</a></span>
                </div>
                <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm">
            </div>
            <div class="table-responsive">
                <table class="table table-striped text-2xl table-line">
                <thead>
                    <tr>
                    <th class="text-center"  style="width: 50px;">STT</th>
                    <th class="text-left">Tên</th>
                    <th class="text-center" style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, i) in cau_hinh.data" :key="i">
                        <td class="text-center">{{ i+1 }}</td>
                        <td>{{ item.ten }}</td>
                        <td class="text-center"><a :data-id=item.id class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                    </tr>
                </tbody>
                </table>
                <!--<table :key="key" v-data="{ data: cau_hinh.data, columns: columns }"
                class="table table-striped text-2xl">
                </table> -->
            </div>
            <Pagination
                :all-data="cau_hinh"
                @changePage="changePage"
            />
        </div>
        <Modal
            :keyModal="keyModal"
            :cau_hinh="cau_hinh_selected"
            :loai="loai"
            @closeModal="modal.hideModal"
            @refresh="onRefresh"
            />
    </MainLayout>
</template>
