<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstudioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'centro' => new CentroResource($this->centro),
            'curso' => $this->curso,
            'ciclo' => new CicloResource($this->ciclo),
        ];
    }
}
