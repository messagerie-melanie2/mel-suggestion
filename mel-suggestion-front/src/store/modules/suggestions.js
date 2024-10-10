import Vue from 'vue';
import axiosClient from '../../axios';

Vue.prototype.$user = {};
Vue.prototype.$anonymised = false;

const state = {
  suggestions: [],
  loadingStatus: false
};

const getters = {
  allSuggestions: (state) => state.suggestions,
  loadingStatus: (state) => state.loadingStatus
};

const actions = {
  fetchSuggestions({ commit }) {
    commit('loadingStatus', true)
    axiosClient
      .get("/user")
      .catch((error) => {
        console.log(error);
        commit('loadingStatus', false)
        this._vm.$toast.error("Erreur lors du chargement des données");
      })
      .then((response) => {
        Vue.prototype.$user = response.data;
        Vue.prototype.$anonymised = response.data.anonymised ?? false;

        //Si l'utilisateur n'est pas connecté
        if (response.data.error) {
          window.location.href = process.env.VUE_APP_ROOT_API;
        }
        axiosClient
          .get("/suggestions")
          .then((response) => {
            if (typeof response.data === 'object') {
              response.data = Object.keys(response.data)
                .map(function (key) {
                  return response.data[key];
                });
            }
            commit('setSuggestions', response.data);
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
  }
};

const mutations = {
  loadingStatus: (state, newLoadingStatus) => (state.loadingStatus = newLoadingStatus),
  setSuggestions: (state, suggestions) => (state.suggestions = suggestions),
  newSuggestion: (state, suggestion) => {
    state.suggestions.push(suggestion)
  },
  deleteSuggestion: (state, id) => {
    state.suggestions = state.suggestions.filter(suggestion => suggestion.id !== id)
  },
  updateSuggestion: (state, updSuggestion) => {
    const index = state.suggestions.findIndex(suggestion => suggestion.id === updSuggestion.id);
    if (index !== -1) {
      state.suggestions.splice(index, 1, updSuggestion);
    }
  }
};

export default {
  state,
  getters,
  actions,
  mutations
}
