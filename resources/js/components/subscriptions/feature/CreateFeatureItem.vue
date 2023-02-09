<template>
    <div class="w-[25em]">
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Name</label>
            <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.name" type="text" name="name"
                placeholder="Job Type Name, e.x -  Fulltime, Part-time, etc" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Config Name (Unique)</label>
            <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.config_name" type="text" name="config_name"
                placeholder="Job Type Name, e.x -  Fulltime, Part-time, etc" />
        </div>
        <Transition>
            <p v-show="showSuccessMsg" class="raleway-font pt-4 text-teal-600 font-bold">
                Feature created successfully.
            </p>
        </Transition>
        <div class="flex justify-end mt-10">
            <a href="#!" class="
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
          " @click="submit">Create</a>
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
                config_name: "",
            },
            showSuccessMsg: false,
        };
    },
    methods: {
        submit() {
            this.$store
                .dispatch("subscription/new_feature", {
                    name: this.form.name,
                    config_name: this.form.config_name
                })
                .then(() => {
                    this.form = {
                        name: "",
                        config_name: "",

                    };
                    this.showSuccessMsg = true;
                });
        },
    },
};
</script>
  