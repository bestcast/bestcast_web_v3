<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlocksMoviesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     'id' => (string)$this->id,
        //     'blocks_id' => $this->blocks_id,
        //     'movies_id' => $this->movies_id,
        //     'movies' => new MoviesResource($this->movies)
        // ];
        return new MoviesListResource($this->movies);
    }
}