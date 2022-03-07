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