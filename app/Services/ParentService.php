<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ParentService
{
    /**
     * Create a new class instance.
     */
    public function fetchParents(int $perpage = 30, string|null $search = null)
    {
        return DB::connection('abia_sms')->table('parents')
            ->leftJoin('schools', 'parents.school_id', '=', 'schools.school_id')
            ->leftJoin('lgas as mlgas', 'parents.mlga', '=', 'mlgas.id')
            ->leftJoin('status_detail', 'parents.status', '=', 'status_detail.id')
            ->leftJoin('states as fstates', 'parents.fsoo', '=', 'fstates.id')
            ->select('parents.id', 'parents.parent_id', 'parents.fname', 'parents.sname',  'parents.phone', 'parents.email', 'parents.oname', 'parents.address', 'schools.name as school', 'mlgas.name as mlga', 'status_detail.name as status', 'fstates.name as state')
            ->whereAny([
                'parents.fname',
                'parents.sname',
                'parents.parent_id',
                'parents.phone',
            ],'like', "%$search%")
            ->paginate($perpage)->withQueryString();
    }
}
