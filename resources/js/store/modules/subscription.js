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
        features: {},
        packages: {},
    },
    getters: {
        features(state) {
            return state.features;
        },
        packages(state) {
            return state.packages;
        },
    },
    mutations: {
        SET_FEATURES(state, value) {
            state.features = value;
        },
        SET_PACKAGES(state, value) {
            state.packages = value;
        },
    },
    actions: {
        index_packages({ commit }) {
            return Api.get("/api/admin/packages")
                .then(({ data }) => {
                    commit("SET_PACKAGES", data);
                })
                .catch(({ response: { data } }) => {
                    console.log("Couldn't fetch data from packages.");
                });
        },
        index_features({ commit }) {
            return Api.get("/api/admin/features")
                .then(({ data }) => {
                    commit("SET_FEATURES", data);
                })
                .catch(({ response: { data } }) => {
                    console.log("Couldn't fetch data from features.");
                });
        },
        new_feature({ dispatch }, data) {
            return Api.post("/api/admin/features", {
                name: data.name,
                config_name: data.config_name,
            }).then((res) => {
                console.info(
                    "[Feature Created]",
                    res.data.data.name + " feature created successfully."
                );
                dispatch("index_features");
            });
        },
        new_package({ dispatch }, data) {
            return Api.post("/api/admin/packages", {
                name: data.name,
                price: data.price,
                subscription_type: data.subscription_type,
                is_active: data.is_active,
                features: data.features
            }).then((res) => {
                console.info(
                    "[Package Created]",
                    res.data.data.name + " package created successfully."
                );
                dispatch("index_packages");
            });
        },
        edit_feature({ dispatch }, data) {
            return Api.post("/api/admin/features/"+data.id, {
                _method: "PUT",
                name: data.name
            }).then((res) => {
                console.info(
                    "[Feature Updated]",
                    res.data.data.name + " feature updated successfully."
                );
                dispatch("index_features");
            });
        },
        edit_package({ dispatch }, data) {
            return Api.post("/api/admin/packages/"+data.id, {
                _method: "PUT",
                name: data.name,
                price: data.price,
                subscription_type: data.subscription_type,
                is_active: data.is_active,
                features: data.features
            }).then((res) => {
                console.info(
                    "[Package Updated]",
                    res.data.data.name + " package updated successfully."
                );
                dispatch("index_packages");
            });
        },
        delete_feature({ dispatch }, id) {
            return Api.post("/api/admin/features/"+id, {
                _method: "DELETE"
            }).then(
                (res) => {
                    console.info(
                        "[Feature Deleted]",
                        "Feature deleted successfully."
                    );
                    dispatch("index_features");
                }
            )
        },
        delete_package({ dispatch }, id) {
            return Api.post("/api/admin/packages/"+id, {
                _method: "DELETE"
            }).then(
                (res) => {
                    console.info(
                        "[Package Deleted]",
                        "Package deleted successfully."
                    );
                    dispatch("index_packages");
                }
            )
        }
    },
};
