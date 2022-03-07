<template>
  <div>
    <b-row>
        <b-col cols="6" md="4"> </b-col>
        <b-col cols="12" md="8"><h1>Suggestion</h1> </b-col>
      </b-row>
      <b-col cols="6" md="4">
        <b-button variant="primary" @click="fetchAllsuggestion(today)"
          >New</b-button
        >
        <b-button variant="primary" @click="fetchAllsuggestion(top)"
          >Top</b-button
        ></b-col
      >
       <b-button variant="primary" @click="Generatefilr() " v-if="Role=1"
          >Top</b-button>
        
      
      <b-col cols="2" md="4">
        <input
          type="Search"
          id="Search"
          placeholder="Search une suggestion"
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
          v-on:change="Search"
          v-model="search"
        />
      </b-col>
       <b-col cols="2" md="4"
        ><b-button v-b-modal.modal-prevent-closing class="pull-right" v-if="role=1"
          >Creer Suggestion</b-button

        >
      </b-col>
 <b-col cols="2" md="4"
        ><b-button v-b-modal.modal-prevent-closing class="pull-right" v-if="role=2 && this.search!=''"
          >Creer Suggestion</b-button
          
        >
      </b-col>
       <b-modal
        id="modal-prevent-closing"
        ref="modal"
        title="Faire une Suggestion"
      >
        <form ref="form" @submit.stop.prevent="handleSubmit">
          <b-form-group
            label="Titre"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-input
              id="name-input"
              v-model="newSuggestion.title"
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
          <b-btn @click="CreateSuggestion">Ajouté</b-btn>
        </form>
      </b-modal>
  </div>
</template>
<script>
import BootsrapVue from "bootstrap-vue";
Vue.use(BootsrapVue);

export default {
  data() {
    return {
      Role: "",
      /** */
      newSuggestion: {
        date: new Date(),
        title: "",
        description: "",
      },
      SuggestionDetail: {},
      search: "",
      url: window.location,
      start_date: new Date(),
     
    };
  },
  /**
  Ici j'initialise
   */
  created() {
    /**Je récupère le rôle de l'utilisateur envoyer par le php
     * et j'initialise la listes des suggestions au lancement de l'application
     */
    Role=JSON($user)
    fetchAllsuggestion(init);
  },
  methods: {
  CreateSuggestion() {
    let urlfinal = converturl("/AddSuggestion");

    axios.
    post(urlfinal, {
      title: this.newSuggestion.title,
      description: this.newSuggestion.description,
      date: this.newSuggestion.date,
    });
  },
  /**
   * Cette fonction me permet de récupérer toutes les suggestions de la base de données
   * Ci la Liste des suggestion est vide , alors elle vas récupérer
   */
  fetchAllsuggestion(Place) {
    if(this.ListSuggestion.length()=0){
      let urlfinal = converturl ("/RecoverySuggestion");
    
    axios
      .get(urlfinal)
      .response((response) => (this.ListSuggestion = response.data));
 /**
  * Ici selon l'endroit ou j'appel la function ca envoie un argument avec une valeur qui permet de faire un trie
  * Le premier étant pour avoir la listes de celui qui à le plus grand nombre de votes 
  * Le deuxième étant pour avoir les dernières sugestion qui sont sortir
  */
     if (Place === "init") {
      this.ListSuggestion.sort((a, b) => a.number_votes - b.number_votes);
       } else {
      this.ListSuggestion.sort((a, b) => a.start_date - b.start_date);a
    }

  }


  },
 /**La function me permet de modifier l'etat de la suggedtions
  * Ci la le paramètre envoyer est supprimer alors ca déclanche la supression de la suggestion
  * Et sinon elle appel la function de changement d'état en prennant la valeur de l'état et l'id de la sugestion
  */
Updateetatsuggestion(type, id) {
    
      if(type="5"){
        urlfinal = converturl ( "/Deletesuggestion");
        axios.delete(urlfinal + id);
        let urlfinal = converturl ("/DeleteVote");
           }else{
            let urlfinal = converturl ( "/UpdateState");
            axios.put(urlfinal, {etat: type,id_suggestion: id});

    }

   
  },
  UpdateSuggestion() {
     let urlfinal = converturl ("/UpdateSuggestion");
     
   
    axios.put(urlfinal, [
      (title = this.SuggestionDetail.title),
      (description = this.SuggestionDetail.description),
      (date = this.date),
    ]);
  },
  Vote(id) {
    let urlfinal = converturl ("/AddVote");
    axios
    .post(urlfinal,{date=start_date,id=id})
  },
  DeleteVote(id){
    let urlfinal = converturl ("/DeleteVote");
    axios
    .delete(urlfinal ,{suggestion_id=id}

    )
    
  },
  
  
  converturl(root) {
    urlfinal = url + root;
    return urlfinal;
  },
  Generatefilr(){
   
     let urlfinal = converturl ("/GenerationCSV");
      axios.get(urlfinal)
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
    },
  }
  }


</script>