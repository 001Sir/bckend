<template>
    <div v-if="post != null" class="max-w-[128em] min-h-screen mx-auto px-4">
        <DashboardHeader title="Edit Blog Post" subTitle="Make changes to your blog post." />
        <div class="mt-2">&nbsp;</div>
        <router-link to="/admin/content-management">
            <p class="flex items-center raleway-font text-lg">
                <font-awesome-icon :icon="['fas', 'chevron-circle-left']" />&nbsp;<span class="text-xs">back to
                    <b>Content Management</b></span>
            </p>
        </router-link>
        <div class="mb-4 mt-6">
            <label class="block uppercase tracking-wide text-xs font-bold">Featured</label>
            <input class="mt-2 p-4 border-2 rounded-md" v-model="post.is_featured" :checked="post.is_featured"
                type="checkbox" name="featured" />
        </div>
        <div class="mb-4 mt-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Title</label>
            <input class="w-full mt-2 p-4 border-2 rounded-md" v-model="post.post_title" @input="toSlug" type="text"
                name="name" placeholder="Web Page Name" />
        </div>
        <div class="flex flex-row items-center mb-4 mt-4">
            <label class="h-2 block uppercase tracking-wide text-xs font-bold">Slug</label>
            <div v-if="editSlug" class="flex flex-row">
                <input class="w-[20em] mt-2 py-2 px-4 ml-4 border-2 rounded-md" v-model="post.slug" type="text"
                    name="name" placeholder="Web Page Name" />
                <button @click.prevent="textToSlug"
                    class="w-8 h-8 border text-xs rounded-full mt-4 ml-2 hover:bg-teal-200">
                    <font-awesome-icon :icon="['fas', 'check']" />
                </button>
            </div>
            <div v-else>
                <p class="max-w-[40em] h-4 truncate raleway-font mt-2 ml-4 text-sm">{{ post.slug }}</p>
            </div>
            <button @click.prevent="() => { editSlug = !editSlug }" class="w-8 h-8 border text-xs rounded-full mt-2 ml-6"
                :class="(editSlug) ? 'bg-teal-100 border-teal-300' : ''">
                <font-awesome-icon :icon="['fas', 'pencil']" />
            </button>
        </div>
        <div class="flex flex-col justify-start">
            <div v-if="uploadedFile != null">
                <p class="w-64 truncate raleway-font mt-4 ml-2 text-md italic">
                    Uploaded file: <span class="font-bold">{{ uploadedFile.name }}</span>
                </p>
            </div>
            <p v-else>
                <img :src="post.featured_image" class="block w-[250px]" />
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
            <textarea class="w-full mt-2 block p-2.5 text-sm border-2 rounded-base" v-model="post.excerpt"></textarea>
        </div>
        <div class="mb-4 mt-4">
            <label class="block uppercase tracking-wide text-xs font-bold">Body</label>
            <WYSIWYG :modelValue="post.body" @update:modelValue="newValue => post.body = newValue" />
        </div>
        <div class="mb-4 mt-24">
            <label class="block uppercase tracking-wide text-xs font-bold">Is Published</label>
            <input class="mt-2 p-4 border-2 rounded-md" v-model="post.is_published" :checked="post.is_published"
                type="checkbox" name="published" />
        </div>
        <Transition>
            <div v-if="error != null">
                <ul>
                    <li class="raleway-font pt-4 text-red-400 font-bold" v-for="msg in error.msg" :key="msg">{{ msg }}
                    </li>
                </ul>
            </div>
        </Transition>
        <Transition>
            <p v-show="showSuccessMsg" class="raleway-font pt-4 text-teal-600 font-bold">
                Blog Post created successfully.
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
            post: {
                author_id: '',
                is_featured: '',
                post_title: '',
                slug: '',
                featured_image: '',
                excerpt: '',
                body: '',
                is_published: false,
                is_featured: false,
            },
            editSlug: false,
            uploadedFile: null,
            error: null,
            showSuccessMsg: false,
        }
    },
    created() {
        const USER_ID = this.$store.state.auth.user.data.id;
        this.post.author_id = USER_ID;
        document.title = `[NEW] Blog Post`;
    },
    methods: {
        onFileChange(e) {
            let files = e.target.files;
            this.uploadedFile = files[0];
        },
        textToSlug() {
            const regexExp = /^[a-z0-9]+(?:-[a-z0-9]+)*$/g;

            if(!regexExp.test(this.post.slug)) {
                this.post.slug = this.post.slug.toLowerCase().replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            }
        },
        toSlug() {
            const NEW_SLUG = this.post.post_title.toLowerCase().replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');

            if (!this.editSlug) {
                this.post.slug = NEW_SLUG;
            }
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
                        this.post.featured_image = _url + '/img/uploads/' + res.data.data.file_name;


                        Api.post("/api/admin/blogposts", this.post).then((res) => {
                            this.showSuccessMsg = true;
                        });
                    });
            } else {
                this.error = { msg: ['Please upload an image'] };
            }
        }
    }
}
</script>
