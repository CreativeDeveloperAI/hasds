<?php

namespace Database\Seeders;

use App\Models\CustomFieldDefinition;
use App\Models\Organization;
use Database\Factories\CustomFieldDefinitionFactory;
use Illuminate\Database\Seeder;

class CustomFieldDefinitionSeeder extends Seeder
{
    /**
     * تهيئة 2-3 حقول ديناميكية مخصصة لكل مؤسسة، مع ضمان عدم تكرار field_key
     * داخل نفس المؤسسة (قيد فريد في قاعدة البيانات: organization_id + field_key).
     */
    public function run(): void
    {
        Organization::all()->each(function (Organization $organization) {
            $pairs = collect(CustomFieldDefinitionFactory::$fieldPairs)
                ->shuffle()
                ->take(fake()->numberBetween(2, 3));

            foreach ($pairs as $pair) {
                CustomFieldDefinition::factory()->create([
                    'organization_id' => $organization->id,
                    'field_label' => $pair['label'],
                    'field_key' => $pair['key'],
                    'field_type' => $pair['type'],
                    'options' => $pair['options'],
                    'is_required' => fake()->boolean(30),
                ]);
            }
        });
    }
}
