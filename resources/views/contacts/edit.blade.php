@extends('layouts.main')
@section('title', 'Contact App | Edit Contact')
@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            Settings
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action active">Profile</span></a>
                            <a href="#" class="list-group-item list-group-item-action">Account</span></a>
                            <a href="#" class="list-group-item list-group-item-action">Import & Export</span></a>
                        </div>
                    </div>
                </div><!-- /.col-md-3 -->

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header card-title">
                            <strong>Edit Profile</strong>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('contacts.update', $contact) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @include('contacts._form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
