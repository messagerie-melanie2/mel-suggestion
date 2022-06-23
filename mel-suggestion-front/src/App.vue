<template>
  <div>
    <h1 v-if="$moderator">Modérateur</h1>

    <body class="flex items-center justify-center" :class="isDarkTheme ? 'dark-mode-suggestion' : ''">
      <div class="w-full max-w-4xl px-4">
        <Header :title="allText.title" />
        <div class="border rounded-lg border pb-6 border-gray-300">
          <div class="flex items-center border-b border-gray-300 justify-between px-6 py-3">
            <SortingButton :darkTheme="isDarkTheme" />
            <Search />
          </div>
          <div>
            <section v-if="errored" class="flex justify-center my-3">
              <p>Une erreur s'est produite lors du chargement des données</p>
            </section>
            <section v-else>
              <div v-if="loadingStatus">
                <Preloader color="gray" />
              </div>
              <div v-else>
                <Suggestions :suggestions="allSuggestions" :darkTheme="isDarkTheme"/>
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
      suggestions: [],
      loading: false,
      errored: false,
      title: "Module de suggestion"
    };
  },
  created() {
    this.fetchSuggestions(),
      this.fetchText(),
      this.checkTheme()
  },
  methods: {
    ...mapActions(['fetchSuggestions', 'fetchText', 'checkTheme']),
  },
  components: {
    Header,
    SortingButton,
    Search,
    Suggestions,
    Preloader
  },
  computed: mapGetters(['allSuggestions', 'loadingStatus', 'allText', 'isDarkTheme']),
};
</script>


<style>
/* Css for Dark Theme */
.dark-mode-suggestion h1,
.dark-mode-suggestion label,
.dark-mode-suggestion .ql-picker-label::before,
.dark-mode-suggestion p {
  color: #96b9e7;
}

.dark-mode-suggestion .border-gray-300,
.dark-mode-suggestion input {
  border-color: #E1C58F;
  color: #E1C58F;
}

.dark-mode-suggestion .bg-white {
  background-color: transparent;
}

.dark-mode-suggestion .bg-gray-200 {
  background-color: #E1C58F !important;
  color: black !important;
}

.ql-toolbar.ql-snow {
  border-radius: 0.25rem 0.25rem 0 0;
}
.ql-container.ql-snow {
  border-radius: 0 0 0.25rem 0.25rem;
}

.dark-mode-suggestion .ql-toolbar.ql-snow {
  border-color:#E1C58F;
}
.dark-mode-suggestion .ql-container.ql-snow {
  border-color:#E1C58F;
}
.dark-mode-suggestion svg {
  filter: invert(69%) sepia(75%) saturate(408%) hue-rotate(184deg) brightness(100%) contrast(82%)
}
</style>