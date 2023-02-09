<template>
  <div class="pt-2 md:pt-10">
    <h2 class="font-bold uppercase">Insights</h2>
    <div
      class="
        grid grid-cols-1
        md:grid-cols-3
        rounded-xl
        gap-4
        mt-4
        bg-gray-200
        py-4
        px-4
      "
    >
      <InsightCard icon="/img/icons/jobs.svg" color="bg-teal-500">
        <p>Total - <b>{{ insights.jobs.total }}</b></p>
        <p>Posts this Week - <b>{{ insights.jobs.this_week }}</b></p>
        <p>Change from Last Week - <b>{{ insights.jobs.change_diff }}%</b></p>
      </InsightCard>
      <InsightCard icon="/img/icons/users.svg" color="bg-blue-500">
        <p>Applicants - <b>{{ insights.applicants.total }}</b></p>
        <p>Selected Candidates this Month - <b>{{ insights.applicants.selected_candidates }}</b></p>
        <p>Change from Last Month - <b>{{ insights.applicants.change_diff }}%</b></p>
      </InsightCard>
      <InsightCard icon="/img/icons/partners.svg" color="bg-orange-500">
        <p>Partners - <b>{{ insights.partner.total }}</b></p>
        <p>New Partners this Month - <b>{{ insights.partner.new_partners }}</b></p>
        <p>Change from Last Month - <b>{{ insights.partner.change_diff }}%</b></p>
      </InsightCard>
    </div>
  </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";
import InsightCard from "./insight-card.vue";

export default {
  components: {
    InsightCard,
  },
  data() {
    return {
      insights: []
    }
  },
  setup() {
    const store = useStore();

    return {
      getInsights: store.dispatch('dashboard/insights'),
      insights: computed(() => store.getters["dashboard/insights"]),
    };
  },
}
</script>
