<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionType;

class DropdownController extends Controller
{
    /**
     * Formats a list of transaction types for select.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transactionTypes(Request $request)
    {
        // Load the transaction types
        $transactionTypes = TransactionType::query();
        if ($request->has('search')) {
            $transactionTypes = $transactionTypes->where('name', 'LIKE', "%{$request->get('search')}%");
        }

        if ($request->has('department_id')) {
            $transactionTypes = $transactionTypes->where('department_id', $request->get('department_id'))->orderBy('sort');
        }

        $transactionTypes = $transactionTypes->get();

        // Format for select
        $data['results'] = [];
        foreach ($transactionTypes as $transactionType) {
            $data['results'][] = [
                'id' => $transactionType->id,
                'text' => $transactionType->name
            ];
        }

        // We're done
        return response()->json($data);
		
    }
}
