<?php

namespace App\DTOs;

readonly class StudentPerformanceDTO
{
    public function __construct(
        public string $firstname,
        public string $surname,
        public ?string $othername,
        public float $score,
        public string $school,
        public string $class,
        public string $gender,
        public string $student_id,
        public string $subject
    ) {}

    public static function fromRaw(object $data): self
    {
        return new self(
            firstname: $data->firstname,
            surname: $data->surname,
            othername: $data->othername ?? null,
            score: (float) $data->average,
            school: $data->school,
            class: $data->class_name ?? 'N/A',
            gender: $data->gender,
            student_id: $data->student_id,
            subject: $data->subject
        );
    }
}
