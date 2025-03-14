<template>
    <FormateurLayout>
        <div class="p-6 bg-gray-900 text-gray-100">
            <h1 class="text-2xl font-bold mb-6">Détails de l'Apprenant</h1>

            <div class="bg-gray-800 rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-white">{{ apprenant.nom }}</h2>
                <p class="text-gray-300">Email: {{ apprenant.email }}</p>
                <p class="text-gray-300">Téléphone: {{ apprenant.telephone }}</p>
                <p class="text-gray-300">Formations Inscrites: {{ apprenant.formations.length }}</p>
            </div>

            <h2 class="text-xl font-semibold text-white mb-4">Formations Inscrites</h2>
            <table class="min-w-full bg-gray-800 rounded-lg">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left">Titre de la Formation</th>
                        <th class="py-3 px-4 text-left">Date d'Inscription</th>
                        <th class="py-3 px-4 text-left">Note Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="formation in apprenant.formations" :key="formation.id">
                        <td class="py-3 px-4">{{ formation.titre }}</td>
                        <td class="py-3 px-4">{{ formation.date_inscription }}</td>
                        <td class="py-3 px-4">{{ formation.note_moyenne }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </FormateurLayout>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import FormateurLayout from '@/Layouts/FormateurLayout.vue'; // Assurez-vous que le chemin est correct

// Définir les propriétés reçues depuis le contrôleur
const props = defineProps<{
    apprenant: {
        id: number;
        nom: string;
        email: string;
        telephone: string;
        formations: Array<{
            id: number;
            titre: string;
            date_inscription: string;
            note_moyenne: number;
        }>
    }
}>();
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border-bottom: 1px solid #3E3E3A;
}

th {
    background-color: #2D2D2D;
}

tr:hover {
    background-color: #3A3A3A;
}
</style>