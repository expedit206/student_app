<script setup lang="ts">
import { ref, computed, defineProps } from 'vue';
import FormateurLayout from '@/components/Formateurs/FormateurLayout.vue';

const props = defineProps<{
    apprenants: Array<{
        id: number;
        nom: string;
        prenom: string;
        telephone: string;
        formation_id: number;
        user: { email: string };
    }>;
    formations: Array<{ id: number; titre: string }>;
}>();

const searchQuery = ref('');
const selectedFormation = ref<number | null>(null);

const filteredApprenants = computed(() => {
    return props.apprenants.filter(apprenant => {
        const searchTerm = searchQuery.value.toLowerCase();
        const matchesSearch =
            apprenant.nom.toLowerCase().includes(searchTerm) ||
            apprenant.prenom.toLowerCase().includes(searchTerm) ||
            apprenant.user.email.toLowerCase().includes(searchTerm);

        const matchesFormation = selectedFormation.value
            ? apprenant.formation_id === selectedFormation.value
            : true;

        return matchesSearch && matchesFormation;
    });
});

const totalApprenants = computed(() => props.apprenants.length);
const totalFormations = computed(() => props.formations.length);
const apprenantsParFormation = computed(() => {
    return props.formations.map(formation => ({
        titre: formation.titre,
        count: props.apprenants.filter(a => a.formation_id === formation.id).length
    })).sort((a, b) => b.count - a.count)[0]?.titre || 'Aucune';
});
</script>

<template>
    <FormateurLayout>
        <div class="p-6 bg-gray-900 text-gray-100 min-h-screen">
            <!-- Header -->
            <h1 class="text-3xl font-extrabold mb-6 flex items-center animate-fade-in">
                <i class="fas fa-users mr-3 text-blue-400 text-4xl"></i>
                Gestion des Apprenants
            </h1>

            <!-- Statistiques Compactes -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div
                    class="bg-gray-800 rounded-lg shadow-md p-4 flex items-center justify-between transform hover:scale-105 transition-transform">
                    <div class="flex items-center">
                        <i class="fas fa-user-graduate text-2xl text-blue-400 mr-2"></i>
                        <div>
                            <p class="text-xs text-gray-400">Apprenants</p>
                            <h2 class="text-lg font-bold text-white">{{ totalApprenants }}</h2>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-800 rounded-lg shadow-md p-4 flex items-center justify-between transform hover:scale-105 transition-transform">
                    <div class="flex items-center">
                        <i class="fas fa-book-open text-2xl text-purple-400 mr-2"></i>
                        <div>
                            <p class="text-xs text-gray-400">Formations</p>
                            <h2 class="text-lg font-bold text-white">{{ totalFormations }}</h2>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-800 rounded-lg shadow-md p-4 flex items-center justify-between transform hover:scale-105 transition-transform">
                    <div class="flex items-center">
                        <i class="fas fa-star text-2xl text-yellow-400 mr-2"></i>
                        <div>
                            <p class="text-xs text-gray-400">Top Formation</p>
                            <h2 class="text-lg font-bold text-white truncate">{{ apprenantsParFormation }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input v-model="searchQuery" type="text" placeholder="Rechercher par nom, prénom ou email"
                        class="w-full pl-12 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-md hover:shadow-lg transition-shadow" />
                </div>
                <div class="relative">
                    <i
                        class="fas fa-graduation-cap absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select v-model="selectedFormation"
                        class="pl-12 pr-10 py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none shadow-md hover:shadow-lg transition-shadow">
                        <option value="">Toutes les formations</option>
                        <option v-for="formation in props.formations" :key="formation.id" :value="formation.id">
                            {{ formation.titre }}
                        </option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="py-4 px-6 text-left text-gray-300 font-semibold">
                                <i class="fas fa-user mr-2"></i>Nom
                            </th>
                            <th class="py-4 px-6 text-left text-gray-300 font-semibold">
                                <i class="fas fa-user-circle mr-2"></i>Prénom
                            </th>
                            <th class="py-4 px-6 text-left text-gray-300 font-semibold">
                                <i class="fas fa-phone mr-2"></i>Téléphone
                            </th>
                            <th class="py-4 px-6 text-left text-gray-300 font-semibold">
                                <i class="fas fa-envelope mr-2"></i>Email
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="apprenant in filteredApprenants" :key="apprenant.id"
                            class="border-b border-gray-700 hover:bg-gray-700 transition-colors duration-200">
                            <td class="py-4 px-6 text-gray-100">{{ apprenant.nom }}</td>
                            <td class="py-4 px-6 text-gray-100">{{ apprenant.prenom }}</td>
                            <td class="py-4 px-6 text-gray-100">{{ apprenant.telephone }}</td>
                            <td class="py-4 px-6 text-gray-100">{{ apprenant.user.email }}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="filteredApprenants.length === 0" class="p-6 text-center text-gray-400 animate-pulse">
                    <i class="fas fa-exclamation-circle mr-2 text-xl"></i>
                    Aucun apprenant trouvé
                </div>
            </div>
        </div>
    </FormateurLayout>
</template>

<style scoped>
select {
    background-image: none;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}
</style>