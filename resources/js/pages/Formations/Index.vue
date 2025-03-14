<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/layouts/MonLayout.vue';
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
const filteredFormations = ref([...props.formations]); // Copie des formations

// Watcher pour filtrer les formations à chaque changement dans la recherche
watch(search, () => {
    filterFormations();
});

function filterFormations() {
    if (search.value) {
        filteredFormations.value = props.formations.filter(formation =>
            formation.titre.toLowerCase().includes(search.value.toLowerCase())
        );
    } else {
        filteredFormations.value = [...props.formations]; // Réinitialiser à la liste complète
    }
}

function deleteFormation(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette formation ?")) {
        useForm().delete(route('formations.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Retirer la formation de la liste
                filteredFormations.value = filteredFormations.value.filter(formation => formation.id !== id);
            },
        });
    }
}
</script>

<template>
    <MonLayout>

        <Head title="Liste des Formations" />
        <div>
            <h1 class="text-2xl font-bold">Liste des Formations</h1>
            <div class="mb-4">
                <TextLink href="/formations/create" class="bg-green-500 text-white rounded p-2">
                    Ajouter une Formation
                </TextLink>
            </div>
            <div class="mb-4">
                <input type="text" v-model="search" placeholder="Rechercher une formation"
                    class="border rounded p-2 w-full" />
            </div>

            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Titre</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Formateur</th>
                        <th class="border px-4 py-2">Heures Hebdomadaires</th>
                        <th class="border px-4 py-2">Heures Totales</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="formation in filteredFormations" :key="formation.id">
                        <td class="border px-4 py-2">{{ formation.id }}</td>
                        <td class="border px-4 py-2">{{ formation.titre }}</td>
                        <td class="border px-4 py-2">{{ formation.description }}</td>
                        <td class="border px-4 py-2">{{ formation.formateur.nom }} {{ formation.formateur.prenom }}</td>
                        <td class="border px-4 py-2">{{ formation.nbh_hebdomadaire }}</td>
                        <td class="border px-4 py-2">{{ formation.nbh_total }}</td>
                        <td class="border px-4 py-2">
                            <TextLink :href="route('formations.edit', formation.id)" class="text-yellow-500">Modifier
                            </TextLink>
                            <button @click="deleteFormation(formation.id)" class="text-red-500">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </MonLayout>
</template>

<style scoped>
/* Ajoutez des styles globaux ou spécifiques ici */
</style>