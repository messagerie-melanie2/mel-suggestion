<template>
  <tr class="inline-block w-full px-6 pt-3 cursor-pointer "
    :class="[!showSuggestion ? 'hover:bg-gray-100 dark:hover:bg-dark-blue' : '', suggestion.state == 'moderate' ? 'border-l-2 border-yellow-500' : '', suggestion.state == 'validate' ? 'border-l-2 border-green-500' : '']"
    @click.prevent="description = !description">
    <td class="inline-block w-full">
      <div class="flex justify-between" v-show="!showSuggestion">
        <div id="suggestion" class="flex items-center w-full">
          <div
            class="rounded-sm p-2.5 cursor-pointer dark:bg-light-blue "
            :class="[
              suggestion.voted ? 'bg-green-200 border dark:border-voted-green dark:text-voted-green' : 'bg-gray-300  border dark:border-title-blue text-slate-800 dark:text-title-blue',
            ]" @mouseenter="changeVoteText" @mouseleave="resetVoteText" @click.stop="toggleVote">
            <div v-if="voteHover" class="text-center mb-2">
              <div v-if="suggestion.voted">
                <i class="fa-solid fa-xl fa-times"></i>
              </div>
              <div v-else>
                <i class="fa-solid fa-xl fa-circle-up"></i>
              </div>
            </div>
            <div v-else>
              <p class="text-center text-lg font-bold">{{
                  suggestion.nb_votes
              }}</p>
            </div>
            <p>Votes</p>
          </div>
          <div class="pl-3">
            <div class="flex items-center text-sm leading-none">
              <p class="font-semibold overflow-hidden truncate custom_width text-slate-800 dark:text-title-blue">
                {{ suggestion.title | strippedContent }}
              </p>
            </div>
            <div :class="[description ? 'invisible' : 'visible', $darkTheme ? 'dark-text' : '']"
              class="overflow-hidden truncate custom_width dark:text-common-blue">
              <span>{{ suggestion.description | strippedContent }}</span>
            </div>
          </div>
        </div>
        <div id="user-actions" v-show="(suggestion.my_suggestion || $moderator) && suggestion.state == 'moderate'">
          <i class="fa-solid fa-edit mb-4 dark:text-title-blue dark:hover:text-blue-500 hover:text-blue-500 cursor-pointer" @click="toggleSuggestion"
            title="Éditer la suggestion"></i>
          <br>
          <i v-show="!$moderator" class="fa-solid fa-trash mt-4 dark:text-title-blue dark:hover:text-red-500 hover:text-red-500 cursor-pointer"
            title="Supprimer la suggestion" @click.stop="onDelete"></i>
        </div>
      </div>

      <ModeratorCommands :suggestion="suggestion" />

      <div v-if="showSuggestion">
        <div class="flex justify-end">
          <i class="fa-solid fa-edit mb-4 hover:text-blue-500 cursor-pointer" @click="toggleSuggestion"></i>
        </div>

        <UpdateSuggestion @update-suggestion="updateSuggestions" :suggestion="suggestion" />
      </div>
    </td>
    <Accordion :suggestion="suggestion" :active="description" v-show="!showSuggestion" />
  </tr>
</template>

<script>
import axios from "axios";
import moment from 'moment';
import { mapActions } from "vuex";
import ModeratorCommands from "./Moderator/ModeratorCommands";

import UpdateSuggestion from "./UpdateSuggestion";
import Accordion from "./Accordion";

export default {
  name: "Suggestion",
  props: {
    suggestion: Object
  },
  data() {
    return {
      voteHover: false,
      voteId: Number,
      showSuggestion: false,
      modifiedSuggestion: false,
      description: false,
    }
  },
  filters: {
    strippedContent: function (value) {
      const div = document.createElement('div')
      div.innerHTML = value
      const text = div.textContent || div.innerText || ''
      return text
    },
    formatDate: function (value) {
      if (value) {
        return moment(String(value)).format('MM/DD/YYYY')
      }
    }
  },
  methods: {
    ...mapActions(['deleteSuggestion']),
    changeVoteText() {
      if (!this.suggestion.my_suggestion && this.suggestion.state != 'validate')
        this.voteHover = true
    },
    resetVoteText() {
      if (!this.suggestion.my_suggestion && this.suggestion.state != 'validate')
        this.voteHover = false
    },
    toggleVote() {
      if (!this.suggestion.my_suggestion && this.suggestion.state != 'validate') {
        this.suggestion.voted = !this.suggestion.voted
        if (this.suggestion.voted) {
          this.suggestion.nb_votes++
          axios.post("index.php/api/votes", {
            user_email: 'Arnaud@goubier.fr',
            suggestion_id: this.suggestion.id
          }).then((res) => {
            this.voteId = res.data.id
          })
            .catch((error) => {
              console.log(error);
            })
        }
        else {
          this.suggestion.nb_votes--
          if (this.suggestion.vote_id) {
            this.voteId = this.suggestion.vote_id;
            delete this.suggestion.vote_id;
          }
          axios.delete(`index.php/api/votes/${this.voteId}`).catch((error) => {
            console.log(error);
          })
        }
      }
      this.resetVoteText();
    },
    onDelete() {
      if (confirm('Voulez-vous supprimer cette suggestion ?')) {
        // this.$emit("delete-suggestion", this.suggestion.id);
        this.deleteSuggestion(this.suggestion.id)
      }
    },
    updateSuggestions() {
      this.toggleSuggestion();
      this.modifiedSuggestion = true;
    },

    toggleSuggestion() {
      this.showSuggestion = !this.showSuggestion
    },
    showDescription() {
      this.description = !this.description
    }
  },
  components: {
    UpdateSuggestion,
    Accordion,
    ModeratorCommands

  },
};
</script>
<style scoped>
*>>>.ql-editor {
  padding: 5px 20px 12px 15px;
  min-height: 100px;
}

@media (max-width: 576px) {
  .custom_width {
    width: 17rem;
  }
}

@media (min-width: 576px) {
  .custom_width {
    width: 25rem;
  }
}

@media (min-width: 768px) {
  .custom_width {
    width: 36rem;
  }
}

@media (min-width: 992px) {
  .custom_width {
    width: 44rem;
  }
}

@media (min-width: 1200px) {
  .custom_width {
    width: 45rem;
  }
}
</style>