<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';
import axios from 'axios';
// import echo ;
// import Echo from 'laravel-echo';
// import { Echo } from "./echo";

// Props passées par Inertia (initialement chargées par le serveur)
const props = defineProps({
    currentUser: Object,
    contacts: Array,
    friend: Object,
    messages: Array
});

// Variables réactives
const selectedUser = ref(props.friend);
const messageInput = ref('');
const currentMessages = ref(props.messages); // Messages de la conversation actuelle
const messagesContainer = ref(null); // Référence à la zone de messages pour le scroll

// Sélectionner un utilisateur pour discuter
const selectUser = async (user) => {
    selectedUser.value = user;
    try {
        // Récupérer les messages pour la conversation avec cet utilisateur
        const response = await axios.get(`/chat/messages`, {
            params: {
                sender_id: props.currentUser.id,
                sender_role: props.currentUser.role,
                receiver_id: user.id,
                receiver_role: user.role
            }
        });
        Echo.private(`chat.${props.currentUser.id}`)
            .listen('MessageSend', (response) => {
                currentMessages.value.push(response.message);
            });
            
        
        currentMessages.value = response.data;
    } catch (error) {
        console.error('Erreur lors de la récupération des messages:', error);
        currentMessages.value = [];
    }
};

selectUser(props.friend);
// Envoyer un message
const sendMessage = async () => {
    if (!messageInput.value.trim() || !selectedUser.value) return;

    // Ajouter le message à la liste avant l'envoi pour un feedback immédiat
    currentMessages.value.push({
        sender: props.currentUser.name,
        message: messageInput.value,
        timestamp: new Date()
    });
    scrollToBottom();

    try {
        console.log(messageInput.value);
        console.log(selectedUser.value.id);
        const response = await axios.post('/chat/send', {
            message: messageInput.value,
            receiver_id: selectedUser.value.id,
            receiver_role: selectedUser.value.role
        });
        messageInput.value = ''; // Réinitialiser le champ de saisie
    } catch (error) {
        console.error('Erreur lors de l\'envoi du message:', error);
    }
};

// Fonction pour scroller automatiquement vers le bas
const scrollToBottom = () => {
    nextTick(() => {
        if (messagesContainer.value) {
            messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
        }
    });
};

// Surveiller les changements dans currentMessages pour scroller vers le bas
watch(currentMessages, () => {
    scrollToBottom();
});

// Scroller vers le bas au chargement initial
watch(selectedUser, () => {
    scrollToBottom();
});
</script>

<template>
    <div class="max-w-4xl mx-auto p-4 bg-white shadow-lg rounded-lg flex h-[600px]">
        <!-- Menu de chat (liste des contacts) -->
        <div class="w-1/3 border-r p-4 overflow-y-auto">
            <h2 class="text-lg font-semibold mb-4">Contacts</h2>
            <ul>
                <li v-for="contact in contacts" :key="`${contact.role}-${contact.id}`" @click="selectUser(contact)"
                    class="p-2 mb-2 rounded cursor-pointer hover:bg-gray-100"
                    :class="{ 'bg-gray-200': selectedUser && selectedUser.id === contact.id }">
                    {{ contact.name }} ({{ contact.role.charAt(0).toUpperCase() + contact.role.slice(1) }})
                </li>
            </ul>
        </div>

        <!-- Zone de conversation -->
        <div class="w-2/3 p-4 flex flex-col">
            <h2 class="text-lg font-semibold mb-4">
                {{ selectedUser?.name ? `Discussion avec ${selectedUser.name}` : (selectedUser ? 'Discussion avec l\'admin' : 'Sélectionnez un contact') }}
            </h2>

            <!-- Liste des messages -->
            <div ref="messagesContainer" class="flex-1 overflow-y-auto border p-4 mb-4 rounded">
                <div v-if="!selectedUser" class="text-gray-500 text-center">
                    Sélectionnez un contact pour commencer une discussion.
                </div>
                <div v-else v-for="(msg, index) in currentMessages" :key="index" class="mb-2 flex"
                    :class="{ 'justify-end': msg.sender === props.currentUser.name, 'justify-start': msg.sender !== props.currentUser.name }">
                    <div class="max-w-xs"
                        :class="{ 'bg-indigo-500 text-white rounded-l-lg rounded-br-lg': msg.sender === props.currentUser.name, 'bg-gray-200 text-gray-800 rounded-r-lg rounded-bl-lg': msg.sender !== props.currentUser.name }">
                        <div class="p-2">
                            <span class="font-bold">{{ msg.sender }}</span>
                            <span class="text-xs ml-2 opacity-75">
                                {{ new Date(msg.timestamp).toLocaleTimeString() }}
                            </span>
                            <p>{{ msg.message }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'envoi de message -->
            <div v-if="selectedUser" class="flex gap-2">
                <input v-model="messageInput" type="text" placeholder="Tapez votre message..."
                    class="flex-1 p-2 border rounded" @keyup.enter="sendMessage" />
                <button @click="sendMessage" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Envoyer
                </button>
            </div>
        </div>
    </div>
</template>