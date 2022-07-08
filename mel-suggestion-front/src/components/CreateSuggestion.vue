<template>
  <form @submit="onSubmit" class="px-8 pt-6 pb-8 mb-4">
    <div class="flex flex-wrap -mx-3 mb-6">
      <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
        <label class="block text-slate-700 dark:text-title-blue text-sm font-bold mb-2" for="title">
          Prénom
        </label>
        <input
          class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none"
          id="grid-first-name" type="text" placeholder="Jane">
      </div>
      <div class="w-full md:w-1/2 px-3">
        <label class="block text-slate-700 dark:text-title-blue text-sm font-bold mb-2" for="title">
          Nom
        </label>
        <input
          class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none"
          id="grid-last-name" type="text" placeholder="Doe">
      </div>
    </div>
    <div class="mb-4">
      <label class="block text-slate-700 dark:text-title-blue text-sm font-bold mb-2" for="title">
        Email
      </label>
      <input
        class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none"
        id="title" v-model="title" type="text" placeholder="Titre..." maxlength="255"
        :class="[titleError ? 'border-red-500' : 'dark:border-light-yellow']" />
      <span class="text-red-500" v-show="titleError">Merci de renseigner un titre</span>
    </div>
    <hr class="my-6">
    <div class="mb-4">
      <label class="block text-slate-700 dark:text-title-blue text-sm font-bold mb-2" for="title">
        Titre
      </label>
      <input
        class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none"
        id="title" v-model="title" type="text" placeholder="Titre..." maxlength="255"
        :class="[titleError ? 'border-red-500' : 'dark:border-light-yellow']" />
      <span class="text-red-500" v-show="titleError">Merci de renseigner un titre</span>
    </div>
    <div class="mb-6">
      <label class="block text-gray-700 dark:text-title-blue text-sm font-bold mb-2" for="password">
        Description
      </label>
      <vue-editor v-model="description"></vue-editor>
      <span class="text-red-500" v-show="descriptionError">Merci de renseigner une description</span>
    </div>
    <div class="flex items-center justify-between">
      <button
        class="text-gray-900 dark:text-light-yellow bg-white dark:bg-light-blue border border-gray-300 dark:border-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue rounded-md text-sm px-5 py-2.5 mr-2 mb-2 mt-2"
        type="submit">
        Valider
      </button>
    </div>
  </form>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { mapActions } from "vuex";

export default {
  name: "CreateSuggestion",
  props: {
    titleprops: String
  },
  data() {
    return {
      title: this.titleprops,
      titleError: false,
      description: this.descriptionprops,
      descriptionError: false,
    };
  },
  watch: {
    titleprops(newVal) {
      this.title = newVal;
    },
  },
  methods: {
    ...mapActions(['addSuggestion']),
    onSubmit(e) {
      e.preventDefault();

      if (!this.title) {
        this.titleError = true
        return
      }
      else {
        this.titleError = false
      }

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

      this.$root.$emit('reset-search')
      this.$root.$emit('sort-suggestion-active', 'updated_at')
    },
  },
  components: {
    VueEditor
  }
};
</script>

<style scoped>
.ql-editor {
  padding: 12px 20px 12px 0px;
  min-height: auto;
}
</style>