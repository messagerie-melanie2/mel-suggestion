<template>
  <div>
    <h2>Traitement en cours</h2>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import { UserManager } from 'oidc-client';

export default {
  computed: {
    ...mapGetters(['oidcSettings', 'isAuthenticated'])  // Récupérer les settings depuis Vuex
  },
  methods: {
    ...mapActions(['setIsAuthenticated', 'setUser'])
  },
  async mounted() {
    try {
      const settings = JSON.parse(localStorage.getItem('oidcSettings'));

      if (settings) {
        const userManager = new UserManager(settings);

        // Traiter le callback
        const user_info = await userManager.signinRedirectCallback();
        
        user_info.profile.connector = 'google';
        console.log('USER_INFO', user_info.profile);
        this.setUser(user_info.profile);
        // Mettre à jour l'état d'authentification
        // this.setIsAuthenticated(true);

        // console.log(this.isAuthenticated);
        
        // Assurer que la redirection ne se produit qu'une fois
        if (this.$route.path !== '/app') {
          this.$router.push({ name: 'App' });
        }
      } else {
        console.error('OIDC Settings not found.');
        this.$router.push({ name: 'Login' });
      }
    } catch (error) {
      console.error('Error during OIDC callback:', error);
      this.$router.push({ name: 'Login' });
    }
  }
};
</script>