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
      <h4 class="text-base font-bold">Skills</h4>
      <a
        href="#!"
        class="
          w-32
          px-2
          py-2
          border border-gray-300
          bg-teal-400
          text-white
          font-bold
          text-sm text-center
          rounded-lg
          hover:bg-green-400
          ml-4
        "
        @click="openCreateForm()"
        >+ Add New Skill</a
      >
    </div>
    <div class="w-full h-[15em] overflow-scroll md:overflow-auto mt-2">
      <Datatable
        :headers="tableHeaders"
        :data="skills.data"
        :allowedActions="allowedActions"
        :editMethod="openEditForm"
        :deleteMethod="openDeleteDialog"
      />
    </div>

    <Modal v-show="showModal" @closeModal="closeModal">
      <template v-slot:header>
        <h4 v-if="modalType == 'new-skill'">Create Skill</h4>
        <h4 v-if="modalType == 'edit-skill'">Edit Skill</h4>
      </template>

      <template v-slot:body>
        <div v-if="modalType == 'new-skill'"><CreateSkillForm /></div>
        <div v-if="modalType == 'edit-skill'"><UpdateSkillForm :id="skill.id" :name="skill.name" :hinted="skill.hinted" /></div>
      </template>
    </Modal>

    <ConfirmDialog v-if="showDeleteDialog" @closeModal="closeDialog">
      <template v-slot:header>
        <h4>Are you sure you want to delete {{ skill.name }}?</h4>
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
            @click="deleteSkill(skill.id)"
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
import CreateSkillForm from "./forms/skills/CreateSkillForm.vue";
import UpdateSkillForm from "./forms/skills/UpdateSkillForm.vue";

export default {
  name: "SkillsTable",
  components: {
    Datatable,
    Modal,
    ConfirmDialog,
    CreateSkillForm,
    UpdateSkillForm
  },
  data() {
    return {
      showModal: false,
      showDeleteDialog: false,
      modalType: null, // Used to select form that loads
      tableHeaders: [
        { name: "Name", value: "name", isImage: false },
        { name: "Hinted", value: "visible", isImage: false, isBoolean: true },
      ],
      skill: {
        id: null,
        name: null,
        hinted: false,
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
      this.skill = {
        id: id,
        name: this.skills.data[index].name,
        hinted: this.skills.data[index].visible,
      };
      this.showModal = true;
      this.modalType = "edit-skill";
    },
    openDeleteDialog(id, index) {
      this.skill = {
        id: id,
        name: this.skills.data[index].name,
        hinted: this.skills.data[index].visible,
      };
      this.showDeleteDialog = true;
    },
    deleteSkill(id) {
      this.$store.dispatch("skills/delete_skill", id);
      this.showDeleteDialog = false;
    },
    openCreateForm() {
      this.showModal = true;
      this.modalType = "new-skill";
    },
    closeDialog() {
      this.showDeleteDialog = false;
      this.skill = {
        id: null,
        name: null,
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
      skills: computed(() => store.getters["skills/skills"]),
    };
  },
};
</script>