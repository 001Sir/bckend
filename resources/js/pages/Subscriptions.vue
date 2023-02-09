<template>
  <div class="max-w-[128em] min-h-screen mx-auto px-4">
    <DashboardHeader title="Subscriptions" subTitle="Monetize your job portal." />
    <div class="w-full grid grid-cols-1 md:grid-cols-6 mt-10">
      <div class="flex flex-col md:flex-row col-span-4 justify-between md:pr-4">
        <!-- Packages -->
        <div class="
            w-full
            md:w-1/2 md:h-[52em]
            md:overflow-y-auto
            flex flex-col
            bg-gray-200
            rounded-md
            md:mr-4
            px-5
            py-6
            mb-8
            md:mb-0
          ">
          <div class="flex flex-row items-center mb-4">
            <h5 class="font-medium text-lg">Packages</h5>
            <a href="#!" class="
                w-32
                px-1
                py-2
                border border-gray-300
                bg-white
                text-black
                font-bold
                text-sm
                rounded-md
                hover:bg-gray-300
                ml-4
              " @click.prevent="openCreatePackage">+ New Package</a>
          </div>
          <PackagesList v-for="item in packages.data" :key="item.id" :id="item.id" :name="item.name"
            :is_active="item.active" :price="item.price" :subscriptionType="item.subscription_type"
            :features="item.features" />
        </div>
        <!-- Features -->
        <div class="
            w-full
            md:w-1/2 md:h-[52em]
            md:overflow-y-auto
            flex flex-col
            bg-gray-200
            rounded-md
            md:ml-4
            px-5
            py-6
          ">
          <div class="flex flex-row items-center mb-6">
            <h5 class="font-medium text-lg">Features</h5>
            <a href="#!" class="
                w-32
                px-1
                py-2
                border border-gray-300
                bg-white
                text-black
                font-bold
                text-sm
                rounded-md
                hover:bg-gray-300
                ml-4
              " @click.prevent="openCreateFeature">+ New Feature</a>
          </div>
          <FeatureList v-for="item in features.data" :key="item.id" :id="item.id" :name="item.name"
            :config_name="item.config" :displayOnly="item.display" />
        </div>
      </div>
      <div class="flex flex-col col-span-1 md:col-span-2 mt-8 md:mt-0 md:pl-6">
        <div class="flex flex-row justify-between mb-8">
          <div v-if="showStats" class="flex flex-col">
            <p class="font-medium text-sm">Sales Revenue</p>
            <LineChart chartId="revenueLineChart" :labels="statsLabel" :data="statsPackagesData" />
          </div>
        </div>
        <h4 class="font-bold text-base py-4">Invoices</h4>
        <div class="w-full overflow-x-scroll md:overflow-auto">
          <Datatable :headers="tableHeaders" :data="invoices.data" :hideActions="true" />
        </div>
        <div class="w-full" v-if="invoices.meta">
          <Pagination v-if="invoices.meta.last_page > 1" :pagination="invoices.meta" :offset="3"
            @paginate="fetchInvoiceData" />
        </div>
      </div>
    </div>

    <Modal v-show="showModal" @closeModal="closeModal">
      <template v-slot:header>
        <h4 v-if="modalType == 'new-feature'">Create Job</h4>
        <h4 v-if="modalType == 'new-package'">Create Package</h4>
      </template>

      <template v-slot:body>
        <div v-if="modalType == 'new-feature'">
          <CreateFeatureItem />
        </div>
        <div v-if="modalType == 'new-package'">
          <CreatePackageItem />
        </div>
      </template>
    </Modal>
  </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "../components/shared/modal.vue";
import DashboardHeader from "../components/main/DashboardHeader.vue";
import PackagesList from "../components/subscriptions/packages-list.vue";
import FeatureList from "../components/subscriptions/feature-list.vue";
import LineChart from "../components/shared/line-chart";
import Datatable from "../components/shared/datatable.vue";
import Pagination from "../components/shared/pagination.vue";
import CreateFeatureItem from "../components/subscriptions/feature/CreateFeatureItem.vue";
import CreatePackageItem from "../components/subscriptions/package/CreatePackageItem.vue";

export default {
  components: {
    DashboardHeader,
    PackagesList,
    FeatureList,
    LineChart,
    Datatable,
    Pagination,
    Modal,
    CreateFeatureItem,
    CreatePackageItem
  },
  data() {
    return {
      invoices: {},
      tableHeaders: [
        { name: "Parner", value: "partner" },
        { name: "Package", value: "package" },
        {
          name: "Paid",
          value: "paid",
          isBoolean: true,
        }
      ],
      showStats: false,
      statsLabel: [],
      statsPackagesData: [],
      showModal: false,
      modalType: null,
    };
  },
  mounted() {
    this.fetchInvoiceData();
    this.retrieveStats();
  },
  methods: {
    fetchInvoiceData(page = 1) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      let query = '/api/admin/invoices?page=' + page;

      Api.get(query)
        .then(response => {
          this.invoices = response.data;
        });
    },
    retrieveStats() {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();


      Api.get('/api/invoices/sales-revenue')
        .then(response => {
          const firstElem = Object.keys(response.data.data)[0];
          this.statsLabel = Object.keys(response.data.data[firstElem]).reverse();
          const keys = Object.keys(response.data.data);
          const values = Object.values(response.data.data);
          const colors = ["rgba(255, 0, 0, 0.5)", "rgba(255, 178, 0, 0.5)", "rgba(178, 85, 25, 0.5)"];
          keys.forEach((item, index) => {
            return this.statsPackagesData.push(
              {
                label: item,
                data: values[index],
                fill: true,
                backgroundColor: colors[index],
                tension: 0.25,
              }
            );
          });
          this.showStats = true;
        });
    },
    openCreateFeature() {
      this.showModal = true;
      this.modalType = "new-feature";
    },
    openCreatePackage() {
      this.showModal = true;
      this.modalType = "new-package";
    },
    closeModal() {
      this.showModal = false;
    }
  },
  setup() {
    const store = useStore();

    return {
      getPackages: store.dispatch('subscription/index_packages'),
      getFeatures: store.dispatch('subscription/index_features'),
      packages: computed(() => store.getters["subscription/packages"]),
      features: computed(() => store.getters["subscription/features"]),
    };
  },
}
</script>