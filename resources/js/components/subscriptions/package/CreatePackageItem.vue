<template>
    <div class="w-[25em]">
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Name</label>
            <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.name" type="text" name="name"
                placeholder="Job Type Name, e.x -  Fulltime, Part-time, etc" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Price</label>
            <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.price" type="number" name="config_name"
                placeholder="Job Type Name, e.x -  Fulltime, Part-time, etc" />
        </div>
        <div v-if="showFeatures" class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Features</label>
            <VueMultiselect v-model="features_selected" :options="features_options" label="name" track-by="id" :multiple="true"
                :close-on-select="true"></VueMultiselect>
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Subscription Type</label>
            <select v-model="form.subscription_type" class="px-2 py-4 mt-2 rounded-base">
                <option value="monthly">Monthly</option>
                <option value="annual">Annual</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Is Active?</label>
            <input class="mt-2 p-4 border-2 rounded-md" v-model="form.is_active" :checked="form.is_active"
                type="checkbox" name="active" />
        </div>
        <Transition>
            <p v-show="showSuccessMsg" class="raleway-font pt-4 text-teal-600 font-bold">
                Package created successfully.
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
import { computed } from 'vue';
import { useStore } from 'vuex';
import VueMultiselect from 'vue-multiselect';

export default {
    components: {
        VueMultiselect,
    },
    data() {
        return {
            store: null,
            form: {
                name: "",
                price: "",
                subscription_type: "monthly",
                is_active: false
            },
            showFeatures: false,
            features_selected: [],
            features_options: null,
            showSuccessMsg: false,
        };
    },
    mounted() {
        if(this.features) {
            this.features_options = this.features.data.map((item) => {
                return {'id': item.id, 'name': item.name};
            });

            this.showFeatures = true;
        }
    },
    methods: {
        submit() {
            const FEATURES = this.features_selected.map((item) => {
                return item.id;
            });
            this.$store
                .dispatch("subscription/new_package", {
                    name: this.form.name,
                    price: this.form.price,
                    subscription_type: this.form.subscription_type,
                    is_active: this.form.is_active,
                    features: FEATURES
                })
                .then(() => {
                    this.form = {
                        name: "",
                        price: "",
                        subscription_type: "monthly",
                        is_active: false
                    };
                    this.features_selected = [];
                    this.showSuccessMsg = true;
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
  