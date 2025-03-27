<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

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

onMounted(() => {
    if (!selectedFormation.value && props.formations.length > 0) {
        selectedFormation.value = props.formations[0].id;
    }
});

const currentFormation = computed(() => {
    return props.formations.find(f => f.id === selectedFormation.value) || { apprenants: [], disciplines: [] };
});

const changeFormation = (formationId: number | null) => {
    selectedFormation.value = formationId;
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
        <div class="p-4 sm:p-6 bg-gray-100 text-gray-900 min-h-screen overflow-hidden">
            <!-- Header -->
            <h1 class="text-2xl sm:text-3xl font-extrabold mb-8 flex items-center animate-slide-in">
                <i class="fas fa-star mr-3 text-3xl sm:text-4xl text-yellow-600 animate-spin-slow"></i>
                Gestion des Notes
            </h1>

            <!-- Sélection de la formation -->
            <div class="mb-8 animate-fade-up">
                <div class="relative max-w-md">
                    <i
                        class="fas fa-graduation-cap absolute left-4 top-1/2 transform -translate-y-1/2 text-yellow-600 animate-pulse"></i>
                    <select v-model="selectedFormation" @change="changeFormation(selectedFormation)"
                        class="w-full pl-12 pr-10 py-2 sm:py-3 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent appearance-none shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-50 text-sm sm:text-base">
                        <option :value="null">Sélectionnez une formation</option>
                        <option v-for="formation in props.formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }}
                        </option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-yellow-600 animate-bounce pointer-events-none"></i>
                </div>
            </div>

            <!-- Desktop : Tableau -->
            <div v-if="selectedFormation"
                class="hidden sm:block bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-[0_0_15px_rgba(234,179,8,0.5)] animate-fade-in">
                <div class="max-h-[500px] overflow-y-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 animate-slide-in">
                                <th class="py-4 px-6 text-left font-semibold sticky left-0 bg-gray-200 z-10">Apprenant
                                </th>
                                <th v-for="discipline in currentFormation.disciplines" :key="discipline.id"
                                    class="py-4 px-6 text-left font-semibold">
                                    {{ discipline.nom }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(apprenant, index) in currentFormation.apprenants" :key="apprenant.id"
                                class="border-b border-gray-200 hover:bg-gray-50 transition-all duration-300 animate-row-in"
                                :style="{ animationDelay: `${0.1 + index * 0.1}s` }">
                                <td class="py-4 px-6 text-gray-900 sticky left-0 bg-white z-10 font-medium">
                                    {{ apprenant.prenom }} {{ apprenant.nom }}</td>
                                <td v-for="discipline in currentFormation.disciplines" :key="discipline.id"
                                    class="py-4 px-6 text-gray-900">
                                    <div v-if="editingNote && editingNote.apprenantId === apprenant.id && editingNote.disciplineId === discipline.id"
                                        class="flex items-center gap-2">
                                        <input v-model="editingNote.value" type="number" min="0" max="20" step="0.01"
                                            class="w-20 p-2 bg-gray-100 border border-gray-300 rounded text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 shadow-sm hover:shadow-md transition-all duration-200"
                                            @blur="saveNote(apprenant.id, discipline.id)"
                                            @keyup.enter="saveNote(apprenant.id, discipline.id)" ref="noteInput"
                                            autofocus />
                                        <button @click="saveNote(apprenant.id, discipline.id)"
                                            class="text-green-600 hover:text-green-700 text-lg hover:scale-110 transition-transform duration-200">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button @click="editingNote = null"
                                            class="text-red-600 hover:text-red-700 text-lg hover:scale-110 transition-transform duration-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <span v-else
                                        class="cursor-pointer hover:text-yellow-700 transition-colors duration-200 font-medium"
                                        @click="startEditingNote(apprenant.id, discipline.id, apprenant.notes[discipline.id] || null)">
                                        {{ apprenant.notes[discipline.id] !== undefined &&
                                            apprenant.notes[discipline.id] !== null
                                            ? apprenant.notes[discipline.id] + '/20' : 'Non noté' }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="currentFormation.apprenants.length === 0">
                                <td :colspan="currentFormation.disciplines.length + 1"
                                    class="p-6 text-center text-gray-500 animate-pulse">
                                    <i class="fas fa-exclamation-circle mr-2 text-xl animate-bounce"></i> Aucun
                                    apprenant trouvé pour cette formation
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile : Cartes -->
            <div v-if="selectedFormation"
                class="sm:hidden grid grid-cols-1 gap-4 bg-white rounded-xl shadow-2xl p-4 max-h-[500px] overflow-y-auto animate-fade-in">
                <div v-for="(apprenant, index) in currentFormation.apprenants" :key="apprenant.id"
                    class="bg-gray-50 rounded-lg shadow-md border border-gray-200 p-4 hover:shadow-xl transition-all duration-300 animate-card-in hover:bg-gray-100"
                    :style="{ animationDelay: `${0.1 + index * 0.1}s` }">
                    <div class="flex flex-col space-y-3">
                        <h2 class="text-lg font-semibold text-gray-900 truncate"
                            :title="apprenant.prenom + ' ' + apprenant.nom">
                            <i class="fas fa-user mr-2 text-yellow-600"></i>{{ apprenant.prenom }} {{ apprenant.nom }}
                        </h2>
                        <div class="grid grid-cols-1 gap-2">
                            <div v-for="discipline in currentFormation.disciplines" :key="discipline.id"
                                class="flex flex-col">
                                <span class="text-sm text-gray-600 font-medium">{{ discipline.nom }}</span>
                                <div v-if="editingNote && editingNote.apprenantId === apprenant.id && editingNote.disciplineId === discipline.id"
                                    class="flex items-center gap-2 mt-1">
                                    <input v-model="editingNote.value" type="number" min="0" max="20" step="0.01"
                                        class="w-20 p-2 bg-gray-100 border border-gray-300 rounded text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 shadow-sm hover:shadow-md transition-all duration-200"
                                        @blur="saveNote(apprenant.id, discipline.id)"
                                        @keyup.enter="saveNote(apprenant.id, discipline.id)" ref="noteInput"
                                        autofocus />
                                    <button @click="saveNote(apprenant.id, discipline.id)"
                                        class="text-green-600 hover:text-green-700 text-lg hover:scale-110 transition-transform duration-200">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button @click="editingNote = null"
                                        class="text-red-600 hover:text-red-700 text-lg hover:scale-110 transition-transform duration-200">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <span v-else
                                    class="text-sm text-gray-900 hover:text-yellow-700 transition-colors duration-200 cursor-pointer mt-1 font-medium"
                                    @click="startEditingNote(apprenant.id, discipline.id, apprenant.notes[discipline.id] || null)">
                                    {{ apprenant.notes[discipline.id] !== undefined && apprenant.notes[discipline.id]
                                        !== null
                                        ? apprenant.notes[discipline.id] + '/20' : 'Non noté' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="currentFormation.apprenants.length === 0"
                    class="col-span-full p-4 text-center text-gray-500 animate-pulse bg-gray-50 rounded-lg shadow-md border border-gray-200">
                    <i class="fas fa-exclamation-circle mr-2 text-xl animate-bounce"></i> Aucun apprenant trouvé pour
                    cette formation
                </div>
            </div>

            <!-- Message si aucune formation sélectionnée -->
            <div v-if="!selectedFormation"
                class="text-center text-gray-500 animate-pulse p-6 bg-white rounded-xl shadow-md">
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

/* Styles par défaut (mode clair) */
.bg-gray-100 {
    background-color: #f3f4f6;
}

.text-gray-900 {
    color: #1f2937;
}

.bg-white {
    background-color: #ffffff;
}

.border-gray-300 {
    border-color: #d1d5db;
}

.text-yellow-600 {
    color: #d97706;
}

.hover\:text-yellow-700:hover {
    color: #b45309;
}

.bg-gradient-to-r.from-gray-200 {
    background: linear-gradient(to right, #e5e7eb, #d1d5db);
}

.text-gray-700 {
    color: #4b5563;
}

.bg-gray-200 {
    background-color: #e5e7eb;
}

.border-gray-200 {
    border-color: #e5e7eb;
}

.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}

.bg-gray-50 {
    background-color: #f9fafb;
}

.text-gray-600 {
    color: #4b5563;
}

.text-gray-500 {
    color: #6b7280;
}

.bg-gray-100 {
    background-color: #f3f4f6;
}

.focus\:ring-yellow-500:focus {
    --tw-ring-color: #f59e0b;
}

.text-green-600 {
    color: #059669;
}

.hover\:text-green-700:hover {
    color: #047857;
}

.text-red-600 {
    color: #dc2626;
}

.hover\:text-red-700:hover {
    color: #b91c1c;
}

/* Mode sombre */
html.dark .bg-gray-100 {
    background-color: #111827;
}

html.dark .text-gray-900 {
    color: #f3f4f6;
}

html.dark .bg-white {
    background-color: #1f2937;
}

html.dark .border-gray-300 {
    border-color: #4b5563;
}

html.dark .text-yellow-600 {
    color: #fbbf24;
}

html.dark .hover\:text-yellow-700:hover {
    color: #f59e0b;
}

html.dark .bg-gradient-to-r.from-gray-200 {
    background: linear-gradient(to right, #374151, #1f2937);
}

html.dark .text-gray-700 {
    color: #d1d5db;
}

html.dark .bg-gray-200 {
    background-color: #1f2937;
}

html.dark .border-gray-200 {
    border-color: #374151;
}

html.dark .hover\:bg-gray-50:hover {
    background-color: #374151;
}

html.dark .bg-gray-50 {
    background-color: #374151;
}

html.dark .text-gray-600 {
    color: #9ca3af;
}

html.dark .text-gray-500 {
    color: #6b7280;
}

html.dark .bg-gray-100 {
    background-color: #1f2937;
}

html.dark .focus\:ring-yellow-500:focus {
    --tw-ring-color: #eab308;
}

html.dark .text-green-600 {
    color: #10b981;
}

html.dark .hover\:text-green-700:hover {
    color: #059669;
}

html.dark .text-red-600 {
    color: #ef4444;
}

html.dark .hover\:text-red-700:hover {
    color: #dc2626;
}

/* Animations personnalisées */
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

@keyframes cardIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
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

.animate-slide-in {
    animation: slideIn 0.6s ease-out;
}

.animate-fade-up {
    animation: fadeUp 0.6s ease-out forwards;
}

.animate-row-in {
    animation: rowIn 0.5s ease-out forwards;
}

.animate-card-in {
    animation: cardIn 0.5s ease-out forwards;
}

.animate-spin-slow {
    animation: spinSlow 4s linear infinite;
}

/* Scrollbar stylisée */
.max-h-[500px] {
    position: relative;
    overflow-y: auto;
}

.max-h-[500px]::-webkit-scrollbar {
    width: 8px;
}

.max-h-[500px]::-webkit-scrollbar-track {
    background: #e5e7eb;
    border-radius: 4px;
}

html.dark .max-h-[500px]::-webkit-scrollbar-track {
    background: #1f2937;
}

.max-h-[500px]::-webkit-scrollbar-thumb {
    background: #9ca3af;
    border-radius: 4px;
}

html.dark .max-h-[500px]::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.max-h-[500px]::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

html.dark .max-h-[500px]::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Réactivité et attractivité */
@media (max-width: 640px) {
    .p-6 {
        padding: 1rem;
    }

    .text-3xl {
        font-size: 1.5rem;
    }

    .text-lg {
        font-size: 1.125rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .py-4 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .gap-4 {
        gap: 1rem;
    }

    .shadow-md {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .max-w-md {
        max-width: 100%;
    }
}
</style>