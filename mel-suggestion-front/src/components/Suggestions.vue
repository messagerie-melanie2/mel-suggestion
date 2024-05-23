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
          <div v-for="suggestion in localSuggestions" :key="suggestion.id">
            <Suggestion :suggestion="suggestion" />
          </div>
        </div>
      </tbody>
    </table>
    <div v-if="search.length >= 3 || !filteredSuggestions.length">
      <div v-if="$no_auth" class="text-center mt-3">
        <span class="text-red-600 dark:text-red-400">Vous devez être connecté pour publier une suggestion</span>
      </div>
      <div v-else>
        <div class="flex justify-center dark:text-title-blue">
          <p v-show="!filteredSuggestions.length" class="my-3">Aucun résultat</p>
        </div>
        <div class="flex justify-center">
          <button @click="showCreateSuggestion"
            class="text-gray-900 dark:text-light-yellow bg-white dark:bg-light-blue border border-gray-300 dark:border-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue rounded-md text-sm px-5 py-2.5 mr-2 mb-2 mt-2">Créer
            une suggestion</button>
        </div>
        <div v-show="create">
          <CreateSuggestion :titleprops="search" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Suggestion from "./Suggestion";
import CreateSuggestion from "./CreateSuggestion";
import unaccent from "unaccent";
import { mapGetters } from "vuex";
import { synonymsArray } from "@/dictionary"; // Importer le dictionnaire et les synonymes

function normalizeString(str) {
  return unaccent(str.toLowerCase()).replace(/s\b|x\b/g, '');
}

function levenshteinDistance(a, b) {
  const matrix = Array.from({ length: b.length + 1 }, () => []);

  for (let i = 0; i <= b.length; i++) {
    matrix[i][0] = i;
  }
  for (let j = 0; j <= a.length; j++) {
    matrix[0][j] = j;
  }

  for (let i = 1; i <= b.length; i++) {
    for (let j = 1; j <= a.length; j++) {
      const cost = b.charAt(i - 1) === a.charAt(j - 1) ? 0 : 1;
      matrix[i][j] = Math.min(
        matrix[i - 1][j] + 1,
        matrix[i][j - 1] + 1,
        matrix[i - 1][j - 1] + cost
      );
    }
  }

  return matrix[b.length][a.length];
}

function splitWords(input) {
  return input.split(/\s+/).map(word => word.trim()).filter(word => word.length > 0);
}

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
      sortBy: 'votes_up',
      sortDirection: 'desc',
      localSuggestions: this.suggestions,
      validateOnly: false,
      refusedSuggestion: false
    };
  },
  mounted() {
    this.$root.$on('sort-suggestion', (sortBy, validateOnly, refusedSuggestion) => {
      this.sort(sortBy, validateOnly, refusedSuggestion);
      this.resetSearch();
    });
    this.$root.$on('search', (e) => {
      this.searchValue(e);
    });
    this.$root.$on('reset-search', () => {
      this.resetSearch();
    });
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
    sort(s, v, r) {
      this.sortBy = s;
      this.validateOnly = v;
      this.refusedSuggestion = r;
    },
    searchValue(s) {
      const searchNormalized = normalizeString(s);
      this.search = searchNormalized;
    },
    showCreateSuggestion() {
      this.create = !this.create;
    },
    resetSearch() {
      this.search = "";
      this.$root.$emit('reset-search-text');
    },
    calculateDistance(text, searchWords) {
      const suggestionText = normalizeString(text);
      return searchWords.reduce((totalDistance, searchWord) => {
        return totalDistance + levenshteinDistance(searchWord, suggestionText);
      }, 0);
    },

    searchWithSynonyms(searchWord) {
      for (const synonymEntry of synonymsArray) {
        const word = Object.keys(synonymEntry)[0];
        const synonymList = synonymEntry[word];
        if (word === searchWord || (Array.isArray(synonymList) && synonymList.includes(searchWord))) {
          return [word, ...(Array.isArray(synonymList) ? synonymList : [])];
        }
      }
      return [];
    },
        
    getRelatedWords(searchWord) {
      return new Set(this.searchWithSynonyms(searchWord).concat(searchWord));
    },

    searchSuggestions(searchInput) {
      const relatedWords = this.getRelatedWords(searchInput);

      const suggestionsFound = this.localSuggestions.filter(suggestion => {
        const suggestionText = normalizeString(suggestion.title + " " + suggestion.description);
        return relatedWords.some(word => suggestionText.includes(word));
      });

      return suggestionsFound;
    }
  },

  computed: {
    ...mapGetters(['allIndexes']),
    filteredSuggestions() {
      if (this.search.length >= 3) {
        const searchWords = splitWords(this.search).map(word => normalizeString(word));
        const relatedWords = searchWords.flatMap(word => [...this.getRelatedWords(word), word]);

        const suggestionsContainingSearchWords = this.localSuggestions.filter(suggestion => {
          let suggestionText = normalizeString(suggestion.title + " " + suggestion.description);
          return relatedWords.every(word => suggestionText.includes(word));
        });

        const suggestionsContainingSomeSearchWords = this.localSuggestions.filter(suggestion => {
          let suggestionText = normalizeString(suggestion.title + " " + suggestion.description);
          return relatedWords.some(word => suggestionText.includes(word)) && !suggestionsContainingSearchWords.includes(suggestion);
        });

        suggestionsContainingSearchWords.sort((a, b) => {
          const aDistance = this.calculateDistance(a.title + " " + a.description, relatedWords);
          const bDistance = this.calculateDistance(b.title + " " + b.description, relatedWords);
          return aDistance - bDistance;
        });

        const remainingSuggestions = suggestionsContainingSomeSearchWords.sort((a, b) => {
          const aDistance = this.calculateDistance(a.title + " " + a.description, relatedWords);
          const bDistance = this.calculateDistance(b.title + " " + b.description, relatedWords);
          return aDistance - bDistance;
        });

        return [...suggestionsContainingSearchWords, ...remainingSuggestions];
      } else {
        return this.localSuggestions;
      }
    }
  },
  components: {
    Suggestion,
    CreateSuggestion
  },
};
</script>
