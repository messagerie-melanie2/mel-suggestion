<template>
  <!-- Navigation Bar Component -->
  <nav class="px-2 pt-4 md:pt-0 bg-white border-gray-200 dark:bg-dark-blue dark:border-gray-700">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <div class="flex items-center select-none">
        <!-- Title Display -->
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">{{title ? title : 'Suggestion'}}</span>
      </div>
    </div>
  </nav>
</template>

<script>
import axiosClient from '../../axios';

export default {
  name: "Navbar",
  data() {
    return {
      // Initial state for mobile menu and dark theme
      isHidden: true,
      isMobile: false,
      darkTheme: this.$darkTheme,
    }
  },
  props: {
    // Title displayed in the navbar
    title: String,
  },
  mounted() {
     if (this.title) {
      // Set document title if title prop is provided
       document.title = this.title;
     }
  },
  methods: {
    // Method to disconnect user
    disconnect() {
      axiosClient.get(`disconnect`)
        .then(() => {
          window.location.reload();
        })
        .catch((error) => {
          console.log(error);
        })
    },
    // Method to toggle between light and dark theme
    changeTheme() {
       // Determine current color mode and switch to the opposite
      let color = JSON.parse(localStorage.getItem('colorMode')) == "dark" ? '"light"' : '"dark"'
      this.darkTheme = !this.darkTheme;
      localStorage.setItem('colorMode', color);
      // Inform other components about the theme change
      window.postMessage('colorMode', '*');
    }
  }
};

</script>
