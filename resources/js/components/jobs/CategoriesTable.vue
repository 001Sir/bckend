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
      <h4 class="text-base font-bold">Categories</h4>
      <a
        href="#!"
        class="
          w-44
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
        >+ Add New Category</a
      >
    </div>
    <div class="w-full max-h-[30em] overflow-scroll md:overflow-auto mt-2">
      <Datatable :headers="tableHeaders" :data="categories.data" :allowedActions="allowedActions" :editMethod="openEditForm" :deleteMethod="openDeleteDialog" />
    </div>

    <Modal v-show="showModal" @closeModal="closeModal">
      <template v-slot:header>
        <h4 v-if="modalType == 'new-category'">Create Category</h4>
        <h4 v-if="modalType == 'edit-category'">Edit Category</h4>
      </template>

      <template v-slot:body>
        <div v-if="modalType == 'new-category'">
          <CreateCategory />
        </div>
        <div v-else-if="modalType == 'edit-category'">
          <UpdateCategory :id="category.id" :name="category.name" />
        </div>
      </template>
    </Modal>

    <ConfirmDialog v-if="showDeleteDialog" @closeModal="closeDialog">
      <template v-slot:header>
        <h4>Are you sure you want to delete {{ category.name }}?</h4>
      </template>

      <template v-slot:body>
        <p class="text-sm italic text-gray-500">Click <strong>'Ok'</strong> to confirm, or cancel.</p>

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
        @click="deleteCategory(category.id)"
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
import CreateCategory from "./forms/categories/CreateCategoryForm.vue";
import UpdateCategory from "./forms/categories/UpdateCategoryForm.vue";

export default {
  name: 'CategoriesTable',
  components: {
    Datatable,
    Modal,
    ConfirmDialog,
    CreateCategory,
    UpdateCategory
  },
  data() {
    return {
      showModal: false,
      showDeleteDialog: false,
      modalType: null, // Used to select form that loads
      tableHeaders: [
        { name: "Name", value: "name", isImage: false },
        { name: "Icon", value: "icon", isImage: true },
      ],
      category: {
        id: null,
        name: null
      },
      allowedActions: {
        details: false,
        edit: true,
        delete: true
      },
    };
  },
  methods: {
    openEditForm(id, index) {
      this.category = {
        id: id,
        name: this.categories.data[index].name
      };
      this.showModal = true;
      this.modalType = 'edit-category';
    },
    openDeleteDialog(id, index) {
      this.category = {
        id: id,
        name: this.categories.data[index].name
      };
      this.showDeleteDialog = true;
    },
    deleteCategory(id) {
      this.$store.dispatch("categories/delete_category", id);
      this.showDeleteDialog = false;
    },
    openCreateForm() {
      this.showModal = true;
      this.modalType = 'new-category';
    },
    closeDialog() {
      this.showDeleteDialog = false;
      this.category = {
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
      categories: computed(() => store.getters["categories/categories"]),
    };
  },
};
</script>