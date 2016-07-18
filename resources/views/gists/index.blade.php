@extends('__base')

@section('store')
    <script>
        window.store = {
            gists: {!! $gists !!},
            tags: {!! $tags !!},
            columns: [
                {
                    key: 'type',
                    name: 'Type'
                },
                {
                    key: 'tags',
                    name: 'Tags'
                },
                {
                    key: 'description',
                    name: 'Description'
                },
                {
                    key: 'owner',
                    name: 'Owner'
                },
                {
                    key: 'created',
                    name: 'Created'
                },
                {
                    key: 'updated',
                    name: 'Updated'
                }
            ],
            user: {!! Auth::user()->toJson() !!},
            searchQuery: ''
        }
    </script>
@endsection

@section('styles')
    <style>
        #table-th-created, #table-th-updated {
            width: 150px;
        }
    </style>
@endsection

@section('scripts')

@endsection

@section('content')


    <gists-index-page></gists-index-page>
@endsection
