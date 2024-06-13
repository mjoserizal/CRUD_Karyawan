<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_name' => $this->employee_name,
            'position' => $this->position,
            'gender' => $this->gender,
            'employment_start_date' => $this->employment_start_date,
            'employment_end_date' => $this->employment_end_date,
            'active_status' => $this->active_status,
            'photo' => $this->photo ? url('storage/photos/' . $this->photo) : null,
        ];
    }
}
