<?php

namespace App\Http\Controllers;

use App\Models\AdminRequest;

class RequestController extends Controller
{
    public function approve($id) {
        $requestData = AdminRequest::find($id);
        if ($requestData->status === 'approved' || $requestData->status === 'declined') {
            return response()->json([
                'message' => 'request already approved',
            ]);
        }

        $requestData->status = 'approved';
        $requestData->save();

        return response()->json([
            'status' => 200,
            'message' => 'request approved successfully'
        ]);
    }

    public function decline($id) {
        $requestData = AdminRequest::find($id);
        if ($requestData->status === 'decline' || $requestData->status === 'approved') {
            return response()->json([
                'message' => 'request already declined',
            ]);
        }

        $requestData->status = 'declined';
        $requestData->save();
        $requestData->delete();

        return response()->json([
            'status' => 200,
            'message' => 'request declined successfully'
        ]);
    }

    // public function destroy($id)
    // {
    //     $requestData = AdminRequest::find($id);
    //     $requestData->delete();
    //     return response()->json([
    //         'status' => 204,
    //         'message' => 'request deleted successfully'
    //     ]);
    // }
}
