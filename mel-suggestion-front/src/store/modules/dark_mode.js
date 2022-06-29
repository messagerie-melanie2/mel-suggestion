const state = {
  dark: true
};

const getters = {
  dark: (state) => state.dark
};

const mutations = {
  setDark: (state, bool) => {
    state.dark = bool;
  }
};

export default {
  state,
  getters,
  mutations
}