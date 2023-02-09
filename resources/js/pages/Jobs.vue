<template>
  <div class="max-w-[128em] min-h-screen mx-auto px-4">
    <DashboardHeader
      title="Jobs"
      subTitle="Jobs posted throughout the system."
    />
    <div class="grid grid-cols-1 md:grid-cols-6 md:gap-4">
      <div class="col-span-1 md:col-span-4">
        <JobTable />
        <div class="pt-[26em]"></div>
        <SkillsTable />
      </div>
      <div class="flex flex-col col-span-1 md:col-span-2">
          <CategoriesTable />
          <JobTypesTable />
          <PostDurationsTable />
      </div>
    </div>
  </div>
</template>

<script>
import { useStore } from "vuex";
import DashboardHeader from "../components/main/DashboardHeader.vue";
import JobTable from "../components/jobs/JobTable.vue";
import CategoriesTable from "../components/jobs/CategoriesTable.vue";
import SkillsTable from "../components/jobs/SkillsTable.vue";
import JobTypesTable from "../components/jobs/JobTypesTable.vue";
import PostDurationsTable from "../components/jobs/PostDurationsTable.vue";

export default {
  components: {
    DashboardHeader,
    JobTable,
    CategoriesTable,
    SkillsTable,
    JobTypesTable,
    PostDurationsTable
},
  setup() {
    const store = useStore();
    
    return {
      getPartners: store.dispatch('partners/index'),
      getPostDurations: store.dispatch('postdurations/index'),
      getCategories: store.dispatch('categories/index'),
      getSkills: store.dispatch('skills/index'),
      getJobTypes: store.dispatch('jobtypes/index')
    }
  }
}
</script>
