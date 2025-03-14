<template>
    <MonLayout>
        <div>
            <h1 class="text-black text-2xl font-bold">Modifier un Formateur</h1>
            <form @submit.prevent="submit">
                <div>
                    <input v-model="form.nom" type="text" placeholder="Nom" required
                        class="text-black border rounded p-2 w-full" />
                    <input v-model="form.prenom" type="text" placeholder="Prénom" required
                        class="text-black border rounded p-2 w-full" />
                    <input v-model="form.specialite" type="text" placeholder="Spécialité" required
                        class="text-black border rounded p-2 w-full" />
                    <input v-model="form.telephone" type="text" placeholder="Téléphone" required
                        class="text-black border rounded p-2 w-full" />
                    <input v-model="form.dernier_diplome" type="text" placeholder="Dernier Diplôme" required
                        class="text-black border rounded p-2 w-full" />
                </div>
                <button type="submit" class="text-black bg-blue-500 text-white rounded p-2">Mettre à jour</button>
            </form>
        </div>
    </MonLayout>
</template>

<script setup>
import MonLayout from '@/layouts/MonLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const { formateur } = usePage().props;

const form = ref({ ...formateur });

function submit() {
    useForm(form.value).put(route('formateurs.update', formateur.id));
}
</script>