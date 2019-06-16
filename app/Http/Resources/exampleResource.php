<?php

namespace App\Http\Resources;

use Helper;
use Illuminate\Http\Resources\Json\Resource;

class ExampleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_transaction' => $this->id,
            'day' => Helper::hello_world(),
        ];
    }
}
