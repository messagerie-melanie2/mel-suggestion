<template>
  <form @submit="onSubmit" class="px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
        Titre
      </label>
      <input class="
                shadow
                appearance-none
                border
                rounded
                w-full
                py-2
                px-3
                text-gray-700
                leading-tight
                focus:outline-none focus:shadow-outline
              " id="title" v-model="localSuggestion.title" type="text" placeholder="Titre..." maxlength="255"
        :class="titleError ? 'border-red-500' : ''" />
      <span class="text-red-500" v-show="titleError">Merci de renseigner un titre</span>
    </div>
    <div class="mb-6">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
        Description
      </label>
      <vue-editor v-model="localSuggestion.description"></vue-editor>
      <span class="text-red-500" v-show="descriptionError">Merci de renseigner une description</span>
    </div>
    <div class="flex items-center justify-between">
      <button @click.stop class="
                bg-blue-500
                hover:bg-blue-700
                text-white
                font-bold
                py-2
                px-4
                rounded
                focus:outline-none focus:shadow-outline
              " type="submit">
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
