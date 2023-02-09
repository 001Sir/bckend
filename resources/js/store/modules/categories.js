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
        categories: {},
    },
    getters: {
        categories(state) {
            return state.categories;
        },
    },
    mutations: {
        SET_CATEGORIES(state, value) {
            state.categories = value;
        },
    },
    actions: {
        index({ commit }) {
            return Api.get("/api/categories/all")
                .then(({ data }) => {
                    commit("SET_CATEGORIES", data);
                })
                .catch(({ response: { data } }) => {
                    console.log("Couldn't fetch data from categories.");
                });
        },
        new_category({ dispatch }, data) {
            return Api.post("/api/admin/categories", {
                category_name: data.name,
                icon_url: data.icon,
            }).then((res) => {
                console.info(
                    "[Category Created]",
                    res.data.name + " category created successfully."
                );
                dispatch("index");
            });
        },
        edit_category({ dispatch }, data) {
            const req = data.icon
                ? {
                    _method: "PUT",
                    category_name: data.name,
                    icon_url: data.icon
                }
                : {
                    _method: "PUT",
                    category_name: data.name,
                  };

            return Api.post("/api/admin/categories/"+data.id, req).then(
                (res) => {
                    console.info(
                        "[Category Updated]",
                        res.data.name +
                            "[ID:" +
                            res.data.id +
                            "] category updated successfully."
                    );
                    dispatch("index");
                }
            );
        },
        delete_category({ dispatch }, id) {
            return Api.post("/api/admin/categories/"+id, {
                _method: "DELETE"
            }).then(
                (res) => {
                    console.info(
                        "[Category Deleted]",
                        "Category deleted successfully."
                    );
                    dispatch("index");
                }
            )
        }
    },
};
