<?php
  
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class Campus extends JsonResource
{
    public function toArray($request)
    {
        return [
            'campus_id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'code' => $this->code,
            'code_expire_date' => $this->code_expire_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
    
}