<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    formations: {
        type: Array,
        required: true,
    },
});

const search = ref('');
const filteredFormations = ref([...props.formations]);

watch(search, () => {
    filterFormations();
});

function filterFormations() {
    if (search.value) {
        filteredFormations.value = props.formations.filter(formation =>
            formation.titre.toLowerCase().includes(search.value.toLowerCase())
        );
    } else {
        filteredFormations.value = [...props.formations];
    }
}

function deleteFormation(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette formation ?")) {
        useForm().delete(route('formations.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                filteredFormations.value = filteredFormations.value.filter(formation => formation.id !== id);
            },
        });
    }
}
</script>

<template>
    <MonLayout>

        <Head title="Liste des Formations" />
        <div class="container mx-auto px-4 py-8 pt-2 text-gray-900 dark:text-gray-100">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6 animate-fade-in">
                <h1 class="text-3xl font-extrabold flex items-center text-primary">
                    <i class="fas fa-book mr-3 text-blue-500 dark:text-blue-400"></i>
                    Liste des Formations
                </h1>
                <!-- Recherche -->
                <div class="relative mb-6 animate-slide-up" :style="{ animationDelay: '0.2s' }">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                    <input type="text" v-model="search" placeholder="Rechercher une formation..."
                        class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" />
                </div>
                <TextLink href="/formations/create"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter
                </TextLink>
            </div>

            <!-- Tableau optimisé -->
            <div class="rounded-xl shadow-lg bg-white dark:bg-gray-800 animate-fade-in pb-4"
                :style="{ animationDelay: '0.4s' }">
                <div class="max-h-[500px] overflow-auto">
                    <table class="min-w-full table-fixed border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                            <tr>
                                <th
                                    class="w-16 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i class="fas fa-id-badge mr-1"></i>ID
                                </th>
                                <th
                                    class="w-28 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i class="fas fa-book mr-1"></i>Titre
                                </th>
                                <th
                                    class="w-32 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>Description
                                </th>
                           
                                <th
                                    class="w-40 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i class="fas fa-cogs mr-1"></i>Actions
                                </th>
                                <th
                                    class="w-40 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i class="fas fa-cogs mr-1"></i>Disciplines
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(formation, index) in filteredFormations" :key="formation.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 animate-row-in"
                                :style="{ animationDelay: `${0.1 * index}s` }">
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm">{{ formation.id }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm">{{ formation.titre }}
                                </td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="formation.description">
                                    {{ formation.description.length > 30 ? formation.description.substring(0, 30) +
                                    '...' : formation.description }}
                                </td>
                             
                                <td class="px-2 py-3 flex space-x-2">
                                    <TextLink :href="route('formations.edit', formation.id)"
                                        class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300 transition-colors duration-200 flex items-center text-sm">
                                        <i class="fas fa-edit mr-1 animate-pulse"></i>
                                        Modifier
                                    </TextLink>
                                    <button @click="deleteFormation(formation.id)"
                                        class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors duration-200 flex items-center text-sm">
                                        <i class="fas fa-trash mr-1 animate-pulse"></i>
                                        Supprimer
                                    </button>
                                </td>
                                <td>
                                    <TextLink :href="route('formations.manageDisciplineAssociations', formation.id)"
                                        class="text-blue-500 hover:text-blue-700">
                                        Gérer Disciplines
                                    </TextLink>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="filteredFormations.length === 0"
                        class="p-6 text-center text-gray-500 dark:text-gray-400 animate-pulse">
                        <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                        Aucun formation trouvé
                    </div>
                </div>
            </div>
        </div>
    </MonLayout>
</template>

<style scoped>
/* Couleurs personnalisées */
:root {
    --primary-color: #007BFF;
    /* Bleu */
    --secondary-color: #28A745;
    /* Vert */
    --danger-color: #DC3545;
    /* Rouge */
    --background-color: #F8F9FA;
    /* Gris clair */
    --card-background: #FFFFFF;
    /* Blanc */
    --text-color: #343A40;
    /* Gris foncé */
    --text-light: #6C757D;
    /* Gris clair */
}

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
body {
    background-color: var(--background-color);
    color: var(--text-color);
}

.container {
    max-width: 1280px;
}

table {
    table-layout: fixed;
    /* Force les largeurs définies */
}

/* Styles des cellules */
th,
td {
    white-space: nowrap;
    /* Empêche le retour à la ligne */
    overflow: hidden;
    text-overflow: ellipsis;
}

.bg-white {
    background-color: var(--card-background);
}

/* Scrollbar stylisée */
.max-h-[500px]::-webkit-scrollbar {
    width: 8px;
}

.max-h-[500px]::-webkit-scrollbar-track {
    background: #e5e7eb;
    border-radius: 4px;
}

.max-h-[500px]::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 4px;
}

.max-h-[500px]::-webkit-scrollbar-thumb:hover {
    background: #4b5563;
}

.dark .max-h-[500px]::-webkit-scrollbar-track {
    background: #374151;
}

.dark .max-h-[500px]::-webkit-scrollbar-thumb {
    background: #9ca3af;
}

.dark .max-h-[500px]::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}
</style>