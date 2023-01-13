<template>
  <div class="mt-4 sm-md:mt-0">
    <span
      class="z-10 leading-snug font-normal absolute text-center text-slate-300 dark:text-light-yellow bg-transparent rounded text-base items-center justify-center w-8 pl-3 py-2">
      <i class="fas fa-magnifying-glass"></i>
    </span>
    <input type="text" placeholder="Rechercher ou créer une suggestion"
      class="px-3 py-2 placeholder-slate-300 text-slate-600 dark:text-light-yellow relative bg-white dark:bg-light-blue rounded text-sm border border-gray-300 dark:border-light-yellow outline-none focus:outline-none pl-10 w-72 sm:w-80"
      v-model="search" @input="handleSearch" />
    <i class="fas fa-times text-red-500 hover:text-red-700 cursor-pointer relative" style="margin-left:-25px"
      title="Réinitialiser la recherche" @click="resetSearch"></i>
    <button class="text-slate-600 dark:text-light-yellow font-semibold px-3 ml-6 border border-gray-300 dark:border-light-yellow rounded" style="padding-top: 0.30rem; padding-bottom: 0.45rem;" @click="refresh()" title="Actualiser">
      <i class="fas fa-sync text-slate-400"></i>
    </button>
  </div>
</template>

<script>
export default {
  name: "Search",
  data() {
    return {
      search: ""
    }
  },
  methods: {
    handleSearch() {
      this.$root.$emit('search', this.search)
    },
    resetSearch() {
      this.$root.$emit('reset-search')
      this.search = ""
    },
    refresh() {
      this.$root.$emit('refresh')
    }
  },
  mounted() {
    this.$root.$on('reset-search-text', () => {
      this.search = ""
    })
  }
};
</script>