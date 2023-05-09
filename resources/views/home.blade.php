@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table">
                        <tr>
                            
                            <th>Name</th>
                            <th>Email</th>
                            <th>Referral Code</th>
                            <th>Point</th>
                        </tr>

                           @if(!empty($userList))
                            @foreach($userList as $user)
                            <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->referral_code}}</td>
                            <td>{{$user->point}}</td>
                            </tr>
                            @endforeach
                            @endif
                    </table>

                    <div class="userPagination pull-right" style="float: right">
{{ $userList->links("pagination::bootstrap-4") }}
</div>
                 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
