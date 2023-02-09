<template>
  <div class="w-full mt-8">
    <h2 class="font-bold uppercase mb-4">Partners</h2>
    <div class="max-w-3xl grid grid-cols-2 md:grid-cols-4 gap-5 md:gap-8 justify-center md:justify-start items-center">
      <PartnersCard :img="partners.data[0].profile_picture" :name="partners.data[0].name" :jobPostCount="partners.data[0].posted_jobs" />
      <PartnersCard :img="partners.data[1].profile_picture" :name="partners.data[1].name" :jobPostCount="partners.data[1].posted_jobs" />
      <PartnersCard :img="partners.data[2].profile_picture" :name="partners.data[2].name" :jobPostCount="partners.data[2].posted_jobs" />
      <PartnersCard :img="partners.data[3].profile_picture" :name="partners.data[3].name" :jobPostCount="partners.data[3].posted_jobs" />
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';
import { useStore } from 'vuex';
import PartnersCard from './partners-card.vue';

export default {
  components: {
    PartnersCard
  },
  setup() {
    const store = useStore();

    return {
      getPartners: store.dispatch('partners/dashboardPartners'),
      partners: computed(() => store.getters["partners/dashboard_partners"]),
    };
  },
}
</script>
