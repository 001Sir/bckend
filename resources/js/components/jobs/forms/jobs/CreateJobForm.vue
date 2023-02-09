<template>
  <div class="w-[60em] max-h-[40em] overflow-y-auto px-2">
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Company (Partner)</label>
      <select v-model="form.partner_id" class="px-2 py-4 mt-2 rounded-base">
        <option v-for="(partner, index) in partners.data" :key="index" :value="partner.id">
          {{ partner.name }}
        </option>
      </select>
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Title</label>
      <input class="w-[40em] mt-2 p-4 border-2 rounded-md" v-model="form.job_title" type="text" name="name"
        placeholder="Job Name, e.x -  Automotive Mechanic" />
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Remote Position</label>
      <input class="mt-2 p-4 border-2 rounded-md" v-model="form.is_remote" type="checkbox"
        name="remote_position" />
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Category</label>
      <select v-model="form.category_id" class="px-2 py-4 mt-2 rounded-base">
        <option v-for="(category, index) in categories.data" :key="index" :value="category.id">
          {{ category.name }}
        </option>
      </select>
    </div>
    <div v-if="!form.is_remote">
      <div class="mb-4">
        <label class="block uppercase tracking-wide text-xs font-bold">City</label>
        <input class="w-[20em] mt-2 p-4 border-2 rounded-md" v-model="form.city" type="text" name="city"
          placeholder="City Name, e.x. - New York" />
      </div>
      <div class="mb-4">
        <label class="block uppercase tracking-wide text-xs font-bold">Country</label>
        <input class="w-[25em] mt-2 p-4 border-2 rounded-md" v-model="form.country" type="text" name="country"
          placeholder="Country Name, e.x. - United States" />
      </div>
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">No Pay Range?</label>
      <input class="mt-2 p-4 border-2 rounded-md" v-model="form.no_pay_range" type="checkbox" name="no_pay_range" />
    </div>
    <div v-if="!form.no_pay_range" class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Salary Range</label>
      <div class="flex">
        <input class="w-[10em] mt-2 p-4 border-2 rounded-md" v-model="form.min_salary_range" type="number"
          name="min_salary_range" placeholder="Min. Salary" />
        <p class="mx-4 flex items-center">to</p>
        <input class="w-[10em] mt-2 p-4 border-2 rounded-md" v-model="form.max_salary_range" type="number"
          name="max_salary_range" placeholder="Max. Salary" />
      </div>
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Job Type</label>
      <select v-model="form.job_type_id" class="px-2 py-4 mt-2 rounded-base">
        <option v-for="(jobtype, index) in jobtypes.data" :key="index" :value="jobtype.id">
          {{ jobtype.name }}
        </option>
      </select>
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Description</label>
      <textarea class="w-[35em] mt-2 block p-2.5 text-sm border-2 rounded-base" v-model="form.desc"></textarea>
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Post Durations</label>
      <select v-model="form.job_active_duration" class="px-2 py-4 mt-2 rounded-base">
        <option v-for="(postduration, index) in postdurations.data" :key="index" :value="postduration.id">
          {{ postduration.name }}
        </option>
      </select>
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Is Published?</label>
      <input class="mt-2 p-4 border-2 rounded-md" v-model="form.is_published" type="checkbox" name="published" />
    </div>
    <div class="mb-4">
      <label class="block uppercase tracking-wide text-xs font-bold">Status</label>
      <select v-model="form.status" class="px-2 py-4 mt-2 rounded-base">
        <option value="active">Active</option>
        <option value="paused">Paused</option>
      </select>
    </div>
    <Transition>
      <p v-show="showSuccessMsg" class="raleway-font pt-4 text-teal-600 font-bold">
        Job created successfully.
      </p>
    </Transition>
    <div class="flex justify-end mt-10">
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
        " @click="submit">Create</a>
    </div>
  </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";
import postdurations from "../../../../store/modules/postdurations";

export default {
  data() {
    return {
      store: null,
      emptyFormState: {
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
      form: {
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
      showSuccessMsg: false,
    };
  },
  methods: {
    addMinutesToDate(noOfMinutes) {
      let date = new Date();
      date.setMinutes(date.getMinutes() + noOfMinutes);

      return date;
    },
    submit() {
      if (this.form.published) {
        let minutes;
        this.postdurations.data.forEach((item) => {
          if (item.id == this.form.job_active_duration) {
            minutes = item.duration;
            this.form.expiration = this.addMinutesToDate(minutes);
            this.$store.dispatch("jobs/new_job", this.form).then(() => {
              this.form = this.emptyFormState;
              this.showSuccessMsg = true;
            });
          }
        });
      } else {
        this.$store.dispatch("jobs/new_job", this.form).then(() => {
          this.form = this.emptyFormState;
          this.showSuccessMsg = true;
        });
      }
    },
  },
  setup() {
    const store = useStore();

    return {
      partners: computed(() => store.getters["partners/partners"]),
      categories: computed(() => store.getters["categories/categories"]),
      jobtypes: computed(() => store.getters["jobtypes/jobtypes"]),
      skills: computed(() => store.getters["skills/skills"]),
      postdurations: computed(
        () => store.getters["postdurations/postdurations"]
      ),
    };
  },
};
</script>
