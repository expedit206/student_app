<script setup lang="ts">
import { ref } from 'vue';
import FormateurLayout from '@/components/MonLayout.vue';
import {route} from 'ziggy-js';

defineProps<{
  dashboardData: {
    totalFormations: number;
    totalApprenants: number;
    recentActivities: Array<{ id: number; type: string; description: string; date: string }>;
    formations: Array<{ id: number; name: string; apprenants: number; moyenne: number }>;
  };
}>();
</script>

<template>
  <FormateurLayout>
    <div class="p-6 bg-gray-900 text-gray-100 min-h-screen overflow-hidden">
      <h1 class="text-3xl font-extrabold mb-6 flex items-center animate-slide-in">
        <i class="fas fa-tachometer-alt mr-3 text-blue-400 text-4xl animate-pulse"></i>
        Tableau de Bord
      </h1>

      <!-- Statistiques -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gray-800 rounded-xl shadow-md p-6 transition-all hover:shadow-xl hover:bg-gray-700">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-400">Formations Gérées</p>
              <h2 class="text-3xl font-bold text-white">{{ dashboardData.totalFormations }}</h2>
            </div>
            <i class="fas fa-book text-3xl text-blue-400 animate-bounce"></i>
          </div>
        </div>
        <div class="bg-gray-800 rounded-xl shadow-md p-6 transition-all hover:shadow-xl hover:bg-gray-700">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-400">Apprenants Inscrits</p>
              <h2 class="text-3xl font-bold text-white">{{ dashboardData.totalApprenants }}</h2>
            </div>
            <i class="fas fa-users text-3xl text-blue-400 animate-bounce"></i>
          </div>
        </div>
      </div>

      <!-- Sections principales -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Activités Récentes -->
        <div class="bg-gray-800 rounded-xl shadow-md p-6 overflow-y-auto max-h-[400px] transition-all hover:shadow-xl">
          <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
            <i class="fas fa-clock mr-2 text-blue-400"></i> Activités Récentes
          </h3>
          <div v-if="dashboardData.recentActivities.length" class="space-y-4">
            <div
              v-for="activity in dashboardData.recentActivities"
              :key="activity.id"
              class="p-3 bg-gray-700 rounded-lg transition-all hover:bg-gray-600 hover:shadow-md"
            >
              <p class="text-sm text-gray-300">{{ activity.type }}</p>
              <p class="text-white font-medium">{{ activity.description }}</p>
              <span class="text-xs text-gray-400">{{ activity.date }}</span>
            </div>
          </div>
          <div v-else class="text-center text-gray-400 py-4">
            <i class="fas fa-info-circle mr-2"></i> Aucune activité récente
          </div>
        </div>

        <!-- Mes Formations -->
        <div class="bg-gray-800 rounded-xl shadow-md p-6 overflow-y-auto max-h-[400px] transition-all hover:shadow-xl">
          <a :href="route('formateur.formations')" class="text-xl font-semibold text-white mb-4 flex items-center hover:text-blue-400 transition-colors">
            <i class="fas fa-graduation-cap mr-2 text-blue-400"></i> Mes Formations
          </a>
          <div v-if="dashboardData.formations.length" class="space-y-4">
            <div
              v-for="formation in dashboardData.formations"
              :key="formation.id"
              class="p-3 bg-gray-700 rounded-lg transition-all hover:bg-gray-600 hover:shadow-md cursor-pointer"
              @click="$router.get(route('formateur.notes', { formation: formation.id }))"
            >
              <h4 class="font-medium text-white">{{ formation.name }}</h4>
              <div class="flex justify-between items-center">
                <p class="text-sm text-gray-300">Apprenants: {{ formation.apprenants }}</p>
                <p class="text-sm" :class="{
                  'text-green-400': formation.moyenne >= 80,
                  'text-yellow-400': formation.moyenne >= 60 && formation.moyenne < 80,
                  'text-red-400': formation.moyenne < 60,
                }">
                  Moyenne: {{ formation.moyenne }}%
                </p>
              </div>
            </div>
          </div>
          <div v-else class="text-center text-gray-400 py-4">
            <i class="fas fa-info-circle mr-2"></i> Aucune formation disponible
          </div>
        </div>
      </div>
    </div>
  </FormateurLayout>
</template>

<style scoped>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';

body {
  font-family: 'Arial', sans-serif;
}

.animate-slide-in {
  animation: slideIn 0.6s ease-out;
}

.animate-pulse {
  animation: pulse 2s infinite;
}

.animate-bounce {
  animation: bounce 1s infinite;
}

.max-h-[400px] {
  max-height: 400px;
}

@keyframes slideIn {
  from { opacity: 0; transform: translateX(-20px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-10px); }
  60% { transform: translateY(-5px); }
}
</style>