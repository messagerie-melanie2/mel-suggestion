<template>
<div v-if="role === 'moderateur'">
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
          type="Search"
          id="Search"
          placeholder="Search une sugestion"
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
          <b-btn @click="AddSugestion">Ajouté</b-btn>
        </form>
      </b-modal>
    </div>
    <h2>Liste des Sugestion à valider</h2>
    <b-card-group deck v-for="sugestion in ListSugestion" :key="sugestion.id">
      <b-card v-if="(sugestion.etat = '1')">
        <b-card-text> Titre {{ sugestion.Title }} </b-card-text>
        <b-card-text>
          {{ sugestion.Description }}
        </b-card-text>
        <b-card-text> nb votes:{{ sugestion.number_votes }} </b-card-text>
        <div v-if="(sugestion.etat = '1')">
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
        </div>
        <div v-else>
          <b-card-text> Titre {{ sugestion.Title }} </b-card-text>
          <b-card-text>
            {{ sugestion.Description }}
          </b-card-text>
          <b-card-text> nb votes:{{ sugestion.number_votes }} </b-card-text>
          <b-btn @click="Vote(sugestion.id)">Vote</b-btn>
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
        <b-button variant="primary" @click="fetchAllsugestion(init)"
          >New</b-button
        >
        <b-button variant="primary" @click="fetchAllsugestion(top)"
          >Top</b-button
        ></b-col
      >
      <b-col cols="2" md="4">
        <input
          type="Search"
          id="Search"
          placeholder="Search une sugestion"
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

      <b-col cols="2" md="4" v-if="this.search != ''"
        ><b-button v-b-modal.modal-prevent-closing class="pull-right" v-if="recherche"
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
          <b-btn @click="AddSugestion">Ajouté</b-btn>
        </form>
      </b-modal>
    </div>
    <h2>Liste de mes sugestion<h2>
        <div id="documents" >
    <b-card-group deck v-for="sugestion in ListSugestion" :key="sugestion.id">
      <b-card v-if="sugestion.appartien='oui'">
        <b-card-text class="document"> Titre {{ sugestion.title }} </b-card-text>
        <b-card-text>
          {{ sugestion.description}}
        </b-card-text>

        <b-card-text> nb votes:{{ sugestion.number_votes}} </b-card-text>
          <b-button v-if="sugestion.state=='1' ||sugestion.state=='3' "
            variant="success"
            v-b-modal.modal-1
            @click="Updateetatsugestion(amodifier, sugesstion.id)"
            >à Modifier</b-button
          >
            <b-button v-if="sugestion.state=='1' ||sugestion.appartien=='oui' "
            variant="danger"
            @click="Updateetatsugestion(suprimer, sugesstion.id)"
            >Pas bon</b-button
          >

      </b-card>
    </b-card-group>
    </div>
 <h2>Liste des Sugestion déjà faite</h2>
 <div id="documents" >
    <b-card-group deck v-for="sugestion in ListSugestion" :key="sugestion.id">
      <b-card v-if="sugestion.state='2'">
        <b-card-text class="document"> Titre {{ sugestion.title }} </b-card-text>
        <b-card-text>
          {{ sugestion.description}}
        </b-card-text>
        <b-card-text> nb votes:{{ sugestion.number_votes}} </b-card-text>
        <b-btn @click="Vote(sugestion.id)" v-if="sugestion.etvote='non'">Vote</b-btn>
        <b-btn @click="DeleteVote(sugestion.id)" v-if="sugestion.etvote='oui'">Enlever vote</b-btn>
      </b-card>
    </b-card-group>
    </div>
  
</div>

    
</template>

<script>
import BootsrapVue from "bootstrap-vue";
Vue.use(BootsrapVue);

export default defineComponent({
  data() {
    return {
      role: "",
      date: new Date(),
      title: "",
      description: "",
      search: "",
    };
  },
  created() {
    Fetchrole();
    fetchAllsugestion(init);
  },
  methods: {
    Fetchrole() {
      axios
        .get("http://127.0.0.1:8000/role")
        .response((this.role = response.data));
    },
  },
  CreateSugestion() {
    axios.post("http://127.0.0.1:8000/AddSugestion", [
      this.title,
      this.description,
      this.date,
      
    ]);
  },
  fetchAllsugestion(Place) {
    axios
      .get("http://127.0.0.1:8000/RecoverySugestion")
      .response((this.ListSugestion = response.data));

    if (Place === "init") {
      this.ListSugestion.sort((a, b) => a.number_votes - b.number_votes);
    } else {
      this.ListSugestion.sort((a, b) => a.start_date - b.start_date);
    }
  },
  Updateetatsugestion(type, id) {
    if (type == "valide") {
      axios.put("http://127.0.0.1:8000/Updateeta", [
        (etat = 2),
        (id_sugestion = id),
      ]);
      axios.post("http://127.0.0.1:8000/Vote", [date, id]);

      this.fetchAllsugestion(init);
    } else if ((type = "suprimer")) {
      axios.delete("http://127.0.0.1:8000/Deletesugestion" + id);
      this.fetchAllsugestion(init);
    } else {
      axios.put("http://127.0.0.1:8000/Updateeta", [
        (etat = 3),
        (id_sugestion = id),
      ]);
      this.fetchAllsugestion(init);
    }
  },
  UpdateSugestion() {
    axios.put("http://127.0.0.1:8000/UpdateSugestion", [
      this.title,
      this.description,
      this.date,
    ]);
  },
});
</script>
