<?php

namespace App\Traits\Helpers;

use App\Http\Resources\GarantieResource;
use App\Jobs\SendEmailJob;
use App\Models\ExchangeRate;
use App\Models\Garantie;
use App\Models\GarantieOptionnelle;
use App\Models\MoyenPaiement;
use App\Models\Pack;
use App\Models\Pays;
use App\Models\Role;
use App\Models\Transaction;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;


trait DocHelperTrait
{


    public function packComparaisonVoyage($projet, $choice)
    {

        try {
            $packs = $projet->produit->packs;
            $devis = [];

            $garantieOptionnelles = $projet->produit->garantieOptionnelles;

            $options = [];
            foreach ($garantieOptionnelles as $garantie) {
                array_push($options, [
                    'garantie' => new GarantieResource($garantie),
                    'valeur' => $projet->produit->options->where('type', $garantie['slug'])->first()->valeur
                ]);
            }

            foreach ($packs as $key => $pack) {
                if ($key == 3)
                    break;

                if ($pack->id == $choice['packId'])
                    continue;

                $attributs = [
                    "zoneDeCouverture" => 1,
                    "pack" => $pack,
                    "garanties" => $pack->garanties,
                    "garantieOptionnelleId" => $garantieOptionnelles[$key]['id']
                ];
                $garanties = [];

                foreach ($pack->garanties as $garantie) {
                    array_push($garanties, [
                        "garantie" => new GarantieResource($garantie),
                        "type" => 0
                    ]);
                }

                foreach (GarantieResource::collection(Garantie::whereIn('typeProduit', [2, -1])->whereNotIn('id', $pack->garanties->pluck('id'))->get()) as $garantie) {
                    array_push($garanties, [
                        "garantie" => new GarantieResource($garantie),
                        "type" => 2
                    ]);
                }

                array_push($devis, [
                    "zoneDeCouverture" => 1,
                    'cotisation' => $this->conversion($this->voyageDevis($projet, $attributs)),
                    'cotisationNette' => $projet->B,
                    'accessoire' => $projet->E,
                    'taxe' => $projet->G,
                    'assistance' => $projet->P,
                    'fraisMedicaux' => $options[$key]['valeur'],
                    'niveauDeCouverture' => 100,
                ]);
            }

            $pack = Pack::where('id', $choice['packId'])->first();

            $garantieOptionnelle = GarantieOptionnelle::find($choice['garantieOptionnelleId']);

            $option = $projet->produit->options->where('type', $garantieOptionnelle->slug)->first();


            $garantieAll = [];

            foreach ($pack->garanties as $garantie) {
                array_push($garantieAll, $garantie);
            }
            if (isset($choice['garanties']))
            {
                foreach (Garantie::whereIn('typeProduit', [2, -1])->whereNotIn('id', $pack->garanties->pluck('id'))->whereIn('id', $choice['garanties'])->get() as $garantie) {
                    array_push($garantieAll, $garantie);
                }
            }

            $choice = array_merge($choice, [
                "pack" => $pack,
                "garanties" => $garantieAll,
            ]);

            array_push($devis, [
                "zoneDeCouverture" => 1,
                'cotisation' => $this->conversion($this->voyageDevis($projet, $choice)),
                'cotisationNette' => $projet->B,
                'accessoire' => $projet->E,
                'taxe' => $projet->G,
                'assistance' => $projet->P,
                'fraisMedicaux' => $option->valeur,
                'date' => date('Y-m-d'),
                'niveauDeCouverture' => 100,
            ]);

            return $devis;
        } catch (\Throwable $th) {

        }
    }


}
