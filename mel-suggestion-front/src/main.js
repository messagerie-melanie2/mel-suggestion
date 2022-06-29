import Vue from 'vue'
import App from './App.vue'
import axiosClient from './axios';
import axios from 'axios';
import Toast from "vue-toastification";
import VueCookies from 'vue-cookies';
import store from './store'
import "vue-toastification/dist/index.css";
import './index.css'


Vue.config.productionTip = false
Vue.use(Toast, { position: "bottom-right" });
Vue.use(VueCookies);
Vue.prototype.$axios = axios;
Vue.prototype.$moderator = false;


new Vue({
  store,
  beforeCreate:
    function () {

      Vue.prototype.$darkTheme = this.$cookies.get("colorMode") == "dark" ? true : false;
      document.getElementsByTagName("html")[0].setAttribute( 'class', this.$cookies.get("colorMode") );


      listenCookieChange()
      axiosClient
        .get("moderator")
        .then((response) => {
          Vue.prototype.$moderator = response.data
        })
    },
  render: h => h(App),
}).$mount('#app')


function listenCookieChange(interval = 1000) {
  let lastCookie = document.cookie;
  setInterval(() => {
    let cookie = document.cookie;
    if (cookie !== lastCookie) {
      let value = cookie.split('=');
      if (value[0] == "colorMode") {
        try {
          Vue.prototype.$darkTheme = value[1] == "dark" ? true : false;
          document.getElementsByTagName("html")[0].setAttribute( 'class', value[1] );
        } finally {
          lastCookie = cookie;
        }
      }
    }
  }, interval);
}