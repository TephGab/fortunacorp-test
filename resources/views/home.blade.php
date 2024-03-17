@extends('layouts.master')

@section('content')
<router-view :user="{{ Auth::user() }}" :userrole="{{ json_encode(Auth::user()->roles->first()) }}"></router-view>
@endsection