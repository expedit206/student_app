<template>
    <MonLayout>
        <div
            class="max-w-lg mx-auto mt-10 pb-16 p-6 bg-white rounded-lg shadow-lg h-[calc(100vh-2rem)] overflow-y-auto">
            <h1 class="text-black text-2xl font-bold mb-4">Modifier un Formateur</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <input v-model="form.nom" type="text" placeholder="Nom" required
                        class="text-black border rounded p-2 w-full focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                    <input v-model="form.prenom" type="text" placeholder="Prénom" required
                        class="text-black border rounded p-2 w-full focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Spécialité</label>
                    <input v-model="form.specialite" type="text" placeholder="Spécialité" required
                        class="text-black border rounded p-2 w-full focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input v-model="form.telephone" type="text" placeholder="Téléphone" required
                        class="text-black border rounded p-2 w-full focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dernier Diplôme</label>
                    <input v-model="form.dernier_diplome" type="text" placeholder="Dernier Diplôme" required
                        class="text-black border rounded p-2 w-full focus:ring-2 focus:ring-blue-500" />
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white rounded p-2 hover:bg-blue-600 transition duration-300">
                    Mettre à jour
                </button>
                <TextLink :href="route('formateurs.index')" class="mb-4 !text-blue-500 hover:underline">
                    Retour
                </TextLink>
            </form>
        </div>
    </MonLayout>
</template>

<script setup>
import MonLayout from '@/components/MonLayout.vue';
import TextLink from '@/components/TextLink.vue'; // Assurez-vous d'importer TextLink
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const { formateur } = usePage().props;
const form = ref({ ...formateur });

function submit() {
    useForm(form.value).put(route('formateurs.update', formateur.id));
}
</script>

<style scoped>
/* Styles personnalisés */
body {
    background-color: #f9fafb;
    /* Couleur de fond douce */
}

h1 {
    margin-bottom: 1.5rem;
    /* Espacement pour le titre */
}
</style>