<template>
  <div class="modal-overlay" @click="$emit('close-modal')">
    <div id="popup-modal" tabindex="-1" @click.stop
      class="grid h-screen place-items-center overflow-y-auto overflow-x-hidden h-modal md:h-full">
      <form @submit.prevent="onSubmit">
        <div class="relative p-4 w-full max-w-lg h-full md:h-auto">
          <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button @click="$emit('close-modal')" type="button"
              class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
              data-modal-toggle="popup-modal">
              <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
              </svg>
              <span class="sr-only">Fermer</span>
            </button>
            <div class="p-6 text-center">
              <div v-if="modalInfo.state == 'validate'">
                <i class="far fa-circle-check text-5xl mx-auto mb-4 text-gray-400 svg"></i>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Etes-vous sûr de vouloir accepter
                  cette suggestion ?</h3>
                <textarea id="message" rows="2" v-model="comment"
                  class="block p-2.5 mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="(Facultatif) commentaire..."></textarea>
              </div>

              <div v-if="modalInfo.state == 'refused'">
                <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Etes-vous sûr de vouloir refuser
                  cette suggestion ?</h3>
                <textarea id="message" rows="2" v-model="comment"
                  class="block p-2.5 mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="(Facultatif) commentaire..."></textarea>
              </div>

              <div v-if="modalInfo.state == 'delete'">
                <i class="far fa-circle-xmark text-5xl mx-auto mb-4 text-gray-400 svg"></i>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Etes-vous sûr de vouloir supprimer
                  cette suggestion ?</h3>
                <div class="flex items-center mb-7 select-none">
                  <input id="default-checkbox" type="checkbox" v-model="sendMail"
                    class="w-4 h-4 text-blue-600 bg-gray-700 rounded border-gray-700 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                  <label for="default-checkbox" class="ml-2 font-normal text-gray-500 dark:text-gray-400">Envoyer un
                    mail</label>
                </div>
              </div>

              <button type="button" @click="$emit('close-modal')"
                class="mr-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Annuler</button>
              <button type="submit" :class="[modalInfo.state == 'validate' ? 'text-white bg-blue-500 hover:bg-blue-700' : '',
              modalInfo.state == 'refused' ? 'text-white bg-red-600 hover:bg-red-800' : '',
              modalInfo.state == 'delete' ? 'text-white bg-gray-900' : '']"
                class=" focus:ring-4 focus:outline-none font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center ">
                Accepter
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'ChangeStateModal',
  data() {
    return {
      comment: '',
      sendMail: false,
    };
  },
  props: {
    modalInfo: Object,
  },
  methods: {
    ...mapActions(['changeStateSuggestion', 'deleteSuggestion', 'fetchText']),
    onSubmit() {
      if (this.modalInfo.state == 'delete') {
        this.deleteSuggestion(this.modalInfo.suggestion.id)
        if (this.sendMail) {
          this.sendEmail(this.allText.mail_subject.replace('%%title%%', this.modalInfo.suggestion.title), this.allText.mail_body);
        }
        this.$emit('close-modal');

      }
      else {
        this.changeStateSuggestion({ id: this.modalInfo.suggestion.id, state: this.modalInfo.state, comment: this.comment });
        this.comment = '';
        this.$emit('close-modal');
      }
    },
    sendEmail(subject = '', body = '') {
      const windowRef = window.open(`mailto:${this.modalInfo.suggestion.user_email}?subject=${subject}&body=${body}`);
      windowRef.focus();
      setTimeout(function () {
        if (!windowRef.document.hasFocus()) {
          windowRef.close();
        }
      }, 500);
    },
  },
  computed: mapGetters(['allText']),
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  background-color: #000000b2;
  z-index: 1;
}
</style>
