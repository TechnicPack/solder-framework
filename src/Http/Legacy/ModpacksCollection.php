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

use Illuminate\Http\Resources\Json\ResourceCollection;

class ModpacksCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'modpacks'   => $this->collect($request),
            'mirror_url' => null,
        ];
    }

    /**
     * Collect the modpacks.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    private function collect($request)
    {
        if ('full' === $request->query('include')) {
            return ModpackResource::collection($this->collection)
                ->collection
                ->keyBy('slug');
        }

        return $this->collection
            ->pluck('name', 'slug')
            ->all();
    }
}
