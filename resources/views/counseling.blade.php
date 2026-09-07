@extends('layouts.app')

@section('title', 'Cognitive Counseling Center')

@section('content')
<div class="container-fluid px-2 px-md-4 py-2 flex-grow-1 d-flex flex-column" style="max-width: 1400px; height: calc(100vh - 85px);">
    <chat-interface :initial-session-token="'{{ $sessionToken ?? '' }}'" class="flex-grow-1 d-flex flex-column h-100"></chat-interface>
</div>
@endsection
