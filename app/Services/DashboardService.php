<?php

namespace App\Services;

use App\DTOs\AlumniDTO;
use App\DTOs\DashboardMetricsDTO;
use App\DTOs\PenaltyDTO;
use App\DTOs\StudentPerformanceDTO;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private const CACHE_TTL = 600;

    private const INTERVENTION_THRESHOLD = 50.0;

    public function getDashboardMetrics(?string $schoolId = null): DashboardMetricsDTO
    {
        $schoolId = $schoolId ?: null;

        return Cache::remember("dashboard_context_v9:{$schoolId}", self::CACHE_TTL, function () use ($schoolId) {
            $schools = $this->fetchSchools();
            $selectedSchool = $schoolId
                ? $schools->firstWhere('school_id', $schoolId)
                : null;

            $studentCount = $this->getNumberofStudents($schoolId);
            $staffCount = $this->getNumberofStaffs($schoolId);
            $parentCount = $this->getNumberofParents($schoolId);
            $parentLinkedCount = $this->getLinkedStudentParentCount($schoolId);
            $dataQuality = $this->fetchDataQuality($schoolId);
            $schoolAnalytics = $this->fetchSchoolAnalytics();
            $examRecordCount = $this->getExamRecordCount($schoolId);
            $examSummaryCount = $this->getExamSummaryCount($schoolId);
            $subjects = $this->fetchSubjects($schoolId);
            $interventionStudents = $this->fetchInterventionStudents($schoolId);

            return new DashboardMetricsDTO(
                selectedSchool: $selectedSchool,
                schools: $schools,
                schoolCount: $this->getNumberofSchools(),
                studentCount: $studentCount,
                staffCount: $staffCount,
                parentCount: $parentCount,
                alumniCount: $this->getAlumniCount($schoolId),
                classCount: $this->getNumberofClasses($schoolId),
                subjectCount: $this->getNumberofSubjects(),
                examRecordCount: $examRecordCount,
                examSummaryCount: $examSummaryCount,
                penaltyCount: $this->getPenaltyCount($schoolId),
                interventionCount: $this->getInterventionCount($schoolId),
                interventionThreshold: self::INTERVENTION_THRESHOLD,
                studentStaffRatio: $staffCount > 0 ? round($studentCount / $staffCount, 1) : 0,
                parentCoverageRate: $studentCount > 0 ? round(($parentLinkedCount / $studentCount) * 100, 1) : 0,
                dataCompletenessRate: $dataQuality->count() > 0
                    ? round($dataQuality->avg('complete_rate'), 1)
                    : 0,
                genderDistribution: $this->fetchGenderDistribution($schoolId),
                ageDistribution: $this->fetchStudentAgeDistribution($schoolId),
                stageDistribution: $this->fetchStageDistribution($schoolId),
                classDistribution: $this->fetchClassDistribution($schoolId),
                schoolDistribution: $this->fetchSchoolDistribution(),
                schoolAnalytics: $schoolAnalytics,
                dataQuality: $dataQuality,
                interventionStudents: $interventionStudents,
                academicAvailability: $this->fetchAcademicAvailability($examRecordCount, $examSummaryCount, $subjects->count()),
                topStudents: $this->fetchTopFiveTopPerformingStudents(null, $schoolId),
                lowStudents: $this->fetchTopFiveLowPerformingStudents(null, $schoolId),
                subjects: $subjects,
                lgaDistribution: $this->fetchLgaDistribution($schoolId),
                alumniList: $this->fetchAlumni(null, $schoolId),
                penaltyList: $this->fetchStudentPenalties($schoolId),
                sessions: $this->fetchSessions(),
                attendanceRate: $this->fetchAverageAttendance($schoolId),
                topSchools: $schoolAnalytics->sortByDesc('active_students')->values()->take(10)
            );
        });
    }

    public function fetchSchools(): Collection
    {
        return DB::connection('abia_sms')->table('schools as s')
            ->leftJoin('lgas as l', 's.lga', '=', 'l.id')
            ->select('s.school_id', 's.name', 's.status', 'l.name as lga')
            ->orderBy('s.name')
            ->get();
    }

    public function fetchAllSchoolRankings(): Collection
    {
        return $this->fetchSchoolAnalytics()
            ->sortByDesc('active_students')
            ->values()
            ->take(10);
    }

    public function fetchSchoolAnalytics(): Collection
    {
        $schools = DB::connection('abia_sms')->table('schools as s')
            ->leftJoin('lgas as l', 's.lga', '=', 'l.id')
            ->select('s.school_id', 's.name as school', 'l.name as lga', 's.status')
            ->orderBy('s.name')
            ->get();

        $students = DB::connection('abia_sms')->table('students')
            ->select(
                'school_id',
                DB::raw('COUNT(*) as total_students'),
                DB::raw('SUM(current_stage_id != 5) as active_students'),
                DB::raw('SUM(current_stage_id = 5) as alumni_students'),
                DB::raw("SUM(sex = 'Female') as female_students"),
                DB::raw("SUM(sex = 'Male') as male_students"),
                DB::raw("SUM(sex IS NULL OR sex = '') as missing_gender"),
                DB::raw("SUM(parent_id IS NOT NULL AND parent_id != '' AND parent_id != '0') as linked_parents"),
                DB::raw('SUM(current_class_id IS NULL OR current_class_id = 0) as missing_class')
            )
            ->groupBy('school_id')
            ->get()
            ->keyBy('school_id');

        $staffs = DB::connection('abia_sms')->table('staffs')
            ->select('school_id', DB::raw('COUNT(*) as total'))
            ->groupBy('school_id')
            ->pluck('total', 'school_id');

        $parents = DB::connection('abia_sms')->table('parents')
            ->select('school_id', DB::raw('COUNT(*) as total'))
            ->groupBy('school_id')
            ->pluck('total', 'school_id');

        $classes = DB::connection('abia_sms')->table('classes')
            ->select('school_id', DB::raw('COUNT(*) as total'))
            ->groupBy('school_id')
            ->pluck('total', 'school_id');

        return $schools->map(function ($school) use ($students, $staffs, $parents, $classes) {
            $student = $students->get($school->school_id);
            $activeStudents = (int) ($student->active_students ?? 0);
            $staffCount = (int) ($staffs[$school->school_id] ?? 0);
            $linkedParents = (int) ($student->linked_parents ?? 0);
            $missingGender = (int) ($student->missing_gender ?? 0);
            $missingClass = (int) ($student->missing_class ?? 0);

            return (object) [
                'school_id' => $school->school_id,
                'school' => $school->school,
                'lga' => $school->lga ?: 'Unassigned',
                'status' => $school->status,
                'total_students' => (int) ($student->total_students ?? 0),
                'active_students' => $activeStudents,
                'alumni_students' => (int) ($student->alumni_students ?? 0),
                'female_students' => (int) ($student->female_students ?? 0),
                'male_students' => (int) ($student->male_students ?? 0),
                'missing_gender' => $missingGender,
                'staff_count' => $staffCount,
                'parent_count' => (int) ($parents[$school->school_id] ?? 0),
                'class_count' => (int) ($classes[$school->school_id] ?? 0),
                'student_staff_ratio' => $staffCount > 0 ? round($activeStudents / $staffCount, 1) : 0,
                'parent_coverage_rate' => $activeStudents > 0 ? round(($linkedParents / $activeStudents) * 100, 1) : 0,
                'profile_completeness_rate' => $activeStudents > 0 ? round((1 - (($missingGender + $missingClass) / ($activeStudents * 2))) * 100, 1) : 0,
            ];
        });
    }

    public function getAlumniCount(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('students'), $schoolId)
            ->where('current_stage_id', 5)
            ->count();
    }

    public function fetchAlumni(?int $sessionId = null, ?string $schoolId = null): Collection
    {
        $query = DB::connection('abia_sms')->table('students as s')
            ->leftJoin('schools as sch', 's.school_id', '=', 'sch.school_id')
            ->leftJoin('sch_sessions as ses', 's.current_ses_id', '=', 'ses.id')
            ->select('s.student_id', 's.fname', 's.sname', 'sch.name as school_name', 'ses.name as session_name', 's.graduation_date')
            ->where('s.current_stage_id', 5);

        if ($sessionId) {
            $query->where('s.current_ses_id', $sessionId);
        }

        if ($schoolId) {
            $query->where('s.school_id', $schoolId);
        }

        return $query->orderByRaw('COALESCE(s.graduation_date, s.id) DESC')
            ->limit(50)
            ->get()
            ->map(fn ($item) => AlumniDTO::fromRaw($item));
    }

    public function fetchStudentPenalties(?string $schoolId = null): Collection
    {
        $query = DB::connection('abia_sms')->table('penalties as p')
            ->join('students as s', 'p.code_id', '=', 's.student_id')
            ->leftJoin('schools as sch', 'p.school_id', '=', 'sch.school_id')
            ->select('s.student_id', 's.fname', 's.sname', 'p.offence', 'p.purnishment', 'p.offence_date', 'sch.name as school_name');

        if ($schoolId) {
            $query->where('p.school_id', $schoolId);
        }

        return $query->orderBy('p.id', 'desc')
            ->limit(20)
            ->get()
            ->map(fn ($item) => PenaltyDTO::fromRaw($item));
    }

    public function fetchSessions(): Collection
    {
        return DB::connection('abia_sms')->table('sch_sessions')
            ->select('id', 'name')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function fetchAverageAttendance(?string $schoolId = null): float
    {
        return (float) $this->schoolScoped(DB::connection('abia_sms')->table('exam_records_summary'), $schoolId)
            ->avg('attendance') ?: 0;
    }

    public function fetchLgaDistribution(?string $schoolId = null): Collection
    {
        $query = DB::connection('abia_sms')->table('schools')
            ->leftJoin('lgas', 'schools.lga', '=', 'lgas.id')
            ->where('lgas.state_id', 2647)
            ->select('lgas.name as lga', DB::raw('count(*) as total'))
            ->groupBy('lgas.name')
            ->orderBy('total', 'desc');

        if ($schoolId) {
            $query->where('schools.school_id', $schoolId);
        }

        return $query->get();
    }

    public function getNumberofSchools(): int
    {
        return DB::connection('abia_sms')->table('schools')->count();
    }

    public function getNumberofStudents(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('students'), $schoolId)
            ->where('current_stage_id', '!=', 5)
            ->count();
    }

    public function getNumberofStaffs(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('staffs'), $schoolId)->count();
    }

    public function getNumberofParents(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('parents'), $schoolId)->count();
    }

    public function getNumberofClasses(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('classes'), $schoolId)->count();
    }

    public function getNumberofSubjects(): int
    {
        return DB::connection('abia_sms')->table('subjects')->count();
    }

    public function getExamRecordCount(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('exam_records'), $schoolId)->count();
    }

    public function getExamSummaryCount(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('exam_records_summary'), $schoolId)->count();
    }

    public function getPenaltyCount(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('penalties'), $schoolId)->count();
    }

    public function getInterventionCount(?string $schoolId = null): int
    {
        if ($this->getExamSummaryCount($schoolId) > 0) {
            return $this->schoolScoped(DB::connection('abia_sms')->table('exam_records_summary'), $schoolId)
                ->whereRaw('CAST(NULLIF(average, "") AS DECIMAL(10,2)) < ?', [self::INTERVENTION_THRESHOLD])
                ->count();
        }

        if ($this->getExamRecordCount($schoolId) === 0) {
            return 0;
        }

        return $this->schoolScoped(DB::connection('abia_sms')->table('exam_records'), $schoolId)
            ->select('student_id')
            ->whereNotNull('sub_total')
            ->where('sub_total', '!=', '')
            ->groupBy('student_id')
            ->havingRaw('AVG(CAST(NULLIF(sub_total, "") AS DECIMAL(10,2))) < ?', [self::INTERVENTION_THRESHOLD])
            ->get()
            ->count();
    }

    public function getLinkedStudentParentCount(?string $schoolId = null): int
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('students'), $schoolId)
            ->where('current_stage_id', '!=', 5)
            ->whereNotNull('parent_id')
            ->where('parent_id', '!=', '')
            ->where('parent_id', '!=', '0')
            ->count();
    }

    public function fetchGenderDistribution(?string $schoolId = null): Collection
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('students'), $schoolId)
            ->select(DB::raw("CASE WHEN sex IS NULL OR sex = '' THEN 'Unspecified' ELSE sex END as sex"), DB::raw('count(*) as total'))
            ->where('current_stage_id', '!=', 5)
            ->groupBy('sex')
            ->orderBy('total', 'desc')
            ->get();
    }

    public function fetchStudentAgeDistribution(?string $schoolId = null): Collection
    {
        $data = $this->schoolScoped(DB::connection('abia_sms')->table('students'), $schoolId)
            ->select('dob', DB::raw('count(*) as total'))
            ->where('current_stage_id', '!=', 5)
            ->whereNotNull('dob')
            ->where('dob', '!=', '')
            ->groupBy('dob')
            ->get();

        return collect($data)
            ->filter(fn ($item) => $this->hasParseableDate($item->dob))
            ->groupBy(fn ($item) => Carbon::parse($item->dob)->year)
            ->map(fn ($group) => $group->sum('total'))
            ->sortKeys();
    }

    public function fetchStageDistribution(?string $schoolId = null): Collection
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('students'), $schoolId)
            ->select('current_stage_id as stage', DB::raw('COUNT(*) as total'))
            ->where('current_stage_id', '!=', 5)
            ->groupBy('current_stage_id')
            ->orderBy('current_stage_id')
            ->get();
    }

    public function fetchClassDistribution(?string $schoolId = null): Collection
    {
        return $this->schoolScoped(DB::connection('abia_sms')->table('students as s'), $schoolId, 's.school_id')
            ->leftJoin('classes as c', 's.current_class_id', '=', 'c.id')
            ->select(DB::raw("COALESCE(NULLIF(c.name, ''), 'Unassigned') as class_name"), DB::raw('COUNT(*) as total'))
            ->where('s.current_stage_id', '!=', 5)
            ->groupBy('class_name')
            ->orderByDesc('total')
            ->limit(12)
            ->get();
    }

    public function fetchSchoolDistribution(): Collection
    {
        return DB::connection('abia_sms')->table('schools as school')
            ->leftJoin('students as student', function ($join) {
                $join->on('student.school_id', '=', 'school.school_id')
                    ->where('student.current_stage_id', '!=', 5);
            })
            ->select('school.school_id', 'school.name as school', DB::raw('COUNT(student.id) as total'))
            ->groupBy('school.school_id', 'school.name')
            ->orderByDesc('total')
            ->get();
    }

    public function fetchDataQuality(?string $schoolId = null): Collection
    {
        $summary = $this->schoolScoped(DB::connection('abia_sms')->table('students'), $schoolId)
            ->where('current_stage_id', '!=', 5)
            ->selectRaw('
                COUNT(*) as total,
                SUM(sex IS NOT NULL AND sex != "") as gender_complete,
                SUM(dob IS NOT NULL AND dob != "") as dob_complete,
                SUM(parent_id IS NOT NULL AND parent_id != "" AND parent_id != "0") as parent_complete,
                SUM(current_class_id IS NOT NULL AND current_class_id != 0) as class_complete
            ')
            ->first();

        $studentCount = (int) ($summary->total ?? 0);

        if ($studentCount === 0) {
            return collect();
        }

        $checks = [
            'Gender captured' => (int) $summary->gender_complete,
            'Date of birth captured' => (int) $summary->dob_complete,
            'Parent linked' => (int) $summary->parent_complete,
            'Class assigned' => (int) $summary->class_complete,
        ];

        return collect($checks)->map(function ($complete, $label) use ($studentCount) {
            return (object) [
                'label' => $label,
                'complete' => $complete,
                'missing' => $studentCount - $complete,
                'complete_rate' => round(($complete / $studentCount) * 100, 1),
            ];
        })->values();
    }

    public function fetchAcademicAvailability(int $examRecords, int $summaryRecords, int $scoredSubjects): array
    {
        return [
            'has_records' => $examRecords > 0 || $summaryRecords > 0,
            'exam_records' => $examRecords,
            'summary_records' => $summaryRecords,
            'scored_subjects' => $scoredSubjects,
            'message' => $examRecords > 0 || $summaryRecords > 0
                ? 'Academic records are available for this context.'
                : 'No exam records have been captured for this context yet.',
        ];
    }

    public function fetchInterventionStudents(?string $schoolId = null): Collection
    {
        if ($this->getExamSummaryCount($schoolId) > 0) {
            return $this->schoolScoped(DB::connection('abia_sms')->table('exam_records_summary as ers'), $schoolId, 'ers.school_id')
                ->join('students as s', 's.student_id', '=', 'ers.student_id')
                ->leftJoin('schools as sch', 'sch.school_id', '=', 'ers.school_id')
                ->leftJoin('classes as c', 'c.id', '=', 'ers.class_id')
                ->select(
                    's.student_id',
                    's.fname',
                    's.sname',
                    'sch.name as school',
                    'c.name as class_name',
                    DB::raw('CAST(NULLIF(ers.average, "") AS DECIMAL(10,2)) as score'),
                    'ers.attendance'
                )
                ->whereRaw('CAST(NULLIF(ers.average, "") AS DECIMAL(10,2)) < ?', [self::INTERVENTION_THRESHOLD])
                ->orderBy('score')
                ->limit(10)
                ->get()
                ->map(fn ($student) => $this->formatInterventionStudent($student, 'Overall average'));
        }

        if ($this->getExamRecordCount($schoolId) === 0) {
            return collect();
        }

        return $this->schoolScoped(DB::connection('abia_sms')->table('exam_records as er'), $schoolId, 'er.school_id')
            ->join('students as s', 's.student_id', '=', 'er.student_id')
            ->leftJoin('schools as sch', 'sch.school_id', '=', 'er.school_id')
            ->leftJoin('classes as c', 'c.id', '=', 'er.class_id')
            ->select(
                's.student_id',
                's.fname',
                's.sname',
                'sch.name as school',
                'c.name as class_name',
                DB::raw('AVG(CAST(NULLIF(er.sub_total, "") AS DECIMAL(10,2))) as score'),
                DB::raw('NULL as attendance')
            )
            ->whereNotNull('er.sub_total')
            ->where('er.sub_total', '!=', '')
            ->groupBy('s.student_id', 's.fname', 's.sname', 'sch.name', 'c.name')
            ->havingRaw('AVG(CAST(NULLIF(er.sub_total, "") AS DECIMAL(10,2))) < ?', [self::INTERVENTION_THRESHOLD])
            ->orderBy('score')
            ->limit(10)
            ->get()
            ->map(fn ($student) => $this->formatInterventionStudent($student, 'Subject average'));
    }

    public function fetchStudentPerformance(?int $subjectId = null, string $orderBy = 'desc', ?string $schoolId = null): Collection
    {
        $subjectId ??= $this->fetchSubjects($schoolId)->first()->id ?? null;

        if (! $subjectId) {
            return collect();
        }

        $query = DB::connection('abia_sms')->table('exam_records as ers')
            ->leftJoin('students', 'ers.student_id', '=', 'students.student_id')
            ->leftJoin('schools', 'ers.school_id', '=', 'schools.school_id')
            ->leftJoin('subjects as sub', 'sub.id', '=', 'ers.subject_id')
            ->leftJoin('classes', 'students.current_class_id', '=', 'classes.id')
            ->select(
                'students.fname as firstname',
                'students.sname as surname',
                'students.oname as othername',
                'ers.sub_total as average',
                'schools.name as school',
                'students.sex as gender',
                'students.student_id',
                'sub.name as subject',
                'classes.name as class_name'
            )
            ->where('sub.id', $subjectId)
            ->whereNotNull('ers.sub_total')
            ->where('ers.sub_total', '>', 0);

        if ($schoolId) {
            $query->where('ers.school_id', $schoolId);
        }

        return $query->orderBy('average', $orderBy)
            ->limit(5)
            ->get()
            ->map(fn ($item) => StudentPerformanceDTO::fromRaw($item));
    }

    public function fetchTopFiveLowPerformingStudents(?int $subjectId = null, ?string $schoolId = null): Collection
    {
        return $this->fetchStudentPerformance($subjectId, 'asc', $schoolId);
    }

    public function fetchTopFiveTopPerformingStudents(?int $subjectId = null, ?string $schoolId = null): Collection
    {
        return $this->fetchStudentPerformance($subjectId, 'desc', $schoolId);
    }

    public function fetchSubjects(?string $schoolId = null): Collection
    {
        $query = DB::connection('abia_sms')->table('exam_records as ers')
            ->join('subjects as sub', 'sub.id', '=', 'ers.subject_id')
            ->select('sub.id', 'sub.name')
            ->distinct()
            ->where('ers.sub_total', '>', 0);

        if ($schoolId) {
            $query->where('ers.school_id', $schoolId);
        }

        return $query->orderBy('sub.name', 'asc')->get();
    }

    private function schoolScoped(Builder $query, ?string $schoolId, string $column = 'school_id'): Builder
    {
        if ($schoolId) {
            $query->where($column, $schoolId);
        }

        return $query;
    }

    private function hasParseableDate(?string $value): bool
    {
        if (! $value) {
            return false;
        }

        try {
            Carbon::parse($value);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function formatInterventionStudent(object $student, string $basis): object
    {
        return (object) [
            'student_id' => $student->student_id,
            'name' => trim("{$student->fname} {$student->sname}"),
            'school' => $student->school ?: 'Unassigned school',
            'class_name' => $student->class_name ?: 'Unassigned class',
            'score' => round((float) $student->score, 1),
            'attendance' => $student->attendance,
            'basis' => $basis,
        ];
    }
}
