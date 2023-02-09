<template>
  <div class="max-w-[128em] min-h-screen mx-auto px-4">
    <DashboardHeader
      title="Settings"
      subTitle="Configure your app."
    />
    <div class="mt-4">
        <TabButtonGroup :activeTab="activeTab" v-on:change-tab="changeTab" />
    </div>
    <div class="mt-4">
      <GeneralTab v-if="activeTab == 'general'" />
      <ApplicationTab v-if="activeTab == 'application'" />
    </div>
  </div>
</template>

<script>
import DashboardHeader from '../components/main/DashboardHeader.vue';
import TabButtonGroup from '../components/settings/tab-button-group.vue';
import GeneralTab from '../components/settings/general/index.vue';
import ApplicationTab from '../components/settings/application/index.vue';

export default {
  components: {
    DashboardHeader,
    TabButtonGroup,
    GeneralTab,
    ApplicationTab
  },
  data() {
    return {
      activeTab: 'general'
    };
  },
  methods: {
    changeTab(value) {
      this.activeTab = value;
    }
  }
}
</script>