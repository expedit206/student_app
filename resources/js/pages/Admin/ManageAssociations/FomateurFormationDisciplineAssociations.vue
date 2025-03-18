<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps<{
    formateur: {
        id: number;
        nom: string;
        disciplines: { id: number; nom: string; formation_id: number | null }[];
    };
    disciplines: { id: number; nom: string }[];
    formations: { id: number; titre: string; disciplines: { id: number; nom: string }[] }[];
    formateurFormations: { id: number; titre: string; disciplines: { id: number; nom: string }[] }[];
}>();

// Formation sélectionnée pour modification
const selectedFormation = ref('');

// Initialiser le formulaire
const form = useForm({
    discipline_ids: [],
    formation_id: '',
});

// Disciplines filtrées selon la formation sélectionnée
const filteredDisciplines = computed(() => {
    if (!selectedFormation.value) return [];
    const formation = props.formations.find(f => f.id === Number(selectedFormation.value));
    return formation ? formation.disciplines : [];
});

// Mettre à jour les disciplines cochées en fonction de la formation sélectionnée
watch(selectedFormation, (newFormationId) => {
    form.formation_id = newFormationId;
    if (newFormationId) {
        const formationId = Number(newFormationId);
        form.discipline_ids = props.formateur.disciplines
            .filter(d => d.formation_id === formationId)
            .map(d => d.id);
    } else {
        form.discipline_ids = [];
    }
});

// Soumission du formulaire
function submit() {
    form.post(route('formateurs.updateFormateurFormationDisciplineAssociations', props.formateur.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Message de succès géré par le contrôleur
        },
    });
}
</script>

<template>

    <Head :title="`Associer ${formateur.nom} à des Disciplines`" />
    <MonLayout>
        <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8 pb-16">
            <!-- Titre principal -->
            <h1
                class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-gray-100 mb-6 flex items-center animate-fade-in">
                <i class="fas fa-user-tie mr-3 text-blue-500 dark:text-blue-400 text-xl sm:text-2xl"></i>
                Associer {{ formateur.nom }} à des Disciplines
            </h1>

            <!-- Section des associations actuelles -->
            <section class="mb-8 bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg transition-all duration-300">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <i class="fas fa-list mr-2 text-green-500 dark:text-green-400"></i>
                    Associations actuelles
                </h2>
                <div v-if="formateurFormations.length > 0" class="space-y-4">
                    <div v-for="formation in formateurFormations" :key="formation.id"
                        class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-200">{{
                            formation.titre }}</h3>
                        <ul
                            class="mt-2 text-sm sm:text-base text-gray-700 dark:text-gray-300 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <li v-for="discipline in formation.disciplines" :key="discipline.id"
                                class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-sm"></i>
                                <span>{{ discipline.nom }}</span>
                            </li>
                        </ul>
                        <p v-if="formation.disciplines.length === 0"
                            class="text-gray-500 dark:text-gray-400 text-sm sm:text-base">
                            Aucune discipline associée à cette formation.
                        </p>
                    </div>
                </div>
                <p v-else class="text-gray-500 dark:text-gray-400 text-sm sm:text-base">
                    Aucune formation ou discipline actuellement associée au formateur.
                </p>
            </section>

            <!-- Section de modification -->
            <form @submit.prevent="submit"
                class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg transition-all duration-300">
                <div class="mb-6">
                    <label for="formation"
                        class="block text-sm sm:text-base font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Choisir une Formation pour modification
                    </label>
                    <div class="relative">
                        <i
                            class="fas fa-book absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                        <select id="formation" v-model="selectedFormation"
                            class="pl-10 pr-4 py-2 sm:py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 text-sm sm:text-base">
                            <option value="">Sélectionnez une formation</option>
                            <option v-for="formation in formations" :key="formation.id" :value="formation.id">
                                {{ formation.titre }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-6" v-if="selectedFormation">
                    <label class="block text-sm sm:text-base font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Disciplines
                    </label>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-64 overflow-y-auto p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 transition-all duration-200">
                        <label v-for="discipline in filteredDisciplines" :key="discipline.id"
                            class="flex items-center space-x-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-150">
                            <input type="checkbox" v-model="form.discipline_ids" :value="discipline.id"
                                class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 dark:border-gray-700 rounded" />
                            <span class="text-gray-900 dark:text-gray-100 text-sm sm:text-base">{{ discipline.nom
                                }}</span>
                        </label>
                        <p v-if="filteredDisciplines.length === 0"
                            class="text-gray-500 dark:text-gray-400 text-sm sm:text-base p-2">
                            Aucune discipline associée à cette formation.
                        </p>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing || !selectedFormation"
                    class="w-full bg-blue-500 text-white rounded-lg py-2 sm:py-3 text-sm sm:text-base hover:bg-blue-600 transition-all duration-300 flex items-center justify-center shadow-md disabled:bg-gray-400 dark:disabled:bg-gray-600">
                    <span v-if="!form.processing">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour les associations
                    </span>
                    <span v-else class="flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Envoi en cours...
                    </span>
                </button>
            </form>
        </div>
    </MonLayout>
</template>

<style scoped>
/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

/* Scrollbar stylisée */
.max-h-64::-webkit-scrollbar {
    width: 6px;
}

.max-h-64::-webkit-scrollbar-track {
    background: #e5e7eb;
    border-radius: 3px;
}

.max-h-64::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 3px;
}

.max-h-64::-webkit-scrollbar-thumb:hover {
    background: #4b5563;
}

.dark .max-h-64::-webkit-scrollbar-track {
    background: #374151;
}

.dark .max-h-64::-webkit-scrollbar-thumb {
    background: #9ca3af;
}

.dark .max-h-64::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}

/* Conteneur principal */
.container {
    padding: 1rem;
}

/* Réactivité et adaptabilité */
@media (min-width: 640px) {

    /* sm */
    .container {
        padding: 1.5rem;
    }
}

@media (min-width: 1024px) {

    /* lg */
    .container {
        padding: 2rem;
    }
}

/* Ajustements pour petits écrans */
@media (max-width: 639px) {
    h1 {
        font-size: 1.5rem;
    }

    h2 {
        font-size: 1.125rem;
    }

    h3 {
        font-size: 1rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .space-y-4>*+* {
        margin-top: 1rem;
    }
}
</style>