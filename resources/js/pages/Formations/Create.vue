<template>
    <MonLayout>
        <template #default>
            <h1>Ajouter une Formation</h1>
            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" v-model="titre" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea v-model="description" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="formateur_id">Formateur</label>
                    <select v-model="formateur_id" class="form-control" required>
                        <option v-for="formateur in formateurs" :key="formateur.id" :value="formateur.id">
                            {{ formateur.nom }} {{ formateur.prenom }}
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
            </form>
        </template>
    </MonLayout>
</template>

<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    props: {
        formateurs: Array,
    },
    setup() {
        const titre = ref('');
        const description = ref('');
        const formateur_id = ref('');

        const submit = () => {
            Inertia.post(route('formations.store'), {
                titre: titre.value,
                description: description.value,
                formateur_id: formateur_id.value,
            });
        };

        return { titre, description, formateur_id, submit };
    },
};
</script>