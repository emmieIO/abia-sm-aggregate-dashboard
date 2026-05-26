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

    public function fetchStudents(int $perpage = 30, ?string $search = null, array $filters = [])
    {
        $query = DB::connection('abia_sms')->table('students')
            ->leftJoin('schools', 'students.school_id', '=', 'schools.school_id')
            ->leftJoin('classes', 'students.current_class_id', '=', 'classes.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.parent_id')
            ->leftJoin('states', 'students.soo', '=', 'states.id')
            ->select('students.id', 'students.student_id', 'students.address', 'students.village', 'states.name as state', 'students.fname', 'students.sname', 'students.oname', 'students.sex', 'students.dob', 'students.status', 'schools.name as school', 'classes.name as class', 'parents.fname as parent_fname', 'parents.sname as parent_sname', 'parents.phone as parent_phone', 'students.school_id', 'students.current_class_id')
            ->where('students.current_stage_id', '!=', 5); // Exclude Alumni

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('students.fname', 'like', "%$search%")
                    ->orWhere('students.sname', 'like', "%$search%")
                    ->orWhere('students.oname', 'like', "%$search%")
                    ->orWhere('students.student_id', 'like', "%$search%");
            });
        }

        if (! empty($filters['school_id'])) {
            $query->where('students.school_id', $filters['school_id']);
        }

        if (! empty($filters['class_id'])) {
            $query->where('students.current_class_id', $filters['class_id']);
        }

        if (! empty($filters['sex'])) {
            $query->where('students.sex', $filters['sex']);
        }

        return $query->paginate($perpage)->withQueryString();
    }

    public function fetchSchools()
    {
        return DB::connection('abia_sms')->table('schools')->select('school_id as id', 'name')->orderBy('name')->get();
    }

    public function fetchClasses(?string $schoolId = null)
    {
        $query = DB::connection('abia_sms')->table('classes')
            ->select('id', 'name', 'school_id')
            ->orderBy('name');

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return $query->get();
    }

    public function fetchAlumni(int $perpage = 30, ?string $search = null, ?int $session_id = null, ?string $schoolId = null)
    {
        $query = DB::connection('abia_sms')->table('students')
            ->leftJoin('schools', 'students.school_id', '=', 'schools.school_id')
            ->leftJoin('sch_sessions', 'students.current_ses_id', '=', 'sch_sessions.id')
            ->select('students.id', 'students.student_id', 'students.fname', 'students.sname', 'students.oname', 'students.graduation_date', 'schools.name as school', 'sch_sessions.name as session')
            ->where('students.current_stage_id', 5); // Alumni Stage ID

        if ($session_id) {
            $query->where('students.current_ses_id', $session_id);
        }

        if ($schoolId) {
            $query->where('students.school_id', $schoolId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('students.fname', 'like', "%$search%")
                    ->orWhere('students.sname', 'like', "%$search%")
                    ->orWhere('students.oname', 'like', "%$search%")
                    ->orWhere('students.student_id', 'like', "%$search%");
            });
        }

        return $query->orderBy('students.graduation_date', 'desc')
            ->paginate($perpage)->withQueryString();
    }

    public function fetchPenalties(int $perpage = 30, ?string $search = null, array $filters = [])
    {
        $query = DB::connection('abia_sms')->table('penalties')
            ->join('students', 'penalties.code_id', '=', 'students.student_id')
            ->leftJoin('schools', 'penalties.school_id', '=', 'schools.school_id')
            ->select(
                'students.student_id',
                'students.fname',
                'students.sname',
                'penalties.offence',
                'penalties.purnishment as punishment',
                'penalties.offence_date',
                'schools.name as school'
            );

        if (! empty($filters['school_id'])) {
            $query->where('penalties.school_id', $filters['school_id']);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('students.fname', 'like', "%$search%")
                    ->orWhere('students.sname', 'like', "%$search%")
                    ->orWhere('students.student_id', 'like', "%$search%")
                    ->orWhere('penalties.offence', 'like', "%$search%");
            });
        }

        return $query->orderBy('penalties.id', 'desc')
            ->paginate($perpage)->withQueryString();
    }
}
