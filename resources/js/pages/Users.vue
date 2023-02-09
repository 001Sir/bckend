<template>
  <div class="max-w-[128em] min-h-screen mx-auto px-4">
    <DashboardHeader title="Users" subTitle="Users registered on the platform." />
    <div class="w-full flex flex-col md:flex-row justify-between items-center mt-12 mb-6">
      <Search @search-form="searchForm" />
      <FilterButton @change-filter="changeFilter" :current="filter" :values="this.filterOptions" />
    </div>
    <div v-if="users.data" class="w-full overflow-x-scroll md:overflow-auto mt-10">
      <Datatable :headers="tableHeaders" :data="users.data" :allowedActions="allowedActions"
        :detailMethod="openDetails">
        <template v-slot:actions="actions">
          <li>
            <a class="
                    block
                    w-full
                    text-left text-red-700
                    hover:bg-gray-100
                    px-4
                    py-2
                  " href="#!" @click.prevent="userBlockToggle(actions.index)">{{ (users.data[actions.index].blocked) ?
                      'Unblock' : 'Block'
                  }}</a>
          </li>
        </template>
      </Datatable>
    </div>
    <div class="w-full" v-if="users.meta">
      <Pagination v-if="users.meta.last_page > 1" :pagination="users.meta" :offset="10" @paginate="fetchUsers" />
    </div>
  </div>
</template>

<script>
import { useToast } from 'vue-toastification';
import Pagination from "../components/shared/pagination.vue";
import DashboardHeader from "../components/main/DashboardHeader.vue";
import Search from "../components/shared/search.vue";
import FilterButton from "../components/shared/filter-button.vue";
import Datatable from "../components/shared/datatable.vue";

export default {
  components: {
    Pagination,
    DashboardHeader,
    Search,
    FilterButton,
    Datatable,
  },
  data() {
    return {
      toast: {},
      users: {},
      search: null,
      filter: 'first_name',
      filterOptions: [
        { name: "Name: A - Z", value: "first_name" },
        { name: "Name: Z - A", value: "-first_name" }
      ],
      tableHeaders: [
        { name: "Profile Picture", value: "profile_picture", isImage: true },
        { name: "Name", value: "full_name" },
        { name: "email", value: "email" },
        { name: "Profession", value: "profession" },
        { name: "Blocked", value: "blocked", isBoolean: true }
      ],
      allowedActions: {
        details: false,
        edit: false,
        delete: false,
      },
    };
  },
  mounted() {
    this.toast = useToast();
    this.fetchUsers();
  },
  methods: {
    fetchUsers(page = 1) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      let search_parameter = (this.search != null) ? '&s=' + this.search : '';
      let filter_option = (this.filter != null) ? '&sort=' + this.filter : '';
      let query = '/api/admin/users?page=' + page + '' + search_parameter + filter_option;

      Api.get(query)
        .then(response => {
          this.users = response.data;
        });
    },
    searchForm(value) {
      this.search = value;
      this.fetchUsers();
    },
    changeFilter(value) {
      this.filter = value;
      this.fetchUsers();
    },
    openDetails() {
      return;
    },
    userBlockToggle(index) {
      let token = localStorage.getItem("auth_token");
      let Api = token
        ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
        })
        : axios.create();

      const USER_CURRENT_STATE = this.users.data[index].blocked;
      const USER_ID = this.users.data[index].id;
      let query = (USER_CURRENT_STATE) ? '/api/user/unblock/' + USER_ID : '/api/user/block/' + USER_ID;

      Api.get(query)
        .then(response => {
          const NOTIFICATION_TEXT = response.data.message;
          this.toast.success(NOTIFICATION_TEXT, {
            position: "top-right",
            timeout: 1500,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: true,
            closeButton: "button",
            icon: true,
            rtl: false
          });
          this.fetchUsers();
        });
    }
  }
}
</script>
