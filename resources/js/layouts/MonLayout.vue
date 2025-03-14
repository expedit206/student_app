<template>
    <div class="min-h-screen bg-gray-100">
        <Navigation />


        <main class="p-4 bg-gray-500">
            <slot />
        </main>

        <footer class="bg-gray-800 text-white p-4 text-center">
            © 2024 Mon Application
        </footer>
    </div>
</template>

<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import Navigation from '@/components/Navigation.vue';
// Tu peux ajouter des scripts ici si nécessaire
</script>

<style scoped>
/* Ajoute des styles globaux ou spécifiques ici */
</style>