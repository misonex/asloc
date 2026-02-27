<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SuiteType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToAssociation;

class Suite extends Model
{
    use HasFactory, BelongsToAssociation;

    protected $fillable = [
        'association_id',
        'staircase_id',
        'suite_type_id',
        'number',
        'floor',
        'share_quota',
        'persons_count',
        'has_central_heating',
        'is_active',
    ];

    protected $casts = [
        'share_quota' => 'decimal:6',
        'has_central_heating' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    public function staircase()
    {
        return $this->belongsTo(Staircase::class);
    }

    public function suiteType()
    {
        return $this->belongsTo(SuiteType::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

    /*
        public function getAreaAttribute()
        {
            return $this->suiteType ? $this->suiteType->area : null;
        }

        public function getRoomsAttribute()
        {
            return $this->suiteType ? $this->suiteType->rooms : null;
        }

        public function getTypeNameAttribute()
        {
            return $this->suiteType ? $this->suiteType->name : null;
        }

        public function getCentralHeatingStatusAttribute()
        {
            return $this->has_central_heating ? 'Da' : 'Nu';
        }

        public function getActiveStatusAttribute()
        {
            return $this->is_active ? 'Activă' : 'Inactivă';
        }

        public function getPersonsCountAttribute($value)
        {
            return $value ?? 0;
        }

        public function setPersonsCountAttribute($value)
        {
            $this->attributes['persons_count'] = $value ?? 0;
        }

        public function scopeActive($query)
        {
            return $query->where('is_active', true);
        }

        public function scopeWithCentralHeating($query)
        {
            return $query->where('has_central_heating', true);
        }

        public function scopeWithoutCentralHeating($query)
        {
            return $query->where('has_central_heating', false);
        }

        public function scopeOnFloor($query, $floor)
        {
            return $query->where('floor', $floor);
        }

        public function scopeOfType($query, $typeId)
        {
            return $query->where('suite_type_id', $typeId);
        }

        public function scopeWithPersonsCount($query, $count)
        {
            return $query->where('persons_count', $count);
        }

        public function scopeWithoutPersons($query)
        {
            return $query->where('persons_count', 0);
        }

        public function scopeWithShareQuotaAbove($query, $quota)
        {
            return $query->where('share_quota', '>', $quota);
        }

        public function scopeWithShareQuotaBelow($query, $quota)
        {
            return $query->where('share_quota', '<', $quota);
        }

        public function scopeWithShareQuotaBetween($query, $minQuota, $maxQuota)
        {
            return $query->whereBetween('share_quota', [$minQuota, $maxQuota]);
        }

        public function scopeWithAreaAbove($query, $area)
        {
            return $query->whereHas('suiteType', function ($q) use ($area) {
                $q->where('area', '>', $area);
            });
        }

        public function scopeWithAreaBelow($query, $area)
        {
            return $query->whereHas('suiteType', function ($q) use ($area) {
                $q->where('area', '<', $area);
            });
        }

        public function scopeWithAreaBetween($query, $minArea, $maxArea)
        {
            return $query->whereHas('suiteType', function ($q) use ($minArea, $maxArea) {
                $q->whereBetween('area', [$minArea, $maxArea]);
            });
        }

        public function scopeWithRooms($query, $rooms)
        {
            return $query->whereHas('suiteType', function ($q) use ($rooms) {
                $q->where('rooms', $rooms);
            });
        }

        public function scopeWithTypeName($query, $typeName)
        {
            return $query->whereHas('suiteType', function ($q) use ($typeName) {
                $q->where('name', $typeName);
            });
        }

        public function scopeWithTypeNameLike($query, $typeName)
        {
            return $query->whereHas('suiteType', function ($q) use ($typeName) {
                $q->where('name', 'like', '%' . $typeName . '%');
            });
        }

        public function scopeWithRoomsAbove($query, $rooms)
        {
            return $query->whereHas('suiteType', function ($q) use ($rooms) {
                $q->where('rooms', '>', $rooms);
            });
        }

        public function scopeWithRoomsBelow($query, $rooms)
        {
            return $query->whereHas('suiteType', function ($q) use ($rooms) {
                $q->where('rooms', '<', $rooms);
            });
        }

        public function scopeWithRoomsBetween($query, $minRooms, $maxRooms)
        {
            return $query->whereHas('suiteType', function ($q) use ($minRooms, $maxRooms) {
                $q->whereBetween('rooms', [$minRooms, $maxRooms]);
            });
        }

        public function scopeWithTypeNameIn($query, $typeNames)
        {
            return $query->whereHas('suiteType', function ($q) use ($typeNames) {
                $q->whereIn('name', $typeNames);
            });
        }

        public function scopeWithRoomsIn($query, $roomsArray)
        {
            return $query->whereHas('suiteType', function ($q) use ($roomsArray) {
                $q->whereIn('rooms', $roomsArray);
            });
        }

        public function scopeWithAreaIn($query, $areas)
        {
            return $query->whereHas('suiteType', function ($q) use ($areas) {
                $q->whereIn('area', $areas);
            });
        }

        public function scopeWithShareQuotaIn($query, $quotas)
        {
            return $query->whereIn('share_quota', $quotas);
        }

        public function scopeWithPersonsCountIn($query, $counts)
        {
            return $query->whereIn('persons_count', $counts);
        }

        public function scopeWithCentralHeatingStatus($query, $hasCentralHeating)
        {
            return $query->where('has_central_heating', $hasCentralHeating);
        }

        public function scopeWithActiveStatus($query, $isActive)
        {
            return $query->where('is_active', $isActive);
        }

        public function scopeWithNumber($query, $number)
        {
            return $query->where('number', $number);
        }

        public function scopeWithFloor($query, $floor)
        {
            return $query->where('floor', $floor);
        }

        public function scopeWithNumberLike($query, $number)
        {
            return $query->where('number', 'like', '%' . $number . '%');
        }

        public function scopeWithFloorAbove($query, $floor)
        {
            return $query->where('floor', '>', $floor);
        }

        public function scopeWithFloorBelow($query, $floor)
        {
            return $query->where('floor', '<', $floor);
        }

        public function scopeWithFloorBetween($query, $minFloor, $maxFloor)
        {
            return $query->whereBetween('floor', [$minFloor, $maxFloor]);
        }

        public function scopeWithNumberIn($query, $numbers)
        {
            return $query->whereIn('number', $numbers);
        }

        public function scopeWithFloorIn($query, $floors)
        {
            return $query->whereIn('floor', $floors);
        }

        // ...
    */
