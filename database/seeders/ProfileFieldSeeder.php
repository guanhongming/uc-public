<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProfileField;

class ProfileFieldSeeder extends Seeder
{
    public function run(): void
    {


        $fields = [
            ['label' => 'IG Subject 1', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'IG Subject 2', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'IG Subject 3', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'IG Subject 4', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'IG Subject 5', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'A Level Subject 1', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'A Level Subject 2', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'A Level Subject 3', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'Country Applying To', 'field_type' => 'text', 'is_required' => true],
            ['label' => 'Major Applying For', 'field_type' => 'text', 'is_required' => true],
            ['label' => 'Target Universities', 'field_type' => 'textarea', 'is_required' => true],
            ['label' => 'Language Test Details (e.g., IELTS/TOEFL Score, Date)', 'field_type' => 'textarea', 'is_required' => false],
            ['label' => 'SAT/ACT Details (Score, Sections)', 'field_type' => 'textarea', 'is_required' => false],
            ['label' => 'Official CIE Score 1', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'Official CIE Score 2', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'Official CIE Score 3', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'Official CIE Score 4', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'Official CIE Score 5', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'Official CIE Score 6', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'Official CIE Score 7', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'Official CIE Score 8', 'field_type' => 'text', 'is_required' => false],
            ['label' => 'University Offer Conditions', 'field_type' => 'textarea', 'is_required' => false],
            ['label' => 'Academic Achievements / Activities', 'field_type' => 'textarea', 'is_required' => false],
            ['label' => 'Non-Academic Activities / Interests', 'field_type' => 'textarea', 'is_required' => false],
            ['label' => 'Holiday / Break Plans', 'field_type' => 'textarea', 'is_required' => false],
            ['label' => 'College Went', 'field_type' => 'textarea', 'is_required' => false]
        ];

        foreach ($fields as $fieldData) {
            ProfileField::updateOrCreate($fieldData);
        }
    }
}