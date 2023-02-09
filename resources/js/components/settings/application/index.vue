<template>
    <div v-if="form != null" class="w-full pb-4">
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs">{{ form.recaptcha_active.name }}</label>
            <input class="mt-2 p-4 border-2 rounded-md" v-model="form.recaptcha_active.value" type="checkbox" name="recaptcha_active" :checked="!!form.recaptcha_active.value" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs">{{ form.recaptcha_site_key.name }}</label>
            <input class="w-[40em] mt-2 p-4 border-2 rounded-md" v-model="form.recaptcha_site_key.value" type="text"
                name="app-name" placeholder="BeamCoda" />
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-xs">{{ form.recaptcha_secret_key.name }}</label>
            <input class="w-[40em] mt-2 p-4 border-2 rounded-md" v-model="form.recaptcha_secret_key.value" type="text"
                name="address" />
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

            Api.get('/api/settings/application')
                .then(response => {
                    this.form = {};
                    let result = response.data.data;
                    result.forEach(item => {
                        if(item.config == 'recaptcha_active') {
                            item.value = !!Number(item.value);
                        }
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
                        setting_tab: 'application'
                    });
                }
            } finally {
                this.toast.success("Application Settings Saved!", {
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