<template>
  <div class="modal-overlay" @click="closeModal">
    <div id="popup-modal" tabindex="-1" @click.stop
      class="grid h-screen place-items-center overflow-y-auto overflow-x-hidden h-modal md:h-full">
      <form @submit.prevent="onSubmit">
        <div class="relative p-8 m-8 w-full max-w-lg h-full md:h-auto">
          <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button @click="closeModal" type="button"
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
              <i class="far fa-pen-to-square text-5xl mx-auto mb-4 text-gray-400 svg"></i>
              <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Modifier le commentaire de la
                suggestion</h3>
              <textarea id="message" rows="2" v-model="localComment"
                class="block p-2.5 mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="(Facultatif) commentaire..."></textarea>


              <button type="button" @click="closeModal"
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
import { mapActions } from 'vuex';

export default {
  name: 'ChangeCommentModal',
  props: ['modalInfo','comment'],
  data() {
    return {
      localComment: '',
      sendMail: false,
    };
  },
  methods: {
    ...mapActions(['changeStateSuggestion']),
    onSubmit() {
      this.changeStateSuggestion({ id: this.modalInfo.id, state: this.modalInfo.state, comment: this.localComment });
      this.$emit('close-modal');
    },
    closeModal() {
      this.$emit('close-modal');
    }
  },
  watch: {
   comment(value) {
      this.localComment = value
   }
}
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
  z-index: 11;
}
</style>
