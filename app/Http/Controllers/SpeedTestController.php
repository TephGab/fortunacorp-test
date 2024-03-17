<?php

namespace App\Http\Controllers;

use FastSpeedTest\Api;
use Illuminate\Http\Request;

class SpeedTestController extends Controller
{
    public function performSpeedTest()
    {
        try {
            $speedTest = n

            // Perform the speed test
            $speedData = $speedTest->getSpeed();

            // Check if download speed is at least 10 Mbps (you can adjust the threshold as needed)
            if ($speedData['downloadSpeed'] >= 10) {
                return response()->json($speedData);
            } else {
                return response()->json(['error' => 'Your internet connection speed is too slow.'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
