<?php

namespace App\Domain\Associations;

use InvalidArgumentException;

class BuildingPresetFactory
{
    public function make(string $preset, array $options = []): array
    {
        return match ($preset) {
            'P+4'  => $this->makePreset(4, $options),
            'P+8'  => $this->makePreset(8, $options),
            'P+10' => $this->makePreset(10, $options),
            default => throw new InvalidArgumentException("Unknown preset [$preset]"),
        };
    }

    protected function makePreset(int $floorsAboveGround, array $options): array
    {
        $apartmentsPerFloor = $options['apartments_per_floor'] ?? 4;

        $defaultApartmentArea = $options['apartment_area'] ?? 65.00;

        $includeCommercial = $options['include_commercial_ground'] ?? false;
        $commercialUnitsCount = $options['commercial_units_count'] ?? 0;
        $commercialArea = $options['commercial_area'] ?? 100.00;

        return [
            'staircases' => [
                [
                    'name' => $options['staircase_name'] ?? 'A',
                    'floors_count' => $floorsAboveGround,
                    'floors_start_from' => 0,

                    'ground_floor' => [
                        'units' => $this->buildGroundFloor(
                            $apartmentsPerFloor,
                            $defaultApartmentArea,
                            $includeCommercial,
                            $commercialUnitsCount,
                            $commercialArea
                        ),
                    ],

                    'typical_floor' => [
                        'units' => $this->buildApartmentUnits(
                            $apartmentsPerFloor,
                            $defaultApartmentArea
                        ),
                    ],

                    'last_floor' => null,
                ],
            ],
        ];
    }

    protected function buildGroundFloor(
        int $apartmentsPerFloor,
        float $apartmentArea,
        bool $includeCommercial,
        int $commercialUnitsCount,
        float $commercialArea
    ): array {
        $units = [];

        if ($includeCommercial && $commercialUnitsCount > 0) {
            for ($i = 0; $i < $commercialUnitsCount; $i++) {
                $units[] = [
                    'unit_type' => 'commercial',
                    'area' => $commercialArea,
                ];
            }
        }

        $apartmentUnits = $this->buildApartmentUnits(
            $apartmentsPerFloor,
            $apartmentArea
        );

        return array_merge($units, $apartmentUnits);
    }

    protected function buildApartmentUnits(int $count, float $area): array
    {
        $units = [];

        for ($i = 0; $i < $count; $i++) {
            $units[] = [
                'unit_type' => 'apartment',
                'suite_type' => $this->inferSuiteTypeFromArea($area),
                'area' => $area,
            ];
        }

        return $units;
    }

    protected function inferSuiteTypeFromArea(float $area): string
    {
        return match (true) {
            $area <= 45 => '1 cameră',
            $area <= 60 => '2 camere',
            $area <= 75 => '3 camere',
            default      => '4 camere',
        };
    }
}