<template>
  <div class="w-full">
    <div class="
          w-full
          flex flex-col
          md:flex-row
          justify-between
          items-center
          mt-12
          mb-6
        ">
      <h4 class="text-base font-bold">Post Durations</h4>
      <a href="#!" class="
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
          " @click="openCreateForm()">+ Add New Post Duration</a>
    </div>
    <div class="w-full h-[15em] overflow-scroll md:overflow-auto mt-2">
      <Datatable :headers="tableHeaders" :data="postdurations.data" :allowedActions="allowedActions"
        :editMethod="openEditForm" :deleteMethod="openDeleteDialog" />
    </div>

    <Modal v-show="showModal" @closeModal="closeModal">
      <template v-slot:header>
        <h4 v-if="modalType == 'new-postduration'">Create Post Duration</h4>
        <h4 v-if="modalType == 'edit-postduration'">Edit Post Duration</h4>
      </template>

      <template v-slot:body>
        <div v-if="modalType == 'new-postduration'">
          <CreatePostDurationForm />
        </div>
        <div v-if="modalType == 'edit-postduration'">
          <UpdatePostDurationForm :id="postduration.id" :name="postduration.name" :duration="postduration.duration" :premium="postduration.premium" />
        </div>
      </template>
    </Modal>

    <ConfirmDialog v-if="showDeleteDialog" @closeModal="closeDialog">
      <template v-slot:header>
        <h4>Are you sure you want to delete {{ postduration.name }}?</h4>
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
              " @click="deletePostDuration(postduration.id)">Ok, Confirm</a>
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
import { computed } from "vue";
import { useStore } from "vuex";
import Datatable from "../shared/datatable.vue";
import Modal from "../shared/modal.vue";
import ConfirmDialog from "../shared/confirm-dialog.vue";
import CreatePostDurationForm from "./forms/postdurations/CreatePostDurationForm.vue";
import UpdatePostDurationForm from "./forms/postdurations/UpdatePostDurationForm.vue";

export default {
  name: "SkillsTable",
  components: {
    Datatable,
    Modal,
    ConfirmDialog,
    CreatePostDurationForm,
    UpdatePostDurationForm
  },
  data() {
    return {
      showModal: false,
      showDeleteDialog: false,
      modalType: null, // Used to select form that loads
      tableHeaders: [
        { name: "Name", value: "name", isImage: false },
        { name: "Premium", value: "premium", isBoolean: true }
      ],
      postduration: {
        id: null,
        name: null,
        duration: null,
        premium: null
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
      this.postduration = {
        id: id,
        name: this.postdurations.data[index].name,
        duration: this.postdurations.data[index].duration,
        premium: this.postdurations.data[index].premium
      };
      this.showModal = true;
      this.modalType = 'edit-postduration';
    },
    openDeleteDialog(id, index) {
      this.jobtype = {
        id: id,
        name: this.postdurations.data[index].name,
        duration: this.postdurations.data[index].duration,
        premium: this.postdurations.data[index].premium
      };
      this.showDeleteDialog = true;
    },
    deleteJobtype(id) {
      this.$store.dispatch("postdurations/delete_postduration", id);
      this.showDeleteDialog = false;
    },
    openCreateForm() {
      this.showModal = true;
      this.modalType = 'new-postduration';
    },
    closeDialog() {
      this.showDeleteDialog = false;
      this.postduration = {
        id: null,
        name: null,
        duration: null
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
      postdurations: computed(() => store.getters["postdurations/postdurations"]),
    };
  },
};
</script>