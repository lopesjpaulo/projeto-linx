<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param int $pages
     * @return LengthAwarePaginator
     */
    public function paginate(int $pages): LengthAwarePaginator;

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): ?Model;

    /**
     * @param array $attributes
     * @param int $id
     * @return bool
     */
    public function update(array $attributes, int $id): bool;

    /**
     * @param int $id
     * @return int 
     */
    public function delete($id): int;
}