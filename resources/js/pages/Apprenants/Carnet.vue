<script setup lang="ts">
import { defineProps, computed } from 'vue';
import MonLayout from '@/components/MonLayout.vue';

const props = defineProps<{
    carnetData: {
        titre: string;
        ecole: string;
        apprenant: string;
        annee_scolaire: string;
        disciplines: Array<{
            id: number;
            titre: string;
            heures_hebdo: number;
            heures_total: number;
            formateur: string;
            coefficient: number;
            notes: string;
            statut: string;
        }>;
        total_heures: number;
        moyenne_formation: number | null;
    };
}>();

const getMoyenneColor = (moyenne: number | null) => {
    if (!moyenne) return 'text-gray-400';
    if (moyenne >= 16) return 'text-green-600';
    if (moyenne >= 12) return 'text-yellow-600';
    return 'text-red-600';
};

const getMention = (moyenne: number | null) => {
    if (!moyenne) return 'Non défini';
    if (moyenne >= 16) return 'Très Bien';
    if (moyenne >= 14) return 'Bien';
    if (moyenne >= 12) return 'Assez Bien';
    if (moyenne >= 10) return 'Passable';
    return 'Insuffisant';
};

const totalHeures = computed(() => props.carnetData.total_heures ?? props.carnetData.disciplines.reduce((sum, d) => sum + d.heures_total, 0));
const moyenneFormation = computed(() => props.carnetData.moyenne_formation ?? 0);

const printCarnet = () => {
    window.print();
};
</script>

<template>
    <MonLayout>
        <div class="max-w-4xl mx-auto p-6 bg-white text-gray-900 shadow-lg border border-gray-300 print:border-black">
            <!-- En-tête avec logo -->
            <header class="mb-6 border-b-2 border-gray-300 pb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="/img/logo.png" alt="Logo École" class="h-16 w-16 mr-4 print:h-12 print:w-12" />
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Bulletin de Notes</h1>
                            <p class="text-sm text-gray-600">{{ props.carnetData.ecole }}</p>
                        </div>
                    </div>
                    <div class="text-right text-sm text-gray-700">
                        <p><strong>Année scolaire :</strong> {{ props.carnetData.annee_scolaire }}</p>
                    </div>
                </div>
                <div class="mt-4 text-gray-700">
                    <p><strong>Apprenant :</strong> {{ props.carnetData.apprenant }}</p>
                    <p><strong>Formation/Classe :</strong> {{ props.carnetData.titre }}</p>
                </div>
                <button @click="printCarnet"
                    class="print-hidden mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-all flex items-center mx-auto">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
            </header>

            <!-- Contenu -->
            <div v-if="props.carnetData.disciplines?.length" class="space-y-2">
                <!-- Tableau -->
                <div class="overflow-x-auto border border-gray-300 rounded-lg">
                    <table class="w-full text-left text-gray-700">
                        <thead class="bg-gray-100">
                            <tr class="border-b border-gray-300">
                                <th class="p-3 text-sm font-semibold text-gray-600">Discipline</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">H. Total</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Formateur</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Coef</th>
                                <th class="p-3 text-sm font-semibold text-gray-600">Notes (/20)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="discipline in props.carnetData.disciplines" :key="discipline.id"
                                class="border-b border-gray-200 last:border-0">
                                <td class="p-3 font-medium">{{ discipline.titre }}</td>
                                <td class="p-3">{{ discipline.heures_total }}h</td>
                                <td class="p-3">{{ discipline.formateur }}</td>
                                <td class="p-3 text-center">{{ discipline.coefficient }}</td>
                                <td class="p-3 text-center">{{ discipline.note }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Résumé -->
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-gray-700">
                        <p><strong>Total des heures :</strong> {{ totalHeures }}h</p>
                        <p><strong>Moyenne :</strong> <span :class="getMoyenneColor(moyenneFormation)">{{
                            moyenneFormation ? moyenneFormation.toFixed(2) : 'N/A' }} / 20</span></p>
                        <p><strong>Mention :</strong> <span :class="getMoyenneColor(moyenneFormation)">{{
                                getMention(moyenneFormation) }}</span></p>
                    </div>
                </div>

                <!-- Signature et Date -->
                <div class="mt-6 flex justify-between items-end text-gray-700">
                    <div class="w-1/2">
                        <p class="text-sm"><strong>Date :</strong> __________________________</p>
                    </div>
                    <div class="w-1/2 text-right">
                        <p class="text-sm"><strong>Signature de la Directrice :</strong></p>
                        <div class="h-12 border-b border-gray-400 w-48 inline-block mt-2"></div>
                    </div>
                </div>
            </div>

            <!-- Message si vide -->
            <div v-else class="border border-yellow-300 rounded-lg p-6 text-center bg-yellow-50">
                <p class="text-gray-700 text-lg">Aucune discipline ou notes disponibles pour le moment.</p>
            </div>

            <!-- Pied de page -->
            <footer class="mt-6 text-sm text-gray-600 text-center border-t border-gray-300 pt-4 print:text-black">
                <p>© {{ new Date().getFullYear() }} {{ props.carnetData.ecole }} - Tous droits réservés</p>
            </footer>
        </div>
    </MonLayout>
</template>

<style scoped>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

table {
    border-collapse: collapse;
    width: 100%;
}

th,
td {
    min-width: 80px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #e5e7eb;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #6366f1;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .max-w-4xl {
        max-width: 100%;
    }

    th,
    td {
        font-size: 0.875rem;
        padding: 0.75rem;
    }

    h1 {
        font-size: 1.75rem;
    }
}

@media print {
    .print-hidden {
        display: none !important;
    }

    .bg-white,
    .bg-gray-50,
    .bg-gray-100,
    .bg-yellow-50 {
        background: none !important;
    }

    .text-gray-900,
    .text-gray-700,
    .text-gray-600,
    .text-gray-400,
    .text-green-600,
    .text-yellow-600,
    .text-red-600 {
        color: #000000 !important;
    }

    .shadow-lg {
        box-shadow: none !important;
    }

    .border,
    .border-b,
    .border-t {
        border-color: #000000 !important;
    }

    table,
    th,
    td {
        border: 1px solid #000 !important;
        font-size: 12pt;
    }

    .max-w-4xl {
        max-width: 100%;
        margin: 0;
        padding: 20mm;
    }
}
</style>