<template>
  <div>
    <div class="inline-flex">
      <button class="text-slate-600 font-semibold py-2 px-3 rounded-l shadow border border-gray-300 dark:border-light-yellow "
        @click="sort('nb_votes', false)"
        v-bind:class="[sortBy === 'nb_votes' && validateOnly === false ? 'bg-gray-200 dark:bg-light-yellow dark:text-slate-800': ' dark:text-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue']">
        <i class="far fa-star text-slate-400"></i> Top
      </button>
      <button class="text-slate-600 font-semibold py-2 px-3  border border-gray-300 dark:border-light-yellow shadow "
        @click="sort('updated_at', false)"
        v-bind:class="[sortBy === 'updated_at' && validateOnly === false ? 'bg-gray-200 dark:bg-light-yellow dark:text-slate-800': ' dark:text-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue' ]">
        <i class="far fa-clock text-slate-400"></i> Nouveau
      </button>
      <button class="text-slate-600 font-semibold py-2 px-3  border border-gray-300 dark:border-light-yellow rounded-r shadow "
        @click="sort('updated_at', true)" v-bind:class="[validateOnly === true ? 'bg-gray-200 dark:bg-light-yellow dark:text-slate-800': ' dark:text-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue']">
        <i class="fas fa-list-check text-slate-400 mr-1"></i> A venir
      </button>
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
      validateOnly: false
    }
  },
  mounted() {
    this.$root.$on('sort-suggestion-active', (e) => {
      this.sort(e, false)
    })
  },
  methods: {
    sort(sorting, validateOnly) {
      this.sortBy = sorting;
      this.validateOnly = validateOnly;
      this.$root.$emit('sort-suggestion', sorting, validateOnly)
    }
  }
};
</script>

<style>
.dark-mode-suggestion .hover:bg-gray-100 {
  background-color: #2E2E2E !important;
}
</style>