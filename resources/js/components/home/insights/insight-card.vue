<template>
  <div
    :class="`
      w-full
      h-36
      md:h-32
      flex flex-row
      justify-center
      items-center
      rounded-xl
      p-4
      shadow-md
      cursor-pointer `+
      props.color
    "
  >
    <img class="w-12 md:w-16 invert" :src=props.icon />
    <div class="text-sm md:text-md text-left text-white ml-4">
        <slot></slot>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  icon: {
    type: String,
    required: true,
  },
  color: {
    type: String,
    required: true,
  },
});
</script>
