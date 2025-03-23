<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, computed, ref } from 'vue';

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
            formateur: string;
        }[];
    };
}>();

const search = ref('');
const filteredDisciplines = computed(() => {
    if (!search.value) return props.formation.disciplines;
    return props.formation.disciplines.filter(d =>
        d.nom.toLowerCase().includes(search.value.toLowerCase()) ||
        (d.description && d.description.toLowerCase().includes(search.value.toLowerCase())) ||
        d.formateur.toLowerCase().includes(search.value.toLowerCase())
    );
});

const totalHeures = computed(() => props.formation.disciplines.reduce((sum, d) => sum + d.heures_total, 0));
const totalDisciplines = computed(() => props.formation.disciplines.length);
const moyenneCoefficient = computed(() => {
    const totalCoef = props.formation.disciplines.reduce((sum, d) => sum + d.coefficient, 0);
    return totalDisciplines.value > 0 ? (totalCoef / totalDisciplines.value).toFixed(2) : 0;
});

function truncateDescription(description: string | undefined, maxLength: number) {
    if (!description) return 'Aucune description';
    return description.length > maxLength ? description.substring(0, maxLength) + '...' : description;
}
</script>

<template>

    <Head :title="`Disciplines - ${props.formation.titre}`" />
    <MonLayout>
        <div class="max-w-6xl mx-auto p-4 sm:p-6">
            <!-- Titre -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-100 mb-6 flex items-center animate-slide-in">
                <i class="fas fa-graduation-cap mr-3 text-indigo-400 animate-pulse"></i>
                Mes Disciplines - {{ props.formation.disciplines[0].titre }}
            </h1>

            <!-- Synthèse -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 animate-fade-in">
                <div
                    class="bg-gradient-to-r from-indigo-900 to-indigo-800 p-4 rounded-xl shadow-lg flex items-center transform hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-book text-indigo-400 mr-3 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-300">Disciplines</p>
                        <p class="text-xl font-bold text-white">{{ totalDisciplines }}</p>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-green-900 to-green-800 p-4 rounded-xl shadow-lg flex items-center transform hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-clock text-green-400 mr-3 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-300">Heures Totales</p>
                        <p class="text-xl font-bold text-white">{{ totalHeures }}h</p>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-r from-blue-900 to-blue-800 p-4 rounded-xl shadow-lg flex items-center transform hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-weight text-blue-400 mr-3 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-300">Moy. Coefficient</p>
                        <p class="text-xl font-bold text-white">{{ moyenneCoefficient }}</p>
                    </div>
                </div>
            </div>

            <!-- Recherche -->
            <div class="mb-6 animate-slide-in">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input v-model="search" placeholder="Rechercher une discipline ou formateur..."
                        class="w-full pl-10 pr-4 py-3 bg-gray-700 text-gray-200 rounded-lg border border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 shadow-md" />
                </div>
            </div>

            <!-- Disciplines -->
            <div v-if="filteredDisciplines.length" class="animate-fade-in">
                <!-- Tableau (Desktop) -->
                <div class="hidden md:block overflow-x-auto bg-gray-800 rounded-xl shadow-lg">
                    <table class="w-full text-left">
                        <thead class="bg-gradient-to-r from-gray-900 to-gray-800 sticky top-0 z-10">
                            <tr>
                                <th class="p-4 text-sm font-semibold text-gray-300"><i
                                        class="fas fa-id-badge mr-2"></i>ID</th>
                                <th class="p-4 text-sm font-semibold text-gray-300"><i
                                        class="fas fa-book-open mr-2"></i>Nom</th>
                                <th class="p-4 text-sm font-semibold text-gray-300"><i
                                        class="fas fa-info-circle mr-2"></i>Description</th>
                                <th class="p-4 text-sm font-semibold text-gray-300"><i
                                        class="fas fa-user-tie mr-2"></i>Formateur</th>
                                <th class="p-4 text-sm font-semibold text-gray-300"><i class="fas fa-clock mr-2"></i>H.
                                    Hebdo</th>
                                <th class="p-4 text-sm font-semibold text-gray-300"><i
                                        class="fas fa-hourglass mr-2"></i>H. Total</th>
                                <th class="p-4 text-sm font-semibold text-gray-300"><i
                                        class="fas fa-weight mr-2"></i>Coef</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(discipline, index) in filteredDisciplines" :key="discipline.id"
                                class="border-b border-gray-700 hover:bg-gray-700 transition-all duration-300 animate-row-in"
                                :style="{ animationDelay: `${index * 0.1}s` }">
                                <td class="p-4 text-gray-200">{{ discipline.id }}</td>
                                <td class="p-4 text-gray-100 font-medium">{{ discipline.nom }}</td>
                                <td class="p-4 text-gray-400 truncate" :title="discipline.description">{{
                                    truncateDescription(discipline.description, 40) }}</td>
                                <td class="p-4 text-gray-200">{{ discipline.formateur }}</td>
                                <td class="p-4 text-gray-200">{{ discipline.heures_hebdo }}h</td>
                                <td class="p-4 text-gray-200">{{ discipline.heures_total }}h</td>
                                <td class="p-4 text-gray-200">{{ discipline.coefficient }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Cartes (Mobile) -->
                <div class="md:hidden space-y-4">
                    <div v-for="(discipline, index) in filteredDisciplines" :key="discipline.id"
                        class="bg-gray-800 p-4 rounded-xl shadow-lg animate-row-in"
                        :style="{ animationDelay: `${index * 0.1}s` }">
                        <h3 class="text-lg font-semibold text-gray-100 flex items-center">
                            <i class="fas fa-book-open mr-2 text-indigo-400"></i>{{ discipline.nom }}
                        </h3>
                        <p class="text-sm text-gray-400 mt-1" :title="discipline.description">
                            {{ truncateDescription(discipline.description, 60) }}
                        </p>
                        <p class="text-sm text-gray-200 mt-2"><i class="fas fa-user-tie mr-1"></i>{{
                            discipline.formateur }}</p>
                        <div class="flex justify-between mt-2 text-gray-200 text-sm">
                            <span><i class="fas fa-clock mr-1"></i>{{ discipline.heures_hebdo }}h/sem</span>
                            <span><i class="fas fa-hourglass mr-1"></i>{{ discipline.heures_total }}h</span>
                            <span><i class="fas fa-weight mr-1"></i>{{ discipline.coefficient }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message si vide -->
            <div v-else
                class="bg-gradient-to-r from-yellow-900 to-yellow-800 p-6 rounded-xl shadow-lg text-center animate-pulse">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-3xl mb-3"></i>
                <p class="text-gray-200 text-lg">Aucune discipline trouvée.</p>
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

/* Styles généraux */
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
    background: #6366f1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #818cf8;
}

/* Réactivité */
@media (max-width: 768px) {
    .max-w-6xl {
        max-width: 100%;
    }

    th,
    td {
        font-size: 0.875rem;
        padding: 0.75rem;
        min-width: 80px;
    }
}

@media (max-width: 640px) {
    .p-6 {
        padding: 1rem;
    }

    h1 {
        font-size: 1.75rem;
    }

    .text-xl {
        font-size: 1.125rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }
}
</style>