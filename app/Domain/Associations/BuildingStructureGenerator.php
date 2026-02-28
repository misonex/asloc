<?php

namespace App\Domain\Associations;

use App\Models\Association;
use App\Models\Staircase;
use App\Models\Suite;
use App\Models\SuiteType;

class BuildingStructureGenerator
{
    protected int $globalUnitCounter = 1;

    public function generate(Association $association, array $blueprint): void
    {
        foreach ($blueprint['staircases'] as $staircaseData) {
            $this->generateStaircase($association, $staircaseData);
        }
    }

    protected function generateStaircase(Association $association, array $data): void
    {
        $staircase = Staircase::create([
            'association_id' => $association->id,
            'name' => $data['name'],
        ]);

        $floorsCount = $data['floors_count'];
        $startFrom = $data['floors_start_from'] ?? 0;

        for ($floor = $startFrom; $floor <= $floorsCount; $floor++) {

            $floorConfig = $this->resolveFloorConfiguration($data, $floor, $floorsCount);

            foreach ($floorConfig['units'] as $unit) {
                $this->createUnit(
                    $association,
                    $staircase,
                    $floor,
                    $unit
                );
            }
        }
    }

    protected function resolveFloorConfiguration(array $data, int $floor, int $topFloor): array
    {
        if ($floor === 0 && isset($data['ground_floor'])) {
            return $data['ground_floor'];
        }

        if ($floor === $topFloor && isset($data['last_floor']) && $data['last_floor']) {
            return $data['last_floor'];
        }

        return $data['typical_floor'];
    }

    protected function createUnit(
        Association $association,
        Staircase $staircase,
        int $floor,
        array $unitData
    ): void {

        $suiteTypeId = null;

        if ($unitData['unit_type'] === 'apartment') {
            $suiteTypeId = $this->resolveSuiteType(
                $association,
                $unitData['suite_type'],
                $unitData['area']
            );
        }

        Suite::create([
            'association_id' => $association->id,
            'staircase_id' => $staircase->id,
            'unit_type' => $unitData['unit_type'],
            'number' => $this->globalUnitCounter++,
            'floor' => $floor,
            'suite_type_id' => $suiteTypeId,
            'area' => $unitData['area'],
            'persons_count' => $unitData['unit_type'] === 'apartment' ? 0 : null,
            'has_central_heating' => false,
            'share_quota' => 0,
        ]);
    }

    protected function resolveSuiteType(
        Association $association,
        string $name,
        float $area
    ): int {

        $suiteType = SuiteType::firstOrCreate(
            [
                'association_id' => $association->id,
                'name' => $name,
            ],
            [
                'rooms' => $this->inferRoomsFromName($name),
                'area' => $area,
            ]
        );

        return $suiteType->id;
    }

    protected function inferRoomsFromName(string $name): int
    {
        if (str_contains($name, '1')) return 1;
        if (str_contains($name, '2')) return 2;
        if (str_contains($name, '3')) return 3;
        if (str_contains($name, '4')) return 4;

        return 0;
    }
}