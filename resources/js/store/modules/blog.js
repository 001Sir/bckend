import axios from "axios";

let token = localStorage.getItem("auth_token");
let Api = token
    ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
      })
    : axios.create();

export default {
    namespaced: true,
    state: {
        posts: {}
    },
    getters: {
        posts(state) {
            return state.posts;
        },
    },
    mutations: {
        SET_POSTS(state, value) {
            state.posts = value;
        }
    },
    actions: {
        index({ commit }) {
            return Api.get("/api/admin/blogposts")
                .then(({ data }) => {
                    commit("SET_POSTS", data);
                })
                .catch(({ response }) => {
                    console.error("API ERROR:", response);
                });
        },
    },
};
