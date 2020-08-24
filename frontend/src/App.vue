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
      Navigation,
      Breadcrumbs
    }
  }
</script>
