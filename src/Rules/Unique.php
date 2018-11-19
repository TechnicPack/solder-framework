<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TechnicPack\SolderFramework\Rules;

use Illuminate\Contracts\Validation\Rule;

class Unique implements Rule
{
    /**
     * The model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The modpack id to ignore.
     *
     * @var int
     */
    private $ignore;

    /**
     * UniqueSlug constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array                               $ignore
     */
    public function __construct($model, $ignore = [])
    {
        $this->model = $model;
        $this->ignore = \is_array($ignore) ? $ignore : [$ignore];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->model::where($attribute, $value)
            ->whereNotIn('id', $this->ignore)
            ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be unique.';
    }
}
