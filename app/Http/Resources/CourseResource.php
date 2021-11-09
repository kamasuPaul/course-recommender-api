<?php

namespace App\Http\Resources;

use App\Support\TwoFactorAuthenticator;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @mixin \App\Models\User
 */
class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'isActive'            => $this->is_active,
            'code' => $this->code,
            'type'=> $this->type,
            'university_id' => 'required|exists:universities,id',
            'campus_id' => 'exists:campuses,id',
            //essential subjects are required
            'essential_subjects' => 'required|array',
            'essential_subjects.*' => 'required|exists:subjects,id',
            //relevant subjects are required
            'relevant_subjects' => 'required|array',
            'relevant_subjects.*' => 'required|exists:subjects,id',
            //other subjects are required
            'desirable_subjects' => 'required|array',
            'desirable_subjects.*' => 'required|exists:subjects,id',
        ];
    }
}
