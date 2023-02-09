<template>
    <div v-if="form != null" class="w-full pb-8">
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs">{{ form.app_name.name }}</label>
            <input class="w-[40em] mt-2 p-4 border-2 rounded-md" v-model="form.app_name.value" type="text"
                name="app-name" placeholder="BeamCoda" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs">{{ form.address.name }}</label>
            <input class="w-[40em] mt-2 p-4 border-2 rounded-md" v-model="form.address.value" type="text"
                name="address" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs">{{ form.phone.name }}</label>
            <input class="w-[40em] mt-2 p-4 border-2 rounded-md" v-model="form.phone.value" type="tel" name="phone" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs">{{ form.email.name }}</label>
            <input class="w-[40em] mt-2 p-4 border-2 rounded-md" v-model="form.email.value" type="email" name="email" />
        </div>
        <div class="flex flex-row mt-12 mb-4">
            <div class="w-1/2 mr-2">
                <label class="block uppercase tracking-wide text-xs">{{ form.app_url_android.name }}</label>
                <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.app_url_android.value" type="text"
                    name="app_url_android" />
            </div>
            <div class="w-1/2 ml-2">
                <label class="block uppercase tracking-wide text-xs">{{ form.app_url_ios.name }}</label>
                <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.app_url_ios.value" type="text"
                    name="app_url_ios" />
            </div>
        </div>
        <!-- <div class="flex flex-row mb-4">
            <div class="w-1/2 mr-2">
                <label class="block uppercase tracking-wide text-xs">{{ form.app_minimum_android.name }}</label>
                <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.app_minimum_android.value" type="text"
                    name="app_min_android" />
            </div>
            <div class="w-1/2 ml-2">
                <label class="block uppercase tracking-wide text-xs">{{ form.app_minimum_ios.name }}</label>
                <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.app_minimum_ios.value" type="text"
                    name="app_min_ios" />
            </div>
        </div> -->
        <div class="flex flex-row mb-4">
            <div class="w-1/2 mr-4">
                <label class="block uppercase tracking-wide text-xs">{{ form.website_webhook_url.name }}</label>
                <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.website_webhook_url.value" type="text"
                    name="website_webhook_url" />
            </div>
        </div>
        <div class="flex justify-end mt-10">
            <a href="#!" class="
          w-24
          px-4
          py-2
          border border-gray-300
          bg-blue-400
          text-white
          font-bold
          rounded-md
          text-sm text-center
          uppercase
          hover:bg-blue-600
        " @click="submit">Save</a>
        </div>
    </div>
</template>

<script>
import { useToast } from 'vue-toastification';
import axios from 'axios';

export default {
    data() {
        return {
            toast: {},
            form: null,
        };
    },
    mounted() {
        this.toast = useToast();
        this.fetchSettingsGeneral();
    },
    methods: {
        fetchSettingsGeneral() {
            let token = localStorage.getItem("auth_token");
            let Api = token
                ? axios.create({
                    headers: { Authorization: `Bearer ${token}` },
                })
                : axios.create();

            Api.get('/api/settings/general')
                .then(response => {
                    this.form = {};
                    let result = response.data.data;
                    result.forEach(item => {
                        this.form[item.config] = item;
                    });
                });
        },
        submit() {
            let token = localStorage.getItem("auth_token");
            let Api = token
                ? axios.create({
                    headers: { Authorization: `Bearer ${token}` },
                })
                : axios.create();

            try {
                for (const [index, item] of Object.entries(this.form)) {
                    Api.post('/api/settings/save', {
                        config_name: item.config,
                        display_name: item.name,
                        value: item.value,
                        setting_tab: 'general'
                    });
                }
            } finally {
                this.toast.success("General Settings Saved!", {
                    position: "top-right",
                    timeout: 1500,
                    closeOnClick: true,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: true,
                    closeButton: "button",
                    icon: true,
                    rtl: false
                });
            }
        },
    }
}
</script>