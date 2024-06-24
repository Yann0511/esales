<?php

namespace App\Jobs;

use App\Mail\ConfirmationDeCompteEmail;
use App\Mail\ReinitialisationMotDePasseEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $type;
    private $mailer;
    private $password;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $type, $password = null)
    {
        $this->user = $user;
        $this->type = $type;
        $this->password = $password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $details = [];
        if($this->type == "confirmation-compte"){
            $details['view'] = "emails.auth.confirmation_de_compte";
            $details['subject'] = "Bienvenue sur notre plateforme ";
            $details['content'] = [
                "greeting" => "Bienvenu Mr/Mme ". $this->user->nom,
                "introduction" => "Voici vos identifiant de connexion",
                "identifiant" => $this->user->email,
                "password" => $this->password,
                "lien" => config("app.url") . "/password_update?token=" . $this->user->token,
            ];
            $mailer = new ConfirmationDeCompteEmail($details);
        }
        elseif($this->type == "reinitialisation-mot-de-passe")
        {
            $details['view'] = "emails.auth.reinitialisation_mot_passe";
            $details['subject'] = "Réinitialisation de passe";
            $details['content'] = [
                "greeting" => "Bienvenu Mr/Mme ". $this->user->nom,
                "introduction" => "Voici votre lien de réinitialisation",
                "lien" => config("app.url") . "/password_update?token=" . $this->user->token,
            ];
            $mailer = new ReinitialisationMotDePasseEmail($details);
        }

        $when = now()->addSeconds(15);

        Mail::to($this->user)->later($when, $mailer);
    }
}
