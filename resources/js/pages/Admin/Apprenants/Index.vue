<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    apprenants: {
        type: Object,
        required: true,
    },
});

const search = ref('');
const selectedFormation = ref(''); // Filtre par formation
const filteredApprenants = ref([...props.apprenants]);
const currentPage = ref(1);
const itemsPerPage = 20;

// Liste unique des formations pour le menu déroulant
const formations = computed(() => {
    const uniqueFormations = new Set(props.apprenants.map(apprenant => apprenant.formation?.titre || 'Non assignée'));
    return ['Choisir une formation', ...Array.from(uniqueFormations)];
});

watch([search, selectedFormation], () => {
    filterApprenants();
    currentPage.value = 1; // Réinitialiser à la première page lors d'un changement de filtre
});

function filterApprenants() {
    let result = [...props.apprenants];

    if (search.value) {
        result = result.filter(apprenant =>
            (apprenant.nom?.toLowerCase().includes(search.value.toLowerCase()) || '') ||
            (apprenant.prenom?.toLowerCase().includes(search.value.toLowerCase()) || '') ||
            (apprenant.user?.email?.toLowerCase().includes(search.value.toLowerCase()) || '')
        );
    }

    if (selectedFormation.value && selectedFormation.value !== 'Choisir une formation') {
        result = result.filter(apprenant =>
            (apprenant.formation?.titre || 'Non assignée') === selectedFormation.value
        );
    }

    filteredApprenants.value = result;
}

function deleteApprenant(id: number) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?")) {
        useForm().delete(route('apprenants.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                filteredApprenants.value = filteredApprenants.value.filter(apprenant => apprenant.id !== id);
            },
        });
    }
}

const paginatedApprenants = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredApprenants.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredApprenants.value.length / itemsPerPage));
const totalApprenants = computed(() => filteredApprenants.value.length);

function previousPage() {
    if (currentPage.value > 1) currentPage.value--;
}

function nextPage() {
    if (currentPage.value < totalPages.value) currentPage.value++;
}

// Couleurs dynamiques pour les icônes
const iconColors = {
    base: 'text-blue-500',
    hover: 'text-blue-400',
    delete: 'text-red-500',
    deleteHover: 'text-red-400',
    edit: 'text-yellow-500',
    editHover: 'text-yellow-400',
};
</script>

<template>

    <Head title="Liste des Étudiants" />
    <MonLayout>
        <div class="container mx-auto px-4 py-3 text-gray-900 dark:text-gray-100">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-3 animate-fade-in">
                <h1 class="text-2xl sm:text-3xl font-extrabold flex items-center">
                    <i :class="['fas fa-user-graduate mr-3', iconColors.base, 'transition-colors duration-300']"></i>
                    Liste des Étudiants
                </h1>
                <TextLink :href="route('apprenants.create')"
                    class="mt-2 sm:mt-0 bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 flex items-center">
                    <i class="fas fa-plus mr-2 transition-colors duration-300 hover:text-green-300"></i>
                    Ajouter
                </TextLink>
            </div>

            <!-- Filtres -->
            <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-4 mb-4 animate-slide-up"
                :style="{ animationDelay: '0.2s' }">
                <div class="relative flex-1">
                    <i
                        :class="['fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2', iconColors.base]"></i>
                    <input type="text" v-model="search" placeholder="Rechercher..."
                        class="w-full pl-12 pr-4 py-2 sm:py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base" />
                </div>
                <div class="relative sm:w-48">
                    <i :class="['fas fa-book absolute left-4 top-1/2 transform -translate-y-1/2', iconColors.base]"></i>
                    <select v-model="selectedFormation"
                        class="w-full pl-12 pr-4 py-2 sm:py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base">
                        <option v-for="formation in formations" :key="formation" :value="formation">
                            {{ formation }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Compteur -->
            <div class="mb-4 text-gray-600 dark:text-gray-300 text-sm sm:text-base">
                <span>{{ totalApprenants }} apprenants trouvés</span>
            </div>

            <!-- Tableau (Desktop) / Cartes (Mobile) -->
            <div class="rounded-xl shadow-lg bg-white dark:bg-gray-800 animate-fade-in"
                :style="{ animationDelay: '0.4s' }">
                <!-- Desktop : Tableau -->
                <div class="hidden sm:block max-h-[60vh] overflow-y-auto relative">
                    <table class="min-w-full table-fixed">
                        <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                            <tr>
                                <th
                                    class="w-16 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-id-badge mr-1', iconColors.base]"></i>ID
                                </th>
                                <th
                                    class="w-28 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-user mr-1', iconColors.base]"></i>Nom
                                </th>
                                <th
                                    class="w-28 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-user-circle mr-1', iconColors.base]"></i>Prénom
                                </th>
                                <th
                                    class="w-48 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-envelope mr-1', iconColors.base]"></i>Email
                                </th>
                                <th
                                    class="w-36 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-book mr-1', iconColors.base]"></i>Formation
                                </th>
                                <th
                                    class="w-40 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-cogs mr-1', iconColors.base]"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(apprenant, index) in paginatedApprenants" :key="apprenant.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 animate-row-in"
                                :style="{ animationDelay: `${0.1 * index}s` }">
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm">{{ apprenant.id }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="apprenant.nom">{{ apprenant.nom }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="apprenant.prenom">{{ apprenant.prenom }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="apprenant.user?.email">{{ apprenant.user?.email || 'N/A' }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="apprenant.formation?.titre">
                                    <span
                                        class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 px-2 py-1 rounded-full text-xs">
                                        {{ apprenant.formation?.titre || 'Non assignée' }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 flex space-x-2">
                                    <TextLink :href="route('apprenants.edit', apprenant.id)"
                                        :class="['flex items-center text-sm', iconColors.edit, 'hover:' + iconColors.editHover, 'transition-colors duration-200']">
                                        <i class="fas fa-edit mr-1 animate-pulse"></i>
                                        Modifier
                                    </TextLink>
                                    <button @click="deleteApprenant(apprenant.id)"
                                        :class="['flex items-center text-sm', iconColors.delete, 'hover:' + iconColors.deleteHover, 'transition-colors duration-200']">
                                        <i class="fas fa-trash mr-1 animate-pulse"></i>
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile : Cartes -->
                <div class="sm:hidden space-y-3 p-3 max-h-[60vh] overflow-y-auto">
                    <div v-for="(apprenant, index) in paginatedApprenants" :key="apprenant.id"
                        class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 animate-row-in"
                        :style="{ animationDelay: `${0.1 * index}s` }">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <i :class="['fas fa-user mr-2', iconColors.base]"></i>
                                    {{ apprenant.nom }} {{ apprenant.prenom }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-300 truncate"
                                    :title="apprenant.user?.email">
                                    <i :class="['fas fa-envelope mr-2', iconColors.base]"></i>
                                    {{ apprenant.user?.email || 'N/A' }}
                                </p>
                                <p class="text-xs mt-1">
                                    <span
                                        class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 px-2 py-1 rounded-full">
                                        {{ apprenant.formation?.titre || 'Non assignée' }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <TextLink :href="route('apprenants.edit', apprenant.id)"
                                    :class="['text-sm', iconColors.edit, 'hover:' + iconColors.editHover, 'transition-colors duration-200']">
                                    <i class="fas fa-edit"></i>
                                </TextLink>
                                <button @click="deleteApprenant(apprenant.id)"
                                    :class="['text-sm', iconColors.delete, 'hover:' + iconColors.deleteHover, 'transition-colors duration-200']">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="filteredApprenants.length === 0"
                        class="p-4 text-center text-gray-500 dark:text-gray-400 animate-pulse">
                        <i class="fas fa-exclamation-circle mr-2 text-lg"></i>
                        Aucun étudiant trouvé
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="filteredApprenants.length > 0"
                    class="flex flex-col sm:flex-row justify-between items-center py-2 px-4 bg-gray-50 dark:bg-gray-700 rounded-b-lg text-sm space-y-2 sm:space-y-0">
                    <button @click="previousPage" :disabled="currentPage === 1"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg disabled:bg-gray-300 dark:disabled:bg-gray-600 hover:bg-blue-600 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Précédent
                    </button>
                    <span class="text-gray-700 dark:text-gray-200">
                        {{ currentPage }} / {{ totalPages }}
                    </span>
                    <button @click="nextPage" :disabled="currentPage === totalPages"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg disabled:bg-gray-300 dark:disabled:bg-gray-600 hover:bg-blue-600 transition-all duration-200">
                        <i class="fas fa-arrow-right mr-2"></i>Suivant
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

/* Styles optimisés */
.container {
    max-width: 1200px;
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

/* Réactivité mobile */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .text-3xl {
        font-size: 1.5rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .px-2 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .py-3 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .space-x-2>*+* {
        margin-left: 0.5rem;
    }

    .shadow-lg {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
}
</style>