<template>
  <div>
    <div class="tab__header">
      <a href="#" class="tab__link pb-2 block bg-blue-dark text-gray-600 dark:text-common-blue flex justify-center">
        <span v-show="!active"><i class="fa-solid fa-chevron-down"></i></span>
        <span class="up-Arrow" v-show="active"><i class="fa-solid fa-chevron-up"></i></span>
      </a>
    </div>
    <div class="tab__content p-2 dark:text-common-blue" v-show="active">
      <div class="ql-snow" v-if="active">
        <div class="ql-editor">
          <div v-html="suggestion.description"></div>
        </div>
      </div>
      <span class="text-yellow-500" v-if="suggestion.state == 'moderate' && !$user.moderator">
        Votre suggestion est en attente de modération
      </span>
      <hr class="mb-2 mt-2">
      <div id="moderator_commands" v-if="$user.moderator">
        <div class="flex justify-between mt-3">
          <div>
            Suggestion ajoutée par : <a href="#" class="font-bold" @click.stop="sendEmail">{{
              displayName
            }}</a>
            <br>
            Le : <span class="font-bold"> {{ suggestion.created_at | formatDate }} </span>
          </div>
          <div v-if="suggestion.state != 'refused' && suggestion.state != 'moderate'">
            <button @click.stop="copyLink"
              class="text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white active:bg-blue-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
              <i class="fa-solid fa-link mr-1"></i> {{ copyLinkText }}</button>
          
          </div>
        </div>
      </div>
      <div v-else>
        <div class="flex justify-between">
          <div>
            Suggestion ajoutée le : <span class="font-bold"> {{ suggestion.created_at | formatDate }} </span>
          </div>
          <div v-if="suggestion.state != 'refused' && suggestion.state != 'moderate'">
            <button @click.stop="copyLink"
              class="text-blue-500 border border-blue-500 hover:bg-blue-500 hover:text-white active:bg-blue-600 font-bold uppercase text-xs px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
              <i class="fa-solid fa-link mr-1"></i> {{ copyLinkText }}</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import moment from 'moment';
import { mapGetters } from 'vuex';

export default {
  name: "Accordion",
  props: [
    'suggestion', 'active'
  ],
  data() {
    return {
      copyLinkText: "Copier le lien",
    }
  },
  filters: {
    formatDate: function (value) {
      if (value) {
        return moment(String(value)).format('DD/MM/YYYY')
      }
    }
  },
  methods: {
    sendEmail() {
      if (this.suggestion.user_email !== 'Anonyme') {
      const windowRef = window.open(`mailto:${this.suggestion.user_email}?subject=${this.allText.mail_subject.replace('%%title%%', this.suggestion.title)}`);
      windowRef.focus();
      setTimeout(function () {
        if (!windowRef.document.hasFocus()) {
          windowRef.close();
        }
      }, 500);
    }

    },
    copyLink() {
      this.copyLinkText = "Lien copié !"
      navigator.clipboard.writeText(this.$suggestionUrl.replace('suggestionId', this.suggestion.id));

      setTimeout(() => {
        this.copyLinkText = "Copier le lien"
      }, 2000);
    },
  },
  computed: {
    ...mapGetters(['allText']),
    displayName: function () {
      if (this.suggestion.user_lastname) {
        return this.suggestion.user_firstname + ' ' + this.suggestion.user_lastname;
      }
      return this.suggestion.user_email;
    }
  },

}
</script>

<style>

</style>