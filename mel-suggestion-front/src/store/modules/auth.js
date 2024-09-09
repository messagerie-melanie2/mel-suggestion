import axiosClient from '../../axios';

const state = {
  user: null,
  isAuthenticated: false,
  oidcSettings: null,
  loginOperators: [],
  loginOperatorCredential: [],
};

const getters = {
  isAuthenticated: (state) => state.isAuthenticated,
  user: (state) => state.user,
  oidcSettings: (state) => state.oidcSettings,
  loginOperators: (state) => state.loginOperators,
};

const actions = {
  //Récupère les différents opérateurs de connexion défini dans le fichier d'environnement (google, microsoft, etc...)
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

  //Récupère les information de l'opérateur demandé
  async initOidc({ commit }) {
    return axiosClient.get(`login/operator/${connector}`)
    .then((response) => {
      const settings = response.json();
      commit('setOidcSettings', settings);

      // Initialiser le UserManager avec les paramètres OIDC
      const userManager = new UserManager({
        ...settings,
        userStore: new WebStorageStateStore({ store: window.localStorage })
      });

      commit('setUserManager', userManager);
    })
    .catch((error) => {
      console.error('OIDC Initialization Error:', error);
      this._vm.$toast.error("Erreur lors de la récupération des moyens de connexion");
    });
  },

  async login({ state }) {
    try {
      if (state.oidcSettings) {
        await state.userManager.signinRedirect();
      }
    } catch (error) {
      console.error('Login Error:', error);
    }
  },

  async handleCallback({ commit, state }) {
    try {
      const user = await state.userManager.signinRedirectCallback();
      commit('setUser', user);
      commit('setIsAuthenticated', true);
    } catch (error) {
      console.error('Callback Handling Error:', error);
    }
  },

  async logout({ state, commit }) {
    try {
      await state.userManager.signoutRedirect();
      commit('setUser', null);
      commit('setIsAuthenticated', false);
    } catch (error) {
      console.error('Logout Error:', error);
    }
  },
};

const mutations = {
  setOidcSettings(state, settings) {
    state.oidcSettings = settings;
  },
  setUserManager(state, userManager) {
    state.userManager = userManager;
  },
  setUser(state, user) {
    state.user = user;
  },
  setIsAuthenticated(state, isAuthenticated) {
    state.isAuthenticated = isAuthenticated;
  },
  setLoginOperators(state, loginOperators) {
    state.loginOperators = loginOperators
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};