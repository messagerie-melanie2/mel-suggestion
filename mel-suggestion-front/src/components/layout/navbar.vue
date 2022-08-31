<template>
  <nav class="px-2 pt-3 md:pt-0 bg-white border-gray-200 dark:bg-dark-blue dark:border-gray-700">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <a href="#" class="flex items-center">
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Suggestions</span>
      </a>
      <div class="block w-auto" id="mobile-menu">
        <ul
          class="p-4 bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white dark:bg-dark-blue md:dark:border-dark-blue">
          <li>
            <div v-if="$no_auth" class="pt-2">
              <a href="https://roundcube.ida.melanie2.com/suggestiondev/index.php" id="dropdownNavbarLink"
                data-dropdown-toggle="dropdownNavbar"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Se
                connecter</a>
            </div>
            <div v-if="!$no_auth && $user.origin == 'google'" class="flex justify-between">
              <div
                class="flex justify-between items-center py-2 pr-4 pl-3 w-full font-medium text-gray-700 rounded md:hover:bg-transparent md:border-0 md:p-0 md:w-auto dark:text-gray-400 dark:hover:text-white dark:focus:text-white dark:border-gray-700">
                <!-- CHANGE TO DYNAMIC DATA -->
                <img :src="$user.picture" referrerpolicy="no-referrer" class="rounded-full" width="40">
                <span class="ml-3 dark:text-white">{{ $user.name }}</span>
              </div>
              <button @click="disconnect">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="ml-8 w-5 h-5">
                  <path
                    d="M160 416H96c-17.67 0-32-14.33-32-32V128c0-17.67 14.33-32 32-32h64c17.67 0 32-14.33 32-32S177.7 32 160 32H96C42.98 32 0 74.98 0 128v256c0 53.02 42.98 96 96 96h64c17.67 0 32-14.33 32-32S177.7 416 160 416zM502.6 233.4l-128-128c-12.51-12.51-32.76-12.49-45.25 0c-12.5 12.5-12.5 32.75 0 45.25L402.8 224H192C174.3 224 160 238.3 160 256s14.31 32 32 32h210.8l-73.38 73.38c-12.5 12.5-12.5 32.75 0 45.25s32.75 12.5 45.25 0l128-128C515.1 266.1 515.1 245.9 502.6 233.4z" />
                </svg>
              </button>
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

<style scoped>
.dark svg {
  filter: brightness(0) invert(1);
}

.dark svg:hover {
  filter: invert(69%) sepia(75%) saturate(408%) hue-rotate(184deg) brightness(100%) contrast(82%);
}
</style>