import axios from "axios";
import router from "../../router";

let token = localStorage.getItem("auth_token");
let Api = token
    ? axios.create({
          headers: { Authorization: `Bearer ${token}` },
      })
    : axios.create();

export default {
    namespaced: true,
    state: {
        authenticated: false,
        user: {},
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        user(state) {
            return state.user;
        },
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value;
        },
        SET_USER(state, value) {
            state.user = value;
        },
    },
    actions: {
        login({ commit }, token) {
            localStorage.setItem("auth_token", token);
            return axios
                .create({
                    headers: { Authorization: `Bearer ${token}` },
                })
                .get("/api/admin/auth/details")
                .then(({ data }) => {
                    commit("SET_USER", data.data);
                    commit("SET_AUTHENTICATED", true);
                    router.push({ name: "home" });
                })
                .catch(({ response: { data } }) => {
                    commit("SET_USER", {});
                    commit("SET_AUTHENTICATED", false);
                });
        },
        logout({ commit }) {
            return Api.post("/api/admin/auth/logout").then(({ data }) => {
                localStorage.setItem("auth_token", null);
                commit("SET_USER", {});
                commit("SET_AUTHENTICATED", false);
            });
        },
    },
};
