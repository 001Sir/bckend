<template>
  <div class="w-[25em]">
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold"
        >Name</label
      >
      <input
        class="w-full mt-2 p-4 border-2 rounded-md"
        v-model="form.name"
        type="text"
        name="name"
        placeholder="Job Type Name, e.x -  Fulltime, Part-time, etc"
      />
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold"
        >Duration (In Minutes)</label
      >
      <input
        class="w-full mt-2 p-4 border-2 rounded-md"
        v-model="form.duration"
        type="number"
        name="duration"
        placeholder="Time value in minutes, E.g. A week has (10080) minutes."
      />
    </div>
    <div class="mb-4 text-left">
      <label class="block uppercase tracking-wide text-xs font-bold"
        >Is Premium? (Only available for subscribed users)</label
      >
      <input
        class="mt-2 p-4 border-2 rounded-md"
        v-model="form.premium"
        type="checkbox"
        name="premium"
      />
    </div>
    <Transition>
      <p
        v-show="showSuccessMsg"
        class="raleway-font pt-4 text-teal-600 font-bold"
      >
        Post Duration created successfully.
      </p>
    </Transition>
    <div class="flex justify-end mt-10">
      <a
        href="#!"
        class="
          w-24
          px-4
          py-2
          border border-gray-300
          bg-gray-200
          text-black
          font-bold
          text-sm text-center
          uppercase
          hover:bg-white
        "
        @click="submit"
        >Create</a
      >
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      store: null,
      form: {
        name: "",
        duration: "",
        premium: ""
      },
      showSuccessMsg: false,
    };
  },
  methods: {
    submit() {
      this.$store
        .dispatch("postdurations/new_postduration", {
          name: this.form.name,
          duration: this.form.duration,
          premium: this.form.premium
        })
        .then(() => {
          this.form = {
            name: "",
            duration: "",
            premium: ""
          };
          this.showSuccessMsg = true;
        });
    },
  },
};
</script>
