<template>
  <!-- Form for creating a suggestion -->
  <form @submit="onSubmit" class="px-8 pt-6 mb-4">
    <div class="mb-4">
      <label class="block text-slate-700 dark:text-title-blue text-sm font-semibold mb-2">
        Titre
      </label>
      <!-- Input for title -->
      <input @input="handleSearch"
        class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none dark:border-light-yellow"
        id="title" type="text" placeholder="Titre..." maxlength="255" required v-model="title" />
    </div>
    <div class="mb-3">
      <label class="block text-slate-700 dark:text-title-blue text-sm font-semibold mb-2">
        Description
      </label>
      <!-- VueEditor for description -->
      <vue-editor v-model="description"></vue-editor>
      <span class="text-red-500" v-show="descriptionError">Merci de renseigner une description</span>
      <div v-if="$anonymised" class="pt-3 ">
        <i class="fas fa-circle-info text-blue-600 dark:text-title-blue mr-1"></i><span class="text-blue-600 dark:text-title-blue">La suggestion sera ajoutée en tant qu'anonyme</span>
      </div>
    </div>
    <div class="flex items-center justify-between">
      <!-- Submit button -->
      <button
        class="text-white border border-blue-500 hover:bg-blue-500 bg-blue-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 px-5 py-2.5 mr-2 mb-2 mt-2"
        type="submit">
        Valider
      </button>
      <button
        class="text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white active:bg-gray-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 px-5 py-2.5 mr-2 mb-2 mt-2"
        type="button" @click="cancelCreate">
        Annuler
      </button>
    </div>
  </form>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { mapActions } from "vuex";

export default {
  // Component name
  name: "CreateSuggestion",
  // Component props
  props: {
    titleprops: String,
  },
  data() {
    return {
      title: this.titleprops,
      description: this.descriptionprops,
      descriptionError: false,
    };
  },
  // Watchers
  watch: {
    titleprops(newVal) {
      this.title = newVal;
    },
  },
  // Component methods
  methods: {
    // Map Vuex actions
    ...mapActions(['addSuggestion']),
    // Submit method
    onSubmit(e) {
      e.preventDefault();

      if (!this.description) {
        this.descriptionError = true
        return
      }

      const newSuggestion = {
        title: this.title,
        description: this.description,
      };
      
      this.title = "";
      this.description = "";

      this.addSuggestion(newSuggestion);

      // Emit events to reset search and sort suggestions
      this.$root.$emit('reset-search')
      this.$root.$emit('sort-suggestion-active', 'updated_at')
    },
    handleSearch() {
      this.$root.$emit('search', this.title)
    },
    cancelCreate() {
      this.title = '';
      this.description = '';
      this.$root.$emit('cancel-create', true)
    },
  },
  // Vue components
  components: {
    VueEditor
  }
};
</script>

<style scoped>
.ql-editor {
  padding: 12px 20px 12px 0px;
  min-height: 50px !important;
}
</style>