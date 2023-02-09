<template>
    <div class="w-[25em]">
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Name</label>
            <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.name" type="text" name="name"
                placeholder="Job Type Name, e.x -  Fulltime, Part-time, etc" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Price</label>
            <input type="number" class="w-full mt-2 p-4 border-2 rounded-md" name="price" v-model="form.price" />
        </div>
        <div v-if="showFeatures" class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Features</label>
            <VueMultiselect v-model="packageFeatures" :options="features_options" label="name" track-by="id" :multiple="true"
                :close-on-select="true"></VueMultiselect>
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Subscription Type</label>
            <select v-model="form.subscription_type" class="mt-2 p-2">
                <option value="monthly">Monthly</option>
                <option value="annual">Annual</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Is Active?</label>
            <input class="mt-2 p-4 border-2 rounded-md" v-model="form.is_active" :checked="form.is_active"
                type="checkbox" name="published" />
        </div>
        <Transition>
            <p v-show="showSuccessMsg" class="raleway-font pt-4 text-teal-600 font-bold">
                Feature updated successfully.
            </p>
        </Transition>
        <div class="flex justify-between mt-10">
            <a v-if="id != 1" href="#!" class="
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
                <font-awesome-icon :icon="['fas', 'trash']" /> Delete Package
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
import { computed } from 'vue';
import { useStore } from 'vuex';
import VueMultiselect from 'vue-multiselect';
import ConfirmDialog from '../../shared/confirm-dialog.vue';

export default {
    props: {
        id: Number,
        name: String,
        price: String,
        subscriptionType: String,
        isActive: Boolean,
        packageFeatures: Array,
        deletePackage: Function
    },
    components: {
        VueMultiselect,
        ConfirmDialog,
    },
    data() {
        return {
            showDeleteDialog: false,
            store: null,
            form: {
                name: "",
                price: "",
                subscription_type: "monthly",
                is_active: false
            },
            showFeatures: false,
            features_options: null,
            showSuccessMsg: false,
        };
    },
    mounted() {
        this.form.name = this.name;
        this.form.price = this.price;
        this.form.is_active = this.isActive;
        this.form.subscription_type = this.subscriptionType;
        
        if(this.features) {
            this.features_options = this.features.data.map((item) => {
                return {'id': item.id, 'name': item.name};
            });

            
            this.showFeatures = true;
        }
    },
    methods: {
        submit() {
            const FEATURES = this.packageFeatures.map((item) => {
                return item.id;
            });
            this.$store
                .dispatch("subscription/edit_package", {
                    id: this.id,
                    name: this.form.name,
                    price: this.form.price,
                    subscription_type: this.form.subscription_type,
                    is_active: this.form.is_active,
                    features: FEATURES
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
                .dispatch("subscription/delete_package", this.id)
                .then(() => {
                    this.deletePackage();
                });
        },
    },
    setup() {
        const store = useStore();

        return {
            features: computed(() => store.getters["subscription/features"]),
        };
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
  