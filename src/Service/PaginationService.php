<?php

namespace App\Service;

class PaginationService
{
    public function paginate(array $items, int $page, int $itemsPerPage): array
    {
        $totalItems = count($items);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $page = max(1, min($page, $totalPages));
        
        $offset = ($page - 1) * $itemsPerPage;
        $paginatedItems = array_slice($items, $offset, $itemsPerPage);
        
        return [
            'items' => $paginatedItems,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            
            'totalItems' => $totalItems,
        ];
    }
}