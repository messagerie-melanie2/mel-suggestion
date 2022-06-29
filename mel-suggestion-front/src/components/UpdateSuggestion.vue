<template>
  <form @submit="onSubmit" class="px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
      <label class="block text-gray-700 dark:text-title-blue text-sm font-bold mb-2" for="title">
        Titre
      </label>
      <input class="
                 bg-white
              dark:bg-light-blue
                appearance-none
                border
                border-gray-300
                dark:text-common-blue
                rounded
                w-full
                py-2
                px-3
                leading-tight
                focus:outline-none
              " id="title" v-model="localSuggestion.title" type="text" placeholder="Titre..." maxlength="255"
        :class="[titleError ? 'border-red-500' : 'dark:border-light-yellow']" />
      <span class="text-red-500" v-show="titleError">Merci de renseigner un titre</span>
    </div>
    <div class="mb-6" @click.stop>
      <label class="block text-gray-700 dark:text-title-blue text-sm font-bold mb-2" for="password">
        Description
      </label>
      <vue-editor v-model="localSuggestion.description"></vue-editor>
      <span class="text-red-500" v-show="descriptionError">Merci de renseigner une description</span>
    </div>
    <div class="flex items-center justify-between">
      <button @click.stop
        class="text-gray-900 dark:text-light-yellow bg-white dark:bg-light-blue border border-gray-300 dark:border-light-yellow hover:bg-gray-100 dark:hover:bg-dark-blue rounded-md text-sm px-5 py-2.5 mr-2 mb-2 mt-2"
        type="submit">
        Valider
      </button>
    </div>
  </form>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { mapActions } from 'vuex';

export default {
  name: "FormSuggestion",
  props: {
    suggestion: Object,
  },
  data() {
    return {
      localSuggestion: this.suggestion,
      titleError: false,
      descriptionError: false,
    };
  },
  methods: {
    ...mapActions(['updateSuggestion']),
    onSubmit(e) {
      e.preventDefault();

      if (!this.localSuggestion.title) {
        this.titleError = true
        return
      } else {
        this.titleError = false
      }

      if (!this.localSuggestion.description) {
        this.descriptionError = true
        return
      }

      this.updateSuggestion(this.localSuggestion);
      this.$emit("update-suggestion");
    },
  },
  components: {
    VueEditor
  },
  emits: [
    'update-suggestion'
  ]
}
</script>
