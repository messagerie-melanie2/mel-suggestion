<template>
  <div>
    <div class="inline-flex pb-0">
      <button class="text-slate-600 font-semibold px-3 rounded-l border border-gray-300 dark:border-light-yellow "  style="padding-top: 0.30rem;
padding-bottom: 0.45rem;" 
        @click="sort('nb_votes', false)" title="Trier par nombre de votes"
        :class="[sortBy === 'nb_votes' && validateOnly === false && !searching ? 'bg-gray-200 dark:bg-light-yellow dark:text-slate-800' : ' dark:text-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue']">
        <i class="far fa-star text-slate-400"></i> Top
      </button>
      <button class="text-slate-600 font-semibold px-3  border border-gray-300 dark:border-light-yellow "  style="padding-top: 0.30rem;
padding-bottom: 0.45rem;" 
        @click="sort('updated_at', false)" title="Trier par date"
        :class="[sortBy === 'updated_at' && validateOnly === false && !refusedSuggestion && !searching ? 'bg-gray-200 dark:bg-light-yellow dark:text-slate-800' : ' dark:text-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue']">
        <i class="far fa-clock text-slate-400"></i> Récentes
      </button>
      <button class="text-slate-600 font-semibold px-3  border border-gray-300 dark:border-light-yellow"  style="padding-top: 0.30rem;
padding-bottom: 0.45rem;" 
        title="Suggestions à venir" @click="sort('updated_at', true)"
        :class="[validateOnly === true && !searching ? 'bg-gray-200 dark:bg-light-yellow dark:text-slate-800' : ' dark:text-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue', !$user.moderator ? 'rounded-r' : '']">
        <i class="fas fa-list-check text-slate-400 mr-1"></i> Retenues
      </button>
      <div v-if="$user.moderator">
        <button
          class="text-slate-600 font-semibold px-3  border border-gray-300 dark:border-light-yellow rounded-r "  style="padding-top: 0.30rem;
padding-bottom: 0.45rem;" 
          title="Suggestions refusées" @click="sort('updated_at', false, true)" 
          :class="[refusedSuggestion === true ? 'bg-gray-200 dark:bg-light-yellow dark:text-slate-800' : ' dark:text-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue']">
          <i class="fas fa-xmark text-slate-400 mr-1"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SortingButton",
  data() {
    return {
      sortBy: 'nb_votes',
      sortDirection: 'desc',
      searching: false,
      validateOnly: false,
      refusedSuggestion: false
    }
  },
  mounted() {
    this.$root.$on('sort-suggestion-active', (e) => {
      this.sort(e, false)
    }),
      this.$root.$on('search', (e) => {
        if (e.length <= 2) {
          this.searching = false;
        }
        else {
          this.searching = true
        }
      }),
      this.$root.$on('reset-search', () => {
        this.searching = false;
      })
  },
  methods: {
    sort(sorting, validateOnly, refusedSuggestion) {
      this.sortBy = sorting;
      this.validateOnly = validateOnly;
      this.refusedSuggestion = refusedSuggestion;
      this.$root.$emit('sort-suggestion', sorting, validateOnly, refusedSuggestion)
      this.searching = false;
    }
  }
};
</script>

<style>
.dark-mode-suggestion .hover:bg-gray-100 {
  background-color: #2E2E2E !important;
}
</style>