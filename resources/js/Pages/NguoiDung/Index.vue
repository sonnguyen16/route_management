<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref} from "vue";

const props = defineProps({
    nguoi_dung: Object,
})

const td = ref(null);

const key = ref(0);
const changePage = (page) => {
    router.visit(route('nguoi-dung.index', {page: page}));
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'name', label: 'Tên'},
    {field: 'email', label: 'Email'},
    {field: 'action', label: 'Hành động'},
]

// const modal = useModal('modal');

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
                <button class="btn btn-success">Thêm người dùng</button>
                <input class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm người dùng">
            </div>
            <table :key="key" v-data="{ data: nguoi_dung.data, columns: columns }"
                   class="table table-striped text-2xl">
            </table>
            <Pagination
                :all-data="nguoi_dung"
                @changePage="changePage"
            />
        </div>
    </MainLayout>
</template>
