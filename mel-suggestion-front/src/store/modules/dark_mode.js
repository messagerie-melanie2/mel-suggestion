const state = {
  darkMode: false,
};

const getters = {
  isDarkTheme: (state) => state.darkMode,
};

const actions = {
  checkTheme({ commit }) {
    const element = document.querySelector("html");
    if (element.classList.contains('dark-mode')) {
      commit('setTheme', true)
    }
    else {
      commit('setTheme', false)
    }
  }
};

const mutations = {
  setTheme: (state, theme) => (state.darkMode = theme),
};

export default {
  state,
  getters,
  actions,
  mutations
}