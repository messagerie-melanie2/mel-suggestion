<template>
  <section>
    <div v-if="!loadingStatus">
      <Navbar :title="allText.application_name" />
      <ChangeStateModal v-show="showStateModal" :modalInfo="this.modalInfo" @close-modal="showStateModal = false" />
      <ChangeCommentModal v-show="showCommentModal" :modalInfo="this.modalInfo" :comment="this.modalComment"
        @close-modal="showCommentModal = false" />

      <body class="flex items-center justify-center">
        <div class="w-full max-w-4xl px-4" :class="[$user.origin != 'mel' ? 'mt-7' : '']">
          <!-- <Header :title="allText.title" /> -->
          <div class="rounded-lg pb-6 border border-gray-300 dark:border-gray-800 bg-white dark:bg-light-blue">
            <div
              class="flex flex-wrap items-center justify-between px-6 py-3 border-b border-gray-300 dark:border-light-blue">
              <SortingButton />
              <Search />
            </div>
            <div>
              <section>
                <Suggestions :suggestions="allSuggestions"/>
              </section>
            </div>
          </div>
        </div>
      </body>
    </div>
    <div v-else>
      <Preloader color="gray" />
    </div>
    <span v-if="allText.application_version && allText.application_version !== '%%VERSION%%'" class="version-number mr-2 text-gray-700 dark:text-gray-400">
      version {{ allText.application_version }}
    </span>
  </section>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

import SortingButton from "./components/SortingButton";
import Search from "./components/Search";
import Suggestions from "./components/Suggestions";
import Preloader from './components/Preloader.vue'
import Navbar from './components/layout/navbar.vue'
import ChangeStateModal from './components/Modals/ChangeStateModal.vue';
import ChangeCommentModal from "./components/Modals/ChangeCommentModal.vue";

export default {
  name: "App",
  data() {
    return {
      title: "Module de suggestion",
      showStateModal: false,
      showCommentModal: false,
      modalInfo: {},
      modalComment: '',
    };
  },
  created() {
    this.fetchSuggestions(),
      this.fetchText()
  },

  mounted() {
    this.$root.$on('showStateModal', (e) => {
      this.showStateModal = true;
      this.modalInfo = { state: e.state, suggestion: e.suggestion }
    }),
      this.$root.$on('showCommentModal', (e) => {
        this.showCommentModal = true;
        this.modalInfo = e.suggestion;
        this.modalComment = e.suggestion.comment;
      }),
      this.$root.$on('refresh', () => {
        this.fetchSuggestions()
      })
  },
  methods: {
    ...mapActions(['fetchSuggestions', 'fetchText'])
  },
  components: {
    SortingButton,
    Search,
    Suggestions,
    Preloader,
    Navbar,
    ChangeStateModal,
    ChangeCommentModal
  },
  computed: mapGetters(['allSuggestions', 'loadingStatus', 'allText']),
};

</script>


<style>
.Vue-Toastification__toast.bottom-right {
  margin-bottom: 60px !important;
}

.dark body {
  background-color: rgba(31, 41, 51, 100);
}

.ql-toolbar.ql-snow {
  border-radius: 0.25rem 0.25rem 0 0;
}

.ql-container.ql-snow {
  border-radius: 0 0 0.25rem 0.25rem;
}

.dark .ql-toolbar.ql-snow {
  border-color: #E1C58F;
}

.dark .ql-toolbar.ql-snow span {
  color: #96b9e7;
}

.dark .ql-container.ql-snow {
  color: #96b9e7;
  border-color: #E1C58F;
}

.dark svg,
.dark .svg {
  filter: invert(69%) sepia(75%) saturate(408%) hue-rotate(184deg) brightness(100%) contrast(82%);
}

.version-number {
  position: absolute;
  bottom: 0;
  right: 0;
}
</style>