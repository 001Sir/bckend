<template>
  <div class="flex flex-row justify-between my-2 px-3 py-4 bg-white">
    <div class="flex flex-row justify-center items-center">
      <span class="w-2 h-2 rounded-full mr-2" :class="(is_active) ? 'bg-teal-400' : 'bg-red-600'"></span>
      <p class="text-black text-base cursor-pointer hover:text-gray-400" @click.prevent="openModal">{{ name }}</p>
    </div>
    <p class="font-bold text-lg">{{ price }} <span class="text-xs italic">({{ (subscriptionType).toUpperCase()
    }})</span></p>

    <Modal v-show="showModal" @closeModal="closeModal">
      <template v-slot:header>
        <h4>Edit Package</h4>
      </template>

      <template v-slot:body>
        <div>
          <UpdatePackageItem :id="id" :name="name" :price="price" :subscriptionType="subscriptionType" :isActive="is_active" :packageFeatures="new_features"
            :deletePackage="closeAfterPackageDelete" />
        </div>
      </template>
    </Modal>
  </div>
</template>

<script>
import Modal from '../shared/modal.vue';
import UpdatePackageItem from '../subscriptions/package/UpdatePackageItem.vue';

export default {
  props: ['id', 'is_active', 'name', 'price', 'subscriptionType', 'features'],
  components: {
    Modal,
    UpdatePackageItem
  },
  data() {
    return {
      new_features: [],
      showModal: false,
    }
  },
  mounted() {
    this.new_features = this.features.map((item) => {
      return {'id': item.id, 'name': item.name};
    });
  },
  methods: {
    openModal() {
      this.showModal = true;
    },
    closeAfterPackageDelete() {
        this.$store.dispatch('subscription/index_packages');
        this.showModal = false;
    },
    closeModal() {
      this.showModal = false;
    }
  }
}
</script>