<template>
  <div class="max-w-[128em] min-h-screen mx-auto px-4">
    <DashboardHeader title="Partners" subTitle="Insights into partners and performance." />
    <div class="grid grid-cols-1 lg:grid-cols-6 mt-12">
      <div class="flex flex-col col-span-4 pr-4">
        <div class="w-full flex flex-col md:flex-row justify-between items-center">
          <Search @search-form="searchForm" />
          <FilterButton @change-filter="changeFilter" :current="filter" :values="this.filterOptions" />
        </div>
        <div v-if="partners.data" class="w-full overflow-x-scroll md:overflow-auto mt-2">
          <PartnersList v-for="partner in partners.data" :key="partner.id" :img="partner.profile_picture"
            :name="partner.name" :bio="partner.bio" :registered_date="partner.member_since" />
        </div>
        <div class="w-full" v-if="partners.meta">
          <Pagination v-if="partners.meta.last_page > 1" :pagination="partners.meta" :offset="10"
            @paginate="fetchPartners" />
        </div>
      </div>
      <div class="flex flex-col col-span-2 pl-4">
        <div v-if="statsLoaded" class="w-full mb-10">
          <h4 class="font-medium mt-4 mb-4">Jobs Applied / Selected</h4>
          <BarChart chartId="jobConversionBarChart" :labels="statsLabels" :data="stats" />
        </div>
        <div class="w-full">
          <div class="flex flex-row justify-between">
            <h4 class="font-medium mb-4">Top Performers</h4>
            <div v-if="performersLoaded" class="flex flex-row items-center -mt-2">
              <button class="font-bold text-lg mr-2" @click.prevent="prevPerformersList()">&lt;</button>
              <button href="#" class="font-bold text-lg" @click.prevent="nextPerformersList()">&gt;</button>
            </div>
          </div>
          <div class="
              w-full
              grid
              grid-cols-2
              lg:grid-col-3
              gap-2
              md:gap-5
              justify-center
              md:justify-start
              items-center
            ">
            <div v-if="performersLoaded && topPerformers.data.data[0]">
              <PartnersCard :img="topPerformers.data.data[0].img_url" :name="topPerformers.data.data[0].company_name"
                :jobPostCount="topPerformers.data.data[0].jobs_posted" />
            </div>
            <div v-if="performersLoaded && topPerformers.data.data[1]">
              <PartnersCard :img="topPerformers.data.data[1].img_url" :name="topPerformers.data.data[1].company_name"
                :jobPostCount="topPerformers.data.data[1].jobs_posted" />
            </div>
            <div v-if="performersLoaded && topPerformers.data.data[2]">
              <PartnersCard :img="topPerformers.data.data[2].img_url" :name="topPerformers.data.data[2].company_name"
                :jobPostCount="topPerformers.data.data[2].jobs_posted" />
            </div>
            <div v-if="performersLoaded && topPerformers.data.data[3]">
              <PartnersCard :img="topPerformers.data.data[3].img_url" :name="topPerformers.data.data[3].company_name"
                :jobPostCount="topPerformers.data.data[3].jobs_posted" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import DashboardHeader from "../components/main/DashboardHeader.vue";
import Pagination from "../components/shared/pagination.vue";
import Search from "../components/shared/search.vue";
import FilterButton from "../components/shared/filter-button.vue";
import PartnersList from "../components/partners/partners-list.vue";
import BarChart from "../components/shared/bar-chart";
import PartnersCard from "../components/home/partners/partners-card.vue";

export default {
  components: {
    DashboardHeader,
    Pagination,
    Search,
    FilterButton,
    PartnersList,
    BarChart,
    PartnersCard
  },
  data() {
    return {
      search: null,
      filter: 'company_name',
      filterOptions: [
        { name: "Name: A - Z", value: "company_name" },
        { name: "Name: Z - A", value: "-company_name" }
      ],
      stats: {},
      statsLoaded: false,
      statsLabels: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
      partners: {},
      performersPageNo: 1,
      topPerformers: {},
      performersLoaded: false,
    };
  },
  mounted() {
    this.fetchPartners();
    this.fetchTopPerformers();
    this.fetchJobStats();
  },
  methods: {
    fetchPartners(page = 1) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      let search_parameter = (this.search != null) ? '&s=' + this.search : '';
      let filter_option = (this.filter != null) ? '&sort=' + this.filter : '';
      let query = '/api/admin/partners?page=' + page + '' + search_parameter + filter_option;

      Api.get(query)
        .then(response => {
          this.partners = response.data;
        });
    },
    searchForm(value) {
      this.search = value;
      this.fetchPartners();
    },
    changeFilter(value) {
      this.filter = value;
      this.fetchPartners();
    },
    fetchTopPerformers() {
      this.performersLoaded = false;
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      let query = '/api/partner/top-performers?page=' + this.performersPageNo;

      Api.get(query)
        .then(response => {
          this.topPerformers = response.data;
          this.performersLoaded = true;
        });
    },
    fetchJobStats() {
      this.statsLoaded = false;
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      Api.get('/api/jobs/stats')
        .then(response => {
          this.stats = [
            {
              label: "Selected",
              backgroundColor: "#00B8A6",
              data: Object.values(response.data.data.selected_applicants),
            },
            {
              label: "Applied",
              backgroundColor: "#cccccc",
              data: Object.values(response.data.data.job_applications),
            },
          ];
          this.statsLoaded = true;
        });
    },
    prevPerformersList() {
      if (this.topPerformers.data.prev_page_url != null) {
        this.performersPageNo--;
        this.fetchTopPerformers();
      }
    },
    nextPerformersList() {
      if (this.topPerformers.data.next_page_url != null) {
        this.performersPageNo++;
        this.fetchTopPerformers();
      }
    }
  },
}
</script>
