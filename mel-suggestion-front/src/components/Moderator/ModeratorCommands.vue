<template>
  <!-- Moderator Commands Component -->
  <div id="moderator_commands" v-if="$user.moderator">
    <!-- Commands for Suggestions -->
    <div class="flex justify-between mt-5" v-if="suggestion.state == 'moderate'">
      <!-- Remove Suggestion Button -->
      <button @click.stop="removeSuggestion"
        class=" border border-black hover:bg-black hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-trash mr-1"></i>
        Supprimer
      </button>
      <!-- Refuse Suggestion Button -->
      <button @click.stop="refuseSuggestion"
        class="text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-times mr-1"></i>
        Refuser
      </button>
      <!-- Modify Suggestion Button -->
      <button v-if="!$anonymised" @click.stop="modifySuggestion"
        class="text-yellow-500 border border-yellow-500 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-pen mr-1"></i>
        A modifier
      </button>
      <!-- Validate Suggestion Button -->
      <button @click.stop="validateSuggestion"
        class="text-green-500 border border-green-500 hover:bg-green-500 hover:text-white active:bg-green-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-check mr-1"></i>
        Valider
      </button>
    </div>

    <div class="flex justify-between mt-5" v-if="suggestion.state == 'vote'">
      <!-- Refuse Suggestion Button -->
      <button @click.stop="refuseSuggestion"
        class="text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-times mr-1"></i>
        Refuser
      </button>
      <!-- Lock Suggestion Button -->
      <button @click.stop="lockSuggestion"
        class="text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white active:bg-blue-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-lock mr-1"></i>
        Accepter
      </button>
    </div>

    <div class="flex justify-between mt-3" v-if="suggestion.state == 'validate'">
      <!-- Refuse Suggestion Button -->
      <button @click.stop="refuseSuggestion"
        class="text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-times mr-1"></i>
        Refuser
      </button>
    </div>

    <div class="flex justify-between mt-3" v-if="suggestion.state == 'refused'">
      <!-- Remove Suggestion Button -->
      <button @click.stop="removeSuggestion"
        class=" border border-black hover:bg-black hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-trash mr-1"></i>
        Supprimer
      </button>
      <!-- Restore Suggestion Button -->
      <button @click.stop="restoreSuggestion"
        class="text-green-500 border border-green-500 hover:bg-green-500 hover:text-white active:bg-green-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fas fa-trash-arrow-up mr-1"></i>
        Restaurer
      </button>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'ModeratorCommands',
  props: [
    // The suggestion object
    'suggestion'
  ],
  methods: {
    // Map Vuex actions to local methods
    ...mapActions(['changeStateSuggestion', 'fetchText']),
    // Remove suggestion method
    removeSuggestion() {
      this.$root.$emit('showStateModal', { state: 'delete', suggestion: this.suggestion });
    },
    // Refuse suggestion method
    refuseSuggestion() {
      this.$root.$emit('showStateModal', { state: 'refused', suggestion: this.suggestion });
    },
    // Restore suggestion method
    restoreSuggestion() {
      this.$root.$emit('showStateModal', { state: 'moderate', suggestion: this.suggestion });
    },
    // Validate suggestion method
    validateSuggestion() {
      this.$root.$emit('showStateModal', { state: 'vote', suggestion: this.suggestion });
    },
    // Lock suggestion method
    lockSuggestion() {
      this.$root.$emit('showStateModal', { state: 'validate', suggestion: this.suggestion });
    },
    // Modify suggestion method
    modifySuggestion() {
      if(this.suggestion.user_email !== 'Anonyme') {
        this.sendEmail(this.allText.mail_subject.replace('%%title%%', this.suggestion.title));
      }
    },
    // Send email method
    sendEmail(subject = '', body = '') {
      const windowRef = window.open(`mailto:${this.suggestion.user_email}?subject=${subject}&body=${body}`);
      windowRef.focus();
      setTimeout(function () {
        if (!windowRef.document.hasFocus()) {
          windowRef.close();
        }
      }, 500);
    }
  },
  computed: mapGetters(['allText']),
}
</script>
