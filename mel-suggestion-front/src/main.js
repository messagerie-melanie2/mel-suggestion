import Vue from 'vue'
import App from './App.vue'
import axios from 'axios';
import Toast from "vue-toastification";
import store from './store'
import "vue-toastification/dist/index.css";
import './index.css'



Vue.config.productionTip = false
Vue.use(Toast, { position: "bottom-right" });
Vue.prototype.$axios = axios;
// Vue.prototype.$moderator = false;


new Vue({
  store,
  beforeCreate: function () {
    window.addEventListener('message', (arg) => {
      if (arg.data == "colorMode") {
        getTheme();
      }
    })
    getTheme();
  },
  render: h => h(App),
}).$mount('#app')


function getTheme() {
  Vue.prototype.$darkTheme = localStorage.getItem('colorMode') == "dark" ? true : false;
  document.getElementsByTagName("html")[0].setAttribute('class', JSON.parse(localStorage.getItem('colorMode')));
} 