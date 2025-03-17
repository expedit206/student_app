<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    formation: {
        id: number;
        titre: string;
        disciplines: { id: number; nom: string }[];
    };
    disciplines: { id: number; nom: string }[];
}>();

// Initialiser le formulaire avec les disciplines actuelles de la formation
const form = useForm({
    discipline_ids: props.formation.disciplines.map(d => d.id),
});
console.log(form.discipline_ids);

// Soumission du formulaire
function submit() {
    form.post(route('formations.updateDisciplineAssociations', props.formation.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Message de succès géré par le contrôleur
        },
    });
}
</script>

<template>

    <Head :title="`Associer des Disciplines à ${formation.titre}`" />
    <MonLayout>
        <div class="container mx-auto px-4 py-6">
            <h1
                class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-6 flex items-center animate-fade-in">
                <i class="fas fa-book mr-3 text-blue-500 dark:text-blue-400"></i>
                Associer des Disciplines à "{{ formation.titre }}"
            </h1>

            <form @submit.prevent="submit" class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <!-- Disciplines -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Disciplines</label>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto p-2 border border-gray-300 dark:border-gray-700 rounded-lg">
                        <label v-for="discipline in disciplines" :key="discipline.id"
                            class="flex items-center space-x-2">
                            <input type="checkbox" v-model="form.discipline_ids" :value="discipline.id"
                                class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 dark:border-gray-700 rounded" />
                            <span class="text-gray-900 dark:text-gray-100">{{ discipline.nom }}</span>
                        </label>
                        <p v-if="disciplines.length === 0" class="text-gray-500 dark:text-gray-400">
                            Aucune discipline disponible.
                        </p>
                    </div>
                </div>

                <!-- Bouton -->
                <button type="submit" :disabled="form.processing"
                    class="w-full bg-blue-500 text-white rounded-lg py-3 hover:bg-blue-600 transition-all duration-300 flex items-center justify-center shadow-md disabled:bg-gray-400 dark:disabled:bg-gray-600">
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
    }

    to {
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

/* Scrollbar stylisée */
.max-h-64::-webkit-scrollbar {
    width: 8px;
}

.max-h-64::-webkit-scrollbar-track {
    background: #e5e7eb;
    border-radius: 4px;
}

.max-h-64::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 4px;
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

/* Réactivité */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>