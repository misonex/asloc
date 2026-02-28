<?php

namespace App\Domain\Associations;

use App\Models\Association;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssociationSetupService
{
    public function __construct(
        protected BuildingStructureGenerator $structureGenerator,
        protected ShareQuotaCalculator $quotaCalculator
    ) {}

    public function create(array $data): Association
    {
        return DB::transaction(function () use ($data) {

            $association = Association::create([
                'name' => $data['association']['name'],
                'address' => $data['association']['address'],
                'fiscal_code' => $data['association']['fiscal_code'],
            ]);

            $manager = User::create([
                'association_id' => $association->id,
                'name' => $data['manager']['name'],
                'email' => $data['manager']['email'],
                'password' => $data['manager']['password'] ?? bcrypt(Str::random(16)),
            ]);

            $manager->assignRole('manager');

            // trial subscription - simplu MVP
            $association->update([
                'trial_ends_at' => now()->addDays(30),
                'subscription_status' => 'trial',
            ]);

            if (isset($data['staircases'])) {
                $this->structureGenerator->generate($association, $data);
                $this->quotaCalculator->recalculate($association);
            }

            // aici ulterior: dispatch(new AssociationCreated(...))

            return $association;
        });
    }
}