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

// Couleurs dynamiques pour les icônes
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

<template>

    <Head title="Liste des Formations" />
    <MonLayout>
        <div class="container mx-auto px-4 py-8 text-gray-900 dark:text-gray-100">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-6 animate-fade-in">
                <h1 class="text-2xl sm:text-3xl font-extrabold flex items-center text-primary">
                    <i
                        :class="['fas fa-book mr-3', iconColors.base, 'transition-colors duration-300 hover:' + iconColors.hover]"></i>
                    Liste des Formations
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
                    <TextLink :href="route('formations.create')"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition-all duration-300 flex items-center">
                        <i class="fas fa-plus mr-2 transition-colors duration-300 hover:text-green-300"></i>
                        Ajouter
                    </TextLink>
                </div>
            </div>

            <!-- Liste des cartes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[70vh] overflow-y-auto animate-fade-in bg-gray-100 dark:bg-gray-900 p-4 rounded-xl"
                :style="{ animationDelay: '0.4s' }">
                <div v-for="(formation, index) in filteredFormations" :key="formation.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-card-in"
                    :style="{ animationDelay: `${0.1 * index}s` }">
                    <div class="flex flex-col space-y-3">
                        <!-- Titre et ID -->
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate"
                                :title="formation.titre">
                                <i :class="['fas fa-book mr-2', iconColors.base]"></i>
                                {{ formation.titre }}
                            </h2>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full">
                                ID: {{ formation.id }}
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2" :title="formation.description">
                            {{ formation.description }}
                        </p>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-3 pt-2 border-t border-gray-300 dark:border-gray-600">
                            <TextLink :href="route('formations.edit', formation.id)"
                                :class="['flex items-center text-sm', iconColors.edit, 'hover:' + iconColors.editHover, 'transition-colors duration-300']">
                                <i class="fas fa-edit mr-1 animate-pulse"></i>
                                Modifier
                            </TextLink>
                            <button @click="deleteFormation(formation.id)"
                                :class="['flex items-center text-sm', iconColors.delete, 'hover:' + iconColors.deleteHover, 'transition-colors duration-300']">
                                <i class="fas fa-trash mr-1 animate-pulse"></i>
                                Supprimer
                            </button>
                            <TextLink :href="route('formations.manageDisciplineAssociations', formation.id)"
                                :class="['flex items-center text-sm', iconColors.manage, 'hover:' + iconColors.manageHover, 'transition-colors duration-300']">
                                <i class="fas fa-cogs mr-1"></i>
                                Disciplines
                            </TextLink>
                        </div>
                    </div>
                </div>

                <!-- Message vide -->
                <div v-if="filteredFormations.length === 0"
                    class="col-span-full p-6 text-center text-gray-500 dark:text-gray-400 animate-pulse bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                    <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                    Aucune formation trouvée
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

.animate-card-in {
    animation: cardIn 0.5s ease-out forwards;
}

/* Styles optimisés */
.container {
    max-width: 1280px;
}

/* Scrollbar stylisée */
.max-h-[70vh] {
    position: relative;
    overflow-y: auto;
}

.max-h-[70vh]::-webkit-scrollbar {
    width: 8px;
}

.max-h-[70vh]::-webkit-scrollbar-track {
    background: #e5e7eb;
    border-radius: 4px;
}

.max-h-[70vh]::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 4px;
}

.max-h-[70vh]::-webkit-scrollbar-thumb:hover {
    background: #4b5563;
}

.dark .max-h-[70vh]::-webkit-scrollbar-track {
    background: #374151;
}

.dark .max-h-[70vh]::-webkit-scrollbar-thumb {
    background: #9ca3af;
}

.dark .max-h-[70vh]::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}

/* Responsive et séparation visible */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.grid {
    background-color: #f1f5f9;
}

/* Fond clair pour contraste */
.dark .grid {
    background-color: #1f2937;
}

/* Fond sombre pour contraste */

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

    .p-5 {
        padding: 1rem;
    }

    .gap-6 {
        gap: 1rem;
    }

    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .shadow-md {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
}

@media (min-width: 641px) and (max-width: 1023px) {
    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (min-width: 1024px) {
    .grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}
</style>