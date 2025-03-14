<template>
    <MonLayout>
        <div>
            <h1 class="text-2xl font-bold">Ajouter un Formateur</h1>
            <form @submit.prevent="submit">
                <div>
                    <input v-model="form.nom" type="text" placeholder="Nom" required
                        class="border rounded p-2 w-full" />
                    <input v-model="form.prenom" type="text" placeholder="Prénom" required
                        class="border rounded p-2 w-full" />
                    <input v-model="form.specialite" type="text" placeholder="Spécialité" required
                        class="border rounded p-2 w-full" />
                    <input v-model="form.telephone" type="text" placeholder="Téléphone" required
                        class="border rounded p-2 w-full" />
                    <input v-model="form.dernier_diplome" type="text" placeholder="Dernier Diplôme" required
                        class="border rounded p-2 w-full" />
                </div>
                <button type="submit" class="bg-blue-500 text-white rounded p-2">Créer</button>
            </form>
        </div>
    </MonLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = ref({
    nom: '',
    prenom: '',
    specialite: '',
    telephone: '',
    dernier_diplome: '',
});

function submit() {
    useForm(form.value).post(route('formateurs.store'));
}
</script>