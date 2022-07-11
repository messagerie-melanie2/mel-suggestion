<template>
  <form @submit="onSubmit" class="px-8 pt-6 pb-8 mb-4">
    <div id="no_auth_inputs" v-if="$no_auth">
      <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
          <label class="block text-slate-700 dark:text-title-blue text-sm font-semibold mb-2">
            Prénom
          </label>
          <input
            class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none dark:border-light-yellow"
            id="grid-first-name" type="text" placeholder="Prénom..." required v-model="firstname" />
        </div>
        <div class="w-full md:w-1/2 px-3">
          <label class="block text-slate-700 dark:text-title-blue text-sm font-semibold mb-2">
            Nom
          </label>
          <input
            class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none dark:border-light-yellow"
            id="grid-last-name" type="text" placeholder="Nom..." required v-model="lastname" />
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-slate-700 dark:text-title-blue text-sm font-semibold mb-2">
          Email
        </label>
        <input
          class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none dark:border-light-yellow"
          id="title" type="email" placeholder="Titre..." maxlength="255" required v-model="email" />
      </div>
      <hr class="my-6">
    </div>
    <div class="mb-4">
      <label class="block text-slate-700 dark:text-title-blue text-sm font-semibold mb-2">
        Titre
      </label>
      <input
        class="bg-white dark:bg-light-blue appearance-none border border-gray-300 dark:text-common-blue rounded w-full py-2 px-3 leading-tight focus:outline-none dark:border-light-yellow"
        id="title" type="text" placeholder="Titre..." maxlength="255" required v-model="title" />
    </div>
    <div class="mb-6">
      <label class="block text-slate-700 dark:text-title-blue text-sm font-semibold mb-2">
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
      description: this.descriptionprops,
      descriptionError: false,
      firstname: null,
      lastname: null,
      email: null
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

      if (!this.description) {
        this.descriptionError = true
        return
      }

      const newSuggestion = {
        title: this.title,
        description: this.description,
      };

      if (this.$no_auth) {
        newSuggestion.user_firstname = this.firstname,
          newSuggestion.user_lastname = this.lastname,
          newSuggestion.user_email = this.email
      }
      this.title = "";
      this.description = "";
      this.firstname = "";
      this.lastname = "";
      this.email = "";

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