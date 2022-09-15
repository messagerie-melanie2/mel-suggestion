<template>
  <nav class="px-2 pt-4 md:pt-0 bg-white border-gray-200 dark:bg-dark-blue dark:border-gray-700">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <a href="#" class="flex items-center">
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">{{title ? title : 'Suggestion'}}</span>
      </a>
      <button data-collapse-toggle="mobile-menu" type="button" @click="isMobile = !isMobile"
        class="inline-flex justify-center items-center ml-3 text-gray-400 rounded-lg md:hidden hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-500"
        aria-controls="mobile-menu-2" aria-expanded="false">
        <span class="sr-only">Ouvrir le menu principal</span>

        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd"
            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
            clip-rule="evenodd"></path>
        </svg>
      </button>
      <div :class="[isMobile ? 'block' : 'hidden']" class="w-full md:block md:w-auto" id="mobile-menu">
        <ul
          class="flex p-4 mt-4 bg-gray-50 rounded-lg border border-gray-100 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-dark-blue dark:border-gray-700">
          <li>
            <div v-if="$no_auth" class="pt-2">
              <a href="https://roundcube.ida.melanie2.com/suggestiondev/index.php" id="dropdownNavbarLink"
                data-dropdown-toggle="dropdownNavbar"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Se
                connecter</a>
            </div>
            <div v-if="!$no_auth">
              <button @click="isHidden = !isHidden"
                class="flex justify-between items-center py-2 pr-4 pl-3 w-full font-medium text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-gray-400 dark:hover:text-white dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">

                <img v-show="$user.picture" :src="$user.picture" referrerpolicy="no-referrer" class="rounded-full"
                  width="40">
                <span class="ml-3" :class="[!$user.picture ? 'mt-3' : '']">{{ $user.name }}</span>
                <i v-if="isHidden" class="fa-solid fa-chevron-down ml-2 w-3 h-3"
                  :class="[!$user.picture ? 'mt-3' : '']"></i>
                <i v-else class="fa-solid fa-chevron-up ml-2 w-3 h-3" :class="[!$user.picture ? 'mt-3' : '']"></i>
              </button>
              <!-- Dropdown menu -->
              <div id="dropdownNavbar"
                class="z-10 w-44 font-normal bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                :class="[isHidden ? 'hidden' : 'block']"
                style="position: absolute;  margin: 0px; transform: translate(0px, 10px);">
                <div class="py-1">
                  <a href="#" @click="disconnect"
                    class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">Déconnexion</a>
                </div>
              </div>
            </div>
          </li>
          <button id="theme-toggle" data-tooltip-target="tooltip-toggle" type="button" @click="changeTheme"
            class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
            <svg v-if="!darkTheme" aria-hidden="true" id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor"
              viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg v-else aria-hidden="true" id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor"
              viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
          </button>

        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import axiosClient from '../../axios';

export default {
  name: "Navbar",
  data() {
    return {
      isHidden: true,
      isMobile: false,
      darkTheme: this.$darkTheme,
    }
  },
  props: {
    title: String,
  },
  mounted() {
      document.title = this.title;
  },
  methods: {
    disconnect() {
      axiosClient.get(`disconnect`)
        .then(() => {
          window.location.reload();
        })
        .catch((error) => {
          console.log(error);
        })
    },
    changeTheme() {
      let color = JSON.parse(localStorage.getItem('colorMode')) == "dark" ? '"light"' : '"dark"'
      this.darkTheme = !this.darkTheme;
      localStorage.setItem('colorMode', color);
      window.postMessage('colorMode', '*');
    }
  }
};

</script>
