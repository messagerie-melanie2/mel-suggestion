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
          <div class="block my-4 p-4 rounded-md border border-gray-100 shadow-md dark:border-gray-500" v-if="suggestion.comment">
            <h5 class="mb-2 text-md font-semibold tracking-tight dark:text-white">Modérateur<i
                class="fa-solid fa-circle-check text-blue-700 ml-2"></i></h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">{{suggestion.comment}}</p>
          </div>
        </div>
      </div>
      <hr class="mb-2">
      <div id="moderator_commands" v-if="$user.moderator">
        <div class="flex justify-between mt-3">
          <div>
            Suggestion ajoutée par : <a href="#" class="font-bold" @click.stop="sendEmail">{{
            displayName
            }}</a>
          </div>
          <div>
            Le : <span class="font-bold"> {{ suggestion.updated_at | formatDate }} </span>
          </div>
        </div>
      </div>
      <div v-else>
        <p>
          Suggestion ajoutée le : <span class="font-bold"> {{ suggestion.updated_at | formatDate }} </span>
        </p>
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
  filters: {
    formatDate: function (value) {
      if (value) {
        return moment(String(value)).format('DD/MM/YYYY')
      }
    }
  },
  methods: {
    sendEmail() {
      const windowRef = window.open(`mailto:${this.suggestion.user_email}?subject=${this.allText.mail_subject.replace('%%title%%', this.suggestion.title)}`);
      windowRef.focus();
      setTimeout(function () {
        if (!windowRef.document.hasFocus()) {
          windowRef.close();
        }
      }, 500);
    }
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