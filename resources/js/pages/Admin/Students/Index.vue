<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import MonLayout from '@/layouts/nLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    students: {
        type: Array,
        required: true,
    },
});

const search = ref('');
const filteredStudents = ref([...props.students.data]); // Copie des étudiants

// Watcher pour filtrer les étudiants à chaque changement dans la recherche
watch(search, () => {
    filterStudents();
});

function filterStudents() {
    if (search.value) {
        filteredStudents.value = props.students.data.filter(student =>
            student.name.toLowerCase().includes(search.value.toLowerCase()) ||
            student.email.toLowerCase().includes(search.value.toLowerCase())
        );
    } else {
        filteredStudents.value = [...props.students.data]; // Réinitialiser à la liste complète
    }
}

function deleteStudent(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?")) {
        useForm().delete(route('student.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Retirer l'étudiant de la liste
                filteredStudents.value = filteredStudents.value.filter(student => student.id !== id);
            },
        });
    }
}
</script>

<template>

    <Head title="Liste des Étudiants" />

    <MonLayout>
        <div class="mb-4">
            <TextLink :href="route('student.create')"
                class="bg-green-500 text-white rounded p-2 hover:bg-green-600 transition">
                Ajouter un Étudiant
            </TextLink>
        </div>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Liste des Étudiants</h1>

            <!-- Filtre -->
            <div class="mb-4">
                <input type="text" v-model="search" placeholder="Rechercher un étudiant"
                    class="border rounded p-2 w-full text-black" />
            </div>

            <!-- Table des étudiants -->
            <table class="min-w-full border border-gray-500 shadow-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nom</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="student in filteredStudents" :key="student.id" class="hover:bg-gray-100">
                        <td class="text-black border px-4 py-2">{{ student.id }}</td>
                        <td class="text-black border px-4 py-2">{{ student.name }}</td>
                        <td class="text-black border px-4 py-2">{{ student.email }}</td>
                        <td class="text-black border px-4 py-2">
                            <TextLink :href="route('student.edit', student.id)"
                                class="text-yellow-500 hover:text-yellow-600 transition">Modifier</TextLink>
                            <button @click="deleteStudent(student.id)"
                                class="text-red-500 hover:text-red-600 transition ml-2">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </MonLayout>
</template>

<style scoped>
/* Ajoute des styles globaux ou spécifiques ici */
.container {
    max-width: 1200px;
    /* Limite la largeur de la table */
}

table {
    border-collapse: collapse;
    /* Évite les doubles bordures */
}

th,
td {
    text-align: left;
    /* Aligne le texte à gauche */
}
</style>