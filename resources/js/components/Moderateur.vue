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
    
</template>