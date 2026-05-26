<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TeacherService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function fetchStaffs(int $perpage = 30, ?string $search = null, array $filters = [])
    {
        $query = DB::connection('abia_sms')->table('staffs')
            ->leftJoin('schools', 'staffs.school_id', '=', 'schools.school_id')
            ->leftJoin('states', 'staffs.soo', '=', 'states.id')
            ->leftJoin('classes', 'staffs.designate', '=', 'classes.id')
            ->leftJoin('departments', 'staffs.depart_id', '=', 'departments.id')
            ->leftJoin('status_detail', 'staffs.status', '=', 'status_detail.id')
            ->leftJoin('lgas as l', 'schools.lga', '=', 'l.id')
            ->leftJoin('designations as des', 'staffs.role', '=', 'des.id')
            ->select('staffs.id', 'staffs.staff_id', 'staffs.address', 'staffs.village', 'states.name as state', 'staffs.fname', 'staffs.sname', 'staffs.email', 'staffs.oname', 'staffs.sex', 'staffs.dob', 'status_detail.name as status', 'schools.name as school', 'departments.name as department', 'classes.name as designate', 'l.name as lga', 'des.name as designation_name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('staffs.fname', 'like', "%$search%")
                    ->orWhere('staffs.sname', 'like', "%$search%")
                    ->orWhere('staffs.oname', 'like', "%$search%")
                    ->orWhere('staffs.staff_id', 'like', "%$search%");
            });
        }

        if (! empty($filters['school_id'])) {
            $query->where('staffs.school_id', $filters['school_id']);
        }

        if (! empty($filters['depart_id'])) {
            $query->where('staffs.depart_id', $filters['depart_id']);
        }

        if (! empty($filters['designation'])) {
            $query->where('staffs.role', $filters['designation']);
        }

        if (! empty($filters['sex'])) {
            $query->where('staffs.sex', $filters['sex']);
        }

        return $query->paginate($perpage)->withQueryString();
    }

    public function fetchSchools()
    {
        return DB::connection('abia_sms')->table('schools')->select('school_id as id', 'name')->orderBy('name')->get();
    }

    public function fetchDepartments()
    {
        return DB::connection('abia_sms')->table('departments')->select('id', 'name')->orderBy('name')->get();
    }

    public function fetchDesignations()
    {
        return DB::connection('abia_sms')->table('designations')->select('id', 'name')->orderBy('name')->get();
    }
}
