<template>
  <div class="w-full grid grid-cols-1 md:grid-cols-2">
    <div class="flex flex-col items-center md:items-start mt-6">
      <h1 class="font-bold text-lg raleway-font">{{ title }}</h1>
      <h4 class="font-thin text-sm raleway-font pt-2">
        {{ subTitle }}
      </h4>
    </div>
    <div class="flex flex-col justify-start md:justify-end items-center md:items-end my-6 md:my-0">
      <div class="relative flex flex-col">
        <div @click.prevent="showDropdown = !showDropdown"
          class="flex items-center bg-white rounded-3xl py-1 pl-2 hover:shadow-md cursor-pointer">
          <img src="/img/sample-profile.jpg" class="
              w-6
              h-6
              flex
              justify-center
              items-center
              rounded-full" alt="profile picture" />
          <p class="text-xs font-thin text-black pl-2 pr-4">{{ (userDetails.data.name.length > 5) ? userDetails.data.name.substring(0, 20)+'...' : userDetails.data.name }}</p>
        </div>
        <div v-if="showDropdown" class="absolute top-8 left-0 right-0 bg-white rounded-md shadow-md p-2 mt-2">
          <button class="w-full py hover:text-gray-400" @click.prevent="logout">Logout</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    subTitle: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      showDropdown: false,
    };
  },
  computed: {
    ...mapGetters({
      userDetails: "auth/user",
    }),
  },
  methods: {
    ...mapActions({
      signOut: "auth/logout",
    }),
    async logout() {
      this.signOut().then(() => {
        this.$router.push({ name: "login" })
      });
    },
  }
}
</script>

<style>

</style>