<script setup>

import {computed} from "vue";

const props = defineProps({
    allData: Object,
})

const emit = defineEmits(['changePage']);

const visiblePages = computed(() => {
    const pagesBefore = 4;
    const pagesAfter = 4;

    const startPage = Math.max(props.allData.current_page - pagesBefore, 1);
    const endPage = Math.min(props.allData.current_page + pagesAfter, props.allData.last_page);

    let pages = [];
    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }

    return pages;
});

</script>

<template>
    <div class="float-right mt-3 mb-0">
        <div class="flex">
            <div class="text-center">
                <ul v-if="allData?.total > allData?.per_page" class="pagination">
                    <li>
                        <button
                            :disabled="allData.current_page <= 1"
                            @click.prevent="emit('changePage', allData.current_page - 1)"
                            :class="['page-link', allData.current_page <= 1 && 'cursor-not-allowed']"
                        >
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </li>
                    <li v-for="pageNumber in visiblePages" :key="pageNumber" class="page-item">
                        <a
                            class="page-link cursor-pointer"
                            :class="{ 'bg-primary': pageNumber === allData.current_page }"
                            @click.prevent="emit('changePage',pageNumber)"
                        >
                            {{ pageNumber }}
                        </a>
                    </li>
                    <li>
                        <button
                            :disabled="allData.current_page >= allData.last_page"
                            @click.prevent="emit('changePage', allData.current_page + 1)"
                            :class="['page-link', allData.current_page >= allData.last_page && 'cursor-not-allowed']"
                        >
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
