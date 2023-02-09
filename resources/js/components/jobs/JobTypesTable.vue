<template>
  <div class="w-full">
    <div
      class="
        w-full
        flex flex-col
        md:flex-row
        justify-between
        items-center
        mt-12
        mb-6
      "
    >
      <h4 class="text-base font-bold">Job Types</h4>
      <a
        href="#!"
        class="
          w-40
          px-2
          py-2
          border border-gray-300
          bg-teal-400
          text-white
          font-bold
          text-sm
          text-center
          rounded-lg
          hover:bg-green-400
          ml-4
        "
        @click="openCreateForm()"
        >+ Add New Job Type</a
      >
    </div>
    <div class="w-full h-[10em] overflow-scroll md:overflow-auto mt-2">
      <Datatable
        :headers="tableHeaders"
        :data="jobtypes.data"
        :allowedActions="allowedActions"
        :editMethod="openEditForm"
        :deleteMethod="openDeleteDialog"
      />
    </div>

    <Modal v-show="showModal" @closeModal="closeModal">
      <template v-slot:header>
        <h4 v-if="modalType == 'new-jobtype'">Create Job Type</h4>
        <h4 v-if="modalType == 'edit-jobtype'">Edit Job Type</h4>
      </template>

      <template v-slot:body>
        <div v-if="modalType == 'new-jobtype'">
          <CreateJobTypeForm />
        </div>
        <div v-if="modalType == 'edit-jobtype'">
          <UpdateJobTypeForm :id="jobtype.id" :name="jobtype.name" />
        </div>
      </template>
    </Modal>

    <ConfirmDialog v-if="showDeleteDialog" @closeModal="closeDialog">
      <template v-slot:header>
        <h4>Are you sure you want to delete {{ jobtype.name }}?</h4>
      </template>

      <template v-slot:body>
        <p class="text-sm italic text-gray-500">
          Click <strong>'Ok'</strong> to confirm, or cancel.
        </p>

        <div class="flex flex-row mt-4">
          <a
            href="#!"
            class="
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
            "
            @click="deleteJobtype(jobtype.id)"
            >Ok, Confirm</a
          >
          <a
            href="#!"
            class="
              w-24
              px-4
              py-2
              border border-gray-300
              text-black
              font-bold
              text-sm text-center
              uppercase
              hover:bg-gray-200
            "
            @click="this.showDeleteDialog = false"
            >Cancel</a
          >
        </div>
      </template>
    </ConfirmDialog>
  </div>
</template>
    
  <script>
import { computed } from "vue";
import { useStore } from "vuex";
import Datatable from "../shared/datatable.vue";
import Modal from "../shared/modal.vue";
import ConfirmDialog from "../shared/confirm-dialog.vue";
import CreateJobTypeForm from "./forms/job-types/CreateJobTypeForm.vue";
import UpdateJobTypeForm from "./forms/job-types/UpdateJobTypeForm.vue";

export default {
  name: "SkillsTable",
  components: {
    Datatable,
    Modal,
    ConfirmDialog,
    CreateJobTypeForm,
    UpdateJobTypeForm
  },
  data() {
    return {
      showModal: false,
      showDeleteDialog: false,
      modalType: null, // Used to select form that loads
      tableHeaders: [
        { name: "Name", value: "name", isImage: false },
      ],
      jobtype: {
        id: null,
        name: null,
      },
      allowedActions: {
        details: false,
        edit: true,
        delete: true,
      },
    };
  },
  methods: {
    openEditForm(id, index) {
      this.jobtype = {
        id: id,
        name: this.jobtypes.data[index].name
      };
      this.showModal = true;
      this.modalType = 'edit-jobtype';
    },
      openDeleteDialog(id, index) {
        this.jobtype = {
          id: id,
          name: this.jobtypes.data[index].name
        };
        this.showDeleteDialog = true;
      },
      deleteJobtype(id) {
        this.$store.dispatch("jobtypes/delete_jobtype", id);
        this.showDeleteDialog = false;
      },
      openCreateForm() {
        this.showModal = true;
        this.modalType = 'new-jobtype';
      },
      closeDialog() {
        this.showDeleteDialog = false;
        this.jobtype = {
          id: null,
          name: null
        };
      },
      closeModal() {
        this.showModal = false;
        this.modalType = null;
      },
  },
  setup() {
    const store = useStore();

    return {
      jobtypes: computed(() => store.getters["jobtypes/jobtypes"]),
    };
  },
};
</script>