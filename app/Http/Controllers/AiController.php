<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{


    public function index(): JsonResponse
    {
        $search = "
        ### MySQL tables, with their properties:
        #
        # users(id, first_name, last_name, username, password, role, created_at, updated_at)
        # patients(id, first_name, last_name, birth_date, place_of_birth, gender, address, nationality, phone_number, family_situation, emergency_contact_name, emergency_contact_number, created_at, updated_at)
        # complementary_examinations(id, type, medical_record_id, result, created_at, updated_at)
        # observations(id, medical_record_id, name, created_at, updated_at)
        # medical_records(id, patient_id, user_id, medical_specialty, condition_description, state_upon_enter, standard_treatment, state_upon_exit, bed_number, patient_entry_date, patient_leaving_date, created_at, updated_at)
        # images(id, path, observation_id, created_at, updated_at)
        # monitoring_sheets(id, record_id, filled_by_id, filling_date, urine, blood_pressure, weight, temperature, progress_report, created_at, updated_at)
        # medicines(id, name, category, description, price, quantity, is_pharmaceutical, manufacturer, supplier, expiration_date, created_at, updated_at)
        # treatments(id, monitoring_sheet_id, medicine_id, name, dose, type, created_at, updated_at)
        # medicine_requests(id, user_id, record_id, medicine_id, quantity, status, review, created_at, updated_at)  // status: Pending, Approved, Rejected
        # mandatory_declaration(id, diagnosis, detail, medical_record_id, created_at, updated_at)
        #
        ### A query to get  ";
        $q = \request()->get('q');
        if ($q) {
            $search .= $q;
        } else {
            return response()->json(["message" => "query is required"], 200, array(), JSON_PRETTY_PRINT);
        }

        $data = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
        ])
            ->post("https://api.openai.com/v1/chat/completions", [
                "model" => "gpt-3.5-turbo",
                'messages' => [
                    [
                        "role" => "user",
                        "content" => $search
                    ]
                ],
                'temperature' => 0,
                "max_tokens" => 150,
                "top_p" => 1.0,
                "frequency_penalty" => 0,
                "presence_penalty" => 0,
                "stop" => ["#;"],
            ])
            ->json();


        // print array of data
//        dd($data['choices'][0]['message']['content']);
        $query = $data['choices'][0]['message']['content'];
        error_log($query);


        try {
            if ($this->checkQuery($query)) {
                $result = DB::select($query);
                return response()->json($result, 200, array(), JSON_PRETTY_PRINT);
            } else {
                return response()->json(["message" => "You are not allowed to do this query", 'result' => $query], 200, array(), JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json(["message" => "internal error", 'result' => $query], 500, array(), JSON_PRETTY_PRINT);
        }
    }


    // a function to check for malicious sql query ( drop the database , delete the table , etc )
    function checkQuery($query) {
        $query = trim($query);
        // Check for potentially harmful SQL keywords:
        $harmfulKeywords = array('delete', 'drop', 'truncate', 'alter', 'insert', 'update', 'create');
        foreach ($harmfulKeywords as $keyword) {
            if (stripos($query, $keyword) === 0) {
                return false;
            }
        }

        // Check for SQL injection attempts:
        $injectionKeywords = array('union', 'select');
        foreach ($injectionKeywords as $keyword) {
            if (stripos($query, $keyword) !== false && stripos($query, $keyword) !== stripos($query, 'SELECT')) {
                return false;
            }
        }

        // Check if query starts with "select":
        if (stripos($query, 'select') === 0) {
            return true;
        } else {
            return false;
        }
    }
}
