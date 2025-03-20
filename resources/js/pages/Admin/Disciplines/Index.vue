<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

// Définir les props avec des types explicites
const props = defineProps<{
    disciplines: {
        id: number;
        nom: string;
        description?: string;
        heures_hebdo: number;
        heures_total: number;
        formateurs: { id: number; nom: string; pivot: { formation_id: number } }[]; // Relation avec formateurs
        formations: { id: number; titre: string }[];
    }[];
    formations: {
        id: number;
        titre: string;
    }[];
}>();

const search = ref('');
const formationFilter = ref(''); // Filtre par formation
const filteredDisciplines = ref([...props.disciplines]);
const currentPage = ref(1);
const itemsPerPage = 10; // Nombre d'éléments par page

// Watcher pour filtrer les disciplines à chaque changement dans la recherche ou le filtre formation
watch([search, formationFilter], () => {
    filterDisciplines();
    currentPage.value = 1; // Réinitialiser à la première page lors d'un nouveau filtre
});

// Filtrer les disciplines selon la recherche et la formation
function filterDisciplines() {
    let result = [...props.disciplines];

    // Filtre par recherche
    if (search.value) {
        result = result.filter(discipline =>
            discipline.nom.toLowerCase().includes(search.value.toLowerCase()) ||
            (discipline.description && discipline.description.toLowerCase().includes(search.value.toLowerCase()))
        );
    }

    // Filtre par formation
    if (formationFilter.value) {
        result = result.filter(discipline =>
            discipline.formations.some(formation => formation.id === Number(formationFilter.value))
        );
    }

    filteredDisciplines.value = result;
}

// Supprimer une discipline
function deleteDiscipline(id: number) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette discipline ?")) {
        useForm().delete(route('disciplines.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                filteredDisciplines.value = filteredDisciplines.value.filter(discipline => discipline.id !== id);
            },
        });
    }
}

// Calcul des disciplines paginées
const paginatedDisciplines = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredDisciplines.value.slice(start, end);
});

// Nombre total de pages
const totalPages = computed(() => Math.ceil(filteredDisciplines.value.length / itemsPerPage));

// Navigation dans la pagination
function previousPage() {
    if (currentPage.value > 1) currentPage.value--;
}

function nextPage() {
    if (currentPage.value < totalPages.value) currentPage.value++;
}

// Fonction pour tronquer la description
function truncateDescription(description: string | undefined, maxLength: number) {
    if (!description) return 'Aucune description';
    return description.length > maxLength ? description.substring(0, maxLength) + '...' : description;
}
</script>

<template>

    <Head title="Liste des Disciplines" />
    <MonLayout>
        <div class="container mx-auto px-4 py-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-6 animate-fade-in">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 flex items-center">
                    <i class="fas fa-book mr-3 text-blue-500 dark:text-blue-400"></i>
                    Liste des Disciplines ({{ filteredDisciplines.length }})
                </h1>
                <TextLink :href="route('disciplines.create')"
                    class="mt-4 sm:mt-0 bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter une Discipline
                </TextLink>
            </div>

            <!-- Filtres -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="relative animate-slide-up">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                    <input type="text" v-model="search" placeholder="Rechercher par nom ou description..."
                        class="pl-12 pr-4 py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100" />
                </div>
                <div class="relative animate-slide-up">
                    <i
                        class="fas fa-filter absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                    <select v-model="formationFilter"
                        class="pl-12 pr-4 py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100">
                        <option value="">Toutes les formations</option>
                        <option v-for="formation in props.formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }} ({{ formation.id }})
                        </option>
                    </select>
                </div>
            </div>

            <!-- Tableau -->
            <div class="overflow-x-auto rounded-xl shadow-lg bg-white dark:bg-gray-800 animate-fade-in">
                <div class="max-h-[60vh] overflow-y-auto">
                    <table class="min-w-full table-fixed">
                        <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                            <tr>
                                <th
                                    class="w-16 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-id-badge mr-1"></i>ID
                                </th>
                                <th
                                    class="w-1/5 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-book-open mr-1"></i>Nom
                                </th>
                                <th
                                    class="w-1/4 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-info-circle mr-1"></i>Description
                                </th>
                                <th
                                    class="w-16 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-clock mr-1"></i>H. Hebdo
                                </th>
                                <th
                                    class="w-16 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-hourglass mr-1"></i>H. Total
                                </th>
                                <th
                                    class="w-1/5 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-user-tie mr-1"></i>Formateur
                                </th>
                                <th
                                    class="w-16 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-weight mr-1"></i>Coef
                                </th>
                                <th
                                    class="w-1/6 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-cogs mr-1"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(discipline, index) in paginatedDisciplines" :key="discipline.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 animate-row-in"
                                :style="{ animationDelay: `${0.1 * index}s` }">
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm">{{ discipline.id }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="discipline.nom">
                                    {{ discipline.nom }}
                                </td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="discipline.description">
                                    {{ truncateDescription(discipline.description, 30) }}
                                </td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm">{{
                                    discipline.heures_hebdo }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm">{{
                                    discipline.heures_total }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="discipline.formateur">
                                    {{ discipline.formateur }}
                                </td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm">{{ discipline.heures_total/10
                                    }}</td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <TextLink :href="route('disciplines.edit', discipline.id)"
                                        class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300 transition-colors duration-200 flex items-center text-sm">
                                        <i class="fas fa-edit mr-1 animate-pulse"></i>
                                        Modifier
                                    </TextLink>
                                    <button @click="deleteDiscipline(discipline.id)"
                                        class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors duration-200 flex items-center text-sm">
                                        <i class="fas fa-trash mr-1 animate-pulse"></i>
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="paginatedDisciplines.length === 0">
                                <td colspan="8"
                                    class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 animate-pulse">
                                    <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                                    Aucune discipline trouvée
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="filteredDisciplines.length > 0"
                    class="flex justify-between items-center py-2 px-4 bg-gray-50 dark:bg-gray-700 rounded-b-lg text-sm">
                    <button @click="previousPage" :disabled="currentPage === 1"
                        class="px-2 py-1 bg-blue-500 text-white rounded disabled:bg-gray-300 dark:disabled:bg-gray-600 hover:bg-blue-600 transition-all duration-200">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <span class="text-gray-700 dark:text-gray-200">
                        Page {{ currentPage }} / {{ totalPages }} ({{ filteredDisciplines.length }} disciplines)
                    </span>
                    <button @click="nextPage" :disabled="currentPage === totalPages"
                        class="px-2 py-1 bg-blue-500 text-white rounded disabled:bg-gray-300 dark:disabled:bg-gray-600 hover:bg-blue-600 transition-all duration-200">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </MonLayout>
</template>

<style scoped>
/* Animations personnalisées */

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
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

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out forwards;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
}

/* Styles pour la table */
table {
    table-layout: fixed;
}

th,
td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Scrollbar stylisée */
.max-h-[60vh]::-webkit-scrollbar {
    width: 8px;
}

.max-h-[60vh]::-webkit-scrollbar-track {
    background: #e5e7eb;
    border-radius: 4px;
}

.max-h-[60vh]::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 4px;
}

.max-h-[60vh]::-webkit-scrollbar-thumb:hover {
    background: #4b5563;
}

.dark .max-h-[60vh]::-webkit-scrollbar-track {
    background: #374151;
}

.dark .max-h-[60vh]::-webkit-scrollbar-thumb {
    background: #9ca3af;
}

.dark .max-h-[60vh]::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}

/* Réactivité */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    th,
    td {
        font-size: 0.75rem;
        padding: 0.5rem;
        min-width: 60px;
    }
}
    /* Scrollbar stylisée */
.max-h-[60vh]::-webkit-scrollbar { width: 8px; }
.max-h-[60vh]::-webkit-scrollbar-track { background: #e5e7eb; border-radius: 4px; }
.max-h-[60vh]::-webkit-scrollbar-thumb { background: #6b7280; border-radius: 4px; }
.max-h-[60vh]::-webkit-scrollbar-thumb:hover { background: #4b5563; }
.dark .max-h-[60vh]::-webkit-scrollbar-track { background: #374151; }
.dark .max-h-[60vh]::-webkit-scrollbar-thumb { background: #9ca3af; }
.dark .max-h-[60vh]::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>