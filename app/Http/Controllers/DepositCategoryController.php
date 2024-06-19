<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepositCategoryRequest;
use App\Http\Requests\UpdateDepositCategoryRequest;
use App\Models\DepositCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class DepositCategoryController extends Controller
{
    public function index(): LengthAwarePaginator
    {
        Gate::authorize('viewAny', DepositCategory::class);

        return DepositCategory::latest()->paginate(20);
    }

    public function show(DepositCategory $depositCategory): DepositCategory
    {
        Gate::authorize('view', $depositCategory);

        return $depositCategory;
    }

    public function store(StoreDepositCategoryRequest $request): JsonResponse
    {
        Gate::authorize('create', DepositCategory::class);

        DepositCategory::create($request->validated());

        return response()->json([
            'message' => 'Deposit category created successfully.',
        ], 201);
    }

    public function update(UpdateDepositCategoryRequest $request, DepositCategory $depositCategory): JsonResponse
    {
        Gate::authorize('update', $depositCategory);

        $depositCategory->update($request->validated());

        return response()->json([
            'message' => 'Deposit category updated successfully.',
        ], 200);
    }

    public function destroy(DepositCategory $depositCategory): JsonResponse
    {
        Gate::authorize('delete', $depositCategory);

        $depositCategory->deleted_by = (int) auth()->id();
        $depositCategory->save();

        $depositCategory->delete();

        return response()->json([
            'message' => 'Deposit category deleted successfully.',
        ], 204);
    }

    public function restore(int $id): JsonResponse
    {
        $depositCategory = DepositCategory::withTrashed()->findOrFail($id);

        Gate::authorize('restore', $depositCategory);

        $depositCategory->restore();

        return response()->json([
            'message' => 'Deposit category restored successfully.',
        ], 200);
    }

    public function forceDelete(int $id): JsonResponse
    {
        $depositCategory = DepositCategory::findOrFail($id);

        Gate::authorize('forceDelete', $depositCategory);

        $depositCategory->forceDelete();

        return response()->json(['message' => 'Deposit Category force deleted'], 204);
    }
}
