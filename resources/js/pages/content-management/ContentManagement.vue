<template>
  <div class="max-w-[128em] min-h-screen mx-auto px-4">
    <DashboardHeader title="Content Management" subTitle="Manage content across your site." />
    <h3 class="font-medium text-lg mt-4">Pages</h3>
    <div v-if="webpages.data" class="w-full max-h-[20em] overflow-x-scroll md:overflow-auto mt-5">
      <Datatable :headers="tableHeaders" :data="webpages.data" :allowedActions="allowedActions"
        :editMethod="openEditWebPageForm" />
    </div>
    <div v-if="webpages.meta" class="w-full mb-10">
      <Pagination v-if="webpages.meta.last_page > 1" :pagination="webpages.meta" :offset="5"
        @paginate="fetchWebPages" />
    </div>
    <div class="flex flex-row justify-start items-center">
      <h3 class="font-medium text-lg">Blog Posts</h3>
      <router-link to="/admin/content-management/blog/post/new" class="
          w-32
          px-1
          py-2
          border border-gray-300
          bg-white
          text-black
          font-bold
          text-sm
          hover:bg-gray-200
          ml-4
        ">+ Add New Post</router-link>
    </div>
    <div class="grid grid-cols-1 gap-0 md:gap-5 md:grid-cols-4 pt-4">
      <BlogPostTile v-for="post in blogPosts.data" :key="post.id" :id="post.id" :title="post.title"
        :img="post.featured_image" :excerpt="post.excerpt" :status="post.published" :deletePost="openDeleteBlogPost" />
    </div>

    <Modal v-show="showModal" @closeModal="closeModal">
      <template v-slot:header>
        <h4 v-if="modalType == 'edit-page'">Edit Web Page</h4>
      </template>

      <template v-slot:body>
        <div v-if="modalType == 'edit-page'">
          <UpdateWebPage :id="page.id" :title="page.title" :body="page.body" :published="page.is_published"
            :status="page.status" :indexMethod="fetchWebPages" :closeMethod="closeAfterPageDelete" />
        </div>
      </template>
    </Modal>

    <ConfirmDialog v-if="showDeleteDialog" @closeModal="closeDialog">
      <template v-slot:header>
        <h4>Are you sure you want to delete {{ post.title }}?</h4>
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
            " @click="deletePost(post.id)">Ok, Confirm</a>
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
import Modal from "../../components/shared/modal.vue";
import ConfirmDialog from "../../components/shared/confirm-dialog.vue";
import Pagination from "../../components/shared/pagination.vue";
import DashboardHeader from "../../components/main/DashboardHeader.vue";
import Datatable from "../../components/shared/datatable.vue";
import UpdateWebPage from "../../components/blog/UpdateWebPage.vue";
import BlogPostTile from "../../components/blog/blog-post-tile.vue";

export default {
  components: {
    DashboardHeader,
    Pagination,
    Modal,
    ConfirmDialog,
    Datatable,
    BlogPostTile,
    UpdateWebPage
  },
  data() {
    return {
      showModal: false,
      showDeleteDialog: false,
      modalType: null,
      page: {},
      webpages: {},
      post: {},
      blogPosts: {},
      tableHeaders: [
        { name: "Title", value: "title" },
        { name: "Published", value: "published", isBoolean: true },
        { name: "Status", value: "status" },
      ],
      allowedActions: {
        edit: true,
        delete: false,
      },
    };
  },
  mounted() {
    this.fetchWebPages();
    this.fetchBlogPosts();
  },
  methods: {
    fetchWebPages(page = 1) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      let query = '/api/admin/webpages?page=' + page;

      Api.get(query)
        .then(response => {
          this.webpages = response.data;
        });
    },
    openEditWebPageForm(id, index) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      Api.get('/api/admin/webpages/' + id)
        .then(response => {
          this.page = {
            id: id,
            title: response.data.data.title,
            body: response.data.data.body,
            is_published: response.data.data.is_published,
            status: response.data.data.status
          };
          this.showModal = true;
          this.modalType = "edit-page";
        });
    },
    fetchBlogPosts(page = 1) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      let query = '/api/admin/blogposts?page=' + page;

      Api.get(query)
        .then(response => {
          this.blogPosts = response.data;
        });
    },
    openDeleteBlogPost(id) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      Api.get('/api/admin/blogposts/' + id)
        .then(response => {
          this.post = {
            id: id,
            title: response.data.data.post_title
          };
          this.showDeleteDialog = true;
        });
    },
    deletePost(id) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      Api.post("/api/admin/blogposts/" + id, {
        _method: "DELETE",
        id: id
      }).then(
        (res) => {
          this.fetchBlogPosts();
          this.showDeleteDialog = false;
        });
    },
    closeDialog() {
      this.showDeleteDialog = false;
    },
    closeAfterPageDelete() {
      this.showModal = false;
      this.fetchWebPages();
    },
    closeModal() {
      this.showModal = false;
      this.modalType = null;
    },
  }
}
</script>