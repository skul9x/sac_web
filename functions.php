<?php
//======================================================================
//  FUNCTION & VARIABLE DECLARATION
//======================================================================

// Define the single data file name
$dataFile = "battery_data.json";

// Set timezone to GMT+7 for all date/time functions
date_default_timezone_set('Asia/Ho_Chi_Minh');

/**
 * Gets data from the JSON file.
 * @param string $filename The name of the JSON file.
 * @return array The associative array of data.
 */
function getData($filename) {
    if (!file_exists($filename)) {
        // Create the file with default values if it doesn't exist.
        $defaultData = ['ah_now' => 0, 'ah_70' => 70]; // Start with ah_70 at 70 for a new setup
        file_put_contents($filename, json_encode($defaultData, JSON_PRETTY_PRINT));
        return $defaultData;
    }
    $jsonData = file_get_contents($filename);
    return json_decode($jsonData, true);
}

/**
 * Updates the JSON file with new data.
 * @param string $filename The name of the file to write to.
 * @param array $data The associative array of data to write.
 */
function updateData($filename, $data) {
    // Use JSON_PRETTY_PRINT to make the file human-readable
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

// Variable to hold pop-up messages for the user.
$alert_message = '';

// Get all data at once.
$allData = getData($dataFile);
$ahNowOld = $allData['ah_now']; // Get the previous 'ah_now' value to calculate consumption.


//======================================================================
//  PROCESS DATA UPDATE REQUESTS (POST Handlers)
//======================================================================

// --- Primary Update Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_main'])) {
    // Update ah_now
    if (isset($_POST["ah_now"])) {
        $ahNowNew = floatval($_POST["ah_now"]);
        $allData['ah_now'] = $ahNowNew;

        // Provide feedback on consumption if value increased
        if ($ahNowNew > $ahNowOld) {
            $ahConsumed = $ahNowNew - $ahNowOld;
            $percentageUsed = round(($ahConsumed / 70) * 100, 1);
            $alert_message = "⚡️ Bạn vừa sử dụng hết {$percentageUsed}% pin ({$ahConsumed}Ah).";
        }
    }
    
    // Update ah_70 (manual override)
    if (isset($_POST["ah_70"])) {
        $allData['ah_70'] = floatval($_POST["ah_70"]);
         if (empty($alert_message)) {
            $alert_message = "✅ Đã cập nhật mốc 0% thủ công.";
         }
    }
    
    // Calculate distance if provided
    if (!empty($_POST["distance"]) && isset($ahNowNew) && $ahNowNew != $ahNowOld && ($ahNowNew - $ahNowOld) > 0) {
        $distance = floatval($_POST["distance"]);
        $ahConsumed = $ahNowNew - $ahNowOld;
        $distancePerAh = $distance / $ahConsumed;
        $totalDistance = $distancePerAh * 70;
    }

    // Write all changes to the file once
    updateData($dataFile, $allData);
}

// --- "Fully Charged" (Add 70Ah) Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["add_70"])) {
    $allData['ah_70'] = $allData['ah_now'] + 70;
    updateData($dataFile, $allData);
    $alert_message = "🔋 Sạc đầy thành công! Mốc hết pin mới là {$allData['ah_70']}Ah.";
}

// --- "Add Custom Ah" Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["add_custom_ah"])) {
    $charging_current = floatval($_POST["charging_current"]);
    $start_time = $_POST["start_time"];
    $end_time   = $_POST["end_time"];

    // Calculate duration in hours
    $start_minutes = intval(substr($start_time, 0, 2)) * 60 + intval(substr($start_time, 3, 2));
    $end_minutes = intval(substr($end_time, 0, 2)) * 60 + intval(substr($end_time, 3, 2));
    $diff_minutes = $end_minutes - $start_minutes;
    if ($diff_minutes < 0) {
        $diff_minutes += 1440; // Add a day in minutes if it crosses midnight
    }
    $duration_hours = round($diff_minutes / 60, 2);
    
    if ($charging_current > 0 && $duration_hours > 0) {
        $custom_ah = round($charging_current * $duration_hours, 1);
        $allData['ah_70'] += $custom_ah;
        updateData($dataFile, $allData);
        $alert_message = "✅ Đã cộng thêm {$custom_ah}Ah vào pin. Mốc hết pin mới là {$allData['ah_70']}Ah.";
    } else {
        $alert_message = "❌ Dữ liệu không hợp lệ để cộng Ah.";
    }
}


//======================================================================
//  READ FINAL DATA FROM FILES
//======================================================================
// Re-read data in case it was modified by a POST request
$currentData = getData($dataFile);
$ahNow = $currentData['ah_now'];
$ah70 = $currentData['ah_70'];


//======================================================================
//  CALCULATE DISPLAY VARIABLES
//======================================================================

// Calculate remaining battery percentage
$batteryPercentage = 0;
if ($ah70 > $ahNow) {
    $batteryPercentage = round((($ah70 - $ahNow) / 70 * 100), 1);
}
$batteryPercentage = max(0, min(100, $batteryPercentage)); // Clamp between 0 and 100

// Determine battery bar color class
$batteryClass = '';
if ($batteryPercentage <= 20) {
    $batteryClass = 'danger';
} elseif ($batteryPercentage <= 35) {
    $batteryClass = 'warning';
} else {
    $batteryClass = 'good';
}

// Calculate Ah used since last full charge
$ahUsedSinceFull = round(70 * (100 - $batteryPercentage) / 100, 1);


//======================================================================
//  HANDLE READ-ONLY CALCULATION REQUESTS
//======================================================================

// --- "Estimate Time to Full" Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["calculate_time_to_full"])) {
    $charge_current = floatval($_POST["charge_current_for_calc"]);
    if ($charge_current > 0 && $ahUsedSinceFull > 0) {
        $time_in_hours = $ahUsedSinceFull / $charge_current;
        $hours = floor($time_in_hours);
        $minutes = round(($time_in_hours - $hours) * 60);
        $alert_message = "⏳ Với dòng sạc {$charge_current}A, cần khoảng {$hours} giờ {$minutes} phút để sạc đầy.";
    } else if ($ahUsedSinceFull <= 0) {
        $alert_message = "✅ Pin đã đầy!";
    } else {
        $alert_message = "❌ Vui lòng nhập dòng sạc lớn hơn 0.";
    }
}

// --- "Calculate Required Current" Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["calculate_required_current"])) {
    $full_by_time_str = $_POST["full_by_time"];
    
    if ($ahUsedSinceFull <= 0) {
        $alert_message = "✅ Pin đã đầy, không cần sạc.";
    } elseif (!empty($full_by_time_str)) {
        $now = time();
        $target_time = strtotime($full_by_time_str);

        // Handle case where target time is on the next day
        if ($target_time < $now) {
            $target_time = strtotime('+1 day', $target_time);
        }

        $seconds_diff = $target_time - $now;
        
        if ($seconds_diff > 0) {
            $hours_diff = $seconds_diff / 3600;
            $required_current = round($ahUsedSinceFull / $hours_diff, 2);
            $alert_message = "💡 Để pin đầy lúc {$full_by_time_str}, bạn cần sạc với dòng khoảng {$required_current}A.";
        } else {
            $alert_message = "❌ Thời gian chọn phải ở trong tương lai.";
        }
    } else {
        $alert_message = "❌ Vui lòng chọn thời gian muốn sạc đầy.";
    }
}


// --- "Simulate Charge Gain" Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["calculate_simulation"])) {
    $sim_current = floatval($_POST["sim_charging_current"]);
    $sim_start_time = $_POST["sim_start_time"];
    $sim_end_time = $_POST["sim_end_time"];

    // Calculate duration
    $start_minutes = intval(substr($sim_start_time, 0, 2)) * 60 + intval(substr($sim_start_time, 3, 2));
    $end_minutes = intval(substr($sim_end_time, 0, 2)) * 60 + intval(substr($sim_end_time, 3, 2));
    $diff_minutes = $end_minutes - $start_minutes;
     if ($diff_minutes < 0) {
        $diff_minutes += 1440; // Crosses midnight
    }

    if($sim_current > 0 && $diff_minutes > 0) {
        $duration_hours = $diff_minutes / 60;
        $added_ah = $sim_current * $duration_hours;
        $percentage_gained = ($added_ah / 70) * 100;
        $new_total_percentage = round(min(100, $batteryPercentage + $percentage_gained), 1);
        $alert_message = "📈 Sau khi sạc, pin sẽ đạt khoảng {$new_total_percentage}%.";
    } else {
        $alert_message = "❌ Dữ liệu không hợp lệ để tính toán.";
    }
}
?>
