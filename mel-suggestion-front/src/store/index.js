import Vuex from "vuex";
import Vue from "vue";
import suggestions from './modules/suggestions';
import text from './modules/text';
import dark from './modules/dark_mode';

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
  modules: {
    suggestions,
    text,
    dark
  }
});