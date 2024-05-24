<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('roleId')->references('id')->on('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->foreign('auteurId')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('livreurId')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('commande_produits', function (Blueprint $table) {
            $table->foreign('commandeId')->references('id')->on('commandes')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('produitId')->references('id')->on('produits')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('commentaires', function (Blueprint $table) {
            
            $table->foreign('produitId')->references('id')->on('produits')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('userId')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });

        Schema::table('activity_log', function (Blueprint $table) {
            
            $table->foreign('userId')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });

        Schema::table('notations', function (Blueprint $table) {
            
            $table->foreign('produitId')->references('id')->on('produits')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('userId')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });

        Schema::table('paniers', function (Blueprint $table) {
            
            $table->foreign('userId')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });

        Schema::table('panier_produits', function (Blueprint $table) {
            $table->foreign('panierId')->references('id')->on('paniers')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('produitId')->references('id')->on('produits')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('permission_roles', function (Blueprint $table) {
            $table->foreign('permissionId')->references('id')->on('permissions')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreign('roleId')->references('id')->on('roles')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('produits', function (Blueprint $table) {
            
            $table->foreign('categorieId')->references('id')->on('categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });

        Schema::table('transactions', function (Blueprint $table) {
            
            $table->foreign('commandeId')->references('id')->on('commandes')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
