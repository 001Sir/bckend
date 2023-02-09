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
        all_partners: {},
        partners: {},
        dashboard_partners: {}
    },
    getters: {
        all_partners(state) {
            return state.all_partners;
        },
        partners(state) {
            return state.partners;
        },
        dashboard_partners(state) {
            return state.dashboard_partners;
        }
    },
    mutations: {
        SET_ALLPARTNERS(state, value) {
            state.all_partners = value;
        },
        SET_PARTNERS(state, value) {
            state.partners = value;
        },
        SET_DASHBOARDPARTNERS(state, value) {
            state.dashboard_partners = value;
        }
    },
    actions: {
        all({ commit }) {
            return Api.get("/api/partner/all")
                .then(({ data }) => {
                    commit("SET_ALLPARTNERS", data);
                })
                .catch(({ response }) => {
                    console.error("API ERROR:", response);
                });
        },
        index({ commit }) {
            return Api.get("/api/admin/partners")
            .then(({ data }) => {
                commit("SET_PARTNERS", data);
            })
            .catch(({ response }) => {
                console.error("API ERROR:", response);
            });
        },
        dashboardPartners({ commit }) {
            return Api.get("/api/partner/dashboard")
                .then(({ data }) => {
                    commit("SET_DASHBOARDPARTNERS", data)
                })
                .catch(( response ) => {
                    console.error("API ERROR:", response);
                });
        }
    },
};
