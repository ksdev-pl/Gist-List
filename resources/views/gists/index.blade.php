@extends('__base')

@section('content')
    <gists-index-page :gists="{{ $gists }}"></gists-index-page>
@endsection
