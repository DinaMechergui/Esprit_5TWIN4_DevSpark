<?php

namespace Database\Factories;

use App\Models\AlerteMeteo;
use App\Models\NiveauAlerte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AlerteMeteo>
 */
class AlerteMeteoFactory extends Factory
{
    protected $model = AlerteMeteo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phenomenes = [
            'Vigilance Canicule et Vent de Sirocco (Chhili)',
            'Pic de Température Extrême dépassant 45°C',
            'Alerte Vague de Chaleur Saharienne',
            'Risque Fort d\'Incendies de Forêts (Chaleur et Sécheresse)',
            'Avis d\'Orages d\'Été Isolés et Vents de Poussière',
            'Vents Violents et Rafales de Sable Saharien',
            'Avis de Fortes Chaleurs et Consignes Sanitaires',
            'Alerte Vigilance Météo Canicule',
        ];

        $villesTunisie = [
            'Tunis',
            'Ariana',
            'Ben Arous',
            'Manouba',
            'Bizerte',
            'Nabeul',
            'Sousse',
            'Monastir',
            'Mahdia',
            'Sfax',
            'Kairouan',
            'Kasserine',
            'Sidi Bouzid',
            'Gafsa',
            'Tozeur',
            'Kébili',
            'Médenine & Djerba',
            'Tataouine',
            'Gabès',
            'Jendouba & Aïn Draham',
            'Béja',
            'Le Kef',
            'Siliana',
            'Zaghouan',
        ];

        $sources = [
            'Institut National de la Météorologie (INM)',
            'Protection Civile Tunisienne (ONPC)',
            'Ministère de l\'Agriculture et des Ressources Hydrauliques',
            'Direction Générale des Forêts (DGF Tunisie)',
            'Observatoire National du Climat (Tunisie)',
        ];

        $messages = [
            "Selon l'Institut National de la Météorologie (INM), des températures caniculaires exceptionnelles sont attendues avec apparition du vent de sirocco (Chhili). Il est fortement recommandé d'éviter l'exposition directe au soleil entre 11h et 17h, de bien s'hydrater et de maintenir les habitations fermées pendant les pics de chaleur. Numéro d'urgence Protection Civile : 198.",
            "Bulletin d'alerte émis par l'INM : hausse sensible des températures avec risque de départs d'incendies dans les massifs forestiers et zones agricoles. Les autorités locales appellent à une vigilance accrue et au respect scrupuleux des consignes de sécurité.",
            "Avis météorologique spécial Tunisie : un flux d'air saharien très chaud intéresse les régions intérieures et le Sud tunisien. Les températures maximales dépasseront les moyennes saisonnières de 6 à 9 degrés. Protégez les nourrissons et personnes vulnérables.",
            "Alerte météo vigilance renforcée : conditions propices à des coups de chaleur sévères. Les services de la santé et la Protection Civile restent mobilisés 24h/24. En cas de malaise, composez immédiatement le 198 (Protection Civile) ou le 190 (SAMU Tunisie).",
            "Conditions de vigilance météo : ciel peu nuageux avec brise marine sur les côtes et températures modérées à élevées dans les terres. Pensez à aérer la nuit et à vous abriter dans les points de fraîcheur communaux climatisés.",
        ];

        // Par défaut, alertes actives (débutées récemment, durant encore 1 à 4 jours ou sans fin définie)
        $dateDebut = now()->subHours(fake()->numberBetween(1, 36));
        $dateFin = fake()->boolean(75) ? now()->addHours(fake()->numberBetween(12, 72)) : null;

        $titre = fake()->randomElement($phenomenes) . ' - ' . fake()->randomElement($villesTunisie);

        return [
            'niveau_alerte_id' => NiveauAlerte::factory(),
            'titre' => $titre,
            'message' => fake()->randomElement($messages),
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'source' => fake()->randomElement($sources),
        ];
    }

    /**
     * État pour une alerte actuellement active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_debut' => now()->subHours(12),
            'date_fin' => now()->addHours(24),
        ]);
    }

    /**
     * État pour une alerte expirée / passée.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_debut' => now()->subDays(5),
            'date_fin' => now()->subDays(1),
        ]);
    }
}
