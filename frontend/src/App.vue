<template>
  <div id="app" class="container">
    <Navigation></Navigation>
    <Breadcrumbs></Breadcrumbs>
    <router-view/>
    <vue-progress-bar></vue-progress-bar>
  </div>
</template>

<script>
  import Navigation from './components/Navigation'
  import Breadcrumbs from './components/Breadcrumbs'
  export default {
    name: 'app',
    components: {
      'Navigation': Navigation,
      Breadcrumbs
    }
  }
</script>

<style>
  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }
</style>
