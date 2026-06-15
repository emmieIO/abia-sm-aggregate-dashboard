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
            ->leftJoin('classes as admitted_classes', 'students.admitted_class_id', '=', 'admitted_classes.id')
            ->leftJoin('sch_stages as current_stage', 'students.current_stage_id', '=', 'current_stage.id')
            ->leftJoin('sch_stages as admitted_stage', 'students.admitted_stage_id', '=', 'admitted_stage.id')
            ->leftJoin('sch_sessions as current_session', 'students.current_ses_id', '=', 'current_session.id')
            ->leftJoin('sch_sessions as admitted_session', 'students.admitted_ses_id', '=', 'admitted_session.id')
            ->leftJoin('sch_academic_calendar as current_calendar', 'students.current_aca_id', '=', 'current_calendar.id')
            ->leftJoin('sch_academic_calendar as admitted_calendar', 'students.admitted_aca_id', '=', 'admitted_calendar.id')
            ->leftJoin('parents', 'students.parent_id', '=', 'parents.parent_id')
            ->leftJoin('states', 'students.soo', '=', 'states.id')
            ->select(
                'students.id',
                'students.student_id',
                'students.parent_id',
                'students.school_id',
                'students.current_class_id',
                'students.admitted_class_id',
                'students.admitted_stage_id',
                'students.admitted_ses_id',
                'students.admitted_aca_id',
                'students.current_stage_id',
                'students.current_ses_id',
                'students.current_aca_id',
                'students.address',
                'students.village',
                'students.religion',
                'students.phone',
                'students.email',
                'students.sex',
                'students.dob',
                'students.doa',
                'students.blood_group',
                'students.genotype',
                'students.disability',
                'students.asthmatic',
                'students.ailments',
                'students.status',
                'states.name as state',
                'students.fname',
                'students.sname',
                'students.oname',
                'schools.name as school',
                'schools.phone as school_phone',
                'schools.email as school_email',
                'classes.name as class',
                'admitted_classes.name as admitted_class',
                'current_stage.name as current_stage',
                'admitted_stage.name as admitted_stage',
                'current_session.name as current_session',
                'admitted_session.name as admitted_session',
                'current_calendar.term as current_term',
                'current_calendar.start_date as current_term_start',
                'current_calendar.end_date as current_term_end',
                'admitted_calendar.term as admitted_term',
                'parents.fname as parent_fname',
                'parents.sname as parent_sname',
                'parents.oname as parent_oname',
                'parents.phone as parent_phone',
                'parents.email as parent_email',
                'parents.fphone as father_phone',
                'parents.femail as father_email',
                'parents.mphone as mother_phone',
                'parents.memail as mother_email',
                'parents.address as parent_address',
                'parents.occupation as parent_occupation'
            )
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

        if (($filters['sex'] ?? null) === 'missing') {
            $query->where(function ($q) {
                $q->whereNull('students.sex')
                    ->orWhere('students.sex', '');
            });
        } elseif (! empty($filters['sex'])) {
            $query->where('students.sex', $filters['sex']);
        }

        return $query->paginate($perpage)->withQueryString();
    }

    public function fetchSchools()
    {
        return DB::connection('abia_sms')->table('schools')->select('school_id as id', 'name')->orderBy('name')->get();
    }

    public function fetchSchool(string $schoolId): ?object
    {
        return DB::connection('abia_sms')->table('schools')
            ->leftJoin('lgas', 'schools.lga', '=', 'lgas.id')
            ->select('schools.school_id as id', 'schools.school_id', 'schools.name', 'schools.phone', 'schools.email', 'schools.address', 'schools.status', 'lgas.name as lga_name')
            ->where('schools.school_id', $schoolId)
            ->first();
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

    public function fetchClassSummaries(string $schoolId)
    {
        return DB::connection('abia_sms')->table('classes')
            ->leftJoin('students', function ($join) {
                $join->on('students.current_class_id', '=', 'classes.id')
                    ->where('students.current_stage_id', '!=', 5);
            })
            ->select('classes.id', 'classes.name', DB::raw('COUNT(students.id) as student_count'))
            ->where('classes.school_id', $schoolId)
            ->groupBy('classes.id', 'classes.name')
            ->orderBy('classes.name')
            ->get();
    }

    public function fetchAlumni(int $perpage = 30, ?string $search = null, ?int $session_id = null, ?string $schoolId = null)
    {
        $query = DB::connection('abia_sms')->table('students')
            ->leftJoin('schools', 'students.school_id', '=', 'schools.school_id')
            ->leftJoin('classes', 'students.current_class_id', '=', 'classes.id')
            ->leftJoin('sch_sessions', 'students.current_ses_id', '=', 'sch_sessions.id')
            ->leftJoin('states', 'students.soo', '=', 'states.id')
            ->select(
                'students.id',
                'students.student_id',
                'students.fname',
                'students.sname',
                'students.oname',
                'students.sex',
                'students.dob',
                'students.phone',
                'students.email',
                'students.address',
                'students.village',
                'students.religion',
                'students.status',
                'students.graduation_date',
                'schools.name as school',
                'classes.name as class',
                'sch_sessions.name as session',
                'states.name as state'
            )
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
