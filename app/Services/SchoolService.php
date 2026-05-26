<?php

namespace App\Services;

use DB;

class SchoolService
{
    public function fetchSchools(int $perpage = 30, ?string $search = null, array $filters = [])
    {
        $query = DB::connection('abia_sms')->table('schools')
            ->leftJoin('lgas', 'schools.lga', '=', 'lgas.id')
            ->select('schools.id', 'schools.school_id', 'schools.name', 'schools.phone', 'schools.email', 'schools.address', 'schools.status', 'lgas.name as lga_name', 'schools.lga');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('schools.name', 'like', "%$search%")
                    ->orWhere('schools.school_id', 'like', "%$search%")
                    ->orWhere('schools.email', 'like', "%$search%")
                    ->orWhere('schools.phone', 'like', "%$search%");
            });
        }

        if (! empty($filters['lga'])) {
            $query->where('schools.lga', $filters['lga']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('schools.status', $filters['status']);
        }

        return $query->paginate($perpage)->withQueryString();
    }

    public function fetchLgas()
    {
        return DB::connection('abia_sms')->table('lgas')
            ->where('state_id', 2647) // Filter for Abia State
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
