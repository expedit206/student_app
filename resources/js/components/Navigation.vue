<template>
   
        <header class="bg-blue-600 text-white p-4">
            <h1 class="text-xl">Mon Application</h1>
            <nav>
                <ul class="flex space-x-4">
                    <!-- <li><TextLink href="/dashboard">Dashboard</TextLink></li> -->
                    <li><TextLink :href="route('student.index')">Étudiants</TextLink></li>
                    <li><TextLink :href="route('formateurs.index')">Formatteurs</TextLink></li>
                    <li><TextLink :href="route('formations.index')">Formations</TextLink></li>
                    <li><TextLink :href="route('disciplines.index')">Disciplines</TextLink></li>
                    <!-- Ajoute d'autres liens de navigation ici -->
                </ul>
            </nav>
        </header>

       
   
</template>

<script setup lang="ts">
import TextLink from './TextLink.vue';

// Tu peux ajouter des scripts ici si nécessaire
</script>

<style scoped>
/* Ajoute des styles globaux ou spécifiques ici */
</style>