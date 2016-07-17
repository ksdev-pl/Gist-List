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
    <template id="table-cell-partial">
        <template v-if="column.key == 'tags'">
            <span v-for="tag in rowData[column.key]" class="label tag"
                  v-on:click="searchQuery = tag"
                  v-bind:style="{marginRight: '5px', backgroundColor: $root.tagColors[tag]}">
                @{{ tag }}
            </span>
        </template>
        <template v-if="['tags'].indexOf(column.key) == -1">
            @{{ rowData[column.key] }}
        </template>
    </template>

    <left-menu :gists="store.gists"
               :user="store.user"
               :tags="store.tags"
               :search-query.sync="store.searchQuery">
    </left-menu>

    <div class="main">
        <div class="container-fluid">
            <table-grid :rows-data="store.gists"
                        :columns="store.columns"
                        :search-query.sync="store.searchQuery"
                        sort-by-column="updated"
                        table-cell-partial="#table-cell-partial">
            </table-grid>
        </div>
    </div>
@endsection
