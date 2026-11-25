<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\Business\BusinessService;
use App\Http\Requests\Api\V1\BusinessRequest;

class BusinessController extends ApiController
{
    protected $businessService;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    public function index(Request $request)
    {
        $businesses = $this->businessService->getAll(
            $request->user()
        );

        return $this->success(
            $businesses,
            'Businesses retrieved successfully'
        );
    }

    public function store(BusinessRequest $request)
    {
        $business = $this->businessService->create(
            $request->validated(),
            $request->user()
        );

        return $this->success(
            $business,
            'Business created successfully',
            201
        );
    }

    public function show(Request $request, int $business)
    {
        $result = $this->businessService->findForUser(
            $request->user(),
            $business
        );

        return $this->success(
            $result,
            'Business retrieved successfully'
        );
    }

    public function update(
        BusinessRequest $request,
        int $business
    ) {
        $result = $this->businessService->findForUser(
            $request->user(),
            $business
        );

        // Only owners and admins can update business details.
        abort_unless(
            in_array($result->pivot->role, ['owner', 'admin']),
            403
        );

        $result = $this->businessService->update(
            $result,
            $request->validated()
        );

        return $this->success(
            $result,
            'Business updated successfully'
        );
    }
}
