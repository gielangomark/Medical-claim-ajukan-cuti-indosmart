<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Kita definisikan daftar departemen resmi di satu tempat
    private $departments = [
        'IT' => 'Teknologi Informasi',
        'HRD' => 'Sumber Daya Manusia (HRD)',
        'finance' => 'Keuangan',
        'Marketing' => 'Pemasaran',
        'Operation' => 'Operasional',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
        {
            $query = User::where('department', '!=', 'hrd');

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
                });
            }

            // Filter ini sekarang akan bekerja dengan benar karena value-nya 'it', 'finance', dll.
            if ($request->filled('department')) {
                $query->where('department', $request->input('department'));
            }

            $employees = $query->latest()->paginate(15);

            if ($request->ajax()) {
                return response()->json([
                    'table_html' => view('hrd.users._employee_table', compact('employees'))->render(),
                ]);
            }

            // Kirim daftar departemen yang sudah kita definisikan di controller
            return view('hrd.users.index', [
                'employees' => $employees,
                'departments' => $this->departments
            ]);
        }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kirim daftar departemen yang sama ke form 'create'
        return view('hrd.users.create', [
            'departments' => $this->departments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:255|unique:users,nik',
            'email' => 'required|string|email|max:255|unique:users,email',
            'department' => 'required|string|in:' . implode(',', array_keys($this->departments)),
            'gender' => 'required|in:pria,wanita',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('hrd.users.index')->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Tidak digunakan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('familyMembers');
        
        return view('hrd.users.edit', [
            'user' => $user,
            'departments' => $this->departments
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:255|unique:users,nik,' . $user->id,
            'department' => 'required|string|in:' . implode(',', array_keys($this->departments)),
            'gender' => 'required|in:pria,wanita',
            'marital_status' => 'required|in:lajang,menikah',
        ]);

        $user->update($validatedUser);

        $user->familyMembers()->delete();

        if ($request->marital_status === 'menikah' && $request->has('family')) {
            $validatedFamily = $request->validate([
                'family.*.name' => 'required|string|max:255',
                'family.*.relationship' => 'required|string|in:suami,istri,anak',
                'family.*.date_of_birth' => 'nullable|date',
            ]);

            foreach ($validatedFamily['family'] as $memberData) {
                $user->familyMembers()->create($memberData);
            }
        }

        return redirect()->route('hrd.users.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('hrd.users.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
