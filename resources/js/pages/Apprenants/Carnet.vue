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
                notes: string; // Ex. "15 / 20"
                statut: string;
            }>;
            total_heures: number;
            moyenne_formation: number | null;
        };
    }>();

    // Couleur conditionnelle pour la moyenne
    const getMoyenneColor = (moyenne: number | null) => {
        if (!moyenne) return 'text-gray-400';
        if (moyenne >= 16) return 'text-green-400';
        if (moyenne >= 12) return 'text-yellow-400';
        return 'text-red-400';
    };

    // Mention basée sur la moyenne
    const getMention = (moyenne: number | null) => {
        if (!moyenne) return 'Non défini';
        if (moyenne >= 16) return 'Très Bien';
        if (moyenne >= 14) return 'Bien';
        if (moyenne >= 12) return 'Assez Bien';
        if (moyenne >= 10) return 'Passable';
        return 'Insuffisant';
    };

    // Calculs
    const totalHeures = computed(() => props.carnetData.total_heures ?? props.carnetData.disciplines.reduce((sum, d) => sum + d.heures_total, 0));
    const moyenneFormation = computed(() => props.carnetData.moyenne_formation ?? 0);

    // Fonction d'impression (desktop uniquement)
    const printCarnet = () => {
            window.print();
        
    };
    </script>

    <template>
        <MonLayout>
            <div
                class="max-w-5xl mx-auto p-4 sm:p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-gray-100 min-h-screen">
                <!-- En-tête du bulletin -->
                <header class="mb-8 animate-slide-in">
                    <div class="bg-gray-800 p-4 sm:p-6 rounded-xl shadow-lg text-center">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center justify-center">
                            <i class="fas fa-book mr-3 text-indigo-400 animate-pulse"></i>
                            Carnet de Notes
                        </h1>
                        <div class="mt-4 text-gray-200 space-y-2">
                            <p><strong>École :</strong> {{ props.carnetData.ecole }}</p>
                            <p><strong>Apprenant :</strong> {{ props.carnetData.apprenant }}</p>
                            <p><strong>Formation/Classe :</strong> {{ props.carnetData.titre }}</p>
                            <p><strong>Année scolaire :</strong> {{ props.carnetData.annee_scolaire }}</p>
                        </div>
                        <button @click="printCarnet"
                            class="print-hide mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-indigo-700 transition-all duration-300 flex items-center mx-auto">
                            <i class="fas fa-print mr-2"></i> Imprimer
                        </button>
                    </div>
                </header>

                <!-- Contenu -->
                <div v-if="props.carnetData.disciplines?.length" class="space-y-6">
                    <!-- Tableau (Desktop + Impression) -->
                    <div class="hidden md:block print-block overflow-x-auto bg-gray-800 rounded-xl shadow-lg">
                        <table class="w-full text-left text-gray-200">
                            <thead class="bg-gradient-to-r from-gray-900 to-gray-800 sticky top-0 z-10">
                                <tr>
                                    <th class="p-4 text-sm font-semibold text-gray-300 print-hide"><i
                                            class="fas fa-id-badge mr-2"></i>N°</th>
                                    <th class="p-4 text-sm font-semibold text-gray-300"><i
                                            class="fas fa-book-open mr-2"></i>Discipline</th>
                                    <th class="p-4 text-sm font-semibold text-gray-300 print-hide"><i
                                            class="fas fa-clock mr-2"></i>H. Hebdo</th>
                                    <th class="p-4 text-sm font-semibold text-gray-300"><i
                                            class="fas fa-hourglass mr-2"></i>H. Total</th>
                                    <th class="p-4 text-sm font-semibold text-gray-300"><i
                                            class="fas fa-user-tie mr-2"></i>Formateur</th>
                                    <th class="p-4 text-sm font-semibold text-gray-300"><i
                                            class="fas fa-weight mr-2"></i>Coef</th>
                                    <th class="p-4 text-sm font-semibold text-gray-300"><i
                                            class="fas fa-star mr-2"></i>Notes (/20)</th>
                                    <th class="p-4 text-sm font-semibold text-gray-300 print-hide"><i
                                            class="fas fa-check-circle mr-2"></i>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(discipline, index) in props.carnetData.disciplines" :key="discipline.id"
                                    class="border-b border-gray-700 hover:bg-gray-700 transition-all duration-300 animate-row-in"
                                    :style="{ animationDelay: `${index * 0.1}s` }">
                                    <td class="p-4 print-hide">{{ discipline.id }}</td>
                                    <td class="p-4 font-medium">{{ discipline.titre }}</td>
                                    <td class="p-4 print-hide">{{ discipline.heures_hebdo }}h</td>
                                    <td class="p-4">{{ discipline.heures_total }}h</td>
                                    <td class="p-4">{{ discipline.formateur }}</td>
                                    <td class="p-4">{{ discipline.coefficient }}</td>
                                    <td class="p-4">{{ discipline.note }}</td>
                                    <td class="p-4 print-hide">
                                        <span
                                            :class="discipline.statut === 'Terminé' ? 'text-green-400' : 'text-yellow-400'">
                                            {{ discipline.statut }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cartes (Mobile uniquement, pas en impression) -->
                    <div class="md:hidden print-hide space-y-4">
                        <div v-for="(discipline, index) in props.carnetData.disciplines" :key="discipline.id"
                            class="bg-gray-800 p-4 rounded-xl shadow-lg animate-row-in"
                            :style="{ animationDelay: `${index * 0.1}s` }">
                            <h3 class="text-lg font-semibold text-gray-100 flex items-center">
                                <i class="fas fa-book-open mr-2 text-indigo-400"></i>{{ discipline.titre }}
                            </h3>
                            <div class="grid grid-cols-2 gap-2 mt-2 text-sm text-gray-200">
                                <p><i class="fas fa-clock mr-1"></i>{{ discipline.heures_hebdo }}h/sem</p>
                                <p><i class="fas fa-hourglass mr-1"></i>{{ discipline.heures_total }}h</p>
                                <p><i class="fas fa-user-tie mr-1"></i>{{ discipline.formateur }}</p>
                                <p><i class="fas fa-weight mr-1"></i>{{ discipline.coefficient }}</p>
                                <p><i class="fas fa-star mr-1"></i>{{ discipline.note }}</p>
                                <p>
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <span :class="discipline.statut === 'Terminé' ? 'text-green-400' : 'text-yellow-400'">
                                        {{ discipline.statut }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="bg-gradient-to-r from-gray-700 to-gray-600 p-4 sm:p-6 rounded-xl shadow-lg animate-fade-in">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-gray-200">
                            <p>
                                <i class="fas fa-hourglass-end mr-2 text-indigo-400"></i>
                                <strong>Total des heures :</strong> {{ totalHeures }}h
                            </p>
                            <p>
                                <i class="fas fa-chart-bar mr-2 text-indigo-400"></i>
                                <strong>Moyenne :</strong>
                                <span :class="getMoyenneColor(moyenneFormation)">
                                    {{ moyenneFormation ? moyenneFormation.toFixed(2) : 'N/A' }} / 20
                                </span>
                            </p>
                            <p>
                                <i class="fas fa-award mr-2 text-indigo-400"></i>
                                <strong>Mention :</strong>
                                <span :class="getMoyenneColor(moyenneFormation)">
                                    {{ getMention(moyenneFormation) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Message si vide -->
                <div v-else
                    class="bg-gradient-to-r from-yellow-900 to-yellow-800 p-6 rounded-xl shadow-lg text-center animate-pulse">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-3xl mb-3"></i>
                    <p class="text-gray-200 text-lg">Aucune discipline ou notes disponibles pour le moment.</p>
                </div>
            </div>
        </MonLayout>
    </template>

    <style scoped>
    @import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

    body {
        font-family: 'Inter', 'Arial', sans-serif;
    }

    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes rowIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-slide-in {
        animation: slideIn 0.5s ease-out forwards;
    }

    .animate-fade-in {
        animation: fadeIn 0.7s ease-out forwards;
    }

    .animate-row-in {
        animation: rowIn 0.5s ease-out forwards;
    }

    /* Styles généraux */
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

    /* Scrollbar stylisée */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #2d3748;
        border-radius: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #6366f1;
        border-radius: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #818cf8;
    }

    /* Réactivité */
    @media (max-width: 768px) {
        .max-w-5xl {
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

        .text-lg {
            font-size: 1rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }
    }

    @media (max-width: 640px) {
        .p-6 {
            padding: 1rem;
        }
    }

    /* Styles pour l'impression */
    @media print {
        body {
            background: white;
            font-family: 'Inter', 'Arial', sans-serif;
            margin: 0;
            padding: 20mm;
        }

        .bg-gradient-to-br,
        .bg-gradient-to-r,
        .bg-gray-800,
        .bg-gray-700,
        .bg-gray-900 {
            /* background: white !important; */
            color: black !important;
            box-shadow: none !important;
        }

        .shadow-lg,
        .hover\:shadow-xl,
        .hover\:bg-gray-700,
        .animate-pulse,
        .animate-slide-in,
        .animate-fade-in,
        .animate-row-in {
            box-shadow: none !important;
            transition: none !important;
            animation: none !important;
        }

        .text-gray-100,
        .text-gray-200,
        .text-gray-400 {
            color: black !important;
        }

        .text-indigo-400,
        .text-green-400,
        .text-yellow-400,
        .text-red-400 {
            color: black !important;
            font-weight: bold;
        }

        .print-hide {
            display: none !important;
        }

        /* Cacher bouton, cartes, colonnes N°, H. Hebdo, Statut */
        .print-block {
            display: block !important;
        }

        /* Forcer le tableau */
        .md\:hidden {
            display: none !important;
        }

        /* Cacher les cartes */
        .hidden.md\:block {
            display: block !important;
        }

        /* Afficher le tableau */
        table,
        th,
        td {
            border: 1px solid #000 !important;
            font-size: 12pt;
        }

        header {
            margin-bottom: 20mm;
        }

        .space-y-6>*+* {
            margin-top: 10mm !important;
        }

        h1 {
            font-size: 24pt;
        }

        p {
            font-size: 12pt;
        }
    }
    </style>