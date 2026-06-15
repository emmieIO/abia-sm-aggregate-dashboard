<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        public DashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $schoolId = $request->string('school_id')->toString() ?: null;
        $metrics = $this->dashboardService->getDashboardMetrics($schoolId);

        return view('pages.dashboard', [
            'selected_school' => $metrics->selectedSchool,
            'schools' => $metrics->schools,
            'school_count' => $metrics->schoolCount,
            'student_count' => $metrics->studentCount,
            'staff_count' => $metrics->staffCount,
            'parent_count' => $metrics->parentCount,
            'alumni_count' => $metrics->alumniCount,
            'class_count' => $metrics->classCount,
            'subject_count' => $metrics->subjectCount,
            'exam_record_count' => $metrics->examRecordCount,
            'exam_summary_count' => $metrics->examSummaryCount,
            'penalty_count' => $metrics->penaltyCount,
            'intervention_count' => $metrics->interventionCount,
            'intervention_threshold' => $metrics->interventionThreshold,
            'student_staff_ratio' => $metrics->studentStaffRatio,
            'parent_coverage_rate' => $metrics->parentCoverageRate,
            'data_completeness_rate' => $metrics->dataCompletenessRate,
            'gender_distribution' => $metrics->genderDistribution,
            'age_distribution' => $metrics->ageDistribution,
            'stage_distribution' => $metrics->stageDistribution,
            'class_distribution' => $metrics->classDistribution,
            'school_distribution' => $metrics->schoolDistribution,
            'school_analytics' => $metrics->schoolAnalytics,
            'data_quality' => $metrics->dataQuality,
            'intervention_students' => $metrics->interventionStudents,
            'academic_availability' => $metrics->academicAvailability,
            'top_students' => $metrics->topStudents,
            'least_students' => $metrics->lowStudents,
            'subjects' => $metrics->subjects,
            'lga_distribution' => $metrics->lgaDistribution,
            'alumni_list' => $metrics->alumniList,
            'penalty_list' => $metrics->penaltyList,
            'sessions' => $metrics->sessions,
            'attendance_rate' => $metrics->attendanceRate,
            'top_performing_schools' => $metrics->topSchools,
        ]);
    }

    public function fetchAlumniBySession(Request $request)
    {
        $session_id = $request->input('session_id');
        $alumni = $this->dashboardService->fetchAlumni($session_id);

        return response()->json($alumni);
    }

    public function fetchTopStudentsBySubject(Request $request)
    {
        $subject = $request->integer('subject', 1);
        $schoolId = $request->string('school_id')->toString() ?: null;
        $top_students = $this->dashboardService->fetchTopFiveTopPerformingStudents($subject, $schoolId);

        return response()->json($top_students);
    }

    public function fetchLowStudentsBySubject(Request $request)
    {
        $subject = $request->integer('subject', 1);
        $schoolId = $request->string('school_id')->toString() ?: null;
        $low_students = $this->dashboardService->fetchTopFiveLowPerformingStudents($subject, $schoolId);

        return response()->json($low_students);
    }
}
