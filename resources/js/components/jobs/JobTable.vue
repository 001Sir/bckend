<template>
  <div class="w-full">
    <div class="
        w-full
        flex flex-col
        md:flex-row
        justify-between
        items-center
        mt-12
        mb-4
      ">
      <Search @search-form="searchForm" />
      <FilterButton @change-filter="changeFilter" :current="filter" :values="this.filterOptions" />
    </div>
    <div class="w-full" style="max-height: 500px;">
      <div class="w-full flex flex-row justify-center md:justify-end mb-4">
        <button @click.prevent="fetchData()" class="button py-2 mr-0">
          <font-awesome-icon :icon="['fas', 'refresh']" />
        </button>
        <a href="#!" class="
            w-40
            h-11
            px-2
            py-3
            border border-gray-300
            bg-teal-400
            text-white
            font-bold
            text-sm text-center
            rounded-lg
            hover:bg-green-400
            ml-4
          " @click="openCreateForm()">+ Add New Job</a>
      </div>
      <div class="w-full overflow-x-auto">
        <Datatable :headers="tableHeaders" :data="jobs.data" :allowedActions="allowedActions" :editMethod="openEditForm"
          :deleteMethod="openDeleteDialog" />
      </div>
      <div class="w-full" v-if="jobs.meta">
        <Pagination v-if="jobs.meta.last_page > 1" :pagination="jobs.meta" :offset="10" @paginate="fetchData" />
      </div>
    </div>

    <Modal v-show="showModal" @closeModal="closeModal">
      <template v-slot:header>
        <h4 v-if="modalType == 'new-job'">Create Job</h4>
        <h4 v-if="modalType == 'edit-job'">Edit Job</h4>
      </template>

      <template v-slot:body>
        <div v-if="modalType == 'new-job'">
          <CreateJobForm />
        </div>
        <div v-if="modalType == 'edit-job'">
          <UpdateJobForm :job="job" />
        </div>
      </template>
    </Modal>

    <ConfirmDialog v-if="showDeleteDialog" @closeModal="closeDialog">
      <template v-slot:header>
        <h4>Are you sure you want to delete {{ job.name }}?</h4>
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
            " @click="deleteJob(job.id)">Ok, Confirm</a>
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
import axios from "axios";
import Pagination from '../shared/pagination.vue';
import Search from "../shared/search.vue";
import FilterButton from "../shared/filter-button.vue";
import Datatable from "../shared/datatable.vue";
import Modal from "../shared/modal.vue";
import ConfirmDialog from "../shared/confirm-dialog.vue";
import CreateJobForm from "./forms/jobs/CreateJobForm.vue";
import UpdateJobForm from "./forms/jobs/UpdateJobForm.vue";

export default {
  components: {
    Pagination,
    Search,
    FilterButton,
    Datatable,
    Modal,
    ConfirmDialog,
    CreateJobForm,
    UpdateJobForm
  },
  data() {
    return {
      showModal: false,
      showDeleteDialog: false,
      modalType: null, // Used to select form that loads
      jobs: {},
      filter: 'job_title',
      filterOptions: [
        { name: "Title: A - Z", value: "job_title" },
        { name: "Title: Z - A", value: "-job_title" }
      ],
      search: null,
      tableHeaders: [
        { name: "Title", value: "title", isImage: false },
        { name: "Country", value: "country", isImage: false },
        {
          name: "Is Remote",
          value: "remote_position",
          isImage: false,
          isBoolean: true,
        },
        { name: "Expires on", value: "expiration", isImage: false },
        { name: "Status", value: "status", isImage: false },
      ],
      emptyStateJob: {
        id: null,
        partner_id: null,
        job_title: null,
        is_remote: false,
        category_id: null,
        city: null,
        country: null,
        no_pay_range: false,
        min_salary_range: null,
        max_salary_range: null,
        job_type_id: null,
        desc: null,
        job_active_duration: null,
        is_published: false,
        expiration_date: null,
        status: null,
      },
      job: null,
      allowedActions: {
        edit: true,
        delete: true,
      },
    };
  },
  mounted() {
    this.fetchData();
    this.job = this.emptyStateJob;
  },
  methods: {
    fetchData(page = 1) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      let search_parameter = (this.search != null) ? '&s=' + this.search : '';
      let filter_option = (this.filter != null) ? '&sort=' + this.filter : '';
      let query = '/api/jobs/all?page=' + page + '' + search_parameter + filter_option;

      Api.get(query)
        .then(response => {
          this.jobs = response.data;
        });
    },
    searchForm(value) {
      this.search = value;
      this.fetchData();
    },
    changeFilter(value) {
      this.filter = value;
      this.fetchData();
    },
    openCreateForm() {
      this.showModal = true;
      this.modalType = "new-job";
    },
    retrieveJobFromIndex(id, index) {
      return {
        id: id,
        partner_id: this.jobs.data[index].partner.id,
        job_title: this.jobs.data[index].title,
        is_remote: this.jobs.data[index].remote_position,
        category_id: this.jobs.data[index].category.id,
        city: this.jobs.data[index].city,
        country: this.jobs.data[index].country,
        no_pay_range: this.jobs.data[index].no_pay_range,
        min_salary_range: this.jobs.data[index].min_salary_range,
        max_salary_range: this.jobs.data[index].max_salary_range,
        job_type_id: this.jobs.data[index].job_type.id,
        desc: this.jobs.data[index].desc,
        is_published: this.jobs.data[index].published,
        expiration_date: this.jobs.data[index].expiration,
        status: this.jobs.data[index].status
      };
    },
    openEditForm(id, index) {
      this.job = this.retrieveJobFromIndex(id, index);
      this.showModal = true;
      this.modalType = "edit-job";
    },
    deleteJob(id) {
      this.$store.dispatch("jobs/delete_job", id);
      this.showDeleteDialog = false;
    },
    openDeleteDialog(id, index) {
      this.job = this.retrieveJobFromIndex(id, index);
      this.showDeleteDialog = true;
    },
    closeDialog() {
      this.showDeleteDialog = false;
    },
    closeModal() {
      this.showModal = false;
      this.modalType = null;
    },
  }
};
</script>