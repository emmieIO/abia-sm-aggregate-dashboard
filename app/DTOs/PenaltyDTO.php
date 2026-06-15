<?php

namespace App\DTOs;

readonly class PenaltyDTO
{
    public function __construct(
        public string $student_id,
        public string $fullname,
        public string $offence,
        public string $punishment,
        public string $date,
        public string $school
    ) {}

    public static function fromRaw(object $data): self
    {
        return new self(
            student_id: $data->student_id,
            fullname: "{$data->fname} {$data->sname}",
            offence: $data->offence,
            punishment: $data->purnishment,
            date: $data->offence_date,
            school: $data->school_name
        );
    }
}
