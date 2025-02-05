<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\Activities;
use App\Models\Building;

class OrganizationController extends Controller
{
    public function getOrganizationsByBuilding($buildingId)
    {
        $organizations = Organization::where('building_id', $buildingId)->get();
        return response()->json(['organizations' => $organizations]);
    }

    public function getOrganizationsByActivity($activityId)
    {
        $organizations = Organization::whereHas('activities', function ($query) use ($activityId) {
            $query->where('activities_id', $activityId);
        })->get();

        return response()->json(['organizations' => $organizations]);
    }

    public function getOrganizationsInRadius(Request $request)
    {
        $data = $request->validate([
            'radius' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $organizations = Organization::selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$data->latitude, $data->longitude, $data->latitude])
            ->having("distance", "<", $data->radius)
            ->get();

        return response()->json(['organizations' => $organizations]);
    }

    public function getOrganizationById($organizationId)
    {
        $organization = Organization::with(['building', 'activities'])->find($organizationId);
        if ($organization) {
            return response()->json(['organization' => $organization]);
        }
        return response()->json(['error' => 'Organization not found'], 404);
    }

    public function searchOrganizationsByActivity($activityId)
    {
        $activityIds = Activities::where('parent_id', $activityId)->pluck('id')->toArray();
        $activityIds[] = $activityId;

        $organizations = Organization::with('activities')->whereHas('activities', function ($query) use ($activityIds) {
            $query->whereIn('activities_id', $activityIds);
        })->get();

        return response()->json(['organizations' => $organizations]);
    }

    public function searchOrganizationsByName(Request $request)
    {
        $name = $request->validate([
            'name' => 'required',
        ]);
        $organizations = Organization::where('name', 'LIKE', "%{$name}%")->get();
        return response()->json(['organizations' => $organizations]);
    }
}
