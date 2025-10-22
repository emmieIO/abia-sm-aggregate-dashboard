<?php

namespace App\Services;

use DB;

class SchoolService
{
    public function fetchSchools(int $perpage = 30, string|null $search = null)
    {
        return DB::connection('abia_sms')->table('schools')
            ->leftJoin('lgas', 'schools.lga', '=', 'lgas.id')
            ->select('schools.id', 'schools.school_id', 'schools.name', 'schools.phone', 'schools.email', 'schools.address', 'schools.status', 'lgas.name as lga')
            ->whereAny([
                'schools.name',
                'schools.school_id',
                'schools.email',
                'schools.phone',
            ],'like', "%$search%")
            ->paginate($perpage)->withQueryString();
    }
}
