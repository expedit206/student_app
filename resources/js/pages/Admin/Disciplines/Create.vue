<template>
    <div>
        <h1 class="text-2xl font-bold">Ajouter une Discipline</h1>
        <form @submit.prevent="submit">
            <input v-model="nom_discipline" placeholder="Nom de la discipline" required />
            <textarea v-model="description" placeholder="Description"></textarea>
            <button type="submit">Ajouter</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            nom_discipline: '',
            description: '',
        };
    },
    methods: {
        submit() {
            // Logique pour soumettre le formulaire
        },
    },
};
</script>