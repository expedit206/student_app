<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue'; // Importer le composant InputError
import MonLayout from '@/layouts/nLayout.vue'; // Importer le composant MonLayout
const form = useForm({
    name: '',
    email: '',
    password: '',
});

function submitForm() {
    form.post(route('student.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset(); // Réinitialise le formulaire après succès
        },
    });
}
</script>

<template>

    <Head title="Ajouter un Étudiant" />

    <MonLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold">Ajouter un Étudiant</h1>

            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="name" class="block">Nom</label>
                    <input type="text" v-model="form.name" class="border rounded p-2 w-full" required />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="mb-4">
                    <label for="email" class="block">Email</label>
                    <input type="email" v-model="form.email" class="border rounded p-2 w-full" required />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="mb-4">
                    <label for="password" class="block">Mot de passe</label>
                    <input type="password" v-model="form.password" class="border rounded p-2 w-full" required />
                    <InputError :message="form.errors.password" />
                </div>
                <button type="submit" class="bg-blue-500 text-white rounded p-2">Ajouter l'Étudiant</button>
            </form>
        </div>
    </MonLayout>
</template>