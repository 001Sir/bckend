<template>
    <div class="
              w-full
              h-14
              flex flex-row
              justify-between
              items-center
              text-black
              bg-white
              px-4
              mb-4
            ">
        <h5 class="font-thin text-base cursor-pointer hover:text-gray-400" @click.prevent="openModal">{{ name }}</h5>
        <p class="font-bold text-base">
            <font-awesome-icon v-if="displayOnly" title="Display Only" :icon="['fas', 'eye']" />
            <font-awesome-icon title="Coded on The System" v-else :icon="['fas', 'code']" />
        </p>

        <Modal v-show="showModal" @closeModal="closeModal">
            <template v-slot:header>
                <h4>Edit Feature</h4>
            </template>

            <template v-slot:body>
                <div>
                    <UpdateFeatureItem :id="id" :name="name" :config_name="config_name" :displayOnly="displayOnly" :deletePost="closeAfterFeatureDelete" />
                </div>
            </template>
        </Modal>
    </div>
</template>

<script>
import Modal from '../shared/modal.vue';
import UpdateFeatureItem from './feature/UpdateFeatureItem.vue';

export default {
    props: {
        id: Number,
        name: String,
        config_name: String,
        displayOnly: String
    },
    components: {
        Modal,
        UpdateFeatureItem
    },
    data() {
        return {
            showModal: false,
        };
    },
    methods: {
        openModal() {
            this.showModal = true;
        },
        closeAfterFeatureDelete() {
            this.$store.dispatch('subscription/index_features');
            this.showModal = false;
        },
        closeModal() {
            this.showModal = false;
        }
    }
}
</script>
