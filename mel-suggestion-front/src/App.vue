<template>
  <div>
    <div v-if="$moderator">MODERATEUR</div>

    <body class="flex items-center justify-center">
      <div class="w-full max-w-4xl px-4">
        <Header :title="allText.title" />
        <div class="rounded-lg pb-6 border border-gray-300 dark:border-gray-800 bg-white dark:bg-light-blue">
          <div class="flex items-center justify-between px-6 py-3 border-b border-gray-300 dark:border-light-blue">
            <SortingButton />
            <Search />
          </div>
          <div>
            <section>
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