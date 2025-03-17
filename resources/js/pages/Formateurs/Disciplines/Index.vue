    <template>
        <FormateurLayout>
            <div class="p-6 bg-gray-900 text-gray-100 min-h-screen overflow-hidden">
                <!-- Header -->
                <h1 class="text-3xl font-extrabold mb-8 flex items-center animate-slide-in">
                    <i class="fas fa-book mr-3 text-blue-400 text-4xl animate-spin-slow"></i>
                    Gestion des Disciplines
                </h1>

                <!-- Sélection de Formation -->
                <div class="mb-4">
                    <select v-model="selectedFormation" @change="resetSearch"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg text-gray-100 p-2">
                        <option value="">toutes mes formations</option>
                        <option v-for="formation in formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }}
                        </option>
                    </select>
                </div>

                <!-- Statistiques Compactes -->
                <div class="grid grid-cols-1 gap-4 mb-8">
                    <div
                        class="bg-gray-800 rounded-lg shadow-lg p-4 flex items-center justify-between transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                        <div class="flex items-center">
                            <i class="fas fa-book-open text-2xl text-blue-400 mr-3 animate-pulse"></i>
                            <div>
                                <p class="text-xs text-gray-400">Disciplines</p>
                                <h2 class="text-lg font-bold text-white animate-number">{{ totalDisciplines }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="relative mb-8 animate-fade-up">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 animate-pulse"></i>
                    <input v-model="searchQuery" type="text" placeholder="Rechercher par nom de discipline"
                        class="w-full pl-12 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-md hover:shadow-xl transition-all duration-300 hover:bg-gray-700" />
                </div>

                <!-- Table -->
                <div
                    class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-700 to-gray-800 text-gray-200 animate-slide-in">
                                <th class="py-4 px-6 text-left font-semibold">
                                    <i class="fas fa-book mr-2 text-blue-400 animate-pulse"></i>Nom de Discipline
                                </th>
                                <th class="py-4 px-6 text-left font-semibold">
                                    <i class="fas fa-info-circle mr-2 text-blue-400 animate-pulse"></i>Description
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(discipline, index) in filteredDisciplines" :key="discipline.id"
                                class="border-b border-gray-700 hover:bg-gray-700 transition-all duration-300 animate-row-in"
                                :style="{ animationDelay: `${0.7 + index * 0.1}s` }">
                                <td class="py-4 px-6 text-gray-100 hover:text-blue-300 transition-colors duration-200">
                                    {{ discipline.nom }}
                                </td>
                                <td class="py-4 px-6 text-gray-300 hover:text-blue-300 transition-colors duration-200">
                                    {{ discipline.description || 'Aucune description disponible' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="filteredDisciplines.length === 0" class="p-6 text-center text-gray-400 animate-pulse">
                        <i class="fas fa-exclamation-circle mr-2 text-xl animate-bounce"></i>
                        Aucune discipline trouvée
                    </div>
                </div>
            </div>
        </FormateurLayout>
    </template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';

const props = defineProps<{
    disciplines: Array<{
        id: number;
        nom: string;
        description: string;
        formateur_id: number;
        formation_id: number; // Ajout de l'ID de la formation
    }>;
    formations: Array<{
        id: number;
        nom: string;
    }>;
}>();
console.log(props.formations);

const searchQuery = ref('');
const selectedFormation = ref('');

const filteredDisciplines = computed(() => {
    const searchTerm = searchQuery.value.toLowerCase();
    return props.disciplines.filter(discipline => {
        const matchesFormation = selectedFormation.value ? discipline.formation_id === parseInt(selectedFormation.value) : true;
        const matchesSearch = discipline.nom.toLowerCase().includes(searchTerm);
        return matchesFormation && matchesSearch;
    });
});

const totalDisciplines = computed(() => filteredDisciplines.value.length);

const resetSearch = () => {
    searchQuery.value = '';
};
</script>

<style scoped>
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

@keyframes number {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
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

.animate-bounce-in {
    animation: bounceIn 0.8s ease-out;
}

.animate-spin-slow {
    animation: spinSlow 4s linear infinite;
}

.animate-number {
    animation: number 0.5s ease-out;
}
</style>