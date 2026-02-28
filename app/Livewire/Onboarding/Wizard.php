<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;
use App\Models\AssociationRequestToken;
use App\Models\AssociationRequest;
use App\Domain\Associations\BuildingPresetFactory;
use App\Domain\Associations\AssociationRequestProcessor;
//use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class Wizard extends Component
{
    public string $token;
    public string $step = 'password';

    public AssociationRequest $request;


    public array $preview = [];
    public int $preview_total_units = 0;
    public float $preview_total_area = 0;


    // Step 1
    public string $password = '';
    public string $password_confirmation = '';

    // Step 2
    public string $preset = 'P+4';
    public int $apartments_per_floor = 4;
    public bool $include_commercial_ground = false;
    public int $commercial_units_count = 0;
    public float $commercial_area = 100;

    public function mount($token)
    {
        $tokenModel = AssociationRequestToken::where('token', $token)->firstOrFail();

        if (! $tokenModel->isValid()) {
            abort(403);
        }

        if ($tokenModel->request->status !== 'approved') {
            abort(403);
        }

        $this->token = $token;
        $this->request = $tokenModel->request;
    }

    public function setPassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $this->request->update([
            'metadata' => array_merge($this->request->metadata ?? [], [
                'password' => bcrypt($this->password),
            ]),
        ]);

        $this->step = 'building';
    }


    public function generateAssociation(
        BuildingPresetFactory $presetFactory,
        AssociationRequestProcessor $processor
    )
    {
        $blueprint = $presetFactory->make($this->preset, [
            'apartments_per_floor' => $this->apartments_per_floor,
            'include_commercial_ground' => $this->include_commercial_ground,
            'commercial_units_count' => $this->commercial_units_count,
            'commercial_area' => $this->commercial_area,
        ]);

        $this->request->update([
            'metadata' => array_merge($this->request->metadata ?? [], $blueprint),
        ]);

        $result = $processor->process($this->request);

        AssociationRequestToken::where('token', $this->token)
            ->first()
            ->markAsUsed();

        Auth::login($result['manager']);

        session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function previewStructure(BuildingPresetFactory $presetFactory)
    {
        $blueprint = $presetFactory->make($this->preset, [
            'apartments_per_floor' => $this->apartments_per_floor,
            'include_commercial_ground' => $this->include_commercial_ground,
            'commercial_units_count' => $this->commercial_units_count,
            'commercial_area' => $this->commercial_area,
        ]);

        $this->preview = $blueprint;

        $totalUnits = 0;
        $totalArea = 0;

        foreach ($blueprint['staircases'] as $staircase) {
            $floors = $staircase['floors_count'];
            $start = $staircase['floors_start_from'];

            for ($floor = $start; $floor <= $floors; $floor++) {
                $config = $this->resolveFloorPreview($staircase, $floor, $floors);

                foreach ($config['units'] as $unit) {
                    $totalUnits++;
                    $totalArea += $unit['area'];
                }
            }
        }

        $this->preview_total_units = $totalUnits;
        $this->preview_total_area = $totalArea;

        $this->step = 'preview';
    }

    protected function resolveFloorPreview(array $data, int $floor, int $topFloor): array
    {
        if ($floor === 0 && isset($data['ground_floor'])) {
            return $data['ground_floor'];
        }

        if ($floor === $topFloor && isset($data['last_floor']) && $data['last_floor']) {
            return $data['last_floor'];
        }

        return $data['typical_floor'];
    }

    public function render()
    {
        return view('livewire.onboarding.wizard');
    }
}