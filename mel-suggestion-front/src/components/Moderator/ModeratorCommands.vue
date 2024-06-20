<template>
  <div id="moderator_commands" v-if="$user.moderator">
    <!-- <hr class="mt-2"> -->
    <div class="flex justify-between mt-5" v-if="suggestion.state == 'moderate'">
      <button @click.stop="removeSuggestion"
        class=" border border-black hover:bg-black hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-trash mr-1"></i>
        Supprimer
      </button>
      <button @click.stop="refuseSuggestion"
        class="text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-times mr-1"></i>
        Refuser
      </button>
      <button @click.stop="modifySuggestion"
        class="text-yellow-500 border border-yellow-500 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-pen mr-1"></i>
        A modifier
      </button>
      <button @click.stop="validateSuggestion"
        class="text-green-500 border border-green-500 hover:bg-green-500 hover:text-white active:bg-green-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-check mr-1"></i>
        Valider
      </button>
    </div>

    <div class="flex justify-between mt-5" v-if="suggestion.state == 'vote'">
      <button @click.stop="refuseSuggestion"
        class="text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-times mr-1"></i>
        Refuser
      </button>
      <button @click.stop="lockSuggestion"
        class="text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white active:bg-blue-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-lock mr-1"></i>
        Accepter
      </button>
    </div>

    <div class="flex justify-between mt-3" v-if="suggestion.state == 'validate'">
      <button @click.stop="refuseSuggestion"
        class="text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-times mr-1"></i>
        Refuser
      </button>
    </div>

    <div class="flex justify-between mt-3" v-if="suggestion.state == 'refused'">
      <button @click.stop="removeSuggestion"
        class=" border border-black hover:bg-black hover:text-white active:bg-red-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-trash mr-1"></i>
        Supprimer
      </button>
      <button @click.stop="restoreSuggestion"
        class="text-green-500 border border-green-500 hover:bg-green-500 hover:text-white active:bg-green-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
        type="button">
        <i class="fa-solid fa-trash-arrow-up mr-1"></i>
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
    'suggestion'
  ],
  methods: {
    ...mapActions(['changeStateSuggestion', 'fetchText']),
    removeSuggestion() {
      this.$root.$emit('showStateModal', { state: 'delete', suggestion: this.suggestion });
    },
    refuseSuggestion() {
      this.$root.$emit('showStateModal', { state: 'refused', suggestion: this.suggestion });
    },
    restoreSuggestion() {
      this.$root.$emit('showStateModal', { state: 'moderate', suggestion: this.suggestion });
    },
    validateSuggestion() {
      this.$root.$emit('showStateModal', { state: 'vote', suggestion: this.suggestion });
    },
    lockSuggestion() {
      this.$root.$emit('showStateModal', { state: 'validate', suggestion: this.suggestion });
    },
    modifySuggestion() {
      if(this.suggestion.user_email !== 'Anonyme') {
        this.sendEmail(this.allText.mail_subject.replace('%%title%%', this.suggestion.title));
      }
    },
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
