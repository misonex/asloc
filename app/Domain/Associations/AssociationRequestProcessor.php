<?php

namespace App\Domain\Associations;

use App\Models\AssociationRequest;
//use Illuminate\Support\Str;

class AssociationRequestProcessor
{
    public function __construct(
        protected AssociationSetupService $setupService
    ) {}

    public function process(AssociationRequest $request): array
    {
        if ($request->status !== 'approved') {
            throw new \Exception('Request not approved.');
        }

        $association = $this->setupService->create([
            'association' => [
                'name' => $request->association_name,
                'address' => $request->association_address,
                'fiscal_code' => $request->fiscal_code,
            ],
            'manager' => [
                'name' => $request->manager_name,
                'email' => $request->manager_email,
                'password' => $request->metadata['password'],
            ],
            ...($request->metadata ?? []),
        ]);

        $manager = $association->users()
            ->where('email', $request->manager_email)
            ->first();

        $request->markAsProcessed();

        return [
            'association' => $association,
            'manager' => $manager,
        ];
    }
}
