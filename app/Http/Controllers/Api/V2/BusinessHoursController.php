<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\BusinessWorkingHours;
use App\Models\BusinessDateNonWorking;
use App\Models\Shop;
use Illuminate\Http\Request;

class BusinessHoursController extends Controller
{
    /**
     * Get business working hours and non-working dates for a specific shop
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBusinessHours(Request $request)
    {
        // Validate shop_id is provided
        if (!$request->has('shop_id')) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'Shop ID is required'
            ], 400);
        }

        $shopId = $request->shop_id;

        // Check if shop exists
        $shop = Shop::find($shopId);
        if (!$shop) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Shop not found'
            ], 404);
        }

        // Get working hours sorted by day of week
        $businessWorkingHours = BusinessWorkingHours::where('shop_id', $shopId)
            ->get()
            ->sortBy(function ($workingHours) {
                return array_search($workingHours->dia_semana, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            })
            ->values();

        // Get non-working dates sorted by date
        $businessDateNonWorkings = BusinessDateNonWorking::where('shop_id', $shopId)
            ->orderBy('fecha_no_laborable', 'asc')
            ->get();

        // Format the data for API response
        $formattedWorkingHours = $businessWorkingHours->map(function ($hours) {
            return [
                'shop_id' => $hours->shop_id,
                'day' => $hours->dia_semana,
                'day_translated' => $this->translateDayName($hours->dia_semana),
                'is_open' => (bool)$hours->laborable,
                'opening_time' => $hours->hora_inicio,
                'closing_time' => $hours->hora_fin
            ];
        });

        $formattedNonWorkingDates = $businessDateNonWorkings->map(function ($date) {
            return [
                'date' => $date->fecha_no_laborable,
                'reason' => $date->nota
            ];
        });

        // Add shop basic info
        $shopInfo = [
            'shop_id' => $shop->id,
            'name' => $shop->name,
            'address' => $shop->address,
            'phone' => $shop->phone,
            'logo' => $shop->logo ? uploaded_asset($shop->logo) : null
        ];

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => [
                'shop' => $shopInfo,
                'working_hours' => $formattedWorkingHours,
                'non_working_dates' => $formattedNonWorkingDates
            ]
        ]);
    }

    /**
     * Helper method to translate day names from English to Spanish
     * 
     * @param string $dayName
     * @return string
     */
    private function translateDayName($dayName)
    {
        $translations = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        return $translations[$dayName] ?? $dayName;
    }
}