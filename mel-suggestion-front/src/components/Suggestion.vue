<template>
  <tr class="inline-block w-full px-6 pt-3 cursor-pointer "
    :class="[!showSuggestion ? 'hover:bg-gray-100 dark:hover:bg-dark-blue' : '', suggestion.state == 'moderate' ? 'border-l-2 border-yellow-500' : '', suggestion.state == 'validate' ? 'border-l-2 border-green-400' : '', suggestion.state == 'refused' ? 'border-l-2 border-red-400' : '']"
    @click.prevent="description = !description">
    <td class="inline-block w-full">
      <div class="flex justify-between" v-show="!showSuggestion">
        <div id="suggestion" class="flex items-center w-full">
          <div class="rounded-sm p-2.5 cursor-pointer dark:bg-light-blue " :class="[
            suggestion.voted ? 'bg-green-200 border dark:border-voted-green dark:text-voted-green' : 'bg-gray-300  border dark:border-title-blue text-slate-800 dark:text-title-blue',
          ]" @mouseenter="changeVoteText" @mouseleave="resetVoteText" @click.stop="toggleVote" :title="title()">
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
              <p class="font-semibold overflow-hidden truncate custom_width text-slate-800 dark:text-title-blue h-5">
                {{ suggestion.title | strippedContent }}
              </p>
            </div>
            <div :class="[description ? 'invisible' : 'visible', $darkTheme ? 'dark-text' : '']"
              class="overflow-hidden truncate custom_width dark:text-common-blue">
              <span>{{ suggestion.description | strippedContent }}</span>
            </div>
          </div>
        </div>
        <div v-if="suggestion.state == 'validate'">
          <span
            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-green-600 bg-green-200 -ml-12 whitespace-nowrap">
            A venir
          </span>
        </div>
        <div v-if="suggestion.state == 'refused'">
          <span
            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-red-600 bg-red-200 -ml-12 whitespace-nowrap">
            Refusée
          </span>
        </div>
        <div id="user-actions"
          v-show="(suggestion.my_suggestion || $user.moderator) && suggestion.state == 'moderate' && !this.$no_auth">
          <i class="fa-solid fa-edit mb-4 dark:text-title-blue dark:hover:text-blue-500 hover:text-blue-500 cursor-pointer"
            @click="toggleSuggestion" title="Éditer la suggestion"></i>
          <br>
          <i v-show="!$user.moderator"
            class="fa-solid fa-trash mt-4 dark:text-title-blue dark:hover:text-red-500 hover:text-red-500 cursor-pointer"
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
import axiosClient from '../axios';

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
      if (!this.suggestion.my_suggestion && this.suggestion.state != 'validate' && !this.$no_auth)
        this.voteHover = true
    },
    resetVoteText() {
      if (!this.suggestion.my_suggestion && this.suggestion.state != 'validate' && !this.$no_auth)
        this.voteHover = false

    },
    toggleVote() {
      if (!this.suggestion.my_suggestion && this.suggestion.state != 'validate' && !this.$no_auth) {
        this.suggestion.voted = !this.suggestion.voted
        if (this.suggestion.voted) {
          axiosClient.post("votes", {
            suggestion_id: this.suggestion.id
          }).then((res) => {
            this.voteId = res.data.id
            this.suggestion.nb_votes++
          })
            .catch((error) => {
              this.$toast.error("Une erreur est survenue");
              this.suggestion.voted = !this.suggestion.voted
              console.log(error);
            })
        }
        else {
          if (this.suggestion.vote_id) {
            this.voteId = this.suggestion.vote_id;
            delete this.suggestion.vote_id;
          }
          axiosClient.delete(`votes/${this.voteId}`)
            .then(() => {
              this.suggestion.nb_votes--
            })
            .catch((error) => {
              this.$toast.error("Une erreur est survenue");
              this.suggestion.voted = !this.suggestion.voted
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
    },
    title() {
      if (this.suggestion.my_suggestion)
        return "Il n'est pas possible de voter pour sa suggestion";
      if (this.suggestion.voted && !this.suggestion.my_suggestion)
        return "Annuler mon vote";
      if (!this.suggestion.voted && !this.suggestion.my_suggestion)
        return "Ajouter un vote";

      return '';
    }
  },
  computed: {

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
  padding: 0px 0px 12px 0px;
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