<template>
  <div>
    <body class="flex items-center justify-center" :class="$darkTheme ? 'dark-mode-suggestion' : ''">
      <div class="w-full max-w-4xl px-4">
        <Header :title="allText.title" />
        <div class="rounded-lg pb-6" :class="$darkTheme ? 'dark-background' : 'border border-gray-300'">
          <div class="flex items-center justify-between px-6 py-3"
            :class="$darkTheme ? '' : ' border-b border-gray-300'">
            <SortingButton/>
            <Search/>
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
                <Suggestions :suggestions="allSuggestions"/>
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
  computed: mapGetters(['allSuggestions', 'loadingStatus', 'allText']),
};
</script>


<style>
/* Css for Dark Theme */
.dark-mode .dark-background {
  background-color: #323F4B;
}

.dark-mode-suggestion .dark-title {
  color: #96b9e7;
  border-color: #96b9e7;
}

.dark-mode-suggestion .dark-text {
  color: #c7dfff;
}

.dark-mode-suggestion .dark-voted {
  color: rgb(17, 202, 140);
  border-color: rgb(17, 202, 140);
}

.dark-mode-suggestion h1,
.dark-mode-suggestion label,
.dark-mode-suggestion .ql-picker-label::before {
  color: #96b9e7;
}

.dark-mode-suggestion .border-gray-300,
.dark-mode-suggestion .fas.fa-magnifying-glass,
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
  border-color: #E1C58F;
}

.dark-mode-suggestion .ql-container.ql-snow {
  border-color: #E1C58F;
}

.dark-mode-suggestion svg {
  filter: invert(69%) sepia(75%) saturate(408%) hue-rotate(184deg) brightness(100%) contrast(82%)
}
</style>