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

class ModResource extends JsonResource
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
        $includeMods = 'mods' === $request->query('include');

        return [
            'name'        => $this->mod->modid,
            'version'     => $this->version->tag,
            'md5'         => $this->version->package_hash,
            'url'         => $this->version->package_url,
            'pretty_name' => $this->when($includeMods, $this->mod->name),
            'author'      => $this->when($includeMods, $this->mod->author),
            'description' => $this->when($includeMods, $this->mod->description),
            'link'        => $this->when($includeMods, $this->mod->url),
        ];
    }
}
