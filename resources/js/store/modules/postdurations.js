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
        postdurations: {},
    },
    getters: {
        postdurations(state) {
            return state.postdurations;
        },
    },
    mutations: {
        SET_POSTDURATIONS(state, value) {
            state.postdurations = value;
        },
    },
    actions: {
        index({ commit }) {
            return Api.get("/api/post-durations/all")
                .then(({ data }) => {
                    commit("SET_POSTDURATIONS", data);
                })
                .catch(({ response: { data } }) => {
                    console.log("Couldn't fetch data from post durations.");
                });
        },
        new_postduration({ dispatch }, data) {
            return Api.post("/api/admin/post-durations", {
                name: data.name,
                duration: data.duration,
                is_paid: data.premium
            }).then((res) => {
                console.info(
                    "[Post Duration Created]",
                    res.data.name + " post duration created successfully."
                );
                dispatch("index");
            });
        },
        edit_postduration({ dispatch }, data) {
            const req = {
                    _method: "PUT",
                    name: data.name,
                    duration: data.duration,
                    is_paid: data.premium
                  };

            return Api.post("/api/admin/post-durations/"+data.id, req).then(
                (res) => {
                    console.info(
                        "[Post Duration Updated]",
                        res.data.name +
                            "[ID:" +
                            res.data.id +
                            "] post duration updated successfully."
                    );
                    dispatch("index");
                }
            );
        },
        delete_postduration({ dispatch }, id) {
            return Api.post("/api/admin/post-durations/"+id, {
                _method: "DELETE"
            }).then(
                (res) => {
                    console.info(
                        "[Post Duration Deleted]",
                        "post duration deleted successfully."
                    );
                    dispatch("index");
                }
            )
        }
    },
};
