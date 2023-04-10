<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\Provinces\ProvincesService;
use Exception;
use Illuminate\Http\JsonResponse;

class ProvincesController extends Controller
{
    /**
     * @throws Exception
     */
    public function provinces(ProvincesService $service): JsonResponse {
        $response = $service->execute();
        return $this->successWithData($response);
    }
}
