<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    disciplines: {
        id: number;
        nom: string;
        description?: string;
        heures_hebdo: number;
        heures_total: number;
        formateurs: { id: number; nom: string; pivot: { formation_id: number } }[];
        formations: { id: number; titre: string }[];
    }[];
    formations: {
        id: number;
        titre: string;
    }[];
}>();

console.log(props.disciplines);


const search = ref('');
const formationFilter = ref('');
const filteredDisciplines = ref([...props.disciplines]);
const currentPage = ref(1);
const itemsPerPage = 10;

watch([search, formationFilter], () => {
    filterDisciplines();
    currentPage.value = 1;
});

function filterDisciplines() {
    let result = [...props.disciplines];
    if (search.value) {
        result = result.filter(discipline =>
            discipline.nom.toLowerCase().includes(search.value.toLowerCase()) ||
            (discipline.description && discipline.description.toLowerCase().includes(search.value.toLowerCase()))
        );
    }
    if (formationFilter.value) {
        result = result.filter(discipline =>
            discipline.formations.some(formation => formation.id === Number(formationFilter.value))
        );
    }
    filteredDisciplines.value = result;
}

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

const paginatedDisciplines = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredDisciplines.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredDisciplines.value.length / itemsPerPage));

function previousPage() {
    if (currentPage.value > 1) currentPage.value--;
}

function nextPage() {
    if (currentPage.value < totalPages.value) currentPage.value++;
}

function truncateDescription(description: string | undefined, maxLength: number) {
    if (!description) return 'Aucune description';
    return description.length > maxLength ? description.substring(0, maxLength) + '...' : description;
}

// Couleurs dynamiques pour les icônes
const iconColors = {
    base: 'text-blue-500',
    hover: 'text-blue-400',
    edit: 'text-yellow-500',
    editHover: 'text-yellow-400',
    delete: 'text-red-500',
    deleteHover: 'text-red-400',
};

</script>

<template>

    <Head title="Liste des Disciplines" />
    <MonLayout>
        <div class="container mx-auto px-4 py-6 text-gray-900 dark:text-gray-100">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-6 animate-fade-in">
                <h1 class="text-2xl sm:text-3xl font-extrabold flex items-center">
                    <i
                        :class="['fas fa-book mr-3', iconColors.base, 'transition-colors duration-300 hover:' + iconColors.hover]"></i>
                    Liste des Disciplines ({{ filteredDisciplines.length }})
                </h1>
                <TextLink :href="route('disciplines.create')"
                    class="mt-4 sm:mt-0 bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 flex items-center">
                    <i class="fas fa-plus mr-2 transition-colors duration-300 hover:text-green-300"></i>
                    Ajouter
                </TextLink>
            </div>

            <!-- Filtres -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="relative animate-slide-up">
                    <i
                        :class="['fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2', iconColors.base]"></i>
                    <input type="text" v-model="search" placeholder="Rechercher..."
                        class="pl-12 pr-4 py-2 sm:py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-sm sm:text-base" />
                </div>
                <div class="relative animate-slide-up">
                    <i
                        :class="['fas fa-filter absolute left-4 top-1/2 transform -translate-y-1/2', iconColors.base]"></i>
                    <select v-model="formationFilter"
                        class="pl-12 pr-4 py-2 sm:py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-sm sm:text-base">
                        <option value="">Toutes les formations</option>
                        <option v-for="formation in props.formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Desktop : Tableau -->
            <div class="hidden sm:block overflow-x-auto rounded-xl shadow-lg bg-white dark:bg-gray-800 animate-fade-in">
                <div class="max-h-[60vh] overflow-y-auto">
                    <table class="min-w-full table-fixed">
                        <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                            <tr>
                                <th
                                    class="w-16 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i :class="['fas fa-id-badge mr-1', iconColors.base]"></i>ID
                                </th>
                                <th
                                    class="w-1/5 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i :class="['fas fa-book-open mr-1', iconColors.base]"></i>Nom
                                </th>
                                <th
                                    class="w-1/4 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i :class="['fas fa-info-circle mr-1', iconColors.base]"></i>Description
                                </th>
                                <th
                                    class="w-16 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i :class="['fas fa-clock mr-1', iconColors.base]"></i>H. Hebdo
                                </th>
                                <th
                                    class="w-16 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i :class="['fas fa-hourglass mr-1', iconColors.base]"></i>H. Total
                                </th>
                                <th
                                    class="w-1/5 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i :class="['fas fa-user-tie mr-1', iconColors.base]"></i>Formateurs
                                </th>
                                <th
                                    class="w-16 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i :class="['fas fa-weight mr-1', iconColors.base]"></i>Coef
                                </th>
                                <th
                                    class="w-1/6 px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <i :class="['fas fa-cogs mr-1', iconColors.base]"></i>Actions
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
                                    
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm truncate">
                                    {{discipline.formateurs.map(f => f.nom).join(', ') || 'Aucun'}}
                                </td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-sm">{{
                                    discipline.heures_total / 10 }}</td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <TextLink :href="route('disciplines.edit', discipline.id)"
                                        :class="['flex items-center text-sm', iconColors.edit, 'hover:' + iconColors.editHover, 'transition-colors duration-300']">
                                        <i class="fas fa-edit mr-1 animate-pulse"></i>
                                        Modifier
                                    </TextLink>
                                    <button @click="deleteDiscipline(discipline.id)"
                                        :class="['flex items-center text-sm', iconColors.delete, 'hover:' + iconColors.deleteHover, 'transition-colors duration-300']">
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
            </div>

            <!-- Mobile : Cartes -->
            <div
                class="sm:hidden grid grid-cols-1 gap-4 max-h-[60vh] overflow-y-auto animate-fade-in bg-gray-100 dark:bg-gray-900 p-4 rounded-xl">
                <div v-for="(discipline, index) in paginatedDisciplines" :key="discipline.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-4 hover:shadow-xl transition-all duration-300 animate-card-in"
                    :style="{ animationDelay: `${0.1 * index}s` }">
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate"
                                :title="discipline.nom">
                                <i :class="['fas fa-book-open mr-2', iconColors.base]"></i>
                                {{ discipline.nom }}
                            </h2>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">
                                ID: {{ discipline.id }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2"
                            :title="discipline.description">
                            {{ truncateDescription(discipline.description, 50) }}
                        </p>
                        <div class="text-xs text-gray-600 dark:text-gray-300">
                            <p><i :class="['fas fa-clock mr-1', iconColors.base]"></i>Hebdo: {{ discipline.heures_hebdo
                                }}h</p>
                            <p><i :class="['fas fa-hourglass mr-1', iconColors.base]"></i>Total: {{
                                discipline.heures_total }}h</p>
                            <p><i :class="['fas fa-user-tie mr-1', iconColors.base]">

                            </i>
                            {{discipline.formateurs.map(f=> f.nom).join(', ') || 'Aucun' }}
                                </p>
                            <p><i :class="['fas fa-weight mr-1', iconColors.base]"></i>Coef: {{ discipline.heures_total
                                / 10 }}</p>
                        </div>
                        <div class="flex gap-3 pt-2 border-t border-gray-300 dark:border-gray-600">
                            <TextLink :href="route('disciplines.edit', discipline.id)"
                                :class="['flex items-center text-sm', iconColors.edit, 'hover:' + iconColors.editHover, 'transition-colors duration-300']">
                                <i class="fas fa-edit mr-1 animate-pulse"></i>
                                Modifier
                            </TextLink>
                            <button @click="deleteDiscipline(discipline.id)"
                                :class="['flex items-center text-sm', iconColors.delete, 'hover:' + iconColors.deleteHover, 'transition-colors duration-300']">
                                <i class="fas fa-trash mr-1 animate-pulse"></i>
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="paginatedDisciplines.length === 0"
                    class="col-span-full p-4 text-center text-gray-500 dark:text-gray-400 animate-pulse bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                    <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                    Aucune discipline trouvée
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="filteredDisciplines.length > 0"
                class="flex flex-col sm:flex-row justify-between items-center py-2 px-4 bg-gray-50 dark:bg-gray-700 rounded-b-lg text-sm mt-4 space-y-2 sm:space-y-0">
                <button @click="previousPage" :disabled="currentPage === 1"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg disabled:bg-gray-300 dark:disabled:bg-gray-600 hover:bg-blue-600 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Précédent
                </button>
                <span class="text-gray-700 dark:text-gray-200">
                    Page {{ currentPage }} / {{ totalPages }} ({{ filteredDisciplines.length }} disciplines)
                </span>
                <button @click="nextPage" :disabled="currentPage === totalPages"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg disabled:bg-gray-300 dark:disabled:bg-gray-600 hover:bg-blue-600 transition-all duration-200">
                    <i class="fas fa-arrow-right mr-2"></i>Suivant
                </button>
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

@keyframes cardIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
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

.animate-card-in {
    animation: cardIn 0.5s ease-out forwards;
}

/* Styles optimisés */
.container {
    max-width: 1280px;
}

table {
    table-layout: fixed;
}

th,
td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Scrollbar stylisée */
.max-h-[60vh] {
    position: relative;
    overflow-y: auto;
}

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

/* Réactivité et attractivité */
@media (max-width: 640px) {
    .container {
        padding: 1rem;
    }

    .text-3xl {
        font-size: 1.5rem;
    }

    .text-lg {
        font-size: 1.125rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .py-3 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .p-4 {
        padding: 1rem;
    }

    .gap-4 {
        gap: 1rem;
    }

    .shadow-md {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .grid {
        background-color: #f1f5f9;
    }

    /* Fond clair pour contraste */
    .dark .grid {
        background-color: #1f2937;
    }

    /* Fond sombre pour contraste */
}
</style>