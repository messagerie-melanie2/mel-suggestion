<template>
  <nav
    class="bg-white px-2 sm:px-4 py-2.5 dark:bg-gray-900 fixed w-full z-20 top-0 left-0 border-b border-gray-200 dark:border-gray-600">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Suggestions</span>
      <div class="flex md:order-2">
        <div v-if="$no_auth" class="py-2">
          <a href="https://roundcube.ida.melanie2.com/suggestiondev/index.php"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Se
            connecter</a>
        </div>
        <div v-if="!$no_auth && $user.origin == 'google'" class="flex">
          <img src="https://lh3.googleusercontent.com/a-/AFdZucrtBOWgTHnR9xCR_kwc_vpBmlbSzoKEtigaGN_gSQ=s96-c" class="rounded-full" width="40">
          <!-- <span class="inline align-text-bottom whitespace-nowrap dark:text-white">Arnaud GOUBIER</span> -->
        </div>
      </div>
      <div class="hidden justify-between items-center w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
      </div>
    </div>
  </nav>
</template>

<script>
export default {

}
</script>

<style>
</style>