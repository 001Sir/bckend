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
        placeholder="Skill Name, e.x -  Engineering"
      />
    </div>
    <div class="mb-4 text-left">
      <label class="block uppercase tracking-wide text-xs font-bold"
        >Is Hinted?</label
      >
      <input
        class="mt-2 p-4 border-2 rounded-md"
        v-model="form.hinted"
        type="checkbox"
        name="hinted"
      />
    </div>
    <Transition>
      <p
        v-show="showSuccessMsg"
        class="raleway-font pt-4 text-teal-600 font-bold"
      >
        Skill created successfully.
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
        hinted: false
      },
      showSuccessMsg: false,
    };
  },
  methods: {
    submit() {
      this.$store
        .dispatch("skills/new_skill", {
          name: this.form.name,
          hinted: this.form.hinted
        })
        .then(() => {
          this.form = {
            name: ""
          };
          this.showSuccessMsg = true;
        });
    },
  },
};
</script>
