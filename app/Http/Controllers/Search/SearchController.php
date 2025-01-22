<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Interfaces\LocationRepository;
use App\Repositories\ElasticSearchRepository;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private $elasticSearchRepository;

    public function __construct(LocationRepository $repository)
    {
        $this->elasticSearchRepository = $repository;
    }

    /**
     * Пошук локацій через Elasticsearch
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function search(Request $request)
    {
        // Отримуємо запит з параметрами
        $query = $request->get('query', '');
        $locations = $this->elasticSearchRepository->search($query);

        // Повертаємо JSON-відповідь
        return response()->json([
            'success' => true,
            'data' => $locations,
            'message' => 'Locations retrieved successfully.',
        ], 200);
    }
}