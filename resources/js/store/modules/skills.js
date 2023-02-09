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
        skills: {},
    },
    getters: {
        skills(state) {
            return state.skills;
        },
    },
    mutations: {
        SET_SKILLS(state, value) {
            state.skills = value;
        },
    },
    actions: {
        index({ commit }) {
            return Api.get("/api/skills/all")
                .then(({ data }) => {
                    commit("SET_SKILLS", data);
                })
                .catch(({ response: { data } }) => {
                    console.log("Couldn't fetch data from skills.");
                });
        },
        new_skill({ dispatch }, data) {
            return Api.post("/api/admin/skills", {
                name: data.name,
                is_hinted: data.hinted,
            }).then((res) => {
                console.info(
                    "[Skill Created]",
                    res.data.name + " skill created successfully."
                );
                dispatch("index");
            });
        },
        edit_skill({ dispatch }, data) {
            const req = {
                    _method: "PUT",
                    name: data.name,
                    is_hinted: data.hinted,
                };

            return Api.post("/api/admin/skills/"+data.id, req).then(
                (res) => {
                    console.info(
                        "[Skill Updated]",
                        res.data.name +
                            "[ID:" +
                            res.data.id +
                            "] skill updated successfully."
                    );
                    dispatch("index");
                }
            );
        },
        delete_skill({ dispatch }, id) {
            return Api.post("/api/admin/skills/"+id, {
                _method: "DELETE"
            }).then(
                (res) => {
                    console.info(
                        "[Skill Deleted]",
                        "Skill deleted successfully."
                    );
                    dispatch("index");
                }
            )
        }
    },
};
