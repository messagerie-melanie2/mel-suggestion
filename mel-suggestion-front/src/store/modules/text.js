import axiosClient from '../../axios';

const state = {
  text: [],
};

const getters = {
  allText: (state) => state.text,
};

const actions = {
  fetchText({ commit }) {
    commit('loadingStatus', true)
    axiosClient
      .get("text")
      .then((response) => {
        commit('setText', response.data);
      })
      .catch((error) => {
        console.log(error);
      });
  }
};

const mutations = {
  setText: (state, text) => (state.text = text),

};

export default {
  state,
  getters,
  actions,
  mutations
}