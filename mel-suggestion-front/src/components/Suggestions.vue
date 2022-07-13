<template>
  <div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left">
      <tbody>
        <div v-if="search.length >= 3 || !filteredSuggestions.length">
          <div v-for="suggestion in filteredSuggestions" :key="suggestion.id">
            <Suggestion :suggestion="suggestion" />
          </div>
        </div>
        <div v-else>
          <div v-for="suggestion in sortedSuggestions" :key="suggestion.id">
            <Suggestion :suggestion="suggestion" />
          </div>
        </div>
      </tbody>
    </table>
    <div v-if="search.length >= 3 || !filteredSuggestions.length">
      <div class="flex justify-center dark:text-title-blue">
        <p v-show="!filteredSuggestions.length" class="my-3">Aucun résultat</p>
      </div>
      <div class="flex justify-center">
        <button @click="showCreateSuggestion"
          class="text-gray-900 dark:text-light-yellow bg-white dark:bg-light-blue border border-gray-300 dark:border-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue rounded-md text-sm px-5 py-2.5 mr-2 mb-2 mt-2">Créer
          une suggestion</button>
      </div>
      <div v-show="create">
        <CreateSuggestion :titleprops=search />
      </div>
    </div>
  </div>
</template>

<script>
import Suggestion from "./Suggestion";
import CreateSuggestion from "./CreateSuggestion";

export default {
  name: "Suggestions",
  props: {
    suggestions: Array,
    index: Array
  },
  data() {
    return {
      create: '',
      search: '',
      sortBy: 'nb_votes',
      sortDirection: 'desc',
      localSuggestions: this.suggestions,
      localIndex: this.index,
      validateOnly: false,
    }
  },
  mounted() {
    this.$root.$on('sort-suggestion', (sortBy, validateOnly) => {
      this.sort(sortBy, validateOnly)
      this.resetSearch();
    }),
      this.$root.$on('search', (e) => {
        this.searchValue(e)
      }),
      this.$root.$on('reset-search', () => {
        this.resetSearch()
      })
  },
  watch: {
    suggestions: {
      handler(newValue) {
        this.localSuggestions = newValue;
      },
      deep: true
    }
  },
  methods: {
    sort(s, v) {
      this.sortBy = s;
      this.validateOnly = v;
    },
    searchValue(s) {
      this.search = s;
    },
    showCreateSuggestion() {
      this.create = !this.create
    },
    resetSearch() {
      this.search = "";
      this.$root.$emit('reset-search-text');
    }
  },
  computed: {
    sortedSuggestions() {
       let filteredlocalSuggestions = this.localSuggestions.slice(0).filter(suggestion => {
        if (this.validateOnly) {
          return suggestion.state.toLowerCase().includes("validate")
        }
        else {
          return !suggestion.state.toLowerCase().includes("validate")
        }
      });

      return filteredlocalSuggestions.slice(0).sort((p1, p2) => {
        let modifier = 1;
        if (this.sortDirection === 'desc') modifier = -1;
        if (p1[this.sortBy] < p2[this.sortBy]) return -1 * modifier; if (p1[this.sortBy] > p2[this.sortBy]) return 1 * modifier;
        return 0;
      });
    },
    filteredSuggestions() {
      let results = {};
      let searchResults = [];

      if (this.search.length > 2) {
        let values = this.search.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().split(' ');
        for (const word in this.localIndex) {
          for (const value of values) {
            if (value.length > 2) {
              if (word.toLowerCase().indexOf(value) !== -1) {
                for (const key of this.localIndex[word]) {
                  if (results[key]) {
                    results[key]++;
                  } else {
                    results[key] = 1;
                  }
                }
              }
            }
          }
        }
        if (Object.keys(results).length) {
          var _res = [];
          for (const key in results) {
            _res.push({ key: key, value: results[key] });
          }
          _res.sort((a, b) => (a.value < b.value) ? 1 : -1)

          var i = 0;

          for (const r of _res) {
            if (i++ > 4) {
              break;
            }
            searchResults.push(this.localSuggestions[r.key])
          }
        }
      }
      if (this.search.length <= 2 && searchResults.length == 0) {
        return this.localSuggestions;
      }
      return searchResults;
    }
  },
  components: {
    Suggestion,
    CreateSuggestion
  },
};
</script>