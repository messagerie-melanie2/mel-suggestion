<template>
  <div>
    <span
      class="z-10 leading-snug font-normal absolute text-center text-slate-300 dark:text-light-yellow bg-transparent rounded text-base items-center justify-center w-8 pl-3 py-2">
      <i class="fas fa-magnifying-glass"></i>
    </span>
    <input type="text" placeholder="Rechercher ou créer une suggestion"
      class="px-3 py-2 placeholder-slate-300 text-slate-600 dark:text-light-yellow relative bg-white dark:bg-light-blue rounded text-sm border border-gray-300 dark:border-light-yellow outline-none focus:outline-none pl-10 w-96"
      v-model="search" @input="handleSearch" />
    <i class="fas fa-times text-red-500 ml-2 hover:text-red-700 cursor-pointer" @click="resetSearch"></i>
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
    }
  },
  mounted() {
    this.$root.$on('reset-search-text', () => {
      this.search = ""
    })
  }
};
</script>

<style>
</style>