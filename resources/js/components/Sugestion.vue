<template>
  <div v-if="type === 'moderateur'">
    <b-row>
      <b-col cols="6" md="4"> </b-col>
      <b-col cols="12" md="8"><h1>Sugestion</h1> </b-col>
    </b-row>
    <div>
      <b-modal
        id="modal-prevent-closing"
        ref="modal"
        title="Faire une Sugestion"
      >
        <form ref="form" @submit.stop.prevent="handleSubmit">
          <b-form-group
            label="Titre"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-input
              id="name-input"
              v-model="titre"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            label="Description"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-textarea
              id="textarea"
              v-model="description"
              placeholder="Une description"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
          <b-btn @click="AddSugestion">Ajouté</b-btn>
        </form>
      </b-modal>
    </div>
    <b-row>
      <b-col cols="6" md="4">
        <b-button variant="primary" @click="fetchdata(today)">New</b-button>
        <b-button variant="primary" @click="fetchdata(top)"
          >Top</b-button
        ></b-col
      >
      <b-col cols="2" md="4"
        ><b-button v-b-modal.modal-prevent-closing class="pull-right"
          >Creer Sugestion</b-button
        >
      </b-col>
      <b-col cols="2" md="4">
        <input
          type="search"
          id="search"
          placeholder="Recherche une sugestion"
          class="
            appearance-none
            border border-gray-400 border-b
            block
            pl-8
            pr-6
            py-2
            w-full
            bg-white
            text-sm
            placeholder-gray-400
            rounded-lg
            text-gray-700
            focus:bg-white
            focus:placeholder-gray-600
            focus:text-gray-700
            focus:outline-none
          "
          @keyup="ser"
          v-on:change="Recherche"
          v-model="recherche"
        />
      </b-col>
    </b-row>
    <b-card-group deck v-for="sugestion in LesaSugestions" :key="sugestion.id">
      <b-card>
        <b-card-text> Titre {{ sugestion.Title }} </b-card-text>
        <b-card-text>
          {{ sugestion.Description }}
        </b-card-text>
        <b-card-text> nb votes:{{ sugestion.nbvote }} </b-card-text>
        <b-button variant="danger" @click="modifetat(suprimer, sugesstion.id)"
          >Pas bon</b-button
        >
        <b-button
          variant="success"
          v-b-modal.modal-1
          @click="modifetat(amodifier, sugesstion.id)"
          >à Modifier</b-button
        >
        <b-button
          variant="outline-primary"
          @click="modifetat(valide, sugesstion.id)"
          >Valide</b-button
        >
      </b-card>
    </b-card-group>
  </div>
  <div v-else>
    <b-row>
      <b-col cols="6" md="4"> </b-col>
      <b-col cols="12" md="8"><h1>Faire une Sugestion</h1> </b-col>
    </b-row>
    <div>
      <b-modal
        id="modal-prevent-closing"
        ref="modal"
        title="Faire une Sugestion"
      >
        <form ref="form" @submit.stop.prevent="handleSubmit">
          <b-form-group
            label="Titre"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-input
              id="name-input"
              v-model="titre"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group
            label="Description"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-textarea
              id="textarea"
              v-model="description"
              placeholder="Une description"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
          <b-btn @click="creersugestion">Ajouté</b-btn>
        </form>
      </b-modal>
    </div>
    <b-row>
      <b-col cols="6" md="4">
        <b-button variant="primary" @click="fetchdata(today)">New</b-button>
        <b-button variant="primary" @click="fetchdata(top)"
          >Top</b-button
        ></b-col
      >
      <b-col cols="2" md="4"
        ><b-button v-b-modal.modal-prevent-closing class="pull-right"
          >Creer Sugestion</b-button
        >
      </b-col>
      <b-col cols="2" md="4">
        <input
          type="search"
          id="search"
          placeholder="Faire une recherche"
          class="
            appearance-none
            border border-gray-400 border-b
            block
            pl-8
            pr-6
            py-2
            w-full
            bg-white
            text-sm
            placeholder-gray-400
            rounded-lg
            text-gray-700
            focus:bg-white
            focus:placeholder-gray-600
            focus:text-gray-700
            focus:outline-none
          "
          v-model="recherche"
        />
      </b-col>
    </b-row>
    <b-card-group deck v-for="sugestion in LesaSugestions" :key="sugestion.id">
      <b-card v-if="(sugestion.etat = 2)">
        <b-card-text>
          {{ sugestion.Title }}
          {{ sugestion.Description }} </b-card-text
        ><b-btn @click="Voter(sugestion.id)">Voter</b-btn>
        <b-btn
          v-if="(sugesstion.email = connected)"
          v-b-modal.modal-prevent-closing
          class="pull-right"
          >Modifier</b-btn
        >
      </b-card>
      <b-card v-else> </b-card>
    </b-card-group>
  </div>
</template>

<script>
import VueSession from "vue-session";
Vue.use(VueSession);
import BootsrapVue from "bootstrap-vue";
Vue.use(BootsrapVue);
export default {
  name: "App",
  data() {
    return {
      recherche: "",
      type: "moderateur",
      recherche: "",
      LesSugestion: {},
      LesSugestionValider: {},
      Vote: {},
      variation: "",
      vote: 0,
      date_today: new date(),
      unesugestion: { titre: "", description: "", nbvote: "", ListUser: [] },
      titre: "",
      description: "",
      today_date: new Date(),
      LesSugestion: {},
      connected: "louka.ruiz@apitech.fr",
    };
  },
  created() {
    this.fetchdataModerateur(premier);
    this.fetchdatauser(premier);
  },
  methods: {
    AddSugestion() {
      axios.post("http://127.0.0.1:8000/AddSugestion", [
        this.titre,
        this.description,
        this.today_date,
        type,
      ]);
    },
    fetchdataModerateur(connected) {
      axios
        .get("http://127.0.0.1:8000/SugestionList")
        .response((this.Sugestions = response.data));
      this.Sugestions = response;
      this.Sugestions.forEach((sugestion) => {
        axios
          .get("http://127.0.0.1:8000/nbVote" + sugestion.id)
          .then((response) => {
            this.Vote = response.data;
          });

        this.variation = this.Vote.length;
        this.unesugestion.titre = sugestion.title;
        this.unesugestion.description = sugestion.description;
        this.unesugestion.nbvote = this.variation;
        this.LesSugestion.push(this.unesugestion);
      });
      if (connected === "premier") {
        this.LesSugestions.sort((a, b) => a.nbvote - b.nbvote);
      } else {
        LesaSugestions.sort((a, b) => a.date_creat - b.date_creat);
      }
    },
    fetchdatauser(lieu, connected) {
      axios.get("http://127.0.0.1:8000/SugestionList").then((response) => {
        this.Sugestions = response.data;
      });
      this.Sugestions.forEach((sugestion) => {
        axios
          .get("http://127.0.0.1:8000/nbVote" + sugestion.id)
          .then((response) => {
            this.Vote = response.data;
          });
        if ((sugestion.etat = 2)) {
          this.variation = this.Vote.length;
          this.unesugestion.titre = sugestion.title;
          this.unesugestion.description = sugestion.description;
          this.unesugestion.nbvote = this.variation;
          this.LesSugestionValider.push(this.unesugestion);
        }
      });
      if (type === "premier") {
        this.LesSugestionsValider.sort((a, b) => a.nbvote - b.nbvote);
      } else {
        LesaSugestionsValider.sort((a, b) => a.date_creat - b.date_creat);
      }
    },
    modifetat(type, id) {
      if (type == "valide") {
        axios.put("http://127.0.0.1:8000/updateeta", [
          (etat = 2),
          (id_sugestion = id),
        ]);

        this.fetchdata();
      } else if ((type = "suprimer")) {
        axios.delete("http://127.0.0.1:8000/deletesugestion" + id);
        this.fetchdata();
      } else {
        axios.put("http://127.0.0.1:8000/updateeta", [
          (etat = 3),
          (id_sugestion = id),
        ]);
      }
    },
    Voter(id) {
      axios.post("http://127.0.0.1:8000/Voter", [
        today_date,
        id,
        this.connected,
        this.titre,
        this.description,
        this.today_date,
        type,
      ]);
    },
    DeleteVote() {
      axios.delete("http://127.0.0.1:8000/enlevervoter" + id);
    },
    Recherche() {
      document.getElementById('recherche').addEventListener('keyup', function(e) {
  var recherche = this.value.toLowerCase();
  var documents = document.querySelectorAll('.document');
 
  Array.prototype.forEach.call(documents, function(document) {
    // On a bien trouvé les termes de recherche.
    if (document.innerHTML.toLowerCase().indexOf(recherche) > -1) {
      document.style.display = 'block';
    } else {
      document.style.display = 'none';
    }
  });
});
    }
  },
};
</script>