<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

readonly class DashboardMetricsDTO
{
    /**
     * @param  Collection<int, StudentPerformanceDTO>  $topStudents
     * @param  Collection<int, StudentPerformanceDTO>  $lowStudents
     * @param  Collection<int, AlumniDTO>  $alumniList
     * @param  Collection<int, PenaltyDTO>  $penaltyList
     */
    public function __construct(
        public ?object $selectedSchool,
        public Collection $schools,
        public int $schoolCount,
        public int $studentCount,
        public int $staffCount,
        public int $parentCount,
        public int $alumniCount,
        public int $classCount,
        public int $subjectCount,
        public int $examRecordCount,
        public int $examSummaryCount,
        public int $penaltyCount,
        public int $interventionCount,
        public float $interventionThreshold,
        public float $studentStaffRatio,
        public float $parentCoverageRate,
        public float $dataCompletenessRate,
        public Collection $genderDistribution,
        public Collection $ageDistribution,
        public Collection $stageDistribution,
        public Collection $classDistribution,
        public Collection $schoolDistribution,
        public Collection $schoolAnalytics,
        public Collection $dataQuality,
        public Collection $interventionStudents,
        public array $academicAvailability,
        public Collection $topStudents,
        public Collection $lowStudents,
        public Collection $subjects,
        public Collection $lgaDistribution,
        public Collection $alumniList,
        public Collection $penaltyList,
        public Collection $sessions,
        public float $attendanceRate,
        public Collection $topSchools
    ) {}
}
