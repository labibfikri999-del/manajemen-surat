<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dokumen;
use Illuminate\Support\Facades\Auth;

class BalasanApiController extends Controller
{
    // Ambil jumlah balasan baru untuk user
    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        $count = DB::table('balasan_read_status')
            ->where('user_id', $user->id)
            ->where('terbaca', false)
            ->count();
        return response()->json(['count' => $count]);
    }

    // Ambil daftar dokumen balasan baru
    public function unreadList(Request $request)
    {
        $user = Auth::user();
        $rows = DB::table('balasan_read_status')
            ->where('user_id', $user->id)
            ->where('terbaca', false)
            ->pluck('dokumen_id');
        $dokumens = Dokumen::whereIn('id', $rows)
            ->whereNotNull('balasan_file')
            ->where('balasan_file', '!=', '')
            ->get();
        return response()->json(['dokumens' => $dokumens]);
    }

    // Tandai balasan sudah dibaca
    public function markRead(Request $request, $dokumenId)
    {
        $user = Auth::user();
        DB::table('balasan_read_status')
            ->where('dokumen_id', $dokumenId)
            ->where('user_id', $user->id)
            ->update(['terbaca' => true, 'updated_at' => now()]);
        return response()->json(['success' => true]);
    }
}
