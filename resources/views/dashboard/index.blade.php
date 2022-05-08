@extends('dashboard.app')

@section('content')

@include('dashboard.includes.messages')		

        @if (Auth::user()->role=='teacher')
            @include('dashboard.includes.index_teacher') 
        @else
            @include('dashboard.includes.index_student') 
        @endif

@endsection


