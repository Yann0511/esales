<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\ResetPasswordRequest;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Traits\Helpers\HelperTrait;
use App\Traits\Helpers\IdTrait;
use App\Traits\Helpers\LogActivity;
use App\Http\Resources\user\auth\AuthResource;
use App\Http\Resources\user\auth\LoginResource;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use IdTrait, HelperTrait;
    public function __construct()
    {
        $this->middleware(['auth:api'])->only(['logout']);
    }


    /**
     * Authentfication et permission d'accès au système
     *
     * @param  \App\Http\Requests\auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {

            $identifiants = $request->all();

            $utilisateur = User::where('email', $identifiants['email'])->first();

            // Si la variable utilisateur est null alors une exception sera déclenché notifiant que l'email renseigner ne correspond à aucun enregistrement de la table users
            if (!$utilisateur) throw new Exception("L'email de l'utilisateur ou mot de passe incorrect.", 401);

            // Vérifier si le mot de passe renseigner correspond au mot de passe du compte uitisateur trouver
            if (!Hash::check($identifiants['password'], $utilisateur->password)) throw new Exception("L'email de l'utilisateur ou mot de passe incorrect.", 401);

            // Vérifier si le compte de l'utilisateur est activé ou pas
            if (!$utilisateur->statut) {
                throw new Exception("Votre compte n'est pas activé.", 401);
            }

            // Connexion...
            if (!Auth::attempt(['email' => $utilisateur["email"], 'password' => $identifiants['password']]))  throw new Exception("Erreur de connexion", 500);

            $user = Auth::user();

            $data = ["access_token" => $user->createToken($this->hashID(8))->accessToken, 'expired_at' => now()->addHours(8), 'user' => $user];

            $user->dernierConnexion = Date('Y-m-d H:i:s');
            $user->save();

            $acteur = $user->prenoms . ' ' . $user->nom;

            $message = Str::ucfirst($acteur) . " s'est connecté(e).";

            //LogActivity::addToLog("Connexion", $message, get_class($user), $user->id);

            // Retourner le token
            return response()->json(['statut' => 'success', 'message' => "Authentification réussi", 'data' => new LoginResource($data), 'statutCode' => Response::HTTP_OK], Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function verificationEmailReinitialisationMotDePasse($email)
    {
        try {

            // Rechercher l'utilisateur grâce à l'identifiant.
            $utilisateur = User::where('email', $email)->first();

            // Si l'utilisateur n'existe pas envoyé une reponse avec comme status code 404
            if (!$utilisateur) return response()->json(['statut' => 'success', 'message' => "Utilisateur non trouvé", 'data' => [], 'statutCode' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);

            //Send verificiation email
            dispatch(new SendEmailJob($utilisateur, "reinitialisation-mot-de-passe"));

            // retourner une reponse avec les détails de l'utilisateur
            return response()->json(['statut' => 'success', 'message' => null, 'data' => new AuthResource($utilisateur), 'statutCode' => Response::HTTP_OK], Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Confirmation et activation de compte
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmationDeCompte($email)
    {
        try {

            // Rechercher l'utilisateur grâce à l'identifiant.
            $utilisateur = User::where('email', $email)->first();

            // Enrégistrement de la date et l'heure de vérification du compte
            $utilisateur->emailVerifiedAt = now();

            // Sauvegarder les informations
            $utilisateur->save();

            return response()->json(['statut' => 'success', 'message' => "Compte utilisateur activé", 'data' => [], 'statutCode' => Response::HTTP_OK], Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Réinitilisation de mot de passe
     *
     * @param  App\Http\Requests\auth\ResetPasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reinitialisationDeMotDePasse(ResetPasswordRequest $request)
    {
        try {

            $attributs = $request->all();

            // Rechercher l'utilisateur grâce à l'identifiant.
            $utilisateur = User::where('email', $attributs['email'])->first();

            // S'assurer que le nouveau mot de passe est différent du mot de passe actuel
            if ((Hash::check($attributs['new_password'], $utilisateur->password))) throw new Exception("Le nouveau mot de passe doit être différent de l'actuel mot de passe. Veuillez vérifier", 422);

            // Hash le nouveau mot de passe
            $password = Hash::make($attributs['new_password']); // Hash user registered password

            // Enrégistrer la donnée
            $utilisateur->password = $password;

            // Sauvegarder les informations
            $utilisateur->save();

            return response()->json(['statut' => 'success', 'message' => 'Mot de passe réinitialisé', 'data' => [], 'statutCode' => Response::HTTP_OK], Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Déconnecter l'utilisateur authentifié
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {


            // Si la suppression du token ne se passe pas correctement, une exception sera déclenchée
            if (!$request->user()->tokens()->delete()) throw new Exception("Erreur pendant la déconnexion", 500);

            /*$acteur = $request->user()->prenoms.' '.$request->user()->nom;

            $message = Str::ucfirst($acteur) . " s'est déconnecté.";

            LogActivity::addToLog("Déconnexion", $message, get_class($$request->user()), $request->user()->id);*/

            return response()->json(['statut' => 'success', 'message' => "Vous êtes déconnecté", 'data' => [], 'statutCode' => Response::HTTP_OK], Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Obtenir le nombre total d'utilisateurs connectés.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function nombreSessions()
    {
        try {
            $nombreSessions = DB::table('oauth_access_tokens')
                ->where('revoked', false)
                ->count();

            return response()->json(['statut' => 'success', 'message' => 'Nombre total de sessions', 'data' => $nombreSessions, 'statutCode' => Response::HTTP_OK], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
