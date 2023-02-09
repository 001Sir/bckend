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
        jobtypes: {},
    },
    getters: {
        jobtypes(state) {
            return state.jobtypes;
        },
    },
    mutations: {
        SET_JOBTYPES(state, value) {
            state.jobtypes = value;
        },
    },
    actions: {
        index({ commit }) {
            return Api.get("/api/job-types/all")
                .then(({ data }) => {
                    commit("SET_JOBTYPES", data);
                })
                .catch(({ response: { data } }) => {
                    console.log("Couldn't fetch data from job types.");
                });
        },
        new_jobtype({ dispatch }, data) {
            return Api.post("/api/admin/job-types", {
                name: data.name
            }).then((res) => {
                console.info(
                    "[Job Type Created]",
                    res.data.name + " job type created successfully."
                );
                dispatch("index");
            });
        },
        edit_jobtype({ dispatch }, data) {
            const req = {
                    _method: "PUT",
                    name: data.name,
                  };

            return Api.post("/api/admin/job-types/"+data.id, req).then(
                (res) => {
                    console.info(
                        "[Job Type Updated]",
                        res.data.name +
                            "[ID:" +
                            res.data.id +
                            "] job type updated successfully."
                    );
                    dispatch("index");
                }
            );
        },
        delete_jobtype({ dispatch }, id) {
            return Api.post("/api/admin/job-types/"+id, {
                _method: "DELETE"
            }).then(
                (res) => {
                    console.info(
                        "[Job Type Deleted]",
                        "Job type deleted successfully."
                    );
                    dispatch("index");
                }
            )
        }
    },
};
