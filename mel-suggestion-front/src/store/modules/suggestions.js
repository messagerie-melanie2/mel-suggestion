import Vue from 'vue';
import axiosClient from '../../axios';

Vue.prototype.$user = {};
Vue.prototype.$anonymised = false;

const state = {
  user: null,
  loginOperators: [],
  loginOperatorCredential: [],
  suggestions: [],
  indexes: [],
  loadingStatus: false,
  oidcSettings: null,
  isAuthenticated: false
};

const getters = {
  allSuggestions: (state) => state.suggestions,
  allIndexes: (state) => state.indexes,
  loadingStatus: (state) => state.loadingStatus,
  user: (state) => state.user,
  loginOperators: (state) => state.loginOperators,
  loginOperatorCredential: (state) => state.loginOperatorCredential,
  oidcSettings: state => state.oidcSettings,
  isAuthenticated: state => state.isAuthenticated,
};

const actions = {
  fetchUser({ commit }) {
    commit('loadingStatus', true)
    return axiosClient.get("/user")
      .catch((error) => {
        console.log(error);
        commit('loadingStatus', false)
        this._vm.$toast.error("Erreur lors du chargement des données");
      })
      .then((response) => {
        //Si l'utilisateur n'est pas connecté
        if (response.data.error) {
          // Vue.prototype.$isAuthenticated = true;
        }
        else {
          Vue.prototype.$user = response.data;
          Vue.prototype.$anonymised = response.data.anonymised ?? false;
          commit('setUser', response.data);
        }
      }).finally(() => {
        commit('loadingStatus', false)
      });
  },

  setUser({ commit }, user) {
    axiosClient
      .post("/login/connect", {
        user: user
      })
      .then((response) => {
        console.log(response);

        commit('isAuthenticated', response.data ? true : false);
      }).catch((error) => {
        console.log(error);
        this._vm.$toast.error("Erreur lors de la création de la suggestion");
      })
  },

  fetchSuggestions({ commit }) {
    axiosClient.get("/suggestions")
      .then((response) => {
        if (typeof response.data === 'object') {
          response.data = Object.keys(response.data)
            .map(function (key) {
              return response.data[key];
            });
        }
        commit('setSuggestions', response.data);
        commit('setIndexes', createIndex(response.data));
      })
      .catch((error) => {
        console.log(error);
        this._vm.$toast.error("Erreur lors du chargement des suggestions");
      })
  },

  fetchLoginOperators({ commit }) {
    axiosClient.get("/login/operators")
      .then((response) => {
        commit('setLoginOperators', response.data);
      })
      .catch((error) => {
        console.log(error);
        this._vm.$toast.error("Erreur lors de la récupération des moyens de connexion");
      });
  },

  fetchOperatorCredential({ commit }, connector) {
    return axiosClient.get(`login/operator/${connector}`)
      .then((response) => {
        commit('setLoginOperatorCredential', response.data);
      })
      .catch((error) => {
        console.log(error);
        this._vm.$toast.error("Erreur lors de la récupération des moyens de connexion");
      });
  },

  addSuggestion({ commit }, suggestion) {
    axiosClient
      .post("/suggestions", {
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
    axiosClient.delete(`/suggestions/${id}`).then(() => {
      commit('deleteSuggestion', id)
      this._vm.$toast.success("Suggestion supprimée avec succès !");
    }).catch((error) => {
      console.log(error);
      this._vm.$toast.error("Erreur lors de la suppression de la suggestion");
    })
  },

  changeStateSuggestion({ commit }, { id, state, comment }) {
    axiosClient.put(`/suggestions/state/${id}`, {
      state,
      comment
    }).then((response) => {
      commit('updateSuggestion', response.data)
      this._vm.$toast.success("Suggestion modifiée avec succès !");
    }).catch((error) => {
      this._vm.$toast.error("Erreur lors de la validation de la suggestion");
      console.log(error);
    })
  },

  updateSuggestion({ commit }, suggestion) {
    axiosClient
      .put(`/suggestions/${suggestion.id}`, {
        title: suggestion.title,
        description: suggestion.description,
      }).then((response) => {
        commit('updateSuggestion', response.data)
        this._vm.$toast.success("Suggestion modifiée avec succès !");
      }).catch((error) => {
        this._vm.$toast.error("Erreur lors de la modification de la suggestion");
        console.log(error);
      })
  },

  setOidcSettings({ commit }, settings) {
    commit('setOidcSettings', settings)
  },

  setIsAuthenticated({ commit }, auth) {
    commit('setIsAuthenticated', auth)
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
  },
  setUser: (state, user) => (state.user = user),
  setLoginOperators: (state, loginOperators) => (state.loginOperators = loginOperators),
  setLoginOperatorCredential: (state, loginOperatorCredential) => (state.loginOperatorCredential = loginOperatorCredential),
  setOidcSettings: (state, settings) => (state.oidcSettings = settings),
  setIsAuthenticated: (state, auth) => (state.isAuthenticated = auth),
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
      if (Array.isArray(index[word])) {
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