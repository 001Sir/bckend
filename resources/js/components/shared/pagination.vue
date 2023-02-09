<template>
    <nav class="w-full flex justify-start" role="navigation" aria-label="pagination">
        <button
            class="rounded-l rounded-sm border-l-2 border-r-2 border-gray-100 px-3 py-2 no-underline text-gray-600 h-10"
            @click.prevent="changePage(pagination.current_page - 1)"
            :disabled="pagination.current_page <= 1">&laquo;</button>
        <ul class="flex flex-row">
            <li v-for="page in pages" :key="page">
                <button class="px-2 my-2 mx-2 text-black rounded-full" :class="isCurrentPage(page) ? 'bg-teal-100' : ''"
                    @click.prevent="changePage(page)">{{ page }}</button>
            </li>
        </ul>
        <button class="rounded-l rounded-sm border-l-2 border-r-2 border-gray-100 px-3 py-2 no-underline text-gray-600 h-10" @click.prevent="changePage(pagination.current_page + 1)"
            :disabled="pagination.current_page >= pagination.last_page">&raquo;</button>
    </nav>
</template>

<style>
.pagination {
    margin-top: 40px;
}
</style>

<script>
export default {
    props: ['pagination', 'offset'],
    methods: {
        isCurrentPage(page) {
            return this.pagination.current_page === page;
        },
        changePage(page) {
            if (page > this.pagination.last_page) {
                page = this.pagination.last_page;
            }
            this.pagination.current_page = page;
            this.$emit('paginate', page);
        }
    },
    computed: {
        pages() {
            let pages = [];
            let from = this.pagination.current_page - Math.floor(this.offset / 2);
            if (from < 1) {
                from = 1;
            }
            let to = from + this.offset - 1;
            if (to > this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            while (from <= to) {
                pages.push(from);
                from++;
            }
            return pages;
        }
    }
}
</script>
