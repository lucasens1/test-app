<script>
import Navbar from './components/partials/Navbar.vue';
import FooterC from './components/partials/FooterC.vue';
export default {
    components : { Navbar, FooterC}
}
</script>

<template>
    <Navbar />
    <router-view></router-view>
    <FooterC />
</template>

<style scoped>
</style>
