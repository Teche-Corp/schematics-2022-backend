<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\Cities\CitiesRequest;
use App\Core\Application\Service\Cities\CitiesService;
use Exception;
use Illuminate\Http\JsonResponse;

class CitiesController extends Controller
{
    /**
     * @throws Exception
     */
    public function cities(string $province_id, CitiesService $service): JsonResponse {

        $input = new CitiesRequest($province_id);
        $response = $service->execute($input);
        return $this->successWithData($response);
    }
}
