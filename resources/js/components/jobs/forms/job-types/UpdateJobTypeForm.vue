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
        placeholder="Job Type Name, e.x - Part-time, Fulltime, etc."
      />
    </div>
    <Transition>
      <p
        v-show="showSuccessMsg"
        class="raleway-font pt-4 text-teal-600 font-bold"
      >
        Job type updated successfully.
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
        >Update</a
      >
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: Number,
      required: true,
    },
    name: String,
  },
  data() {
    return {
      store: null,
      form: {
        name: "",
      },
      showSuccessMsg: false,
    };
  },
  mounted() {
    this.form.name = this.name;
  },
  methods: {
    submit() {
      this.$store
        .dispatch("jobtypes/edit_jobtype", {
          id: this.id,
          name: this.form.name
        })
        .then((res) => {
          this.showSuccessMsg = true;
        });
    },
  },
};
</script>
