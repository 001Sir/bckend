<template>
    <div class="w-[25em]">
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Name</label>
            <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.name" type="text" name="name"
                placeholder="Job Type Name, e.x -  Fulltime, Part-time, etc" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Config Name</label>
            <p class="mt-4 text-md raleway-font font-bold">{{ form.config_name }}</p>
        </div>
        <Transition>
            <p v-show="showSuccessMsg" class="raleway-font pt-4 text-teal-600 font-bold">
                Feature updated successfully.
            </p>
        </Transition>
        <div class="flex justify-between mt-10">
            <a v-if="displayOnly == true" href="#!" class="
            w-38
            px-4
            py-2
            border border-red-300
            bg-red-200
            text-black
            font-bold   
            text-sm text-center
            uppercase
            hover:bg-white
          " @click.prevent="openDeleteDialog">
                <font-awesome-icon :icon="['fas', 'trash']" /> Delete Feature
            </a>
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
          " @click="submit">Update</a>
        </div>

        <ConfirmDialog v-if="showDeleteDialog" @closeModal="closeDialog">
            <template v-slot:header>
                <h4>Are you sure you want to delete {{ form.name }}?</h4>
            </template>

            <template v-slot:body>
                <p class="text-sm italic text-gray-500">
                    Click <strong>'Ok'</strong> to confirm, or cancel.
                </p>

                <div class="flex flex-row mt-4">
                    <a href="#!" class="
              w-32
              px-4
              py-2
              border border-teal-400
              text-black
              font-bold
              text-sm text-center
              uppercase
              mr-6
              hover:bg-teal-400
            " @click="deleteFeature">Ok, Confirm</a>
                    <a href="#!" class="
              w-24
              px-4
              py-2
              border border-gray-300
              text-black
              font-bold
              text-sm text-center
              uppercase
              hover:bg-gray-200
            " @click="this.showDeleteDialog = false">Cancel</a>
                </div>
            </template>
        </ConfirmDialog>
    </div>
</template>
  
<script>
import ConfirmDialog from '../../shared/confirm-dialog.vue';
export default {
    props: {
        id: Number,
        name: String,
        config_name: String,
        displayOnly: Boolean,
        deletePost: Function
    },
    components: {
        ConfirmDialog,
    },
    data() {
        return {
            showDeleteDialog: false,
            store: null,
            form: {
                name: "",
                config_name: "",
            },
            showSuccessMsg: false,
        };
    },
    mounted() {
        this.form.name = this.name;
        this.form.config_name = this.config_name;
    },
    methods: {
        submit() {
            this.$store
            .dispatch("subscription/edit_feature", {
                id: this.id,
                name: this.form.name,
            })
            .then(() => {
                this.showSuccessMsg = true;
            });
        },
        openDeleteDialog() {
            this.showDeleteDialog = true;
        },
        closeDialog() {
            this.showDeleteDialog = false;
        },
        deleteFeature() {
            this.$store
                .dispatch("subscription/delete_feature", this.id)
                .then(() => {
                    this.deletePost();
                });
        },
    },
};
</script>
  