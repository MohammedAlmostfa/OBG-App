<!-- <?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Exception;

class ItemService
{
    public function StoreItem($data)
    {
try{$item =Item::created($data);
return [
    'status' => 500,
    'message' => [
        'errorDetails' => __('item.create_sucssful'),
    ],
];

}
catch (Exception $e) {
    // Log the error if an exception occurs
    Log::error('Error in getCities: ' . $e->getMessage());

    // Return an error message and status
    return [
        'status' => 500,
        'message' => [
            'errorDetails' => __('general.failed'),
        ],
    ];
}
    }
    public function updateItem(Item $item,$data)
    {

    try {
    } catch (Exception $e) {
        // Log the error if an exception occurs
        Log::error('Error in getCities: ' . $e->getMessage());

        // Return an error message and status
        return [
            'status' => 500,
            'message' => [
                'errorDetails' => __('general.failed'),
            ],
        ];
    }
}
    public function softdeletItem(Item $item)
    {

    try {
    } catch (Exception $e) {
        // Log the error if an exception occurs
        Log::error('Error in getCities: ' . $e->getMessage());

        // Return an error message and status
        return [
            'status' => 500,
            'message' => [
                'errorDetails' => __('general.failed'),
            ],
        ];
    }
}
    public function forcedeletItem($id)
    {
    try {
    } catch (Exception $e) {
        // Log the error if an exception occurs
        Log::error('Error in getCities: ' . $e->getMessage());

        // Return an error message and status
        return [
            'status' => 500,
            'message' => [
                'errorDetails' => __('general.failed'),
            ],
        ];
    }
}
}
