<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    tuyen_duong: Object,
})

</script>

<template>
    <MainLayout>
        <div class="py-3 px-4">
            <div class="mb-3 flex justify-between">
                <button  class="btn btn-success">Thêm tài liệu</button>
                <input class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm tài liệu">
            </div>
            <div>
                <p class="mb-2">
                    <i class="fa fa-folder text-yellow-300 me-2 text-[20px]"></i>
                    <span class="text-[20px] text-gray-600">Tài liệu các tuyến đường</span>
                </p>
                <div class="ms-8 text-lg" v-for="td in tuyen_duong">
                    <p class="mb-1 text-success">Đường {{ td.ten }}</p>
                    <div class="ms-3 mb-2">
                        <a :href="'/storage/' + file.file" v-for="file in td.tai_lieu"
                           target="_blank"
                           class="d-block cursor-pointer ">
                            {{ file.ten }}
                        </a>
                    </div>
                </div>
                <p class="mb-2">
                    <i class="fa fa-folder text-yellow-300 me-2 text-[20px]"></i>
                    <span class="text-[20px] text-gray-600">Tài liệu khác</span>
                </p>
            </div>
        </div>
    </MainLayout>
</template>
