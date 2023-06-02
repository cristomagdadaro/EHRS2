<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HematologyRequest;
use App\Http\Resources\HematologyCollection;
use App\Http\Resources\HematologyResource;
use App\Models\Hematology;
use App\Models\HematologyRecord;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HematologyApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new HematologyCollection(HematologyRecord::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HematologyRequest $request)
    {
        $newHematology = Hematology::create($request->validated());
        if ($newHematology){
            $newRecord = HematologyRecord::create([
                'hematology_id' => $newHematology->id,
                'infirmary_id' => $request->infirmary_id,
                'age' => $request->age,
                'sex' => $request->sex,
                'ward' => $request->ward,
                'or_no' => $request->or_no,
                'rqst_physician' => $request->rqst_physician,
                'medical_technologist' => $request->medical_technologist,
                'pathologist' => $request->pathologist,
                'hospital_no' => $request->hospital_no,
                'remarks' =>  $request->remarks,
                'status' =>  $request->status,
            ]);
            if ($newRecord){
                return response()->json([
                    'data' => (new HematologyResource($newRecord)),
                    'notification' => [
                        'id' => uniqid(),
                        'show' => true,
                        'type' => 'success',
                        'message' => 'Successfully created Hematology with id '.$newRecord->id,
                    ]
                ])->setStatusCode(201);
            }
        }
        return response()->json([
            'data' => (new HematologyResource($newHematology)),
            'notification' => [
                'id' => uniqid(),
                'show' => true,
                'type' => 'failed',
                'message' => 'Failed to create Hematology record',
            ]
        ])->setStatusCode(500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new HematologyResource(Hematology::with('hematologyRecord')->findOrFail($request->id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HematologyRequest $request)
    {
        $hematologyRecord = HematologyRecord::findOrFail($request->id);
        $update = $hematologyRecord->update($request->validated());
        if ($update){
            $hematologyRecord->hematology->update($request->validated());
            return response()->json([
                'data' => (new HematologyResource($hematologyRecord)),
                'notification' => [
                    'id' => uniqid(),
                    'show' => true,
                    'type' => 'success',
                    'message' => 'Successfully updated Hematology with id '.$hematologyRecord->id,
                ]
            ])->setStatusCode(200);
        }
        return response()->json([
            'data' => (new HematologyResource($hematologyRecord)),
            'notification' => [
                'id' => uniqid(),
                'show' => true,
                'type' => 'failed',
                'message' => 'Failed to update Hematology record',
            ]
        ])->setStatusCode(500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = explode(',', $request->id);
        HematologyRecord::destroy($id);
        $temp = Hematology::destroy($id);
        return response()->json([
            'notification' => [
                'id' => uniqid(),
                'show' => true,
                'type' => $temp?'success':'failed',
                'message' => $temp?'Successfully deleted '.$temp.' Hematology record/s':'Failed to delete Hematology record with id '. $request->id,
            ]
        ]);
    }

    /**
     * Get all the departments to be displayed in the datatable, that can handle the search, pagination, and sorting.
     */
    public function tableApi(Request $request): JsonResponse
    {
        $query = HematologyRecord::with('hematology')->where('id', '!=', null);
        $totalRecords = $query->count();
        if ($request->has('search')) {
            $search = $request->input('search');
            $searchBy = $request->input('search_by', 'id');
            $query->where(function ($q) use ($search, $searchBy) {
                if ($searchBy == '*') {
                    $q->where('id', 'like', '%' . $search . '%')
                        ->orWhere('infirmary_id', 'like', '%' . $search . '%')
                        ->orWhere('age', 'like', '%' . $search . '%')
                        ->orWhere('sex', 'like', '%' . $search . '%')
                        ->orWhere('ward', 'like', '%' . $search . '%')
                        ->orWhere('or_no', 'like', '%' . $search . '%')
                        ->orWhere('rqst_physician', 'like', '%' . $search . '%')
                        ->orWhere('hospital_no', 'like', '%' . $search . '%');
                } else {
                    $q->where('hematology_records.' . $searchBy, 'like', '%' . $search . '%');
                }
            });
        }

        // Handle sorting
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('sort_dir', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginator->items(),
            'totalCount' => $paginator->total(),
            'totalPages' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),
            'totalRecords' => $totalRecords,
        ]);
    }

    /**
     * Import the departments from the csv file.
     */
    public function import(Request $request): JsonResponse
    {
        $data = $request->all();
        $successCount = 0;
        $failedCount = 0;
        $response = [
            'successCount' => 0,
            'failedCount' => null,
            'data' => [],
        ];

        $validator = new HematologyRequest();

        foreach ($data as $row) {
            $validation = Validator::make($row, $validator->rules());

            if ($validation->fails()) {
                $failedCount++;
                $row['errors'] = $validation->errors();
                $response['data'][] = $row;
            } else {
                try {
                    $hematology = Hematology::create($row);
                    if ($hematology) {
                        $hematology->hematologyRecord()->create($row);
                        $successCount++;
                    } else {
                        $failedCount++;
                    }
                } catch (Exception $e) {
                    $failedCount++;
                }
            }
        }

        $response['successCount'] = $successCount;
        $response['failedCount'] = $failedCount;

        $notificationType = $failedCount ? 'warning' : 'success';
        $notificationMessage = $failedCount ? "Failed to import Hematology $failedCount rows out of " . ($failedCount + $successCount) : 'Successfully imported Hematology without errors';

        return response()->json([
            $response,
            'notification' => [
                'id' => uniqid(),
                'show' => true,
                'type' => $notificationType,
                'message' => $notificationMessage,
            ]
        ]);
    }

}
