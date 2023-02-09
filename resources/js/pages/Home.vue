<template>
  <div class="max-w-[128em] min-h-screen mx-auto px-4">
    <DashboardHeader
      :title="`Welcome, ${userDetails.data.name}`"
      subTitle="Here's a quick insight on the system."
    />
    <Insights />
    <div class="w-full grid grid-cols-1 md:grid-cols-6">
      <div class="flex flex-col col-span-1 md:col-span-4 md:pr-4">
        <Jobs />
        <Partners />
      </div>
      <div class="flex flex-col col-span-1 md:col-span-2 md:pl-6">
        <ConversionMetrics />
        <HomePost />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import DashboardHeader from "../components/main/DashboardHeader.vue";
import Insights from "../components/home/insights/insights.vue";
import Jobs from "../components/home/jobs/jobs.vue";
import Partners from "../components/home/partners/partners.vue";
import ConversionMetrics from "../components/home/conversion-metrics/conversion-metrics.vue";
import HomePost from "../components/home/blog/home-post/index.vue";

export default {
  components: {
    DashboardHeader,
    Insights,
    Jobs,
    Partners,
    ConversionMetrics,
    HomePost
  },
  computed: {
    ...mapGetters({
      userDetails: "auth/user",
    }),
  },
}
</script>