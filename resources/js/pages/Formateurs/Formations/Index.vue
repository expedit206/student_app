<template>
    <FormateurLayout>
        <div class="p-6 bg-gray-900 text-gray-100">
            <h1 class="text-2xl font-bold mb-6">Mes Formations</h1>
            <table class="min-w-full bg-gray-800 rounded-lg">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left">Titre</th>
                        <th class="py-3 px-4 text-left">Description</th>
                        <th class="py-3 px-4 text-left">Heures Hebdomadaires</th>
                        <th class="py-3 px-4 text-left">Heures Totales</th>
                        <th class="py-3 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="formation in formations" :key="formation.id">
                        <td class="py-3 px-4">{{ formation.titre }}</td>
                        <td class="py-3 px-4">{{ formation.description }}</td>
                        <td class="py-3 px-4">{{ formation.nbh_hebdomadaire }}</td>
                        <td class="py-3 px-4">{{ formation.nbh_total }}</td>
                        <td class="py-3 px-4">
                            <Link 
                                class="text-blue-400 hover:underline">
                            Voir Apprenants
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </FormateurLayout>
</template>

<script setup lang="ts">
import FormateurLayout from '@/components/Formateurs/FormateurLayout.vue';

import { defineProps } from 'vue';

// Définir les propriétés reçues depuis le contrôleur
const props = defineProps<{
    formations: Array<{
        id: number;
        titre: string;
        description: string;
        nbh_hebdomadaire: number;
        nbh_total: number;
    }>
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