<?php

namespace App\DTOs;

readonly class AlumniDTO
{
    public function __construct(
        public string $student_id,
        public string $fullname,
        public string $school,
        public string $session,
        public ?string $graduation_date
    ) {}

    public static function fromRaw(object $data): self
    {
        return new self(
            student_id: $data->student_id,
            fullname: "{$data->fname} {$data->sname}",
            school: $data->school_name,
            session: $data->session_name,
            graduation_date: $data->graduation_date
        );
    }
}
