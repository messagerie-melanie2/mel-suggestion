<template>
  <div>
    <b-row>
      <b-col cols="6" md="4"> </b-col>
      <b-col cols="12" md="8"><h1>Suggestion</h1> </b-col>
    </b-row>
    <b-row>
      <b-col cols="6" md="4">
        <b-button variant="primary" @click="fetchAllsuggestion(today)"
          >New</b-button
        >
        <b-button variant="primary" @click="fetchAllsuggestion(top)"
          >Top</b-button
        ></b-col
      >
      <b-col cols="6" md="4">
        <b-button variant="primary" @click="Generatefil()">Top</b-button>
        ></b-col
      >

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
    </b-row>
    <div>
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
              v-model="newSuggestion.description"
              placeholder="Une description"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-form-group>
          <b-btn @click="AddSuggestion">Ajouté</b-btn>
        </form>
      </b-modal>
    </div>
    <div>
     <h2>Liste des Sugestion à valider</h2>
    <b-card-group deck v-for="sugestion in ListSugestion" :key="sugestion.id">
      
      <b-card >
        <b-card-text> Titre {{ sugestion.title }} </b-card-text>
        <b-card-text>
          {{ sugestion.description }}
        </b-card-text>
        <b-card-text> nb votes:{{ sugestion.number_votes }} </b-card-text>
        <div v-if="(sugestion.state = '1')">
          <b-button
            variant="danger"
            @click="Updateetatsugestion(suprimer, sugesstion.id)"
            >Pas bon</b-button
          >
          <b-button
            variant="success"
            v-b-modal.modal-1
            @click="Updateetatsugestion(amodifier, sugesstion.id)"
            >à Modifier</b-button
          >
          <b-button
            variant="outline-primary"
            @click="Updateetatsugestion(valide, sugesstion.id)"
            >Valide</b-button
          >
          <b-button @click="Updateetatsugestion(vérouiller, sugesstion.id)">Vérouiller</b-button> 
        </div>
        <div  v-if="(sugestion.state = '2')">
               <b-button
            variant="danger"
            @click="Updateetatsugestion(suprimer, sugesstion.id)"
            >Pas bon</b-button
          >
             <div  v-if="(sugestion.state = '3')">
               <b-button
            variant="danger"
            @click="Updateetatsugestion(suprimer, sugesstion.id)"
            >Pas bon</b-button
          >
             <b-button
            variant="danger"
            @click="Updateetatsugestion(suprimer, sugesstion.id)"
            >Pas bon</b-button
          >

        </div>
        <div v-else>
            </div>
      </b-card>
    </b-card-group>
     <b-btn @click="Vote(sugestion.id)">Vote</b-btn>
          <b-btn @click="DeleteVote(sugestion.id)">Enlever vote</b-btn>
  </div>

  </div>
</template>
<script>
import BootsrapVue from "bootstrap-vue";
Vue.use(BootsrapVue);

export default defineComponent({
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
    Fetchrole();
    fetchAllsuggestion(init);
  },
  methods: {
    Fetchrole() {
      let urlfinal = converturl("/role");

      axios.get(urlfinal).response((response) => (this.Role = response.data));
    },
  },
  CreateSuggestion() {
    let urlfinal = converturl("/AddSuggestion");

    axios.post(urlfinal, {
      title: this.newSuggestion.title,
      description: this.newSuggestion.description,
      date: this.newSuggestion.date,
    });
  },
  fetchAllsuggestion(Place) {
   let urlfinal = converturl ("/RecoverySuggestion");
    
    axios
      .get(urlfinal)
      .response((response) => (this.ListSuggestion = response.data));

    if (Place === "init") {
      this.ListSuggestion.sort((a, b) => a.number_votes - b.number_votes);
    } else {
      this.ListSuggestion.sort((a, b) => a.start_date - b.start_date);
    }
  },
  Updateetatsuggestion(type, id) {
    if (type == "valide") {
    let urlfinal = converturl ( "/UpdateState");
    
      axios.put(urlfinal, {
        state: 2,
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
        state: 3,
        id_suggestion: id,
      });
      this.fetchAllsuggestion(init);
    } else {
       let urlfinal = converturl ( "/UpdateState");
    
      axios.put(urlfinal, {
        state: 4,
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
  Voter(id) {
    let urlfinal = converturl ("/AddVote");
    axios.post(urlfinal,{date=start_date,id=id

    })
  },
  DeleteVote(id){
    let urlfinal = converturl ("/DeleteVote");
    axios.delete(urlfinal,{id=id

    })
    
  },
  
  
  converturl(root) {
    urlfinal = url + root;
    return urlfinal;
  },
  Generatefil(){
   
     let urlfinal = converturl ("/GenerationCSV");
      axios.get(urlfinal

    )
  },
  fetchDetail(id){

      let urlfinal= converturl("/Detail")
;
axios.get(urlfinal, {id}
) .response((response) => (SuggestionDetail = response.data)); }
  });
</script>
