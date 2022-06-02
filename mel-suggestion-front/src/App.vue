<template>
  <div>
    <h1 v-if="$moderator">Modérateur</h1>

    <body class="flex items-center justify-center py-8">
      <div class="w-full max-w-4xl px-4">
        <Header :title="allText.title" />
        <div class="border rounded-lg border pb-6 border-gray-300">
          <div class="flex items-center border-b border-gray-300 justify-between px-6 py-3">
            <SortingButton />
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
                <Suggestions :suggestions="allSuggestions" />
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
    ...mapActions(['fetchSuggestions','fetchText']),
  },
  components: {
    Header,
    SortingButton,
    Search,
    Suggestions,
    Preloader
  },
  computed: mapGetters(['allSuggestions', 'loadingStatus','allText']),
};
</script>