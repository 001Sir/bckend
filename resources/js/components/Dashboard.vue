<template>
    <div>
        <Menu />
        <div class="h-full ml-24 pt-4 pl-4 pr-4 pb-4 bg-gray-100">
            <router-view />
        </div>
    </div>
</template>

<script setup>
    import Menu from './main/Menu.vue';
</script>