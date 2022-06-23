import Vue from 'vue'
import App from './App.vue'
import axiosClient from './axios';
import axios from 'axios';
import Toast from "vue-toastification";
import store from './store'
import "vue-toastification/dist/index.css";
import './index.css'


Vue.config.productionTip = false
Vue.use(Toast, { position: "bottom-right" });
Vue.prototype.$axios = axios;
Vue.prototype.$moderator = false;


new Vue({
  store,
  beforeCreate: 
  checkTheme, 
  function () {
    axiosClient
      .get("moderator")
      .then((response) => {
        Vue.prototype.$moderator = response.data
      })
  },
  render: h => h(App),
}).$mount('#app')

function checkTheme() {
  Vue.prototype.$darkTheme = false;

  const element = document.querySelector("html");
  if (element.classList.contains('dark-mode')) {
    Vue.prototype.$darkTheme = true;
  }
}