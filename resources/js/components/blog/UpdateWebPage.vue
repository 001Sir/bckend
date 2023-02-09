<template>
  <div class="w-[60em]">
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Title</label>
      <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="form.title" type="text" name="name"
        placeholder="Web Page Name" />
    </div>
    <div v-if="form.body != null" class="mb-28">
      <label class="block uppercase tracking-wide text-xs font-bold">Body</label>
      <WYSIWYG :modelValue="form.body" @update:modelValue="newValue => form.body = newValue" />
    </div>
    <div class="mb-4 text-left">
      <label class="block uppercase tracking-wide text-xs font-bold">Is Published?</label>
      <input class="mt-2 p-4 border-2 rounded-md" v-model="form.is_published" :checked="form.is_published"
        type="checkbox" name="published" />
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Page Status</label>
      <select v-model="form.status" class="px-2 py-4 mt-2 rounded-base">
        <option value="active">Active</option>
        <option value="paused">Paused</option>
      </select>
    </div>
    <Transition>
      <p v-show="showSuccessMsg" class="raleway-font pt-4 text-teal-600 font-bold">
        Web Page updated successfully.
      </p>
    </Transition>
    <div class="flex justify-between mt-10">
      <a href="#!" class="
            w-38
            px-4
            py-2
            border border-red-300
            bg-red-200
            text-black
            font-bold
            text-sm text-center
            uppercase
            hover:bg-white
          " @click.prevent="openDeleteDialog">
        <font-awesome-icon :icon="['fas', 'trash']" /> Delete Page
      </a>
      <a href="#!" class="
            w-24
            px-4
            py-2
            border border-gray-300
            bg-gray-200
            text-black
            font-bold
            text-sm text-center
            uppercase
            hover:bg-white
          " @click="submit">Update</a>
    </div>

    <ConfirmDialog v-if="showDeleteDialog" @closeModal="closeDialog">
      <template v-slot:header>
        <h4>Are you sure you want to delete {{ form.title }}?</h4>
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
            " @click="deletePage">Ok, Confirm</a>
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
import ConfirmDialog from "../shared/confirm-dialog.vue";
import WYSIWYG from "../shared/wysiwyg.vue";

export default {
  props: {
    id: {
      type: Number,
      required: true,
    },
    title: String,
    body: String,
    published: Boolean,
    status: String,
    indexMethod: Function,
    closeMethod: Function,
  },
  components: {
    ConfirmDialog,
    WYSIWYG
  },
  data() {
    return {
      showDeleteDialog: false,
      store: null,
      form: {
        id: null,
        title: null,
        body: null,
        is_published: null,
        status: null
      },
      showSuccessMsg: false,
    };
  },
  mounted() {
    this.form = {
      id: this.id,
      title: this.title,
      body: this.body,
      is_published: this.published,
      status: this.status
    };
  },
  methods: {
    openDeleteDialog() {
      this.showDeleteDialog = true;
    },
    closeDialog() {
      this.showDeleteDialog = false;
    },
    deletePage() {
      this.closeMethod();
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      Api.post("/api/admin/webpages/" + this.id, {
        _method: "DELETE",
        id: this.id
      }).then(
        (res) => {
          this.showDeleteDialog = false;
          this.closeMethod();
        });
    },
    submit() {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      let query = '/api/admin/webpages/' + this.form.id;

      Api.post(query, {
        _method: 'PUT',
        title: this.form.title,
        body: this.form.body,
        is_published: this.form.is_published,
        status: this.form.status
      }).then(response => {
        this.showSuccessMsg = true;
        this.indexMethod();
      });
    }
  },
};
</script>
  