<template>
  <nav class="px-2 pt-4 md:pt-0 bg-white border-gray-200 dark:bg-dark-blue dark:border-gray-700">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <a href="#" class="flex items-center">
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Suggestions</span>
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
          class="flex flex-col p-4 mt-4 bg-gray-50 rounded-lg border border-gray-100 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-dark-blue dark:border-gray-700">
          <li>
            <div v-if="$no_auth" class="py-4">
              <a href="https://roundcube.ida.melanie2.com/suggestiondev/index.php" id="dropdownNavbarLink"
                data-dropdown-toggle="dropdownNavbar"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Se
                connecter</a>
            </div>
            <div v-if="!$no_auth && $user.origin == 'google'">
              <button @click="isHidden = !isHidden"
                class="flex justify-between items-center py-2 pr-4 pl-3 w-full font-medium text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-gray-400 dark:hover:text-white dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
                <img :src="$user.picture"
                  referrerpolicy="no-referrer" class="rounded-full" width="40">
                <span class="ml-3">{{ $user.name }}</span>
                <svg class="ml-1 w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
                </svg>
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
    }
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
    }
  }
};

</script>