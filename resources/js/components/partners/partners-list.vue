<template>
  <div class="flex flex-row justify-start my-4">
    <img
      class="w-[7em] h-[7em] object-cover rounded-md"
      :src="img"
    />
    <div class="w-full flex flex-row justify-between">
      <div class="max-w-[30em] flex flex-col justify-start pl-5">
        <a href="#!">
          <h4 class="text-lg font-bold mb-1">{{ name }}</h4>
        </a>
        <h5 class="text-sm text-gray-400 font-thin mb-2">
          {{ (bio.length > 100) ? bio.substring(0,100) + '...' : bio }}
        </h5>
      </div>
      <div class="flex flex-col justify-start text-right">
        <h5 class="text-gray-400 text-xs font-thin">
          Member since {{ date }}
        </h5>
      </div>
    </div>
  </div>
</template>
  
<script>
import moment from 'moment';

export default {
  props: {
    img: String,
    name: String,
    bio: String,
    registered_date: String,
  },
  computed: {
    date() {
      return moment(this.registered_date).format("MMMM YYYY")
    }
  }
}
</script>