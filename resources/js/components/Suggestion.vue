<template>
<div v-if="Role === 'moderateur'">
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
       <b-button variant="primary" @click="Generatefilr() "
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
        ><b-button v-b-modal.modal-prevent-closing class="pull-right"
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
              v-model="title"
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
          <b-btn @click="AddSuggestion">Ajouté</b-btn>
        </form>
      </b-modal>
    </div>
    <h2>Liste des Suggestion à valider</h2>
    <b-card-group deck v-for="suggestion in ListSuggestion" :key="suggestion.id">
      <b-card v-if="(suggestion.etat = '1')">
        <b-card-text> Titre {{ suggestion.Title }} </b-card-text>
        <b-card-text>
          {{ suggestion.Description }}
        </b-card-text>
        <b-card-text> nb votes:{{ suggestion.number_votes }} </b-card-text>
        <div v-if="(suggestion.etat = '1')">
          <b-button
            variant="danger"
            @click="Updateetatsuggestion(suprimer, sugesstion.id)"
            >Pas bon</b-button
          >
          <b-button
            variant="success"
            v-b-modal.modal-1
            @click="Updateetatsuggestion(amodifier, sugesstion.id)"
            >à Modifier</b-button
          >
          <b-button
            variant="outline-primary"
            @click="Updateetatsuggestion(valide, sugesstion.id)"
            >Valide</b-button
          >
          <b-button @click="Updateetatsuggestion(vérouiller, sugesstion.id)">Vérouiller</b-button> 
        </div>
        <div v-else>
          <b-card-text> {{ suggestion.Title }} </b-card-text>
          <b-card-text>
            {{ suggestion.Description }}
          </b-card-text>
          <b-card-text> {{ suggestion.number_votes }} </b-card-text>
         
        </div>
      </b-card>
    </b-card-group>
     <b-btn @click="Vote(suggestion.id)">Vote</b-btn>
          <b-btn @click="DeleteVote(suggestion.id)">Enlever vote</b-btn>
  </div>
<div v-else>
     <b-row>
        <b-col cols="6" md="4"> </b-col>
        <b-col cols="12" md="8"><h1>Suggestion</h1> </b-col>
      </b-row>
      <b-col cols="6" md="4">
        <b-button variant="primary" @click="fetchAllsuggestion(init)"
          >New</b-button
        >
        <b-button variant="primary" @click="fetchAllsuggestion(top)"
          >Top</b-button
        ></b-col
      >
     
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
         <b-col cols="2" md="4"
        ><b-button v-b-modal.modal-prevent-closing class="pull-right"
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
          <b-btn @click="UpdateSuggestion">Ajouté</b-btn>
        </form>
      </b-modal>
      <b-modal
        id="modal_modifier"
        ref="modal"
        title="Modifier la Suggestion"
      >
        <form ref="form" @submit.stop.prevent="handleSubmit">
          <b-form-group
            label="Titre"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-input
              id="name-input"
              v-model="SuggestionDetail.title"
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
              v-model="SuggestionDetail.description"
              placeholder="Une description"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
          <b-btn @click="M">Modifier</b-btn>
        </form>
      </b-modal>
      <h2>Liste de mes suggestion<h2>
        <div id="documents" >
    <b-card-group deck v-for="suggestion in ListSuggestion" :key="suggestion.id">
      <div v-if="suggestion.appartien='oui'">
      <b-card >
        <b-card-text class="document"> Titre {{ suggestion.title }} </b-card-text>
        <b-card-text>
          {{ suggestion.description}}
        </b-card-text>

        <b-card-text>{{ suggestion.number_votes}} </b-card-text>
        <div v-if="suggestion.state=='1'">
          <b-button  
            variant="success"
            v-b-modal.modal-1
            @click="Updateetatsuggestion(amodifier, sugesstion.id)"
            >à Modifier</b-button
          >
            <b-button v-if="suggestion.state=='1' ||suggestion.appartien=='oui' "
            variant="danger"
            @click="Updateetatsuggestion(suprimer, sugesstion.id)"
            >suprimer</b-button

          >
        </div>
        <div v-else-if="suggestion.state='3'">
           <b-button  
            variant="success"
          v-b-modal.modal-modif
            @click=" Updateeta(modifier,suggestion.id)"
            >Modifier</b-button
          >
           <b-button  
            variant="success"
            v-b-modal.modal-1
            @click="Updateetatsuggestion(suprimer, sugesstion.id)"
            >suprimer</b-button
          >

        </div>
        

      </b-card>
      
    </b-card-group>
     
</div>

    
</template>
 

<script>
import BootsrapVue from "bootstrap-vue";
Vue.use(BootsrapVue);

export default {
  data() {
    return {
      Role: "",
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
  created() {
    Role=JSON($user)
    fetchAllsuggestion(init);
  },
  methods: {
  CreateSuggestion() {
    let urlfinal = converturl("/AddSuggestion");

    axios.post(urlfinal, {
      title: this.newSuggestion.title,
      description: this.newSuggestion.description,
      date: this.newSuggestion.date,
    });
  },
  fetchAllsuggestion(Place) {
    if(this.ListSuggestion.length()=0){
      let urlfinal = converturl ("/RecoverySuggestion");
    
    axios
      .get(urlfinal)
      .response((response) => (this.ListSuggestion = response.data));
    }else{
     if (Place === "init") {
      this.ListSuggestion.sort((a, b) => a.number_votes - b.number_votes);
    } else {
      this.ListSuggestion.sort((a, b) => a.start_date - b.start_date);a
    }

  }


  },
  Updateetatsuggestion(type, id) {
    if (type == "valide") {
    let urlfinal = converturl ( "/UpdateState");
    
      axios.put(urlfinal, {
        etat: 2,
        id_suggestion: id,
      });
      let urlfinal = converturl ( "/AddVote");
     
      axios.post(urlfinal, { date, id });

      this.fetchAllsuggestion(init);
    } else if ((type = "suprimer")) {
     
   let urlfinal = converturl ( "/Deletesuggestion");
      
      axios.delete(urlfinal + id);
      let urlfinal = converturl ("/DeleteVote");
    
      axios.delete(urlfinal+ { id });
      this.fetchAllsuggestion(init);
    } else if ((type = "modifier")) {
       let urlfinal = converturl ("/Updateeta");
      
      axios.put(urlfinal, {
        etat: 3,
        id_suggestion: id,
      });
      this.fetchAllsuggestion(init);
    } else {
       let urlfinal = converturl ( "/UpdateState");
    
      axios.put(urlfinal, {
        etat: 4,
        id_suggestion: id,
      });
      this.fetchAllsuggestion(init);
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
      axios.get(urlfinal

    )
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