<template>
  <div v-if="type === 'moderateur'">
    <div>
      <b-row>
        <b-col cols="6" md="4"> </b-col>
        <b-col cols="12" md="8"><h1>Sugestion</h1> </b-col>
      </b-row>
      <b-col cols="6" md="4">
        <b-button variant="primary" @click="fetchAllsugestion(today)"
          >New</b-button
        >
        <b-button variant="primary" @click="fetchAllsugestion(top)"
          >Top</b-button
        ></b-col
      >
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
      <b-col cols="2" md="4"
        ><b-button v-b-modal.modal-prevent-closing class="pull-right"
          >Creer Sugestion</b-button
        >
      </b-col>

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
              v-model="unesugestion.Title"
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
              v-model="unesugestion.description"
              placeholder="Une description"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
          <b-btn @click="AddSugestion">Ajouté</b-btn>
        </form>
      </b-modal>
    </div>
    <h2>Liste des Sugestion à valider</h2>
    <b-card-group deck v-for="sugestion in ListSugestion" :key="sugestion.id">
    
      <b-card v-if="sugestion.etat='1'">
        <b-card-text> Titre {{ sugestion.Title }} </b-card-text>
        <b-card-text>
          {{ sugestion.Description }}
        </b-card-text>
        <b-card-text> nb votes:{{ sugestion.nbvote }} </b-card-text>
        <div v-if="(sugestion.etat = '1')">
          <b-button variant="danger" @click="Updateetat(suprimer, sugesstion.id)"
            >Pas bon</b-button
          >
          <b-button
            variant="success"
            v-b-modal.modal-1
            @click="Updateetat(amodifier, sugesstion.id)"
            >à Modifier</b-button
          >
          <b-button
            variant="outline-primary"
            @click="Updateetat(valide, sugesstion.id)"
            >Valide</b-button
          >
        
    
        
        </div>
        <div v-else>
          
        <b-card-text> Titre {{ sugestion.Title }} </b-card-text>
        <b-card-text>
          {{ sugestion.Description }}
        </b-card-text>
        <b-card-text> nb votes:{{ sugestion.nbvote }} </b-card-text>
          <b-btn @click="Voter(sugestion.id)">Voter</b-btn>
          <b-btn @click="DeleteVote(sugestion.id)">Enlever vote</b-btn>
        </div>
      </b-card>
    </b-card-group>
  </div>

  <div v-else>
     <div>
      <b-row>
        <b-col cols="6" md="4"> </b-col>
        <b-col cols="12" md="8"><h1>Sugestion</h1> </b-col>
      </b-row>
      <b-col cols="6" md="4">
        <b-button variant="primary" @click="fetchAllsugestion(today)"
          >New</b-button
        >
        <b-button variant="primary" @click="fetchAllsugestion(top)"
          >Top</b-button
        ></b-col
      >
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
      
      <b-col cols="2" md="4" v-if="this.recherche!=''"
        ><b-button v-b-modal.modal-prevent-closing class="pull-right"
          >Creer Sugestion</b-button
        >
      </b-col>

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
              v-model="unesugestion.Title"
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
              v-model="unesugestion.description"
              placeholder="Une description"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
          <b-btn @click="AddSugestion">Ajouté</b-btn>
        </form>
      </b-modal>
    </div>
    <h2>Liste des Sugestion déjà faite</h2>
    <b-card-group deck v-for="sugestion in ListSugestion" :key="sugestion.id">

          <b-card v-if="sugestion.etat=='3'">
        <b-card-text> Titre {{ sugestion.Title }} </b-card-text>
        <b-card-text>
          {{ sugestion.Description }}
        </b-card-text>
        <b-card-text> nb votes:{{ sugestion.nbvote }} </b-card-text>
          <b-btn @click="Voter(sugestion.id)">Voter</b-btn>
          <b-btn @click="DeleteVote(sugestion.id)">Enlever vote</b-btn>
        
      </b-card>
    </b-card-group>
  </div>

</template>

<script>
import BootsrapVue from "bootstrap-vue";
Vue.use(BootsrapVue);
export default {
  name: "App",
  data() {
    return {
      ListSugestion: {},
      FinalSuggestion: {},
      unesugestion: { title: "", description: "", nbvote: "" },
      Vote: {},
      today_date: new Date(),
      type: "Moderateur",
      recherche: "",
    };
  },
  created() {
    this.fetchAllsugestion(premier);
  },
  methods: {
    fetchAllsugestion(connected) {
      axios
        .get("http://127.0.0.1:8000/AllSugestion0")
        .response((this.ListSugestion = response.data));
      this.ListSugestion.forEach((sugestion) => {
        axios
          .get("http://127.0.0.1:8000/Votebyid" + sugestion.id)
          .then((response) => {
            this.Vote = response.data;
          });

        variation = this.Vote.length;
        unesugestion.title = sugestion.title;
        unesugestion.description = sugestion.description;
        unesugestion.nbvote = this.variation;
        this.FinalSuggestion.push(this.unesugestion);
      });
      if (connected === "premier") {
        this.FinalSuggestion.sort((a, b) => a.nbvote - b.nbvote);
      } else {
        this.FinalSuggestion.sort((a, b) => a.date_creat - b.date_creat);
      }
    },
    CreateSugestion() {
      axios.post("http://127.0.0.1:8000/AddSugestion", [
        this.unesugestion.titre,
        this.unesugestion.description,
        this.today_date,
        type,
      ]);
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
      this.fetchAllsugestion(premier);
    },
    DeleteVote(id) {
      axios.delete("http://127.0.0.1:8000/suprimervote" + id);
      this.fetchAllsugestion(premier);
    },
    Updateetat(type, id) {
      if (type == "valide") {
        axios.put("http://127.0.0.1:8000/updateeta", [
          (etat = 2),
          (id_sugestion = id),
        ]);

        this.fetchAllsugestion(premier);
      } else if ((type = "suprimer")) {
        axios.delete("http://127.0.0.1:8000/deletesugestion" + id);
        this.fetchAllsugestion(premier);
      } else {
        axios.put("http://127.0.0.1:8000/updateeta", [
          (etat = 3),
          (id_sugestion = id),
        ]);
        this.fetchAllsugestion(premier);
      }
    },
    UpdateSugestion() {
      axios.put("http://127.0.0.1:8000/UpdateSugestion", [
        this.unesugestion.titre,
        this.unesugestion.description,
        type,
      ]);
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