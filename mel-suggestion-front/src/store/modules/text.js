import axiosClient from '../../axios';

const state = {
  text: [],
  synonyms: []
};

const getters = {
  allText: (state) => state.text,
  synonyms: (state) => state.synonyms,
  excludeWords: () => {    
    const dictionary = require('./dictionary.json');
    return dictionary.exclude;
  },
};

const actions = {
  fetchText({ commit }) {
    commit('loadingStatus', true)
    axiosClient
      .get("/text")
      .then((response) => {
        commit('setText', response.data);
      })
      .catch((error) => {
        console.log(error);
      });
  },

  fetchSynonyms({ commit }) {
    axiosClient
      .get("/synonyms")
      .then((response) => {
        commit('setSynonyms', response.data);
      })
      .catch((error) => {
        console.log(error);
      });
  }
};

const mutations = {
  setText: (state, text) => (state.text = text),
  setSynonyms: (state, synonyms) => (state.synonyms = synonyms)
};

export default {
  state,
  getters,
  actions,
  mutations
}