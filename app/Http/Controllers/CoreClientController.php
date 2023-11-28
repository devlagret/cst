<?php
namespace App\Http\Controllers;
use App\Models\CoreClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\CoreClientDataTable;
use App\Models\CoreClientMember;
use Illuminate\Support\Facades\Session;
class CoreClientController extends Controller
{
    public function index(CoreClientDataTable $table)
    {
        Session::forget('client-data');
        Session::forget('client-token');
        Session::forget('client-member-data');
        return $table->render('content.CoreClient.List.index');
    }
    public function add()
    {
        if (empty(Session::get('client-token'))) {
            Session::put('client-token', Str::uuid());
        }
        $sessiondata = Session::get('client-data');
        $member = Session::get('client-member-data') ?? [];
        return view('content.CoreClient.Add.index', compact('sessiondata', 'member'));
    }
    public function processAdd(Request $request)
    {
        $token =  Session::get('client-token');
        $member =  Session::get('client-member-data');
        if (empty(Session::get('client-token'))) {
            return redirect()->route('client.index')->with(['pesan' => 'Client berhasil dimasukan*', 'alert' => 'success']);
        }
        $request->validate(['name' => 'required', 'address' => 'required', 'contact_person' => 'required', 'phone' => 'required'], [
            'name.required' => 'Masukan Nama Client!',
            'address.required' => 'Masukan Alamat Client!',
            'contact_person.required' => 'Masukan Contact Person Client!',
            'phone.required' => 'Masukan Alamat Client!',
        ]);
        try {
            DB::beginTransaction();
            CoreClient::create([
                'name' => $request->name,
                'address' => $request->address,
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'email' => $request->email,
                'client_token' => $token
            ]);
            $client = CoreClient::where('client_token', $token)->latest()->first();
            if (!empty($member)) {
                foreach ($member as $key => $value) {
                    CoreClientMember::create([
                        'client_id' => $client->client_id,
                        'name' => $value['name'],
                        'position' => $value['position'],
                        'phone' => $value['phone']
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('client.index')->with(['pesan' => 'Client berhasil dimasukan', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            dd($e);
            return redirect()->route('client.index')->with(['pesan' => 'Client gagal dimasukan', 'alert' => 'error']);
        }
    }
    public function detail($client_id)
    {
        $sessiondata = Session::get('client-data');
        $data = CoreClient::with('member')->find($client_id);
        return view('content.CoreClient.Detail.index', compact('sessiondata', 'data'));
    }
    public function edit($client_id)
    {
        if (empty(Session::get('client-token'))) {
            Session::put('client-token', Str::uuid());
        }
        $sessiondata = Session::get('client-data');
        $data = CoreClient::with('member')->find($client_id);
        $member = Session::get('client-member-data') ?? [];
        if (empty($member)) {
            $memData = collect();
            foreach ($data->member as $key => $a) {
                $row = collect()->put('name', $a->name);
                $row->put('position', $a->position);
                $row->put('phone', $a->phone);
                $memData->put($a->client_member_id, $row);
            }
            Session::put('client-member-data', $memData->toArray());
        }
        $member = Session::get('client-member-data') ?? [];
        return view('content.CoreClient.Edit.index', compact('sessiondata', 'data', 'member'));
    }
    public function processEdit(Request $request)
    {
        if (empty(Session::get('client-token'))) {
            return redirect()->route('client.index')->with(['pesan' => 'Client berhasil dimasukan*', 'alert' => 'success']);
        }
        $request->validate(['name' => 'required', 'address' => 'required', 'contact_person' => 'required', 'phone' => 'required'], [
            'name.required' => 'Masukan Nama Client!',
            'address.required' => 'Masukan Alamat Client!',
            'contact_person.required' => 'Masukan Contact Person Client!',
            'phone.required' => 'Masukan Alamat Client!',
        ]);
        $data = CoreClient::find($request->client_id);
        $member =  collect(Session::get('client-member-data'));
        try {
            DB::beginTransaction();
            $data->name          = $request->name;
            $data->address       = $request->address;
            $data->contact_person = $request->contact_person;
            $data->phone         = $request->phone;
            $data->email         = $request->email;
            $data->save();
            if (!empty($member)) {
                $deleteID=collect();
                foreach ($member as $key => $value) {
                    if (!Str::isUuid($key)) {
                       $deleteID->push($key);
                    }
                }
                if(count($deleteID)){
                    CoreClientMember::where('client_id', $request->client_id)->whereNotIn('client_member_id',$deleteID)->delete();
                }
                foreach ($member as $key => $value) {
                    if (Str::isUuid($key)) {
                        CoreClientMember::create([
                            'client_id' => $request->client_id,
                            'name' => $value['name'],
                            'position' => $value['position'],
                            'phone' => $value['phone']
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('client.index')->with(['pesan' => 'Client berhasil diedit', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            dd($e);
            return redirect()->route('client.index')->with(['pesan' => 'Client gagal diedit', 'alert' => 'error']);
        }
    }
    public function delete($client_id)
    {
        try {
            DB::beginTransaction();
            CoreClient::find($client_id)->delete();
            DB::commit();
            return redirect()->route('client.index')->with(['pesan' => 'Client berhasil dihapus', 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('client.index')->with(['pesan' => 'Client gagal dihapus', 'alert' => 'error']);
        }
    }
    public function addMember(Request $request)
    {
        $id = Str::uuid()->toString();
        $data = collect()->put('name', $request->name);
        $member = collect(Session::get('client-member-data'));
        $data->put('position', $request->position);
        $data->put('phone', $request->phone);
        $member->put($id, $data);
        Session::put('client-member-data', $member->toArray());
        $table = "<tr class='client-member' id='cm-{$id}' data-id='{$id}'>
        <td>" . ($request->length + 1) . "</td>
        <td>{$request->name}</td>
        <td>{$request->position}</td>
        <td>{$request->phone}</td>
        <td class='text-center'><button class='btn btn-sm btn-danger' onclick='deleteMember('{$id}')' type='button'>Hapus</button></td>
        <tr>";
        return response($table);
    }
    public function deleteMember(Request $request)
    {
        try {
            $member = collect(Session::get('client-member-data'));
            Session::put('client-member-data', $member->forget($request->key)->toArray());
            return response()->json(['status' => 1]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => $e]);
        }
    }
    public function elemenAdd(Request $request)
    {
        $data = Session::get('client-data');
        $data[$request->name] = $request->value;
        Session::put('client-data', $data);
        return response(11);
    }
}
