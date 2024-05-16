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
import dictionary from "@/dictionary"; // Importer le dictionnaire

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
  let result = '';
  let temp = '';

  for (let i = 0; i < input.length; i++) {
    temp += input[i];
    if (dictionary.includes(temp.toLowerCase())) {
      result += temp + ' ';
      temp = '';
    }
  }

  if (temp) {
    result += temp;  // Ajouter le reste de la chaîne qui n'a pas été matché
  }

  return result.trim();
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
      refusedSuggestion: false,
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
      this.search = s;
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
  },
  computed: {
    ...mapGetters(['allIndexes']),
    sortedSuggestions() {
      const acceptedState = ['vote', 'moderate'];
      let filteredlocalSuggestions = this.localSuggestions.filter(suggestion => {
        if (this.refusedSuggestion) {
          return suggestion.state.toLowerCase().includes("refused");
        }
        if (this.validateOnly) {
          return suggestion.state.toLowerCase().includes("validate");
        }
        return acceptedState.includes(suggestion.state.toLowerCase());
      });

      if (!this.refusedSuggestion && !this.refusedSuggestion) {
        let moderateSuggestions = filteredlocalSuggestions.filter(suggestion => {
          return suggestion.state.toLowerCase().includes("moderate");
        });

        let sortedModerate = moderateSuggestions.sort((p1, p2) => {
          let modifier = this.sortDirection === 'desc' ? -1 : 1;
          if (p1[this.sortBy] < p2[this.sortBy]) return -1 * modifier;
          if (p1[this.sortBy] > p2[this.sortBy]) return 1 * modifier;
          return 0;
        });

        let otherSuggestions = filteredlocalSuggestions.filter(suggestion => {
          return !suggestion.state.toLowerCase().includes("moderate");
        });

        let sortedOther = otherSuggestions.sort((p1, p2) => {
          let modifier = this.sortDirection === 'desc' ? -1 : 1;
          if (p1[this.sortBy] < p2[this.sortBy]) return -1 * modifier;
          if (p1[this.sortBy] > p2[this.sortBy]) return 1 * modifier;
          return 0;
        });

        return sortedModerate.concat(sortedOther);
      } else {
        return filteredlocalSuggestions.sort((p1, p2) => {
          let modifier = this.sortDirection === 'desc' ? -1 : 1;
          if (p1[this.sortBy] < p2[this.sortBy]) return -1 * modifier;
          if (p1[this.sortBy] > p2[this.sortBy]) return 1 * modifier;
          return 0;
        });
      }
    },
    filteredSuggestions() {
      if (this.search.length >= 3) {
        // Utiliser une expression régulière pour diviser les mots tout en tenant compte des mots accolés sans espace
        const searchWords = splitWords(this.search).split(' ').map(word => normalizeString(word));

        // Filtrer les suggestions contenant exactement les mots de recherche
        const suggestionsContainingSearchWords = this.localSuggestions.filter(suggestion => {
          let suggestionText = normalizeString(suggestion.title + " " + suggestion.description);

          return searchWords.every(word => suggestionText.includes(word));
        });

        // Filtrer les suggestions contenant au moins un des mots de recherche, mais pas tous
        const suggestionsContainingSomeSearchWords = this.localSuggestions.filter(suggestion => {
          let suggestionText = normalizeString(suggestion.title + " " + suggestion.description);

          return searchWords.some(word => suggestionText.includes(word)) && !suggestionsContainingSearchWords.includes(suggestion);
        });

        // Trier les suggestions contenant exactement les mots de recherche en premier
        suggestionsContainingSearchWords.sort((a, b) => {
          const aDistance = this.calculateDistance(a.title + " " + a.description, searchWords);
          const bDistance = this.calculateDistance(b.title + " " + b.description, searchWords);
          return aDistance - bDistance;
        });

        // Trier les autres suggestions par distance de Levenshtein
        const remainingSuggestions = suggestionsContainingSomeSearchWords.sort((a, b) => {
          const aDistance = this.calculateDistance(a.title + " " + a.description, searchWords);
          const bDistance = this.calculateDistance(b.title + " " + b.description, searchWords);
          return aDistance - bDistance;
        });

        // Concaténer les deux ensembles triés
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