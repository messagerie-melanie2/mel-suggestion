<template>
  <!-- Navigation Bar Component -->
  <nav class="px-2 pt-4 md:pt-2 bg-white border-gray-200 dark:bg-dark-blue dark:border-gray-700">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <div class="flex items-center select-none">
        <!-- Title Display -->
        <img src="/favicon.ico" alt="Suggestion logo" width="35">
        <span class="self-center text-lg ml-4 font-semibold whitespace-nowrap dark:text-white">{{ title ? title :
          'Suggestion' }}</span>

      </div>
      <ul
        class="flex items-center rounded-lg md:space-x-8 text-sm md:font-medium md:bg-white dark:bg-gray-800 md:dark:bg-dark-blue dark:border-gray-700">
        <li v-if="$user.origin != 'mel'">
          <button @click="isHidden = !isHidden"
            class="flex justify-between items-center p-2.5 w-full font-medium text-gray-700 rounded border-0 hover:text-blue-700 md:w-auto dark:text-gray-400 dark:hover:text-white dark:border-gray-700 dark:hover:bg-transparent focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700">
            <img v-show="$user.picture" :src="$user.picture" referrerpolicy="no-referrer" class="rounded-full"
              width="40">
            <span>{{ $user.name }}</span>
            <i v-if="isHidden" class="fas fa-chevron-down ml-2 w-3 h-3"></i>
            <i v-else class="fas fa-chevron-up ml-2 w-3 h-3"></i>
          </button>
          <div id="dropdownNavbar"
            class="z-10 w-44 font-normal bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
            :class="[isHidden ? 'hidden' : 'block']"
            style="position: absolute;  margin: 0px; transform: translate(0px, 10px);">
            <div class="py-1">
              <a href="#" @click="disconnect"
                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">Déconnexion</a>
            </div>
          </div>
        </li>
        <li v-if="$user.origin != 'mel'">
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
        </li>
        <li>
          <button @click="createSuggestion"
            class="text-gray-900 dark:text-light-yellow bg-white dark:bg-light-blue border border-gray-300 dark:border-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue rounded-md text-sm px-5 py-2.5 mr-2 mb-2 mt-2">Créer
            une suggestion</button>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import axiosClient from '../../axios';

export default {
  name: "Navbar",
  data() {
    return {
      // Initial state for mobile menu and dark theme
      isHidden: true,
      isMobile: false,
      darkTheme: this.$darkTheme,
      search: ""
    }
  },
  props: {
    // Title displayed in the navbar
    title: String,
  },
  mounted() {
    if (this.title) {
      // Set document title if title prop is provided
      document.title = this.title;
    }
  },
  methods: {
    createSuggestion() {
      this.$root.$emit('create-suggestion');
    },
    // Method to disconnect user
    disconnect() {
      axiosClient.get(`disconnect`)
        .then(() => {
          window.location.reload();
        })
        .catch((error) => {
          console.log(error);
        })
    },
    // Method to toggle between light and dark theme
    changeTheme() {
      // Determine current color mode and switch to the opposite
      let color = this.getCookie('colorMode') == "light" ? 'dark' : 'light'
      document.cookie = 'colorMode = ' + color;
      this.darkTheme = !this.darkTheme;

      // Inform other components about the theme change
      window.postMessage('colorMode', '*');
    },
    getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
      return null;
    },
  }
};

</script>
