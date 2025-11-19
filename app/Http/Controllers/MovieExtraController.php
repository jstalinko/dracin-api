<?php
use Illuminate\Support\Str; // Pastikan ini ada di atas class
use App\Http\Controllers\Controller;

class MovieExtraController extends Controller
{
    public function handleDynamicCall(string $method)
    {
        // $method adalah string dari URL, misal: 'get-list' atau 'upload_image'
        
        // --- INI KUNCI OTOMATIS PARSINGNYA ---
        $methodName = Str::camel($method); 
        // -------------------------------------
        
        // Contoh:
        // Input URL segment: 'get-list'  -> $methodName = 'getList'
        // Input URL segment: 'get_detail' -> $methodName = 'getDetail'

        if (method_exists($this, $methodName) && !in_array($methodName, ['handleDynamicCall', '__construct'])) {
            
            // Panggil method yang sudah diubah formatnya
            return $this->$methodName(request()); 
        }

        abort(404, 'API Method not found or forbidden.');
    }
    
    // Method di controller HARUS camelCase
    public function getList($request) 
    {
        return response()->json(['message' => 'Data list berhasil diambil.']);
    }
}