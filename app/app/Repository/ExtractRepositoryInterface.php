<?php 

namespace App\Repository;

interface ExtractRepositoryInterface
{
    /**
     * @param array $attributes
     */
    public function createWithdraw(array $attributes): void;

    /**
     * @param array $attributes
     */
    public function createDeposit(array $attributes): void;
}