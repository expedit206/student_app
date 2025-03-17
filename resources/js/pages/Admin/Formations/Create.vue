<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import MonLayout from '@/components/MonLayout.vue';

const formateurs = defineProps(['formateurs']);

// Utilisation de useForm pour gérer le formulaire
const form = useForm({
    titre: '',
    description: '',
    nbh_hebdomadaire: '',
    nbh_total: '',
    formateur_id: '',
});

// Fonction pour soumettre le formulaire
const submit = () => {
    form.post(route('formations.store'), {
        onSuccess: () => {
            // Logique après la création, par exemple une notification ou redirection
            form.reset(); // Réinitialiser le formulaire après succès
        },
        onError: (errors) => {
            console.error(errors); // Gestion des erreurs
        },
    });
};
</script>

<template>
    <MonLayout>
        <template #default>
            <h1 class="text-3xl font-bold mb-4 animate-fade-in">Ajouter une Formation</h1>
            <form @submit.prevent="submit"
                class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition duration-300">
                <div class="form-group mb-4">
                    <label for="titre" class="block text-gray-700 dark:text-gray-300">Titre</label>
                    <input type="text" v-model="form.titre"
                        class="text-black dark:text-white bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded p-2 w-full focus:ring-2 focus:ring-blue-500"
                        required />
                </div>
                <div class="form-group mb-4">
                    <label for="description" class="block text-gray-700 dark:text-gray-300">Description</label>
                    <textarea v-model="form.description"
                        class="text-black dark:text-white bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded p-2 w-full focus:ring-2 focus:ring-blue-500"
                        required></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="nbh_hebdomadaire" class="block text-gray-700 dark:text-gray-300">Heures
                        Hebdomadaires</label>
                    <input type="number" v-model="form.nbh_hebdomadaire"
                        class="text-black dark:text-white bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded p-2 w-full focus:ring-2 focus:ring-blue-500"
                        required />
                </div>
                <div class="form-group mb-4">
                    <label for="nbh_total" class="block text-gray-700 dark:text-gray-300">Heures Totales</label>
                    <input type="number" v-model="form.nbh_total"
                        class="text-black dark:text-white bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded p-2 w-full focus:ring-2 focus:ring-blue-500"
                        required />
                </div>
               
                <button type="submit"
                    class="bg-blue-500 text-white rounded p-2 hover:bg-blue-600 transition duration-200"
                    :disabled="!form.titre || !form.description || !form.nbh_hebdomadaire || !form.nbh_total ">
                    Créer
                </button>
            </form>
        </template>
    </MonLayout>
</template>

<style scoped>
.form-group {
    margin-bottom: 1rem;
}

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
</style>