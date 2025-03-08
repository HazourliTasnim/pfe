<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appels_d_offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->string('langue')->default('Français');
            $table->string('etat_offre')->default('En Cours');
            $table->string('wilaya')->nullable();
            $table->string('numero_offre')->unique();
            $table->string('titre_offre');
            $table->string('type_offre');
            $table->enum('niveau', ['National', 'International'])->default('National');
            $table->date('date_publication');
            $table->date('date_limite')->nullable();
            $table->date('date_prorogation')->nullable();
            $table->text('description')->nullable();
            $table->string('document_pdf')->nullable();
            $table->enum('etat', ['En rédaction', 'Publié', 'Annulé'])->default('En rédaction');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appels_d_offres');
    }
};
