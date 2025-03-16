<template>
    <div>
        <h1>Ajouter une Note</h1>
        <form @submit.prevent="submitNote">
            <div>
                <label for="etudiant">Étudiant</label>
                <select v-model="etudiantId" required>
                    <option v-for="etudiant in etudiants" :key="etudiant.id" :value="etudiant.id">
                        {{ etudiant.nom }}
                    </option>
                </select>
            </div>

            <div>
                <label for="note">Note</label>
                <input type="number" v-model="note" min="0" max="20" required />
            </div>

            <button type="submit">Ajouter Note</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps(['etudiants', 'formationId', 'disciplineId']);
const router = useRouter();

const etudiantId = ref('');
const note = ref('');

const submitNote = async () => {
    await fetch('/notes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            etudiant_id: etudiantId.value,
            formation_id: props.formationId,
            discipline_id: props.disciplineId,
            note: note.value,
        }),
    });

    router.push({ name: 'notes.index' });
};
</script>