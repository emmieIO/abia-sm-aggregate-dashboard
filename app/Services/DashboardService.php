<?php

namespace App\Services;

use DB;
use Illuminate\Support\Carbon;

class DashboardService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getNumberofSchools()
    {
        return DB::connection('abia_sms')->table('schools')->count();
    }
    public function getNumberofStudents()
    {
        return DB::connection('abia_sms')->table('students')->count();
    }
    public function getNumberofStaffs()
    {
        return DB::connection('abia_sms')->table('staffs')->count();
    }
    public function getNumberofParents()
    {
        return DB::connection('abia_sms')->table('parents')->count();
    }

    public function fetchgenderDistribution()
    {
        return DB::connection('abia_sms')->table('students')
            ->select('sex', DB::raw('count(*) as total'))
            ->groupBy('sex')
            ->orderBy('total', 'desc')->get();
    }

    public function fetchStudentAgeDistribution()
    {
        $data = DB::connection('abia_sms')->table('students')
            ->select('dob', DB::raw('count(*) as total'))
            ->groupBy('dob')
            ->orderBy('dob', 'desc')->get();
        return collect($data)
            ->filter(fn($item) => !empty($item->dob))
            ->groupBy(fn($item) => Carbon::parse($item->dob)->year)
            ->map(fn($group) => $group->sum('total'));
    }

    private function fetchStudentPerformance(int|null $subject_id=1, string $orderBy='desc' )
    {
        if (!$subject_id) {
            $subject = 1; // default subject id
        }
        // Implementation to fetch top performing students
        return DB::connection('abia_sms')->table('exam_records as ers')
            ->leftJoin('students', 'ers.student_id', '=', 'students.student_id')
            ->leftJoin('schools', 'ers.school_id', '=', 'schools.school_id')
            ->leftJoin('subjects as sub', 'sub.id', '=', 'ers.subject_id')
            ->select('students.fname as firstname','students.sname as surname','students.oname as othername', 'ers.sub_total as average', 'schools.name as school', 'students.sex as gender', 'students.student_id', 'sub.name as subject')
            ->where('sub.id',  $subject_id)
            ->whereNotNull('ers.sub_total')
            ->where('ers.sub_total', '>', 0)
            ->orderBy('average', $orderBy)
            ->limit(5)
            ->get();
    }

    public function fetchTopFiveLowPerformingStudents(int|null $subject_id = 1)
    {
        return $this->fetchStudentPerformance($subject_id, 'asc');
    }
    public function fetchTopFiveTopPerformingStudents(int|null $subject_id = 1)
    {
        return $this->fetchStudentPerformance($subject_id, 'desc');
    }

    public function fetchSubjects()
    {
        return DB::connection('abia_sms')->table('exam_records as ers')
            ->join('subjects as sub', 'sub.id', '=', 'ers.subject_id')
            ->select('sub.id', 'sub.name')
            ->distinct()
            ->where('ers.sub_total', '>', 0)
            ->orderBy('sub.name', 'asc')
            ->get();
    }


}
