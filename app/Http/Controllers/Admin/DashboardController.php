<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Models\Room\RoomType;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $inventory = [
            'room_types' => RoomType::query()->count(),
            'physical_rooms' => Room::query()->count(),
            'active_rooms' => Room::query()->where('operational_status', 'active')->count(),
            'inactive_rooms' => Room::query()->where('operational_status', 'inactive')->count(),
            'maintenance_rooms' => Room::query()->where('operational_status', 'maintenance')->count(),
            'out_of_service_rooms' => Room::query()->where('operational_status', 'out_of_service')->count(),
            'available_rooms' => Room::query()
                ->where('operational_status', 'active')
                ->where('housekeeping_status', 'clean')
                ->count(),
        ];

        return view('admin.pages.dashboard', compact('inventory'));
    }
}
