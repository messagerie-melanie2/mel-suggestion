<template>
  <tr :id="this.suggestion.id" class="inline-block w-full px-6 pt-3 cursor-pointer "
    :class="[!showSuggestion ? 'hover:bg-gray-100 dark:hover:bg-dark-blue' : '', suggestion.state == 'moderate' ? 'border-l-2 border-yellow-500' : '', suggestion.state == 'validate' ? 'border-l-2 border-green-400' : '', suggestion.state == 'refused' ? 'border-l-2 border-red-400' : '']"
    @click.prevent="description = !description">
    <td class="inline-block w-full">
      <div class="flex justify-between" v-show="!showSuggestion">
        <div id="suggestion" class="flex items-center w-full">
          <div class="flex justify-between" v-show="suggestion.state == 'vote'">
            <div class="mr-4">
              <i class="fas fa-check text-xl"
                :class="[suggestion.voted_type == 'up' ? 'text-green-500 hover:text-gray-500' : 'text-gray-500 hover:text-green-500 dark:text-gray-400 dark:hover:text-green-500']"
                @click.stop="toggleVote('up')"></i>
              <br>
              <div class="text-center" :class="[suggestion.voted_type == 'up' ? 'text-green-500' : 'text-gray-700 dark:text-gray-300']">
                {{ suggestion.votes_up }}
              </div>
            </div>
            <div class="mr-2">
              <i class="fas fa-times text-xl "
                :class="[suggestion.voted_type == 'down' ? 'text-red-400 hover:text-gray-500' : 'text-gray-500 hover:text-red-400 dark:text-gray-400 dark:hover:text-red-400']"
                @click.stop="toggleVote('down')"></i>
              <br>
              <div class="text-center" :class="[suggestion.voted_type == 'down' ? 'text-red-400' : 'text-gray-700 dark:text-gray-300']">
                {{ suggestion.votes_down }}
              </div>
            </div>
          </div>
          <div class="pl-3">
            <div class="flex items-center text-sm leading-none">
              <p class="font-semibold overflow-hidden custom_width text-slate-800 dark:text-title-blue pb-2" :class="[description ? '' : 'truncate']">
                <Highlighter :searchWords="keywords" :autoEscape="true" :sanitize="sanitize"  :textToHighlight="suggestion.title | strippedContent"/>
              </p>
            </div>
            <div :class="[description ? 'invisible' : 'visible', $darkTheme ? 'dark-text' : '']"
              class="overflow-hidden truncate custom_width dark:text-common-blue">
              <Highlighter :searchWords="keywords" :autoEscape="true"  :sanitize="sanitize" :textToHighlight="suggestion.description | strippedContent"/>
            </div>
          </div>
        </div>

        <div v-if="suggestion.state == 'validate'">
          <span
            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-green-600 bg-green-200 -ml-12 whitespace-nowrap">
            Retenue
          </span>
          <div v-if="!suggestion.comment && $user.moderator">
            <i class="far fa-comment-dots mt-4 dark:text-title-blue dark:hover:text-green-500 hover:text-green-500 cursor-pointer"
              title="Ajouter un commentaire" @click.stop="changeComment"></i>
          </div>
        </div>

        <div v-if="suggestion.state == 'refused'">
          <span
            class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-red-600 bg-red-200 -ml-12 whitespace-nowrap">
            Refusée
          </span>
          <div v-if="!suggestion.comment && $user.moderator">
            <i class="far fa-comment-dots mt-4 dark:text-title-blue dark:hover:text-green-500 hover:text-green-500 cursor-pointer"
              title="Ajouter un commentaire" @click.stop="changeComment"></i>
          </div>
        </div>
        <div id="user-actions"
          v-show="(!$anonymised && (suggestion.my_suggestion || $user.moderator)) && suggestion.state == 'moderate'">
          <i class="fas fa-edit mb-4 dark:text-title-blue dark:hover:text-blue-500 hover:text-blue-500 cursor-pointer"
            @click="toggleSuggestion" title="Éditer la suggestion"></i>
          <br>
          <i v-show="!$user.moderator"
            class="fas fa-trash mt-4 dark:text-title-blue dark:hover:text-red-500 hover:text-red-500 cursor-pointer"
            title="Supprimer la suggestion" @click.stop="onDelete"></i>
        </div>

      </div>

      <ModeratorCommands :suggestion="suggestion" />

      <div v-if="showSuggestion">
        <div class="flex justify-end">
          <i class="fas fa-edit mb-4 hover:text-blue-500 cursor-pointer" @click="toggleSuggestion"></i>
        </div>

        <UpdateSuggestion @update-suggestion="updateSuggestions" :suggestion="suggestion" />
      </div>
      <div
        class="inline-block w-full my-4 p-4 rounded-md border bg-white dark:bg-dark-blue border-gray-100 shadow-md dark:border-gray-500"
        v-if="suggestion.comment">
        <div class="flex justify-between">
          <div>
            <h5 class="mb-2 text-md font-semibold tracking-tight dark:text-white">{{ this.allText.comment_from }}<i
                class="fas fa-circle-check text-blue-700 ml-2"></i></h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">{{ suggestion.comment }}</p>
          </div>
          <i v-if="$user.moderator"
            class="fas fa-edit dark:text-title-blue dark:hover:text-blue-500 hover:text-blue-500 cursor-pointer"
            @click.stop="changeComment" title="Éditer le commentaire"></i>
        </div>
      </div>

    </td>
    <Accordion :suggestion="suggestion" :active="description" v-show="!showSuggestion" />
  </tr>
</template>

<script>
import axiosClient from '../axios';
import Vue from 'vue'

import moment from 'moment';
import { mapGetters, mapActions } from "vuex";
import ModeratorCommands from "./Moderator/ModeratorCommands";

import UpdateSuggestion from "./UpdateSuggestion";
import Accordion from "./Accordion";

import Highlighter from 'vue-highlight-words';
import DOMPurify from 'dompurify';

export default {
  name: "Suggestion",
  props: {
    suggestion: Object,
    words: Array
  },
  data() {
    return {
      voteHover: false,
      voteId: Number,
      showSuggestion: false,
      modifiedSuggestion: false,
      description: false
    }
  },
  mounted() {
    if ((this.$searchId == this.suggestion.id) && !this.$hasScrolled) {
      this.description = true;
      Vue.prototype.$hasScrolled = true;
      document.getElementById(this.suggestion.id).scrollIntoView();        
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
    sanitize(html) {
      // Désinfecter le HTML
      const cleanHtml = DOMPurify.sanitize(html);
      // Enlever les accents
      return cleanHtml.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    },
    changeComment() {
      this.$root.$emit('showCommentModal', { suggestion: this.suggestion });
    },
    toggleVote(new_type) {
      if (!this.suggestion.my_suggestion && this.suggestion.state != 'validate') {
        // on annule le vote
        if (this.suggestion.voted_type && this.suggestion.voted_type == new_type) {
          if (this.suggestion.vote_id) {
            this.voteId = this.suggestion.vote_id;
            delete this.suggestion.vote_id;
          }
          axiosClient.delete(`/votes/${this.voteId}`)
            .then(() => {
              if (new_type == "up") {
                this.suggestion.votes_up--
              }
              else if (new_type == "down") {
                this.suggestion.votes_down--
              }
              this.suggestion.voted_type = null;
            })
            .catch((error) => {
              this.$toast.error("Une erreur est survenue");
              this.suggestion.voted = !this.suggestion.voted
              console.log(error);
            })
        }
        // on change le type du vote
        else if (this.suggestion.voted_type && this.suggestion.voted_type != new_type) {
          if (this.suggestion.vote_id) {
            this.voteId = this.suggestion.vote_id;
            delete this.suggestion.vote_id;
          }
          let type = new_type == "up" ? "down" : "up";
          axiosClient.put(`/votes/${this.voteId}`, {
            type,
          }).then(() => {
            if (new_type == "up") {
              this.suggestion.votes_down--
              this.suggestion.votes_up++
              this.suggestion.voted_type = "up";
            }
            else if (new_type == "down") {
              this.suggestion.votes_up--
              this.suggestion.votes_down++
              this.suggestion.voted_type = "down";
            }

          })
        }
        // on ajoute un vote "up"
        else {
          axiosClient.post("/votes", {
            suggestion_id: this.suggestion.id,
            type: new_type,
          }).then((res) => {
            this.voteId = res.data.id
            if (new_type == "up") {
              this.suggestion.votes_up++
              this.suggestion.voted_type = "up";
            }
            else if (new_type == "down") {
              this.suggestion.votes_down++
              this.suggestion.voted_type = "down";
            }
          })
            .catch((error) => {
              this.$toast.error("Une erreur est survenue");
              this.suggestion.voted = !this.suggestion.voted
              console.log(error);
            })
        }
      }
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
    ...mapGetters(['allText']),
    keywords() {
      if (this.words) {
        return this.words.filter(word => word.length >= 3);
      } else {
        return [];
      }
    }
  },
  components: {
    UpdateSuggestion,
    Accordion,
    ModeratorCommands,
    Highlighter
  },
};
</script>
<style scoped>
*>>>.ql-editor {
  padding: 0px 0px 12px 0px;
  min-height: 100px;
  font-size: 13px;
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