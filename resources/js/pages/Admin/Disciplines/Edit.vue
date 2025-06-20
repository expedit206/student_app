<script setup lang="ts">
import MonLayout from '@/components/MonLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

// Récupérer les props depuis usePage
const { discipline } = usePage().props;

// Initialiser le formulaire avec les données de la discipline
const form = useForm({
    nom: discipline.nom || '',
    description: discipline.description || '',
    heures_hebdo: discipline.heures_hebdo ?? 0,
    heures_total: discipline.heures_total ?? 0,
});

// Fonction de soumission
function submit() {
    form.put(route('disciplines.update', discipline.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Afficher un message de succès ou rediriger
            form.reset('nom', 'description', 'heures_hebdo', 'heures_total', ); // Optionnel
        },
        onError: (errors) => {
            console.log('Validation errors:', errors); // Débogage
        },
    });
}
</script>

<template>
    <MonLayout>
        <div class="container mx-auto px-4 py-6">
            <h1
                class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-6 flex items-center animate-fade-in">
                <i class="fas fa-edit mr-3 text-blue-500 dark:text-blue-400"></i>
                Modifier une Discipline
            </h1>

            <form @submit.prevent="submit"
                class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg animate-slide-up">
                <!-- Champ Nom de la Discipline -->
                <div class="mb-6">
                    <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Nom de la Discipline
                    </label>
                    <div class="mt-1 relative">
                        <i
                            class="fas fa-book-open absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                        <input id="nom" v-model="form.nom" type="text" placeholder="Entrez le nom de la discipline"
                            required
                            class="pl-10 pr-4 py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100" />
                    </div>
                    <InputError :message="form.errors.nom" class="mt-1" />
                </div>

                <!-- Champ Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Description (optionnel)
                    </label>
                    <div class="mt-1 relative">
                        <i class="fas fa-info-circle absolute left-3 top-4 text-gray-500 dark:text-gray-400"></i>
                        <textarea id="description" v-model="form.description" placeholder="Entrez une description"
                            rows="4"
                            class="pl-10 pr-4 py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100 resize-y"></textarea>
                    </div>
                    <InputError :message="form.errors.description" class="mt-1" />
                </div>

                <!-- Champ Heures Hebdomadaires -->
                <div class="mb-6">
                    <label for="heures_hebdo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Heures Hebdomadaires
                    </label>
                    <div class="mt-1 relative">
                        <i
                            class="fas fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                        <input id="heures_hebdo" v-model.number="form.heures_hebdo" type="number" min="0"
                            placeholder="Entrez le nombre d'heures par semaine" required
                            class="pl-10 pr-4 py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100" />
                    </div>
                    <InputError :message="form.errors.heures_hebdo" class="mt-1" />
                </div>

                <!-- Champ Heures Totales -->
                <div class="mb-6">
                    <label for="heures_total" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Heures Totales
                    </label>
                    <div class="mt-1 relative">
                        <i
                            class="fas fa-hourglass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                        <input id="heures_total" v-model.number="form.heures_total" type="number" min="0"
                            placeholder="Entrez le nombre total d'heures" required
                            class="pl-10 pr-4 py-3 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-gray-900 dark:text-gray-100" />
                    </div>
                    <InputError :message="form.errors.heures_total" class="mt-1" />
                </div>
               

                <!-- Bouton de soumission -->
                <button type="submit" :disabled="form.processing"
                    class="w-full bg-blue-500 text-white rounded-lg py-3 px-4 hover:bg-blue-600 transition-all duration-300 flex items-center justify-center shadow-md">
                    <span v-if="!form.processing">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour
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

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out forwards;
}

/* Styles pour InputError si non défini dans le composant */
.input-error {
    @apply text-red-500 dark:text-red-400 text-sm mt-1;
}

/* Réactivité */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>