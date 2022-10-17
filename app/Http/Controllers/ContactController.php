<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactController extends Controller
{

    protected $companyRepo;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepo = $companyRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Contact::query();

        if (request()->query('trash')) {
            $query->onlyTrashed();
        }

        $contacts = $query->latest()->where(function ($q) {
            $companyId = request()->query('company_id');

            if ($companyId) {
                $q->where('company_id', $companyId);
            }
        })->where(function ($q) {
            $search = request()->query('search');

            if ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%');
                $q->orWhere('last_name', 'LIKE', '%' . $search . '%');
                $q->orWhere('email', 'LIKE', '%' . $search . '%');
            }
        })->paginate(10);

        $companies = $this->companyRepo->pluck();

        return view('contacts.index', [
            'contacts' => $contacts,
            'companies' => $companies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = $this->companyRepo->pluck();

        $contact = new Contact();

        return view('contacts.create', [
            'companies' => $companies,
            'contact' => $contact,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:50',
                'min:2',
            ],
            'last_name' => [
                'required',
                'string',
                'max:50',
                'min:2',
            ],
            'email' => [
                'required',
                'email'
            ],
            'phone' => [
                'nullable',
            ],
            'address' => [
                'nullable',
            ],
            'company_id' => [
                'required',
                'exists:companies,id',
            ],
        ]);
        Contact::create($request->all());
        // return response()->json([
        //     'success' => true,
        //     'data' => $contact,
        // ]);
        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        abort_if(empty($contact), 404);

        return view('contacts.show', [
            'contact' => $contact,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companies = $this->companyRepo->pluck();

        $contact = Contact::findOrFail($id);

        return view('contacts.edit', [
            'contact' => $contact,
            'companies' => $companies,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:50',
                'min:2',
            ],
            'last_name' => [
                'required',
                'string',
                'max:50',
                'min:2',
            ],
            'email' => [
                'required',
                'email'
            ],
            'phone' => [
                'nullable',
            ],
            'address' => [
                'nullable',
            ],
            'company_id' => [
                'required',
                'exists:companies,id',
            ],
        ]);
        $contact->update($request->all());
        // return response()->json([
        //     'success' => true,
        //     'data' => $contact,
        // ]);
        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();

        $redirect = request()->query('redirect');
        return ($redirect ? redirect()->route($redirect) : back())
            ->with('message', 'Contact has been moved to trashed')
            ->with('undoRoute', $this->getUndoRoute('contacts.restore', $contact));
    }

    public function restore($id)
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);

        $contact->restore();

        return back()
            ->with('message', 'Contact has been restored from trash')
            ->with('undoRoute', $this->getUndoRoute('contacts.destroy', $contact));
    }

    protected function getUndoRoute($name, $resource)
    {
        return request()->missing('undo') ?  route($name, [$resource->id, 'undo' => true]) : null;
    }

    public function forceDelete($id)
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);

        $contact->forceDelete();

        return back()
            ->with('message', 'Contact has been removed successfully');
    }
}
