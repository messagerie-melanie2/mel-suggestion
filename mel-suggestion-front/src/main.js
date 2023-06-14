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

new Vue({
  store,
  beforeCreate: function () {
    window.addEventListener('message', (arg) => {
      if (arg.data == "colorMode") {
        getTheme();
      }
    })
    getTheme();
    Vue.prototype.$suggestionUrl = window.location.origin + window.location.pathname + '#suggestionId';
    Vue.prototype.$hasScrolled = false;
    Vue.prototype.$searchId = location.hash.replace('#','');
    if (window !== top) {
      top.postMessage('suggestion.app.url', '*');
      window.addEventListener('message', (arg) => {
        if (arg.data.type == "suggestionUrl") {
          Vue.prototype.$suggestionUrl = arg.data.value;
        }
      })
    }

  },
  render: h => h(App),
}).$mount('#app')


function getTheme() {
  let colorName;
  let color = getCookie('colorMode'); 
  if (color === null) {
    color = window.matchMedia('(prefers-color-scheme: dark)')
  }
  else {
    color = color === 'dark';
  }
  colorName = color ? 'dark' : 'light';

  Vue.prototype.$darkTheme = color;
  document.getElementsByTagName("html")[0].setAttribute('class', colorName);
} 

function getCookie(name)
{
  var dc = document.cookie,
    prefix = name + "=",
    begin = dc.indexOf("; " + prefix);

  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0)
      return null;
  }
  else {
    begin += 2;
  }

  var end = dc.indexOf(";", begin);
  if (end == -1)
    end = dc.length;

  return unescape(dc.substring(begin + prefix.length, end));
}