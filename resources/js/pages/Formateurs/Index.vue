<template>
    <MonLayout>

        <Head title="Liste des Formateurs" />
        <div>
            <h1 class="text-2xl font-bold">Liste des Formateurs</h1>
            <div class="mb-4">
                <TextLink href="/formateurs/create" class="bg-green-500 text-white rounded p-2">
                    Ajouter un Formateur
                </TextLink>
            </div>
            <div class="mb-4">
                <input type="text" v-model="search" placeholder="Rechercher un formateur"
                    class="border rounded p-2 w-full" />
            </div>

            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nom</th>
                        <th class="border px-4 py-2">Prénom</th>
                        <th class="border px-4 py-2">Spécialité</th>
                        <th class="border px-4 py-2">Téléphone</th>
                        <th class="border px-4 py-2">Dernier Diplôme</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="formateur in filteredFormateurs" :key="formateur.id">
                        <td class="border px-4 py-2">{{ formateur.id }}</td>
                        <td class="border px-4 py-2">{{ formateur.nom }}</td>
                        <td class="border px-4 py-2">{{ formateur.prenom }}</td>
                        <td class="border px-4 py-2">{{ formateur.specialite }}</td>
                        <td class="border px-4 py-2">{{ formateur.telephone }}</td>
                        <td class="border px-4 py-2">{{ formateur.dernier_diplome }}</td>
                        <td class="border px-4 py-2">
                            <TextLink :href="route('formateurs.edit', formateur.id)" class="text-yellow-500">Modifier
                            </TextLink>
                            <button @click="deleteFormateur(formateur.id)" class="text-red-500">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </MonLayout>
</template>

<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/layouts/MonLayout.vue';
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
const filteredFormateurs = ref([...props.formateurs]); // Copie des formateurs

// Watcher pour filtrer les formateurs à chaque changement dans la recherche
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
        filteredFormateurs.value = [...props.formateurs]; // Réinitialiser à la liste complète
    }
}

function deleteFormateur(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce formateur ?")) {
        useForm().delete(route('formateurs.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Retirer le formateur de la liste
                filteredFormateurs.value = filteredFormateurs.value.filter(formateur => formateur.id !== id);
            },
        });
    }
}
</script>

<style scoped>
/* Ajoutez des styles globaux ou spécifiques ici */
</style>