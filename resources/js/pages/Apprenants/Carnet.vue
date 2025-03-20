<script setup lang="ts">
import { defineProps } from 'vue';
import MonLayout from '@/components/MonLayout.vue';

const props = defineProps({
    carnetData: {
        type: Object,
        default: () => ({}),
    },
});

const getMoyenneColor = (moyenne) => {
    if (!moyenne) return 'text-gray-400';
    if (moyenne >= 15) return 'text-green-400';
    if (moyenne >= 10) return 'text-yellow-400';
    return 'text-red-400';
};
</script>

<template>
    <MonLayout>
        <div class="max-w-5xl mx-auto p-6 bg-gray-800 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold text-white mb-6">Mon Carnet de Notes - {{ carnetData.titre }}</h1>

            <div v-if="carnetData.disciplines?.length" class="space-y-6">
                <!-- Tableau des disciplines -->
                <table class="w-full text-left text-gray-200">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="p-3">N°</th>
                            <th class="p-3">Discipline</th>
                            <th class="p-3">H. Hebdo</th>
                            <th class="p-3">H. Total</th>
                            <th class="p-3">Formateur</th>
                            <th class="p-3">Coefficient</th>
                            <th class="p-3">Notes</th>
                            <th class="p-3">Moyenne</th>
                            <th class="p-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="discipline in carnetData.disciplines" :key="discipline.id"
                            class="border-b border-gray-600">
                            <td class="p-3">{{ discipline.id }}</td>
                            <td class="p-3">{{ discipline.titre }}</td>
                            <td class="p-3">{{ discipline.heures_hebdo }}</td>
                            <td class="p-3">{{ discipline.heures_total }}</td>
                            <td class="p-3">{{ discipline.formateur }}</td>
                            <td class="p-3">{{ discipline.coefficient }}</td>
                            <td class="p-3">{{ discipline }}</td>
                            <td class="p-3" :class="getMoyenneColor(discipline.moyenne)">
                                {{ discipline.moyenne ?? 'N/A' }}
                            </td>
                            <td class="p-3">{{ discipline.statut }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Résumé -->
                <div class="bg-gray-700 p-4 rounded-lg text-gray-200">
                    <p><strong>Total des heures :</strong> {{ carnetData.total_heures }}</p>
                    <p><strong>Moyenne générale :</strong>
                        <span :class="getMoyenneColor(carnetData.moyenne_formation)">
                            {{ carnetData.moyenne_formation ? carnetData.moyenne_formation.toFixed(2) : 'N/A' }}
                        </span>
                    </p>
                </div>
            </div>
            <p v-else class="text-gray-400">Aucune discipline ou notes disponibles pour le moment.</p>
        </div>
    </MonLayout>
</template>

<style scoped>
table {
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #4a5568;
    min-width: 80px;
}
</style>