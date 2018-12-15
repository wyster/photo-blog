<?php

namespace App\Dom\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface TagManager.
 *
 * @package App\Dom\Contracts
 */
interface TagManager
{
    /**
     * Paginate over tags.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $page, int $perPage, array $filters = []): LengthAwarePaginator;
}
