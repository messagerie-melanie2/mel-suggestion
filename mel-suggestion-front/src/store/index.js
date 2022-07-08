import Vuex from "vuex";
import Vue from "vue";
import searchPlugin from 'vuex-search';
import state from './modules/suggestions';
import text from './modules/text';
// import state from './modules/contact';

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
  modules: {
    state,
    text,
  },
  plugins: [
    searchPlugin({
      resources: {
        suggestion: {
          index: ['title', 'description'],
          getter: state => state.state.suggestions.map(elem => (
            {
              id: elem.id,
              title: elem.title.replace(/(<([^>]+)>)/gi, ""),
              description: elem.description.replace(/(<([^>]+)>)/gi, ""),
            }
          )),
            watch: { delay: 500 },
        },
      },
    }),
  ],
});