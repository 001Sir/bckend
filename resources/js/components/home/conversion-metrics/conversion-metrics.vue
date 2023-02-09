<template>
  <div class="w-full mt-8">
    <h2 class="font-bold uppercase mb-4">Conversion Metrics</h2>
    <div class="w-full max-w-xl flex flex-col rounded-lg bg-white p-6 shadow-md">
      <div class="w-full flex flex-row justify-between items-center">
        <h4 class="font-medium text-base">Job Applications</h4>
      </div>
      <div class="mt-4">
          <DoughtnutChart chartId="jobConversionMetrics" :labels="labels" :data="[metrics.shortlisted, metrics.applicants]" />
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";
import DoughtnutChart from '../../shared/doughtnut-chart.js';

export default {
  components: {
    DoughtnutChart
  },
  data() {
    return {
      labels: ["Selected", "Applied"],
    };
  },
  setup() {
    const store = useStore();

    return {
      getMetrics: store.dispatch('dashboard/conversionMetrics'),
      metrics: computed(() => store.getters["dashboard/conversionMetrics"]),
    };
  },
}
</script>
