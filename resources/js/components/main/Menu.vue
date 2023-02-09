<template>
  <div
    class="
      fixed
      flex flex-col
      top-0
      left-0
      w-24
      bg-blue-900
      dark:bg-gray-900
      h-full
      text-white
      border-none
      sidebar
    "
  >
    <div
      class="
        overflow-y-auto overflow-x-hidden
        flex flex-col
        justify-between
        items-center
        flex-grow
      "
    >
      <ul class="flex flex-col py-4 space-y-1">
        <!-- Dashboard -->
        <li>
          <router-link
            to="/admin/home"
            class="
              relative
              flex flex-row
              items-center
              h-11
              focus:outline-none
              text-white-600
              hover:text-white-800
            "
          >
            <span
              class="
                w-16
                h-16
                inline-flex
                justify-center
                p-1
                items-center
                rounded-xl
                border-4 border-gray-400
              "
            >
              <span
                class="
                  w-full
                  h-full
                  p-2
                  flex
                  justify-center
                  rounded-md
                  bg-gray-400
                "
              >
                <img
                  class="w-full invert opacity-50"
                  src="/img/icons/dashboard.svg"
                />
              </span>
            </span>
          </router-link>
        </li>
        <!-- Jobs -->
        <li class="pt-8">
          <router-link
            to="/admin/jobs"
            class="
              relative
              flex flex-row
              items-center
              h-11
              focus:outline-none
              text-white-600
              hover:text-white-800
            "
          >
            <span
              class="
                w-16
                h-16
                inline-flex
                justify-center
                p-1
                items-center
                rounded-xl
                border-4 border-gray-400
              "
            >
              <span
                class="
                  w-full
                  h-full
                  p-2
                  flex
                  justify-center
                  rounded-md
                  bg-gray-400
                "
              >
                <img
                  class="w-full invert opacity-50"
                  src="/img/icons/jobs.svg"
                />
              </span>
            </span>
          </router-link>
        </li>
        <!-- Partners -->
        <li class="pt-8">
          <router-link
            to="/admin/partners"
            class="
              relative
              flex flex-row
              items-center
              h-11
              focus:outline-none
              text-white-600
              hover:text-white-800
            "
          >
            <span
              class="
                w-16
                h-16
                inline-flex
                justify-center
                p-1
                items-center
                rounded-xl
                border-4 border-gray-400
              "
            >
              <span
                class="
                  w-full
                  h-full
                  p-2
                  flex
                  justify-center
                  rounded-md
                  bg-gray-400
                "
              >
                <img
                  class="w-full invert opacity-50"
                  src="/img/icons/partners.svg"
                />
              </span>
            </span>
          </router-link>
        </li>
        <!-- Users -->
        <li class="pt-8">
          <router-link
            to="/admin/users"
            class="
              relative
              flex flex-row
              items-center
              h-11
              focus:outline-none
              text-white-600
              hover:text-white-800
            "
          >
            <span
              class="
                w-16
                h-16
                inline-flex
                justify-center
                p-1
                items-center
                rounded-xl
                border-4 border-gray-400
              "
            >
              <span
                class="
                  w-full
                  h-full
                  p-2
                  flex
                  justify-center
                  rounded-md
                  bg-gray-400
                "
              >
                <img
                  class="w-full invert opacity-50"
                  src="/img/icons/users.svg"
                />
              </span>
            </span>
          </router-link>
        </li>
        <!-- Blog -->
        <li class="pt-8">
          <router-link
            to="/admin/content-management"
            class="
              relative
              flex flex-row
              items-center
              h-11
              focus:outline-none
              text-white-600
              hover:text-white-800
            "
          >
            <span
              class="
                w-16
                h-16
                inline-flex
                justify-center
                p-1
                items-center
                rounded-xl
                border-4 border-gray-400
              "
            >
              <span
                class="
                  w-full
                  h-full
                  p-2
                  flex
                  justify-center
                  rounded-md
                  bg-gray-400
                "
              >
                <img
                  class="w-full invert opacity-50"
                  src="/img/icons/blog.svg"
                />
              </span>
            </span>
          </router-link>
        </li>
        <!-- Subscription -->
        <li class="pt-8">
          <router-link
            to="/admin/subscriptions"
            class="
              relative
              flex flex-row
              items-center
              h-11
              focus:outline-none
              text-white-600
              hover:text-white-800
            "
          >
            <span
              class="
                w-16
                h-16
                inline-flex
                justify-center
                p-1
                items-center
                rounded-xl
                border-4 border-gray-400
              "
            >
              <span
                class="
                  w-full
                  h-full
                  p-2
                  flex
                  justify-center
                  rounded-md
                  bg-gray-400
                "
              >
                <img
                  class="w-full invert opacity-50"
                  src="/img/icons/subscription.svg"
                />
              </span>
            </span>
          </router-link>
        </li>
      </ul>
      <div id="settingsNavIcon">
        <router-link
          to="/admin/settings"
          class="w-10 h-10 mb-4 flex justify-center items-center"
        >
          <span class="w-full h-full p-2 flex justify-center">
            <img class="w-full" src="/img/icons/settings.svg" />
          </span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.auth.user,
    };
  },
};
</script>

<style lang="scss" scoped>
.router-link-active {
  span {
    border-color: white;

    span {
      background: rgb(209, 213, 219);
    }

    img {
      fill: #000;
      filter: invert(0%) !important;
      opacity: 1 !important;
    }
  }
}

#settingsNavIcon {
  .router-link-active {
    span {
      background: #ff0000;
      border-radius: 50px;
    }

    img {
      fill: #fff;
    }
  }
}
</style>
