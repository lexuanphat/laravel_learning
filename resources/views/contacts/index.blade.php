@extends('layouts.main')
@section('title', 'Contact App | All Contacts')
@section('content')

    <main class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-title">
                            <div class="d-flex align-items-center">
                                <h2 class="mb-0">All Contacts @if (request()->query('trash'))
                                        <small>(In Trash)</small>
                                    @endif
                                </h2>
                                <div class="ml-auto">
                                    <a href="{{ route('contacts.create') }}" class="btn btn-success"><i
                                            class="fa fa-plus-circle"></i> Add New</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                    @if (session('undoRoute'))
                                        <form action="{{ session('undoRoute') }}" method="POST" style="display: inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn alert-link">Undo</button>
                                        </form>
                                    @endif
                                </div>

                            @endif
                            @include('contacts._filter')
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Company</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $showTrashedButton = request()->query('trash') ? true : false;
                                    @endphp
                                    @forelse ($contacts as $index => $contact)
                                        @include('contacts._contact', ['contact' => $contact])
                                    @empty
                                        @include('contacts._empty')
                                    @endforelse
                                    {{-- @each('contacts._contact', $contacts, 'contact', 'contacts._empty') --}}

                                </tbody>
                            </table>

                            {{ $contacts->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
