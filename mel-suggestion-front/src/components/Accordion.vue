<template>
  <div>
    <div class="tab__header">
      <a href="#" class="tab__link pb-2 block bg-blue-dark text-gray-600 flex justify-center">
        <span v-show="!active"><i class="fa-solid fa-chevron-down"></i></span>
        <span class="up-Arrow" v-show="active"><i class="fa-solid fa-chevron-up"></i></span>
      </a>
    </div>
    <div class="tab__content p-2" v-show="active" :class="$darkTheme ? 'dark-text' : ''">
      <div class="ql-snow" v-if="active">
        <div class="ql-editor">
          <div v-html="suggestion.description"></div>
        </div>
      </div>
      <hr class="mb-2">
      <div id="moderator_commands" v-if="$moderator">
        <div class="flex justify-between mt-3">
          <div>
            Suggestion ajoutée par : <a href="#" class="font-bold" @click.stop="sendEmail">{{
                suggestion.user_email
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
        return moment(String(value)).format('MM/DD/YYYY')
      }
    }
  },
  methods: {
     sendEmail() {
      const windowRef = window.open(`mailto:${this.suggestion.user_email}?subject=${this.allText.mail_subject.replace('%%title%%',this.suggestion.title)}`);
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

<style>
</style>