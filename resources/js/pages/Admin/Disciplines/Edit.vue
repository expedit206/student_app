<script setup lang="ts">
import MonLayout from '@/layouts/nLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const { discipline } = usePage().props;

const form = ref({ ...discipline });

function submit() {
    useForm(form.value).put(route('disciplines.update', discipline.id));
}
</script>

<template>
    <MonLayout>
        <div>
            <h1 class="text-black text-2xl font-bold">Modifier une Discipline</h1>
            <form @submit.prevent="submit">
                <div>
                    <input v-model="form.nom_discipline" type="text" placeholder="Nom de la Discipline" required
                        class="text-black border rounded p-2 w-full" />
                    <textarea v-model="form.description" placeholder="Description"
                        class="text-black border rounded p-2 w-full" rows="4"></textarea>
                </div>
                <button type="submit" class="text-black bg-blue-500 text-white rounded p-2">Mettre à jour</button>
            </form>
        </div>
    </MonLayout>
</template>

<style scoped>
/* Ajoutez des styles globaux ou spécifiques ici */
</style>