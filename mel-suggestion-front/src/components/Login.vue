<template>
    <div>
      <button @click="loginWithMicrosoft">Se connecter avec Microsoft</button>
      <button @click="loginWithGoogle">Se connecter avec Google</button>
      <button @click="loginWithCerbere">Se connecter avec Cerbère</button>
    </div>
  </template>
  
  <script>
  import { UserManager } from 'oidc-client';
  
  export default {
    methods: {
      async loginWithGoogle() {
    const userManager = new UserManager({
        authority: GOOGLE_URL,
        client_id: GOOGLE_ID,
        redirect_uri: 'URL_Redirect_après_connexion',
        response_type: 'id_token token',
        scope: 'openid profile email',
    });

    userManager.signinRedirect();
      },
    },
  };
  </script>
  
  