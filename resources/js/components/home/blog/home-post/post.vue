<template>
  <div class="flex flex-row justify-start pb-4">
    <img
      class="w-[7em] h-[7em] object-cover rounded-md"
      :src="img"
    />
    <div class="flex flex-col justify-start pl-5">
      <h4 class="text-base md:text-lg font-normal mb-1">
        {{ name.length > 15 ? name.substring(0, 15)+'...' : name }}
      </h4>
      <h5 class="text-xs md:text-sm text-red-500 font-semibold uppercase">
        {{ date }}
      </h5>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    img: {
      type: String,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    date: {
      type: String,
      required: true
    }
  }
}
</script>