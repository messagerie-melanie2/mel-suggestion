<template>
  <div class="relative overflow-x-auto">
    <div v-if="isLoading && isSearching" class="loading-message absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2">
      <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
      </svg>
      <span class="sr-only">Loading...</span>
      <p>Chargement des informations en cours, merci de votre patience...</p>
    </div>
    <div v-else>
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
import { mapGetters, mapActions } from "vuex";
import axiosClient from '../axios';

/**
 * Normalise une chaîne de caractères en la convertissant en minuscules,
 * en supprimant les accents, et en enlevant les 's' ou 'x' en fin de mots.
 *
 * @param {string} str - La chaîne de caractères à normaliser.
 * @return {string} La chaîne de caractères normalisée.
 *
 * @example
 * // retourne 'exampleat'
 * normalizeString('éxamPlèsâtx');
 */
function normalizeString(str) {
  return unaccent(str.toLowerCase()).replace(/s\b|x\b/g, '');
}


/**
 * Calcule la distance de Levenshtein entre deux chaînes de caractères.
 * La distance de Levenshtein est une mesure du nombre de modifications 
 * nécessaires pour transformer une chaîne en une autre.
 *
 * @param {string} a - La première chaîne de caractères.
 * @param {string} b - La deuxième chaîne de caractères.
 * @return {number} La distance de Levenshtein entre les deux chaînes de caractères.
 *
 * @example
 * // retourne 3
 * levenshteinDistance('kitten', 'sitting');
 *
 * @example
 * // retourne 2
 * levenshteinDistance('flaw', 'lawn');
 */
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

/**
 * Divise une chaîne de caractères en mots, en utilisant les espaces comme délimiteurs,
 * et retourne un tableau de mots non vides, sans espaces de début ou de fin.
 *
 * @param {string} input - La chaîne de caractères à diviser en mots.
 * @return {string[]} Un tableau contenant les mots extraits de la chaîne d'entrée.
 *
 * @example
 * // retourne ['Bonjour', 'le', 'monde']
 * splitWords('  Bonjour   le monde  ');
 */
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
      refusedSuggestion: false,
      isLoading: true, // Variable d'état pour le chargement
      isSearching: false // Variable d'état pour savoir si l'utilisateur a commencé une recherche
    };
  },
  mounted() {
    // this.loadSynonyms();
    this.fetchSynonyms();
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
    ...mapActions(['fetchSynonyms']),
    async loadSynonyms() {
      this.isLoading = true;
      try {
        const getUrl = await axiosClient.get('/synonyms');
        const url = getUrl.data;
        const response = await fetch(url);
        this.synonymsArray = await response.json();
      } catch (error) {
        console.error('Erreur lors du chargement des synonymes:', error);
      } finally {
        this.isLoading = false;
      }
    },
    sort(s, v, r) {
      this.sortBy = s;
      this.validateOnly = v;
      this.refusedSuggestion = r;
    },
    searchValue(s) {
      this.isSearching = true; // Indique que l'utilisateur a commencé une recherche
      const searchNormalized = normalizeString(s);
      this.search = s;
      this.searchNormalized = searchNormalized;
    },
    showCreateSuggestion() {
      this.create = !this.create;
    },
    resetSearch() {
      this.search = "";
      this.isSearching = false; // Réinitialiser l'état de recherche lorsque la recherche est réinitialisée
      this.$root.$emit('reset-search-text');
    },

    /**
     * Calcule les scores TF-IDF pour une liste de suggestions basée sur des mots de recherche.
     * Le score TF-IDF mesure l'importance de chaque mot de recherche dans chaque suggestion.
     *
     * @param {Object[]} suggestions - Une liste de suggestions, chaque suggestion contenant un titre, une description et un identifiant.
     * @param {string[]} searchWords - Une liste de mots de recherche pour lesquels calculer les scores TF-IDF.
     * @return {Object} Un objet où les clés sont les identifiants des suggestions et les valeurs sont les scores TF-IDF correspondants.
     *
     * @example
     * const suggestions = [
     *   { id: 1, title: "Énergies renouvelables", description: "Guide complet sur les énergies renouvelables" },
     *   { id: 2, title: "Avantages des énergies renouvelables", description: "Les avantages économiques et environnementaux des énergies renouvelables" }
     * ];
     * const searchWords = ["énergies", "renouvelables"];
     * // retourne { '1': <score_tf_idf_pour_énergies_renouvelables>, '2': <score_tf_idf_pour_renouvelables> }
     * calculateTfIdfScores(suggestions, searchWords);
     */
    calculateTfIdfScores(suggestions, searchWords) {
    const tfIdfScores = {};

    const wordCount = {};
    const documentCount = {};

    suggestions.forEach(suggestion => {
      const suggestionText = normalizeString(suggestion.title + " " + suggestion.description);
      const words = splitWords(suggestionText);
      const wordSet = new Set(words);

      words.forEach(word => {
        if (!wordCount[word]) {
          wordCount[word] = 0;
        }
        wordCount[word]++;
      });

      wordSet.forEach(word => {
        if (!documentCount[word]) {
          documentCount[word] = 0;
        }
        documentCount[word]++;
      });
    });

    const totalDocuments = suggestions.length;

    suggestions.forEach(suggestion => {
      const suggestionText = normalizeString(suggestion.title + " " + suggestion.description);
      const words = splitWords(suggestionText);
      let tfIdf = 0;

      searchWords.forEach(searchWord => {
        const tf = words.filter(word => word === searchWord).length / words.length;
        const idf = Math.log(totalDocuments / (documentCount[searchWord] || 1));
        tfIdf += tf * idf;
      });

      tfIdfScores[suggestion.id] = tfIdf;
    });

    return tfIdfScores;
  },

    /**
     * Calcule la distance entre un texte et une liste de mots de recherche, en utilisant la distance de Levenshtein.
     * La distance de Levenshtein mesure le nombre de modifications nécessaires pour transformer un mot en un autre.
     * Cette fonction accorde une pondération plus élevée aux mots de recherche originaux.
     *
     * @param {string} text - Le texte à évaluer.
     * @param {string[]} searchWords - Les mots de recherche à comparer avec le texte.
     * @param {string[]} originalSearchWords - Les mots de recherche originaux, utilisés pour pondérer le calcul de distance.
     * @return {number} La distance cumulée entre le texte et les mots de recherche, pondérée par l'importance des mots de recherche originaux.
     *
     * @example
     * const text = "Un exemple de texte pour évaluer la distance.";
     * const searchWords = ["exemple", "distance"];
     * const originalSearchWords = ["exemple"];
     * // retourne la distance cumulée pondérée entre le texte et les mots de recherche
     * calculateDistance(text, searchWords, originalSearchWords);
     */
    calculateDistance(text, searchWords, originalSearchWords) {
      const suggestionText = normalizeString(text);
      return searchWords.reduce((totalDistance, searchWord) => {
        // Pondération des mots recherchés
        const weight = originalSearchWords.includes(searchWord) ? 2 : 1; // Score plus élevé pour les mots recherchés
        return totalDistance + weight * levenshteinDistance(searchWord, suggestionText);
      }, 0);
    },

    /**
     * Recherche les synonymes d'un mot donné dans une liste prédéfinie de synonymes.
     *
     * @param {string} searchWord - Le mot pour lequel rechercher des synonymes.
     * @return {string[]} Une liste contenant le mot de recherche et ses synonymes, s'ils existent.
     *
     * @example
     * const searchWord = "voiture";
     * // retourne ["voiture", "automobile", "véhicule"]
     * searchWithSynonyms(searchWord);
     */
    searchWithSynonyms(searchWord) {
      if (this.synonyms[searchWord])  {
          return [searchWord, ...(Array.isArray(this.synonyms[searchWord]) ? this.synonyms[searchWord] : [])];
      }
      return [];
    },
    
    /**
     * Récupère les mots liés à un mot donné en incluant ses synonymes.
     *
     * @param {string} searchWord - Le mot pour lequel récupérer les mots liés.
     * @return {Set<string>} Un ensemble contenant le mot de recherche et ses synonymes.
     *
     * @example
     * const searchWord = "voiture";
     * // retourne un ensemble contenant ["voiture", "automobile", "véhicule"]
     * getRelatedWords(searchWord);
     */
    getRelatedWords(searchWord) {
      return new Set(this.searchWithSynonyms(searchWord).concat(searchWord));
    },

    /**
     * Recherche des suggestions locales basées sur une entrée de recherche donnée.
     *
     * @param {string} searchInput - L'entrée de recherche pour laquelle trouver des suggestions.
     * @return {Object[]} Un tableau contenant les suggestions locales correspondant à l'entrée de recherche.
     *
     * @example
     * const searchInput = "énergie solaire";
     * // retourne un tableau de suggestions locales liées à l'énergie solaire
     * searchSuggestions(searchInput);
     */
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
    ...mapGetters(['allIndexes', 'Synonyms']),

    sortedSuggestions() {
    const acceptedState = ['vote', 'moderate'];

    let filteredLocalSuggestions = this.localSuggestions.slice(0).filter(suggestion => {
      if (suggestion.id == this.$searchId && suggestion.state == 'validate' && !this.$hasScrolled) {
        setTimeout(() => {
          this.$root.$emit('sort-suggestion-validate');
        }, 50);
      }
      if (this.refusedSuggestion) {
        return suggestion.state.toLowerCase().includes("refused");
      }
      if (this.validateOnly) {
        return suggestion.state.toLowerCase().includes("validate");
      } else {
        return acceptedState.includes(suggestion.state.toLowerCase())
      }
    });

    // Trie en fonction de la propriété votes_up
    if (this.sortBy === 'votes_up') {
      filteredLocalSuggestions.sort((suggestion1, suggestion2) => {
        if (this.sortDirection === 'desc') {
          return suggestion2.votes_up - suggestion1.votes_up;
        } else {
          return suggestion1.votes_up - suggestion2.votes_up;
        }
      });
    }

    // Trie en fonction de la propriété updated_at
    if (this.sortBy === 'updated_at') {
      filteredLocalSuggestions.sort((suggestion1, suggestion2) => {
        const date1 = new Date(suggestion1.updated_at);
        const date2 = new Date(suggestion2.updated_at);
        if (this.sortDirection === 'desc') {
          return date2 - date1;
        } else {
          return date1 - date2;
        }
      });
    }

    return filteredLocalSuggestions;
  },


    /**
     * Filtre et trie les suggestions locales en fonction de l'entrée de recherche actuelle.
     * Si l'entrée de recherche contient au moins 3 caractères, les suggestions sont filtrées
     * et triées en fonction de leur pertinence par rapport aux mots de recherche et à leurs synonymes.
     * Sinon, toutes les suggestions locales sont renvoyées.
     *
     * @return {Object[]} Un tableau contenant les suggestions locales filtrées et triées.
     *
     * @example
     * // retourne un tableau de suggestions locales filtrées et triées en fonction de l'entrée de recherche actuelle
     * filteredSuggestions();
     */
    filteredSuggestions() {
    if (this.search.length >= 3) {
      const searchWords = splitWords(this.searchNormalized).map(word => normalizeString(word));
      const relatedWords = searchWords.flatMap(word => [...this.getRelatedWords(word), word]);

      // Calculer TF-IDF pour chaque suggestion
      const tfIdfScores = this.calculateTfIdfScores(this.localSuggestions, relatedWords);

      // Suggestions contenant tous les mots recherchés
      const suggestionsContainingSearchWords = this.localSuggestions.filter(suggestion => {
        const suggestionText = normalizeString(suggestion.title + " " + suggestion.description.replace(/<img[^>]*>/g,""));
        return searchWords.every(word => suggestionText.includes(word));
      });

      // Suggestions contenant au moins un des mots recherchés ou leurs synonymes
      const suggestionsContainingSomeSearchWords = this.localSuggestions.filter(suggestion => {
        const suggestionText = normalizeString(suggestion.title + " " + suggestion.description.replace(/<img[^>]*>/g,""));
        return relatedWords.some(word => suggestionText.includes(word)) && !suggestionsContainingSearchWords.includes(suggestion);
      });

      // Trier les suggestions contenant tous les mots recherchés
      suggestionsContainingSearchWords.sort((a, b) => {
        const aDistance = this.calculateDistance(a.title + " " + a.description, relatedWords, searchWords);
        const bDistance = this.calculateDistance(b.title + " " + b.description, relatedWords, searchWords);
        return aDistance - bDistance;
      });

      // Trier les suggestions contenant certains mots recherchés ou leurs synonymes par TF-IDF puis ordre alphabétique
      suggestionsContainingSomeSearchWords.sort((a, b) => {
        const aTfIdf = tfIdfScores[a.id] || 0;
        const bTfIdf = tfIdfScores[b.id] || 0;
        if (aTfIdf === bTfIdf) {
          const aText = normalizeString(a.title + " " + a.description);
          const bText = normalizeString(b.title + " " + b.description);
          return aText.localeCompare(bText);
        }
        return bTfIdf - aTfIdf;
      });

      return [...suggestionsContainingSearchWords, ...suggestionsContainingSomeSearchWords];
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