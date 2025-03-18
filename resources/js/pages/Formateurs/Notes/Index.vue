<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';
import { router } from '@inertiajs/vue3';
import {route} from 'ziggy-js';

const props = defineProps<{
    formations: Array<{
        id: number;
        titre: string;
        disciplines: Array<{ id: number; nom: string }>;
        apprenants: Array<{ id: number; nom: string; prenom: string; notes: Record<number, number | null> }>;
    }>;
    selectedFormation: number | null;
}>();

const selectedFormation = ref<number | null>(props.selectedFormation);
const editingNote = ref<{ apprenantId: number; disciplineId: number; value: number | null } | null>(null);

// Sélectionne la première formation par défaut si aucune n'est sélectionnée
onMounted(() => {
    if (!selectedFormation.value && props.formations.length > 0) {
        selectedFormation.value = props.formations[0].id;
    }
});

// Filtrer les apprenants et disciplines en fonction de la formation sélectionnée
const currentFormation = computed(() => {
    return props.formations.find(f => f.id === selectedFormation.value) || { apprenants: [], disciplines: [] };
});

const changeFormation = (formationId: number | null) => {
    selectedFormation.value = formationId; // Mise à jour locale uniquement
};

const startEditingNote = (apprenantId: number, disciplineId: number, currentNote: number | null) => {
    editingNote.value = { apprenantId, disciplineId, value: currentNote };
};

const saveNote = (apprenantId: number, disciplineId: number) => {
    if (editingNote.value && editingNote.value.value !== null && selectedFormation.value) {
        router.post(route('formateur.updateNote'), {
            apprenant_id: apprenantId,
            formation_id: selectedFormation.value,
            discipline_id: disciplineId,
            note: editingNote.value.value,
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                // Mise à jour locale des notes après succès
                const formation = props.formations.find(f => f.id === selectedFormation.value);
                const apprenant = formation?.apprenants.find(a => a.id === apprenantId);
                if (apprenant) {
                    apprenant.notes[disciplineId] = editingNote.value!.value;
                }
                editingNote.value = null;
            },
            onError: () => { editingNote.value = null; },
        });
    } else {
        editingNote.value = null;
    }
};
</script>

<template>
    <FormateurLayout>
        <div class="p-6 bg-gray-900 text-gray-100 min-h-screen overflow-hidden">
            <h1 class="text-3xl font-extrabold mb-8 flex items-center animate-slide-in">
                <i class="fas fa-star mr-3 text-yellow-400 text-4xl animate-spin-slow"></i>
                Gestion des Notes
            </h1>

            <!-- Sélection de la formation -->
            <div class="mb-8 animate-fade-up">
                <div class="relative max-w-md">
                    <i
                        class="fas fa-graduation-cap absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 animate-pulse"></i>
                    <select v-model="selectedFormation" @change="changeFormation(selectedFormation)"
                        class="w-full pl-12 pr-10 py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-700">
                        <option :value="null">Sélectionnez une formation</option>
                        <option v-for="formation in props.formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }}
                        </option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 animate-bounce pointer-events-none"></i>
                </div>
            </div>

            <!-- Tableau des notes -->
            <div v-if="selectedFormation"
                class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-700 to-gray-800 text-gray-200 animate-slide-in">
                            <th class="py-4 px-6 text-left font-semibold sticky left-0 bg-gray-800 z-10">Apprenant</th>
                            <th v-for="discipline in currentFormation.disciplines" :key="discipline.id"
                                class="py-4 px-6 text-left font-semibold">
                                {{ discipline.nom }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(apprenant, index) in currentFormation.apprenants" :key="apprenant.id"
                            class="border-b border-gray-700 hover:bg-gray-700 transition-all duration-300 animate-row-in"
                            :style="{ animationDelay: `${0.1 + index * 0.1}s` }">
                            <td class="py-4 px-6 text-gray-100 sticky left-0 bg-gray-800 z-10">{{ apprenant.prenom }} {{
                                apprenant.nom }}</td>
                            <td v-for="discipline in currentFormation.disciplines" :key="discipline.id"
                                class="py-4 px-6 text-gray-100">
                                <div v-if="editingNote && editingNote.apprenantId === apprenant.id && editingNote.disciplineId === discipline.id"
                                    class="flex items-center gap-2">
                                    <input v-model="editingNote.value" type="number" min="0" max="20" step="0.01"
                                        class="w-20 p-2 bg-gray-700 border border-gray-600 rounded text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        @blur="saveNote(apprenant.id, discipline.id)"
                                        @keyup.enter="saveNote(apprenant.id, discipline.id)" ref="noteInput"
                                        autofocus />
                                    <button @click="saveNote(apprenant.id, discipline.id)"
                                        class="text-green-400 hover:text-green-300">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button @click="editingNote = null" class="text-red-400 hover:text-red-300">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <span v-else class="cursor-pointer"
                                    @click="startEditingNote(apprenant.id, discipline.id, apprenant.notes[discipline.id] || null)">
                                    {{ apprenant.notes[discipline.id] !== undefined && apprenant.notes[discipline.id]
                                        !== null ? apprenant.notes[discipline.id] + '/20' : 'Non noté' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="currentFormation.apprenants.length === 0"
                    class="p-6 text-center text-gray-400 animate-pulse">
                    <i class="fas fa-exclamation-circle mr-2 text-xl animate-bounce"></i> Aucun apprenant trouvé pour
                    cette formation
                </div>
            </div>
            <div v-else class="text-center text-gray-400 animate-pulse">
                <i class="fas fa-info-circle mr-2 text-xl animate-bounce"></i> Veuillez sélectionner une formation pour
                voir les notes
            </div>
        </div>
    </FormateurLayout>
</template>

<style scoped>
select {
    background-image: none;
}

.animate-slide-in {
    animation: slideIn 0.6s ease-out;
}

.animate-fade-up {
    animation: fadeUp 0.6s ease-out forwards;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
}

.animate-bounce-in {
    animation: bounceIn 0.8s ease-out;
}

.animate-spin-slow {
    animation: spinSlow 4s linear infinite;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes rowIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }

    50% {
        opacity: 1;
        transform: scale(1.05);
    }

    70% {
        transform: scale(0.9);
    }

    100% {
        transform: scale(1);
    }
}

@keyframes spinSlow {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}
</style>