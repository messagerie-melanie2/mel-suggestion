import Vue from 'vue'
import App from './App.vue'
import axios from 'axios';
import Toast from "vue-toastification";
import store from './store'
import "vue-toastification/dist/index.css";
import './index.css'

window.addEventListener('colorMode', getTheme());

getTheme();

Vue.config.productionTip = false
Vue.use(Toast, { position: "bottom-right" });
Vue.prototype.$axios = axios;
// Vue.prototype.$moderator = false;


new Vue({
  store,
  render: h => h(App),
}).$mount('#app')


function getTheme() {
  Vue.prototype.$darkTheme = localStorage.getItem('colorMode') == "dark" ? true : false;
  document.getElementsByTagName("html")[0].setAttribute('class', localStorage.getItem('colorMode'));

} 