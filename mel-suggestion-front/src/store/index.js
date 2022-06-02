import Vuex from "vuex";
import Vue from "vue";
import suggestions from './modules/suggestions';
import text from './modules/text';

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
  modules: {
    suggestions,
    text
  }
});