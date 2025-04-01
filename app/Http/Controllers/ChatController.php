<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Controller;
use App\Models\Apprenant;
use App\Models\Formateur;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getMessages(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'sender_role' => 'required|in:apprenant,formateur,admin',
            'receiver_id' => 'required|exists:users,id',
            'receiver_role' => 'required|in:apprenant,formateur,admin'
        ]);

        $user = Auth::user();

        // Vérifier que l'utilisateur connecté est bien le sender
        if ($user->id != $request->sender_id || $user->role != $request->sender_role) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $messages = ChatMessage::where(function ($query) use ($request) {
            $query->where('sender_id', $request->sender_id)
                ->where('sender_role', $request->sender_role)
                ->where('receiver_id', $request->receiver_id)
                ->where('receiver_role', $request->receiver_role);
        })->orWhere(function ($query) use ($request) {
            $query->where('sender_id', $request->receiver_id)
                ->where('sender_role', $request->receiver_role)
                ->where('receiver_id', $request->sender_id)
                ->where('receiver_role', $request->sender_role);
        })->orderBy('timestamp', 'asc')->get();
        // dd($messages);  

        $formattedMessages = $messages->map(function ($message) {
            return [
                'sender' => $message->sender_role === 'apprenant' ? $message->sender->apprenant->nom : ($message->sender_role === 'formateur' ? $message->sender->formateur->nom : $message->sender->email),
                'message' => $message->message,
                'timestamp' => $message->timestamp
            ];
        });
        // return response()->json($messages);

        return response()->json($formattedMessages);
    }


    public function chat()
    {
        $user = Auth::user();
        // Récupérer les contacts selon le rôle de l'utilisateur connecté
        $contacts = $this->getContacts($user);
        // dd($contacts->first());
       $firstContact = $contacts->first();
        // Récupérer les messages entre l'utilisateur connecté et le friend
        $messages = ChatMessage::where(function ($query) use ($user, $firstContact) {
            $query->where('sender_id', $user->id)
                ->where('sender_role', $user->role)
                ->where('receiver_id', $firstContact->id)
                ->where('receiver_role', $firstContact->role);
        })->orWhere(function ($query) use ($user, $firstContact) {
            $query->where('sender_id', $firstContact->id)
                ->where('sender_role', $firstContact->role)
                ->where('receiver_id', $user->id)
                ->where('receiver_role', $user->role);
        })->orderBy('timestamp', 'asc')->get();
        // Formater les contacts et les messages pour Inertia
        $formattedContacts = $contacts->map(function ($contact) {
            $contact->load('apprenant', 'formateur');
            // dd($contact->formateur->nom);
            return [
                'id' => $contact->id,
                'name' => $contact->role === 'apprenant' ? $contact->apprenant?->nom : ($contact->role === 'formateur' ? $contact->formateur->nom : $contact->administrateur?->nom),
                'role' => $contact->role
            ];
        });

        $formattedMessages = $messages->map(function ($message) {
            // dd($message->sender);
            return response([
                $message->sender_role
            ]);
            return [
                'sender' => $message->sender_role === 'apprenant' ? $message->sender->apprenant?->nom : ($message->sender_role === 'formateur' ? $message->sender->formateur->nom : $message->sender->email),
                'message' => $message->message,
                'timestamp' => $message->timestamp
            ];
        });

        $friendData = [
            'id' => $firstContact->id,
            'name' => $firstContact->role === 'apprenant' ? $firstContact->apprenant->nom : ($firstContact->role === 'formateur' ? $firstContact->formateur->nom : $firstContact->email),
            'role' => $firstContact->role
        ];

        // getMessages($user->id, $user->role, $firstContact->id, $firstContact->role);
        return inertia('Chat/Chat', [
            'friend' => $friendData,
            'contacts' => $formattedContacts,
            'messages' => $formattedMessages,
            'currentUser' => [
                'id' => $user->id,
                'role' => $user->role,
                'name' => $user->role === 'apprenant' ? $user->apprenant->nom : ($user->role === 'formateur' ? $user->formateur->nom : $user->email)
            ]
        ]);
    }


  

    private function getContacts($user)
    {
        if ($user->role === 'formateur') {
            
            // Formateur : tous ses apprenants (ceux dans ses formations) + l'administrateur
            $formationIds = $user->formateur->formations->pluck('id');
            $apprenants = Apprenant::whereIn('formation_id', $formationIds)
            ->with('user')
                ->get()
                ->pluck('user');
                $administrateurs = User::where('role', 'admin')->get();
                return $apprenants->merge($administrateurs);

            } elseif ($user->role === 'apprenant') {
                            // dd($user);
            // Apprenant : tous les formateurs de sa formation + l'administrateur
            $formationId = $user->apprenant->formation_id;
            // dd($formationId);
            $formateurs = Formateur::whereHas('formations', function ($query) use ($formationId) {
                $query->where('formations.id', $formationId);
            })->with('user')->get()->pluck('user');
            // dd($formateurs);
            $administrateurs = User::where('role', 'admin')->with('formateur')->get();

            return $formateurs->merge($administrateurs);

        } else {
            // Administrateur : tous les apprenants + tous les formateurs
            $apprenants = Apprenant::with('user')->get()->pluck('user');
            $formateurs = Formateur::with('user')->get()->pluck('user');
            return $apprenants->merge($formateurs);
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'receiver_id' => 'required|exists:users,id',
            'receiver_role' => 'required|in:apprenant,formateur,admin'
        ]);

        $user = Auth::user();

        // return response()->json([
        //     'user' => $user,
        //     'request' => $request,
        //     'receiver_id' => $request->receiver_id,
        //     'receiver_role' => $request->receiver_role,
        //     'message' => $request->message,
        //     'sender_id' => $user->id,
        //     'sender_role' => $user->role
            
        // ]);
        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'sender_role' => $user->role,
            'receiver_id' => $request->receiver_id,
            'receiver_role' => $request->receiver_role,
            'message' => $request->message,
            'timestamp' => now()
        ]);
    
        // return response()->json([$request]);

        return response()->json([
            'message' => 'Message envoyé avec succès',
            'data' => [
                'sender' => $user->role === 'apprenant' ? $user->apprenant->nom : ($user->role === 'formateur' ? $user->formateur->nom : $user->email),
                'message' => $message->message,
                'timestamp' => $message->timestamp
            ]
        ]);
    }
}
