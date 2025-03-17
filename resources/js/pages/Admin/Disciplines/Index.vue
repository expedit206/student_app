<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/layouts/nLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    disciplines: {
        type: Array,
        required: true,
    },
});

const search = ref('');
const filteredDisciplines = ref([...props.disciplines]); // Copie des disciplines

// Watcher pour filtrer les disciplines à chaque changement dans la recherche
watch(search, () => {
    filterDisciplines();
});

function filterDisciplines() {
    if (search.value) {
        filteredDisciplines.value = props.disciplines.filter(discipline =>
            discipline.nom_discipline.toLowerCase().includes(search.value.toLowerCase()) ||
            (discipline.description && discipline.description.toLowerCase().includes(search.value.toLowerCase()))
        );
    } else {
        filteredDisciplines.value = [...props.disciplines]; // Réinitialiser à la liste complète
    }
}

function deleteDiscipline(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette discipline ?")) {
        useForm().delete(route('disciplines.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Retirer la discipline de la liste
                filteredDisciplines.value = filteredDisciplines.value.filter(discipline => discipline.id !== id);
            },
        });
    }
}
</script>

<template>
    <MonLayout>

        <Head title="Liste des Disciplines" />
        <div>
            <h1 class="text-2xl font-bold">Liste des Disciplines</h1>
            <div class="mb-4">
                <TextLink href="/disciplines/create" class="bg-green-500 text-white rounded p-2">
                    Ajouter une Discipline
                </TextLink>
            </div>
            <div class="mb-4">
                <input type="text" v-model="search" placeholder="Rechercher une discipline"
                    class="border rounded p-2 w-full" />
            </div>

            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nom de la Discipline</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="discipline in filteredDisciplines" :key="discipline.id">
                        <td class="border px-4 py-2">{{ discipline.id }}</td>
                        <td class="border px-4 py-2">{{ discipline.nom_discipline }}</td>
                        <td class="border px-4 py-2">{{ discipline.description }}</td>
                        <td class="border px-4 py-2">
                            <TextLink :href="route('disciplines.edit', discipline.id)" class="text-yellow-500">Modifier
                            </TextLink>
                            <button @click="deleteDiscipline(discipline.id)" class="text-red-500">Supprimer</button>
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