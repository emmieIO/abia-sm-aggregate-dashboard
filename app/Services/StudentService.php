<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StudentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function fetchStudents(int $perpage = 30, string|null $search = null)
    {
        return DB::connection('abia_sms')->table('students')
            ->leftJoin('schools', 'students.school_id', '=', 'schools.school_id')
            ->leftJoin('classes', 'students.current_class_id', '=', 'classes.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.parent_id')
            ->leftJoin('states', 'students.soo', '=', 'states.id')
            ->select('students.id', 'students.student_id', 'students.address', 'students.village', 'states.name as state', 'students.fname', 'students.sname', 'students.oname', 'students.sex', 'students.dob', 'students.status', 'schools.name as school', 'classes.name as class', 'parents.fname as parent_fname', 'parents.sname as parent_sname', 'parents.phone as parent_phone')
            ->whereAny([
                'students.fname',
                'students.sname',
                'students.oname',
                'students.student_id',
            ],'like', "%$search%")
            ->paginate($perpage)->withQueryString();
    }
}
