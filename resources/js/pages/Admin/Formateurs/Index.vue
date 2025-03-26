<template>
    <MonLayout>

        <Head title="Liste des Formateurs" />
        <div class="container mx-auto px-4 py-8 text-gray-900 dark:text-gray-100">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-6 animate-fade-in">
                <h1 class="text-2xl sm:text-3xl font-extrabold flex items-center">
                    <i
                        :class="['fas fa-chalkboard-teacher mr-3', iconColors.base, 'transition-colors duration-300 hover:' + iconColors.hover]"></i>
                    Liste des Formateurs ({{ filteredFormateurs.length }})
                </h1>
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-4 sm:mt-0">
                    <!-- Recherche -->
                    <div class="relative w-full sm:w-64 animate-slide-up" :style="{ animationDelay: '0.2s' }">
                        <i
                            :class="['fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2', iconColors.base]"></i>
                        <input type="text" v-model="search" placeholder="Rechercher..."
                            class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-sm sm:text-base" />
                    </div>
                    <!-- Bouton Ajouter -->
                    <TextLink :href="route('formateurs.create')"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 flex items-center">
                        <i class="fas fa-plus mr-2 transition-colors duration-300 hover:text-green-300"></i>
                        Ajouter
                    </TextLink>
                </div>
            </div>

            <!-- Desktop : Tableau -->
            <div class="hidden sm:block rounded-xl shadow-lg bg-white dark:bg-gray-800 animate-fade-in"
                :style="{ animationDelay: '0.4s' }">
                <div class="max-h-[500px] overflow-y-auto">
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
                                    class="w-32 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-briefcase mr-1', iconColors.base]"></i>Spécialité
                                </th>
                                <th
                                    class="w-32 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-phone mr-1', iconColors.base]"></i>Téléphone
                                </th>
                                <th
                                    class="w-36 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-graduation-cap mr-1', iconColors.base]"></i>Diplôme
                                </th>
                                <th
                                    class="w-40 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-cogs mr-1', iconColors.base]"></i>Actions
                                </th>
                                <th
                                    class="w-40 px-2 py-3 text-left font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    <i :class="['fas fa-cogs mr-1', iconColors.base]"></i>Associations
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(formateur, index) in filteredFormateurs" :key="formateur.id"
                                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 animate-row-in"
                                :style="{ animationDelay: `${0.1 * index}s` }">
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm">{{ formateur.id }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="formateur.nom">{{ formateur.nom }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="formateur.prenom">{{ formateur.prenom }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm">
                                    <span
                                        class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-2 py-1 rounded-full text-xs truncate"
                                        :title="formateur.specialite">
                                        {{ formateur.specialite }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="formateur.telephone">{{ formateur.telephone }}</td>
                                <td class="px-2 py-3 text-gray-900 dark:text-gray-100 text-sm truncate"
                                    :title="formateur.dernier_diplome">{{ formateur.dernier_diplome }}</td>
                                <td class="px-2 py-3 flex space-x-2">
                                    <TextLink :href="route('formateurs.edit', formateur.id)"
                                        :class="['flex items-center text-sm', iconColors.edit, 'hover:' + iconColors.editHover, 'transition-colors duration-300']">
                                        <i class="fas fa-edit mr-1 animate-pulse"></i>
                                        Modifier
                                    </TextLink>
                                    <button @click="deleteFormateur(formateur.id)"
                                        :class="['flex items-center text-sm', iconColors.delete, 'hover:' + iconColors.deleteHover, 'transition-colors duration-300']">
                                        <i class="fas fa-trash mr-1 animate-pulse"></i>
                                        Supprimer
                                    </button>
                                </td>
                                <td>
                                    <TextLink :href="route('formateurs.manageAssociations', formateur.id)"
                                        :class="['text-sm', iconColors.manage, 'hover:' + iconColors.manageHover, 'transition-colors duration-300']">
                                        Gérer
                                    </TextLink>
                                </td>
                            </tr>
                            <tr v-if="filteredFormateurs.length === 0">
                                <td colspan="8" class="p-6 text-center text-gray-500 dark:text-gray-400 animate-pulse">
                                    <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                                    Aucun formateur trouvé
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile : Cartes -->
            <div class="sm:hidden grid grid-cols-1 gap-4 max-h-[500px] overflow-y-auto animate-fade-in bg-gray-100 dark:bg-gray-900 p-4 rounded-xl"
                :style="{ animationDelay: '0.4s' }">
                <div v-for="(formateur, index) in filteredFormateurs" :key="formateur.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-4 hover:shadow-xl transition-all duration-300 animate-card-in"
                    :style="{ animationDelay: `${0.1 * index}s` }">
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate"
                                :title="formateur.nom + ' ' + formateur.prenom">
                                <i :class="['fas fa-user mr-2', iconColors.base]"></i>
                                {{ formateur.nom }} {{ formateur.prenom }}
                            </h2>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">
                                ID: {{ formateur.id }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <i :class="['fas fa-briefcase mr-1', iconColors.base]"></i>
                            <span
                                class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 px-2 py-1 rounded-full text-xs"
                                :title="formateur.specialite">
                                {{ formateur.specialite }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300" :title="formateur.telephone">
                            <i :class="['fas fa-phone mr-1', iconColors.base]"></i>{{ formateur.telephone }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2"
                            :title="formateur.dernier_diplome">
                            <i :class="['fas fa-graduation-cap mr-1', iconColors.base]"></i>{{ formateur.dernier_diplome
                            }}
                        </p>
                        <div class="flex gap-3 pt-2 border-t border-gray-300 dark:border-gray-600">
                            <TextLink :href="route('formateurs.edit', formateur.id)"
                                :class="['flex items-center text-sm', iconColors.edit, 'hover:' + iconColors.editHover, 'transition-colors duration-300']">
                                <i class="fas fa-edit mr-1 animate-pulse"></i>
                                Modifier
                            </TextLink>
                            <button @click="deleteFormateur(formateur.id)"
                                :class="['flex items-center text-sm', iconColors.delete, 'hover:' + iconColors.deleteHover, 'transition-colors duration-300']">
                                <i class="fas fa-trash mr-1 animate-pulse"></i>
                                Supprimer
                            </button>
                            <TextLink :href="route('formateurs.manageAssociations', formateur.id)"
                                :class="['flex items-center text-sm', iconColors.manage, 'hover:' + iconColors.manageHover, 'transition-colors duration-300']">
                                <i class="fas fa-cogs mr-1"></i>
                                Gérer
                            </TextLink>
                        </div>
                    </div>
                </div>
                <div v-if="filteredFormateurs.length === 0"
                    class="col-span-full p-4 text-center text-gray-500 dark:text-gray-400 animate-pulse bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                    <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                    Aucun formateur trouvé
                </div>
            </div>
        </div>
    </MonLayout>
</template>

<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    formateurs: {
        type: Array,
        required: true,
    },
});

const search = ref('');
const filteredFormateurs = ref([...props.formateurs]);

watch(search, () => {
    filterFormateurs();
});

function filterFormateurs() {
    if (search.value) {
        filteredFormateurs.value = props.formateurs.filter(formateur =>
            formateur.nom.toLowerCase().includes(search.value.toLowerCase()) ||
            formateur.prenom.toLowerCase().includes(search.value.toLowerCase()) ||
            formateur.specialite.toLowerCase().includes(search.value.toLowerCase())
        );
    } else {
        filteredFormateurs.value = [...props.formateurs];
    }
}

function deleteFormateur(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce formateur ?")) {
        useForm().delete(route('formateurs.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                filteredFormateurs.value = filteredFormateurs.value.filter(formateur => formateur.id !== id);
            },
        });
    }
}

const iconColors = {
    base: 'text-blue-500',
    hover: 'text-blue-400',
    edit: 'text-yellow-500',
    editHover: 'text-yellow-400',
    delete: 'text-red-500',
    deleteHover: 'text-red-400',
    manage: 'text-purple-500',
    manageHover: 'text-purple-400',
};
</script>

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
.max-h-[500px] {
    position: relative;
    overflow-y: auto;
}

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