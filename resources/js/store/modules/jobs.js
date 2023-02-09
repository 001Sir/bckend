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
        jobs: {},
    },
    getters: {
        jobs(state) {
            return state.jobs;
        },
    },
    mutations: {
        SET_JOBS(state, value) {
            state.jobs = value;
        },
    },
    actions: {
        index({ commit }) {
            return Api.get("/api/jobs/all")
                .then(({ data }) => {
                    commit("SET_JOBS", data);
                })
                .catch(({ response: { data } }) => {
                    console.log("Couldn't fetch data from jobs.");
                });
        },
        new_job({ dispatch }, data) {
            return Api.post("/api/admin/jobs", data).then((res) => {
                console.info(
                    "[Job Created]",
                    res.data.name + " job created successfully."
                );
            });
        },
        edit_job({ dispatch }, data) {
            const req = {
                    _method: "PUT",
                    ...data
                  };

            return Api.post("/api/admin/jobs/"+data.id, req).then(
                (res) => {
                    console.info(
                        "[Job Updated]",
                        res.data.name +
                            "[ID:" +
                            res.data.id +
                            "] job updated successfully."
                    );
                }
            );
        },
        delete_job({ dispatch }, id) {
            return Api.post("/api/admin/jobs/"+id, {
                _method: "DELETE"
            }).then(
                (res) => {
                    console.info(
                        "[Job Deleted]",
                        "Job deleted successfully."
                    );
                }
            )
        }
    },
};
