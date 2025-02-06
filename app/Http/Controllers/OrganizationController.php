<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\Activities;
use App\Models\Building;
use OpenApi\Annotations as OA;

 
class OrganizationController extends Controller
{
   /**
     * @OA\Get(
     *     path="/api/organizations/building/{buildingId}",
     *     summary="Получить организации по ID здания",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         required=true,
     *         description="ID здания",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="organizations"
     *             )
     *         )
     *     )
     * )
     */
    public function getOrganizationsByBuilding($buildingId)
    {
        $organizations = Organization::where('building_id', $buildingId)->get();
        return response()->json(['organizations' => $organizations]);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/activity/{activityId}",
     *     summary="Получить организации по ID вида деятельности",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="organizations"
     *             )
     *         )
     *     )
     * )
     */
    public function getOrganizationsByActivity($activityId)
    {
        $organizations = Organization::whereHas('activities', function ($query) use ($activityId) {
            $query->where('activities_id', $activityId);
        })->get();

        return response()->json(['organizations' => $organizations]);
    }

    /**
     * @OA\Post(
     *     path="/api/organizations/radius",
     *     summary="Получить организации в радиусе",
     *     tags={"Organizations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"radius", "latitude", "longitude"},
     *             @OA\Property(property="radius", type="number", format="float", example=10),
     *             @OA\Property(property="latitude", type="number", format="float", example=55.7558),
     *             @OA\Property(property="longitude", type="number", format="float", example=37.6176)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="organizations"
     *             )
     *         )
     *     )
     * )
     */
    public function getOrganizationsInRadius(Request $request)
    {
        $data = $request->validate([
            'radius' => 'required|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $organizations = Organization::selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$data->latitude, $data->longitude, $data->latitude])
            ->having("distance", "<", $data->radius)
            ->get();

        return response()->json(['organizations' => $organizations]);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/{organizationId}",
     *     summary="Получить организацию по ID",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="organizationId",
     *         in="path",
     *         required=true,
     *         description="ID организации",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация об организации",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Organization not found")
     *         )
     *     )
     * )
     */
    public function getOrganizationById($organizationId)
    {
        $organization = Organization::with(['building', 'activities'])->find($organizationId);
        if ($organization) {
            return response()->json(['organization' => $organization]);
        }
        return response()->json(['error' => 'Organization not found'], 404);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/activity/search/{activityId}",
     *     summary="Поиск организаций по виду деятельности (включая дочерние)",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="organizations"
     *             )
     *         )
     *     )
     * )
     */
    public function searchOrganizationsByActivity($activityId)
    {
        $activityIds = Activities::where('parent_id', $activityId)->pluck('id')->toArray();
        $activityIds[] = $activityId;

        $organizations = Organization::with('activities')->whereHas('activities', function ($query) use ($activityIds) {
            $query->whereIn('activities_id', $activityIds);
        })->get();

        return response()->json(['organizations' => $organizations]);
    }

    /**
     * @OA\Post(
     *     path="/api/organizations/search",
     *     summary="Поиск организаций по названию",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название организации",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="organizations"
     *             )
     *         )
     *     )
     * )
     */
    public function searchOrganizationsByName(Request $request)
    {
        $name = $request->validate([
            'name' => 'required',
        ]);
        $organizations = Organization::where('name', 'LIKE', "%{$name}%")->get();
        return response()->json(['organizations' => $organizations]);
    }
}
