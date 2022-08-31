import Vue from 'vue';
import axiosClient from '../../axios';

Vue.prototype.$user = {};
Vue.prototype.$no_auth = false;
Vue.prototype.$user.origin = "google";
Vue.prototype.$user.picture = "https://lh3.googleusercontent.com/a-/AFdZucrtBOWgTHnR9xCR_kwc_vpBmlbSzoKEtigaGN_gSQ=s96-c";

const state = {
  suggestions: [],
  indexes: [],
  loadingStatus: false
};

const getters = {
  allSuggestions: (state) => state.suggestions,
  allIndexes: (state) => state.indexes,
  loadingStatus: (state) => state.loadingStatus
};

const actions = {
  fetchSuggestions({ commit }) {
    commit('loadingStatus', true)
    axiosClient
      .get("user")
      .catch((error) => {
        console.log(error);
        commit('loadingStatus', false)
        this._vm.$toast.error("Erreur lors du chargement des données");
      })
      .then((response) => {
        Vue.prototype.$user = response.data;
      

        //Si l'utilisateur n'est pas connecté
        if (Object.keys(response.data).length === 0) {
          Vue.prototype.$no_auth = true;
        }
        axiosClient
          .get("suggestions")
          .then((response) => {
            if (typeof response.data === 'object') {
              response.data = Object.keys(response.data)
                .map(function (key) {
                  return response.data[key];
                });
            }
            commit('setSuggestions', response.data);
            commit('setIndexes', createIndex(response.data));
            // createIndex(response.data);
          })
          .catch((error) => {
            console.log(error);
            this._vm.$toast.error("Erreur lors du chargement des données");
          }).finally(() => {
            commit('loadingStatus', false)
          });
      });
  },

  addSuggestion({ commit }, suggestion) {
    axiosClient
      .post("suggestions", {
        title: suggestion.title,
        description: suggestion.description,
        user_firstname: suggestion.user_firstname,
        user_lastname: suggestion.user_lastname,
        user_email: suggestion.user_email
      })
      .then((response) => {
        response.data.my_suggestion = true;
        commit('newSuggestion', response.data);
        this._vm.$toast.success("Suggestion créée avec succès !");
      }).catch((error) => {
        console.log(error);
        this._vm.$toast.error("Erreur lors de la création de la suggestion");
      })
  },

  deleteSuggestion({ commit }, id) {
    axiosClient.delete(`suggestions/${id}`).then(() => {
      commit('deleteSuggestion', id)
      this._vm.$toast.success("Suggestion supprimée avec succès !");
    }).catch((error) => {
      console.log(error);
      this._vm.$toast.error("Erreur lors de la suppression de la suggestion");
    })
  },

  changeStateSuggestion({ commit }, { id, state }) {
    axiosClient.put(`suggestions/state/${id}`, {
      state
    }).then((response) => {
      console.log(response.data);
      commit('updateSuggestion', response.data)
      this._vm.$toast.success("Suggestion modifiée avec succès !");
    }).catch((error) => {
      this._vm.$toast.error("Erreur lors de la validation de la suggestion");
      console.log(error);
    })
  },

  updateSuggestion({ commit }, suggestion) {
    axiosClient
      .put(`suggestions/${suggestion.id}`, {
        title: suggestion.title,
        description: suggestion.description,
      }).then((response) => {
        commit('updateSuggestion', response.data)
        this._vm.$toast.success("Suggestion modifiée avec succès !");
      }).catch((error) => {
        this._vm.$toast.error("Erreur lors de la modification de la suggestion");
        console.log(error);
      })
  }
};

const mutations = {
  loadingStatus: (state, newLoadingStatus) => (state.loadingStatus = newLoadingStatus),
  setSuggestions: (state, suggestions) => (state.suggestions = suggestions),
  setIndexes: (state, indexes) => (state.indexes = indexes),
  newSuggestion: (state, suggestion) => {
    state.suggestions.push(suggestion)
    state.indexes = createIndex(state.suggestions);
  },
  deleteSuggestion: (state, id) => {
    state.suggestions = state.suggestions.filter(suggestion => suggestion.id !== id)
    state.indexes = createIndex(state.suggestions);
  },
  updateSuggestion: (state, updSuggestion) => {
    const index = state.suggestions.findIndex(suggestion => suggestion.id === updSuggestion.id);
    if (index !== -1) {
      state.suggestions.splice(index, 1, updSuggestion);
    }
    state.indexes = createIndex(state.suggestions);
  }
};

export default {
  state,
  getters,
  actions,
  mutations
}

function createIndex(suggestions) {
  const jsonData = require('./dictionary.json');

  let index = [];
  let k = -1;
  for (const element of suggestions) {
    k++

    let keywords = element.description.replace(/(<([^>]+)>)/gi, "").toLowerCase() + ' ' + element.title.replace(/(<([^>]+)>)/gi, "").toLowerCase();
    let array = keywords.split(' ');

    array.forEach(word => {
      jsonData.synonyme.forEach((e) => {
        if (e.includes(word)) {
          array = array.concat(e)
          array.splice(array.indexOf(word), 1);
        }
      })
      jsonData.exclude.forEach((e) => {
        if (e.includes(word)) {
          array = array.filter(e => e !== word);
        }
      })
    });

    array.forEach(word => {
      if (index[word]) {
        if (index[word] != k) {
          index[word].push(k);
        }
      } else {
        index[word] = [k]
      }
    });
  }
  return index;
}