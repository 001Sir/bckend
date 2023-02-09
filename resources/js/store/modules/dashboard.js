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
        insights: {},
        conversionMetrics: {}
    },
    getters: {
        insights(state) {
            return state.insights;
        },
        conversionMetrics(state) {
            return state.conversionMetrics;
        }
    },
    mutations: {
        SET_INSIGHTS(state, value) {
            state.insights = value;
        },
        SET_CONVERSIONMETRICS(state, value) {
            state.conversionMetrics = value;
        }
    },
    actions: {
        insights({ commit }) {
            return Api.get("/api/admin/insights")
                .then(({ data }) => {
                    commit("SET_INSIGHTS", data);
                })
                .catch(({ response }) => {
                    console.error("API ERROR:", response);
                });
        },
        conversionMetrics({ commit }) {
            return Api.get("/api/admin/conversion-metrics")
            .then(({ data }) => {
                commit("SET_CONVERSIONMETRICS", data.data);
            })
            .catch(({ response }) => {
                console.error("API ERROR:", response);
            });
        }
    },
};
