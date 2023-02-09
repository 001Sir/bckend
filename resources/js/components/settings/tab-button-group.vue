<template>
  <div class="flex items-center justify-center my-10">
    <div class="inline-flex rounded-l-lg rounded-r-lg shadow-sm" role="group">
      {{ active }}
      <button
        type="button"
        class="
          rounded-lg
          px-6
          py-3
          border
          border-gray-300
          bg-white
          text-black
          font-normal
          text-sm
          leading-tight
          hover:bg-blue-600 hover:text-blue-400 hover:bg-opacity-5
          focus:outline-none focus:ring-0
          transition
          duration-150
          ease-in-out
        "
        :class="(activeTab == 'general') ? 'active': ''"
        @click="$emit('changeTab', 'general')"
      >
        General
      </button>
      <!-- <button
        type="button"
        class="
        rounded-r-lg
          px-6
          py-3
          border
          border-gray-300
          bg-white
          text-black
          font-normal
          text-sm
          leading-tight
          hover:bg-blue-600 hover:text-blue-400 hover:bg-opacity-5
          focus:outline-none focus:ring-0
          transition
          duration-150
          ease-in-out
        "
        :class="(activeTab == 'application') ? 'active': ''"
        @click="$emit('changeTab', 'application')"
      >
        Application
    </button> -->
    </div>
  </div>
</template>

<script>
export default {
  emits: ['changeTab'],
  props: ['activeTab'],
}
</script>

<style>
.active {
  color: rgba(0,0,200,1.0);
  background: rgb(0, 128, 255, 0.25);
}
</style>
