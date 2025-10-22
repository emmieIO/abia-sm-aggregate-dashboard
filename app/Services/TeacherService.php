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

    public function fetchStaffs(int $perpage = 30, string|null $search = null)
    {
        return  DB::connection('abia_sms')->table('staffs')
            ->leftJoin('schools', 'staffs.school_id', '=', 'schools.school_id')
            ->leftJoin('states', 'staffs.soo', '=', 'states.id')
            ->leftJoin('classes', 'staffs.designate', '=', 'classes.id')
            ->leftJoin('departments', 'staffs.depart_id', '=', 'departments.id')
            ->leftJoin('status_detail', 'staffs.status', '=', 'status_detail.id')
            ->leftJoin('lgas as l', 'schools.lga', '=', 'l.id')
            ->select('staffs.id', 'staffs.staff_id', 'staffs.address', 'staffs.village', 'states.name as state', 'staffs.fname', 'staffs.sname', 'staffs.email', 'staffs.oname', 'staffs.sex', 'staffs.dob', 'status_detail.name as status', 'schools.name as school','departments.name as department', 'classes.name as designate', 'l.name as lga')
            ->whereAny([
                'staffs.fname',
                'staffs.sname',
                'staffs.oname',
                'staffs.staff_id',
            ],'like', "%$search%")
            ->paginate($perpage)->withQueryString();
    }
}
