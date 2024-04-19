<?php
  
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class User extends JsonResource
{
    public function toArray($request)
    {
        return [
            'token' => $this->token,
            'user_id' => $this->id,
            'user_type' => $this->user_type,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'fullname' => $this->firstname.' '.$this->lastname,
            'country' => $this->Country,
            'photo_path' => $this->photo_path,
            'role' => $this->Role ? $this->Role->name : '',
        ];
    }
    
}