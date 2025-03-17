<template>
    <MonLayout>
        <template #default>
            <h1 class="text-2xl font-bold mb-4">Modifier la Formation</h1>
            <form @submit.prevent="submit" class="bg-white p-6 rounded shadow-md">
                <div class="form-group mb-4">
                    <label for="titre" class="block text-gray-700">Titre</label>
                    <input type="text" v-model="form.titre" class="text-black border border-gray-300 rounded p-2 w-full"
                        required />
                </div>
                <div class="form-group mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea v-model="form.description" class="text-black border border-gray-300 rounded p-2 w-full"
                        required></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="formateur_id" class="block text-gray-700">Formateur</label>
                    <select v-model="form.formateur_id" class="text-black border border-gray-300 rounded p-2 w-full"
                        required>
                        <option disabled value="">Choisir un formateur</option>
                        <option v-for="formateur in formateurs" :key="formateur.id" :value="formateur.id">
                            {{ formateur.nom }} {{ formateur.prenom }}
                        </option>
                    </select>
                </div>
                <button type="submit"
                    class="bg-blue-500 text-white rounded p-2 hover:bg-blue-600 transition duration-200"
                    :disabled="processing">
                    Mettre à jour
                </button>
            </form>
        </template>
    </MonLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import MonLayout from '@/components/MonLayout.vue';
const { formation, formateurs } = usePage().props;

// Utilisation de useForm pour gérer le formulaire
const form = useForm({
    titre: formation.titre,
    description: formation.description,
    formateur_id: formation.formateur_id,
});

// Fonction pour soumettre le formulaire
const submit = () => {
    form.put(route('formations.update', formation.id), {
        onSuccess: () => {
            // Logique après la mise à jour, par exemple une notification
        },
        onError: (errors) => {
            console.error(errors); // Gestion des erreurs
        },
    });
};
</script>

<style scoped>
.form-group {
    margin-bottom: 1rem;
}
</style>