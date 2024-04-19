<template>
   <!-- Header component -->
  <header>
    <!-- Title display -->
    <h1 class="text-3xl font-normal leading-normal mt-0 mb-2 text-center text-slate-800 dark:text-title-blue ">{{title ? title : 'Faire une suggestion'}}</h1>
  </header>
</template>

<script>
export default {
  // Component name
  name: "Header",
  // Component props
  props: {
    title: String,
  },
};
</script>

<style>
</style>