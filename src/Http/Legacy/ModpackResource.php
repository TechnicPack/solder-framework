<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Http\Legacy;

use Illuminate\Http\Resources\Json\JsonResource;

class ModpackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'         => $this->slug,
            'display_name' => $this->name,
            'url'          => $this->url,
            'icon'         => $this->icon,
            'recommended'  => optional($this->recommended)->tag,
            'latest'       => optional($this->latest)->tag,
            'builds'       => $this->builds->pluck('tag'),
        ];
    }
}
