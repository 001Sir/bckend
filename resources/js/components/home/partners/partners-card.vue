<template>
  <div
    class="
      flex flex-col
      justify-center
      items-start
      w-full
      min-w-[128px]
      h-[250px]
      my-2
      mr-4
      p-6
      bg-white
      rounded-md
      shadow-md
    "
  >
    <img
      class="w-[64px] h-[64px] object-cover rounded-full"
      :src="img"
    />
    <h4 class="font-thin text-sm mt-2">{{ name.length > 10 ? name.substring(0, 12)+'...' : name }}</h4>
    <p class="font-bold text-4xl mt-6">{{ jobPostCount }}</p>
    <p class="font-thin text-sm text-gray-400">Jobs Posted</p>
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
    jobPostCount: {
      type: String,
      required: true
    },
  }
}
</script>
