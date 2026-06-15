<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ParentService
{
    /**
     * Create a new class instance.
     */
    public function fetchParents(int $perpage = 30, ?string $search = null, array $filters = [])
    {
        $query = DB::connection('abia_sms')->table('parents')
            ->leftJoin('schools', 'parents.school_id', '=', 'schools.school_id')
            ->leftJoin('lgas as flgas', 'parents.flga', '=', 'flgas.id')
            ->leftJoin('lgas as mlgas', 'parents.mlga', '=', 'mlgas.id')
            ->leftJoin('status_detail', 'parents.status', '=', 'status_detail.id')
            ->leftJoin('states as fstates', 'parents.fsoo', '=', 'fstates.id')
            ->leftJoin('states as mstates', 'parents.msoo', '=', 'mstates.id')
            ->select(
                'parents.id',
                'parents.parent_id',
                'parents.school_id',
                'parents.fname',
                'parents.sname',
                'parents.oname',
                'parents.occupation',
                'parents.femail',
                'parents.fphone',
                'parents.fdob',
                'parents.address',
                'parents.fvillage',
                'parents.msname',
                'parents.mfname',
                'parents.moname',
                'parents.moccupation',
                'parents.memail',
                'parents.mphone',
                'parents.mdob',
                'parents.maddress',
                'parents.mvillage',
                'parents.phone',
                'parents.email',
                'parents.email2',
                'parents.sex',
                'parents.industry',
                'parents.religion',
                'schools.name as school',
                'flgas.name as flga',
                'mlgas.name as mlga',
                'status_detail.name as status',
                'fstates.name as father_state',
                'mstates.name as mother_state'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('parents.fname', 'like', "%$search%")
                    ->orWhere('parents.sname', 'like', "%$search%")
                    ->orWhere('parents.parent_id', 'like', "%$search%")
                    ->orWhere('parents.phone', 'like', "%$search%");
            });
        }

        if (! empty($filters['school_id'])) {
            $query->where('parents.school_id', $filters['school_id']);
        }

        return $query->paginate($perpage)->withQueryString();
    }

    public function fetchSchools()
    {
        return DB::connection('abia_sms')->table('schools')->select('school_id as id', 'name')->orderBy('name')->get();
    }
}
