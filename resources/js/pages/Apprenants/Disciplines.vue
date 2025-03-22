<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, computed } from 'vue';

import { ref } from 'vue';
// Props avec types
const props = defineProps<{
    formation: {
        id: number;
        titre: string;
        disciplines: {
            id: number;
            nom: string;
            description?: string;
            heures_hebdo: number;
            heures_total: number;
            coefficient: number;
        }[];
    };
}>();

const search = ref('');
const filteredDisciplines = computed(() => {
    if (!search.value) return props.formation.disciplines;
    return props.formation.disciplines.filter(d =>
        d.nom.toLowerCase().includes(search.value.toLowerCase()) ||
        (d.description && d.description.toLowerCase().includes(search.value.toLowerCase()))
    );
});
// Calculs pour la synthèse
const totalHeures = computed(() => props.formation.disciplines.reduce((sum, d) => sum + d.heures_total, 0));
const totalDisciplines = computed(() => props.formation.disciplines.length);
const moyenneCoefficient = computed(() => {
    const totalCoef = props.formation.disciplines.reduce((sum, d) => sum + d.coefficient, 0);
    return totalDisciplines.value > 0 ? (totalCoef / totalDisciplines.value).toFixed(2) : 0;
});

// Tronquer la description
function truncateDescription(description: string | undefined, maxLength: number) {
    if (!description) return 'Aucune description';
    return description.length > maxLength ? description.substring(0, maxLength) + '...' : description;
}
</script>

<template>

    <Head :title="`Disciplines - ${props.formation.titre}`" />
    <MonLayout>
        <div class="max-w-6xl mx-auto p-6">
            <!-- Titre avec animation -->
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-6 flex items-center animate-slide-in">
                <i class="fas fa-graduation-cap mr-3 text-indigo-500 dark:text-indigo-400"></i>
                Mes Disciplines - {{ props.formation.titre }}
            </h1>

            <!-- Synthèse -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 animate-fade-in">
                <div class="bg-indigo-100 dark:bg-indigo-900 p-4 rounded-lg shadow-md flex items-center">
                    <i class="fas fa-book text-indigo-500 dark:text-indigo-400 mr-3 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Nombre de disciplines</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ totalDisciplines }}</p>
                    </div>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg shadow-md flex items-center">
                    <i class="fas fa-clock text-green-500 dark:text-green-400 mr-3 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Total des heures</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ totalHeures }}h</p>
                    </div>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg shadow-md flex items-center">
                    <i class="fas fa-weight text-blue-500 dark:text-blue-400 mr-3 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Moyenne des coefficients</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ moyenneCoefficient }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-6 animate-slide-in">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input v-model="search" placeholder="Rechercher une discipline..."
                        class="w-full pl-10 pr-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300" />
                </div>
            </div>

            <!-- Tableau des disciplines en mode sombre -->
            <div v-if="props.formation.disciplines.length"
                class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg animate-fade-in">
                <table class="w-full text-left">
                    <thead class="bg-gray-900 sticky top-0">
                        <tr>
                            <th class="p-4 text-sm font-semibold text-gray-300">
                                <i class="fas fa-id-badge mr-2"></i>ID
                            </th>
                            <th class="p-4 text-sm font-semibold text-gray-300">
                                <i class="fas fa-book-open mr-2"></i>Nom
                            </th>
                            <th class="p-4 text-sm font-semibold text-gray-300">
                                <i class="fas fa-info-circle mr-2"></i>Description
                            </th>
                            <th class="p-4 text-sm font-semibold text-gray-300">
                                <i class="fas fa-user-tie mr-2"></i>Formateur
                            </th>
                            <th class="p-4 text-sm font-semibold text-gray-300">
                                <i class="fas fa-clock mr-2"></i>H. Hebdo
                            </th>
                            <th class="p-4 text-sm font-semibold text-gray-300">
                                <i class="fas fa-hourglass mr-2"></i>H. Total
                            </th>
                            <th class="p-4 text-sm font-semibold text-gray-300">
                                <i class="fas fa-weight mr-2"></i>Coef
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(discipline, index) in filteredDisciplines" :key="discipline.id"
                            class="border-b border-gray-700 hover:bg-gray-700 transition-all duration-300"
                            :class="{ 'animate-row-in': true }" :style="{ animationDelay: `${index * 0.1}s` }">
                            <td class="p-4 text-gray-200">{{ discipline.id }}</td>
                            <td class="p-4 text-gray-100 font-medium">{{ discipline.nom }}</td>
                            <td class="p-4 text-gray-400 truncate" :title="discipline.description">
                                {{ truncateDescription(discipline.description, 40) }}
                            </td>
                            <td class="p-4 text-gray-200">{{ discipline.formateur }}</td>
                            <td class="p-4 text-gray-200">{{ discipline.heures_hebdo }}h</td>
                            <td class="p-4 text-gray-200">{{ discipline.heures_total }}h</td>
                            <td class="p-4 text-gray-200">{{ discipline.coefficient }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Message si aucune discipline -->
            <div v-else class="bg-yellow-900 p-6 rounded-lg shadow-md text-center animate-pulse">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl mb-2"></i>
                <p class="text-gray-300">Aucune discipline associée à cette formation pour le moment.</p>
            </div>
        </div>
    </MonLayout>
</template>

<style scoped>
/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes rowIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slide-in {
    animation: slideIn 0.5s ease-out forwards;
}

.animate-fade-in {
    animation: fadeIn 0.7s ease-out forwards;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
}

/* Styles du tableau en mode sombre */
table {
    border-collapse: collapse;
    width: 100%;
}

th,
td {
    min-width: 100px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Scrollbar stylisée */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #2d3748;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #4a5568;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #718096;
}

/* Réactivité */
@media (max-width: 640px) {

    th,
    td {
        font-size: 0.875rem;
        padding: 0.75rem;
        min-width: 80px;
    }
}
</style>