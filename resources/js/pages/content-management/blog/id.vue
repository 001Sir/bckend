<template>
    <div v-if="post != null" class="max-w-[128em] min-h-screen mx-auto px-4">
        <DashboardHeader title="Edit Blog Post" subTitle="Make changes to your blog post." />
        <div class="mt-2">&nbsp;</div>
        <router-link to="/admin/content-management"><p class="flex items-center raleway-font text-lg"><font-awesome-icon :icon="['fas', 'chevron-circle-left']" />&nbsp;<span class="text-xs">back to <b>Content Management</b></span></p></router-link>
        <div class="mb-4 mt-6">
            <label class="block uppercase tracking-wide text-xs font-bold">Featured</label>
            <input class="mt-2 p-4 border-2 rounded-md" v-model="post.data.is_featured" :checked="post.data.is_featured"
                type="checkbox" name="featured" />
        </div>
        <div class="mb-4 mt-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Title</label>
            <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="post.data.post_title" type="text" name="name"
                placeholder="Web Page Name" />
        </div>
        <div class="flex flex-col justify-start">
            <div v-if="uploadedFile != null">
                <p class="w-64 truncate raleway-font mt-4 ml-2 text-md italic">
                    Uploaded file: <span class="font-bold">{{ uploadedFile.name }}</span>
                </p>
            </div>
            <p v-else>
                <img :src="post.data.featured_image" class="block w-[250px]" />
            </p>
            <div class="
            w-[11em]
            mt-4
            rounded-md
            flex flex-row
            px-4
            py-2
            bg-teal-400
            cursor-pointer
            shadow-md
          ">
                <input class="opacity-0 absolute pin-x pin-y cursor-pointer" type="file" name="cover_image"
                    accept=".jpg, .jpeg, .png" @change="onFileChange" />
                <img class="w-[15px]" src="/img/icons/upload.svg" />
                <p class="pl-2">Upload your file</p>
            </div>
        </div>
        <div class="mb-4 mt-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Excerpt</label>
            <textarea class="w-full mt-2 block p-2.5 text-sm border-2 rounded-base"
                v-model="post.data.excerpt"></textarea>
        </div>
        <div class="mb-4 mt-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Body</label>
            <WYSIWYG :modelValue="post.data.body" @update:modelValue="newValue => post.data.body = newValue" />
        </div>
        <div class="mb-4 mt-24">
            <label class="block uppercase tracking-wide text-xs font-bold">Is Published</label>
            <input class="mt-2 p-4 border-2 rounded-md" v-model="post.data.is_published"
                :checked="post.data.is_published" type="checkbox" name="published" />
        </div>
        <Transition>
            <p v-show="showSuccessMsg" class="raleway-font pt-4 text-teal-600 font-bold">
                Blog Post updated successfully.
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
        " @click="submit">Update</a>
        </div>
    </div>
</template>

<script>
import DashboardHeader from '../../../components/main/DashboardHeader.vue';
import WYSIWYG from '../../../components/shared/wysiwyg.vue';
export default {
    components: {
        DashboardHeader,
        WYSIWYG,
    },
    data() {
        return {
            id: null,
            post: null,
            uploadedFile: null,
            showSuccessMsg: false,
        }
    },
    mounted() {
        this.id = this.$route.params.id;
        this.fetchPostDetails().then(res => {
            this.post.data.is_featured = Boolean(this.post.data.is_featured);
            this.post.data.is_published = Boolean(this.post.data.is_published);
            document.title = `[Edit] ${this.post.data.post_title}`;
        });
    },
    methods: {
        onFileChange(e) {
            let files = e.target.files;
            this.uploadedFile = files[0];
        },
        async fetchPostDetails() {
            let token = localStorage.getItem("auth_token");
            let Api = token
                ? axios.create({
                    headers: { Authorization: `Bearer ${token}` },
                })
                : axios.create();

            await Api.get('/api/admin/blogposts/' + this.id)
                .then(response => {
                    this.post = response.data;
                });
        },
        submit() {
            this.showSuccessMsg = false;
            let data = { image: this.uploadedFile };
            let token = localStorage.getItem("auth_token");
            let Api = token
                ? axios.create({
                    headers: { Authorization: `Bearer ${token}` },
                })
                : axios.create();


            if (this.uploadedFile != null) {
                Api
                    .post("/api/file/upload-image", data, {
                        headers: {
                            "Content-type": "multipart/form-data",
                        },
                    })
                    .then((res) => {
                        const _url = window.location.protocol + "//" + window.location.host;
                        this.post.data.featured_image = _url + '/img/uploads/' + res.data.data.file_name;


                        Api.post("/api/admin/blogposts/" + this.post.data.id, { _method: 'PUT', ...this.post.data }).then((res) => {
                            this.showSuccessMsg = true;
                        });
                    });
            } else {
                Api.post("/api/admin/blogposts/" + this.post.data.id, { _method: 'PUT', ...this.post.data }).then((res) => {
                    this.showSuccessMsg = true;
                });
            }
        }
    }
}
</script>
