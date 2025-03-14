<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { onMounted, ref } from 'vue';
import MonLayout from '@/layouts/MonLayout.vue';
const props = defineProps({
    student: Object, // L'étudiant à modifier
});

const form = useForm({
    name: props.student.name,
    email: props.student.email,
    password: '',
});

function submitForm() {
    form.put(route('student.update', props.student.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset(); // Réinitialiser le formulaire après succès
        },
    });
}
</script>

<template>

    <Head title="Modifier l'Étudiant" />

    <MonLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold">Modifier l'Étudiant</h1>

            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="name" class="block">Nom</label>
                    <input  type="text" v-model="form.name" class="border rounded p-2 text-black w-full" required />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="mb-4">
                    <label for="email" class="block">Email</label>
                    <input  type="email" v-model="form.email" class="border text-black rounded p-2 w-full" required />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="mb-4">
                    <label for="password" class="block">Nouveau Mot de passe (laisser vide si pas de changement)</label>
                    <input  type="password" v-model="form.password" class="border text-black rounded p-2 w-full" />
                    <InputError :message="form.errors.password" />
                </div>
                <button type="submit" class="bg-blue-500 text-white rounded p-2">Mettre à Jour</button>
            </form>
        </div>
    </MonLayout>>
</template>