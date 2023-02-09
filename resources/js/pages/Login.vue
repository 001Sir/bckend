<template>
  <div class="lg:flex">
    <div class="lg:w-1/2 xl:max-w-screen-sm">
      <div
        class="
          py-12
          bg-teal-100
          lg:bg-white
          flex
          justify-center
          lg:justify-start lg:px-12
        "
      >
        <div class="cursor-pointer flex items-center">
          <div class="text-2xl text-teal-800 tracking-wide ml-2 font-semibold">
            <img src="/img/logo.png" class="w-40 mx-auto mb-8" alt="logo" />
          </div>
        </div>
      </div>
      <div
        class="
          mt-10
          px-12
          sm:px-24
          md:px-48
          lg:px-12 lg:mt-16
          xl:px-24 xl:max-w-2xl
        "
      >
        <h2
          class="
            raleway-font
            text-center text-2xl text-teal-900
            font-display font-semibold
            lg:text-left
            xl:text-3xl xl:text-bold
          "
        >
          Login
        </h2>
        <div class="mt-12">
          <div>
            <div class="text-base font-bold text-gray-700 tracking-wide">
              Email Address
            </div>
            <input
              class="
                w-full
                text-sm
                py-2
                border-b border-gray-300
                focus:outline-none focus:border-teal-500
              "
              type="text"
              v-model="auth.email"
              placeholder="mike@gmail.com"
            />
          </div>
          <div class="mt-8">
            <div class="flex justify-between items-center">
              <div class="text-base font-bold text-gray-700 tracking-wide">
                Password
              </div>
              <div>
                <a
                  class="
                    text-xs
                    font-display font-semibold
                    text-red-600
                    hover:text-red-800
                    cursor-pointer
                  "
                  href="/admin/forgot-password"
                >
                  Forgot Password?
                </a>
              </div>
            </div>
            <input
              class="
                w-full
                text-sm
                py-2
                border-b border-gray-300
                focus:outline-none focus:border-teal-500
              "
              type="password"
              v-model="auth.password"
              placeholder="Enter your password"
            />
          </div>
          <div class="mt-10">
            <button
              type="submit"
              :disabled="processing"
              class="
                text-gray-100
                p-4
                w-full
                rounded-full
                tracking-wide
                bg-teal-400
                font-semibold font-display
                focus:outline-none focus:shadow-outline
                hover:bg-teal-600
                shadow-lg
              "
              @click="login"
            >
              {{ processing ? "Logging in..." : "Login" }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div
      class="
        hidden
        lg:flex
        items-center
        justify-center
        bg-teal-100
        flex-1
        h-screen
      "
      style="background: url('https://via.placeholder.com/1600x1067') no-repeat;background-size: cover;"
    ></div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  name: "login",
  data() {
    return {
      auth: {
        email: "",
        password: "",
      },
      processing: false,
    };
  },
  computed: {
    ...mapGetters({
      userDetails: "auth/user",
    }),
  },
  methods: {
    ...mapActions({
      signIn: "auth/login",
    }),
    async login() {
      this.processing = true;
      await axios.get("/sanctum/csrf-cookie");
      await axios
        .post("/api/admin/auth/login", this.auth)
        .then(({ data }) => {
          let token = data.data.token;
          this.signIn(token);
        })
        .catch(({ response: { data } }) => {
          console.error(data.message);
        })
        .finally(() => {
          this.processing = false;
        });
    },
  },
};
</script>