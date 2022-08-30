<template>
  <div>
    <div class="flex justify-between">
      <div></div>
      <div v-if="$no_auth">
        <nav
          class="bg-white px-2 sm:px-4 py-2.5 dark:bg-gray-900 fixed w-full z-20 top-0 left-0 border-b border-gray-200 dark:border-gray-600">
          <div class="container flex flex-wrap justify-between items-center mx-auto">
            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Suggestions</span>
            <div class="flex md:order-2">
              <button type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Se connecter</button>
            </div>
            <div class="hidden justify-between items-center w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            </div>
          </div>
        </nav>
      </div>
      <!-- <a href="https://roundcube.ida.melanie2.com/suggestiondev/index.php">Se connecter</a> -->
      <div v-if="!$no_auth && $user.origin == 'Google'" class="mr-2">
        <nav
          class="bg-white px-2 sm:px-4 py-2.5 dark:bg-gray-900 fixed w-full z-20 top-0 left-0 border-b border-gray-200 dark:border-gray-600">
          <div class="container flex flex-wrap justify-between items-center mx-auto">
            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Suggestions</span>
            <div class="flex md:order-2">
              <button type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"></button>
            </div>
            <div class="hidden justify-between items-center w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            </div>
          </div>
        </nav>
      </div>

    </div>

    <body class="flex items-center justify-center">
      <div class="w-full max-w-4xl px-4 mt-14">
        <Header :title="allText.title" />
        <div class="rounded-lg pb-6 border border-gray-300 dark:border-gray-800 bg-white dark:bg-light-blue">
          <div
            class="flex flex-wrap items-center justify-between px-6 py-3 border-b border-gray-300 dark:border-light-blue">
            <SortingButton />
            <Search />
          </div>
          <div>
            <section>
              <div v-if="loadingStatus">
                <Preloader color="gray" />
              </div>
              <div v-else>
                <Suggestions :suggestions="allSuggestions" :index="allIndexes" />
              </div>
            </section>
          </div>
        </div>
      </div>
    </body>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

import Header from "./components/Header";
import SortingButton from "./components/SortingButton";
import Search from "./components/Search";
import Suggestions from "./components/Suggestions";
import Preloader from './components/Preloader.vue'

export default {
  name: "App",
  data() {
    return {
      title: "Module de suggestion"
    };
  },
  created() {
    this.fetchSuggestions(),
      this.fetchText()
  },
  methods: {
    ...mapActions(['fetchSuggestions', 'fetchText']),
  },
  components: {
    Header,
    SortingButton,
    Search,
    Suggestions,
    Preloader
  },
  computed: mapGetters(['allSuggestions', 'allIndexes', 'loadingStatus', 'allText']),
};
</script>


<style>
.dark body {
  background-color: rgba(31, 41, 51, 100);
}

.ql-toolbar.ql-snow {
  border-radius: 0.25rem 0.25rem 0 0;
}

.ql-container.ql-snow {
  border-radius: 0 0 0.25rem 0.25rem;
}

.dark .ql-toolbar.ql-snow {
  border-color: #E1C58F;
}

.dark .ql-toolbar.ql-snow span {
  color: #96b9e7;
}

.dark .ql-container.ql-snow {
  color: #96b9e7;
  border-color: #E1C58F;
}

.dark svg {
  filter: invert(69%) sepia(75%) saturate(408%) hue-rotate(184deg) brightness(100%) contrast(82%)
}
</style>