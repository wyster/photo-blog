const state = {
    /** @type {User|null} */
    user: null,
    /** @type {boolean} */
    authenticated: false,
};

const mutations = {
    setUser(state, user) {
        state.user = user;
        state.authenticated = true;
    },
    removeUser(state) {
        state.user = null;
        state.authenticated = false;
    },
};

const actions = {
    setUser({commit}, user) {
        commit("setUser", user);
    },
    removeUser({commit}) {
        commit("removeUser");
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};