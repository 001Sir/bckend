<template>
  <div class="text-right">
    <div class="w-full mt-8 overflow-x-scroll md:overflow-auto text-left mb-4">
      <h2 class="font-bold uppercase mb-4">Job Posts</h2>
      <Datatable :headers="tableHeaders" :data="jobs.data.slice(0,5)" :hideActions="true" />    
    </div>
    <router-link to="/admin/jobs" class="text-md text-gray-500 font-thin">View All Jobs &gt;</router-link>
  </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";
import Datatable from "../../shared/datatable.vue"

export default {
  components: {
    Datatable
  },
  data() {
    return {
      tableHeaders: [
        { name: "Title", value: "title", isImage: false },
        { name: "Country", value: "country", isImage: false },
        {
          name: "Is Remote",
          value: "remote_position",
          isImage: false,
          isBoolean: true,
        },
        { name: "Expires on", value: "expiration", isImage: false },
        { name: "Status", value: "status", isImage: false },
      ],
    }
  },
  setup() {
    const store = useStore();
    
    return {
      getJobs: store.dispatch('jobs/index'),
      jobs: computed(() => store.getters["jobs/jobs"]),
    }
  }
}
</script>
