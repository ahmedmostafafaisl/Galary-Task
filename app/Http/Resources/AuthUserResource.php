<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user'=>[
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'image'=>get_file($this->image),
                'city'=>CityResource::make(City::find($this->city_id))
                ],
            'auth'=>[
                'token' => 'Bearer '.$this->token,
            ]
        ];
    }
}
