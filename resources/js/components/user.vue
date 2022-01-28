<template>
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
       
      >
   
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
    
     
        <b-button v-b-modal.modal-prevent-closing class="pull-right"
          >Creer Sugestion</b-button
        >
      </b-col>
 </div>
  
</template>

<script>

import BootsrapVue from "bootstrap-vue";
Vue.use(BootsrapVue);
export default {
  name: "App",
  data() {
    return {
      
    };
  },
  created() {
    this.fetchAllsugestion(init);
  },
  methods: {
    
  },
};
</script>

