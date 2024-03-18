<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Search for zip codes by city & state</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    </head>
    <body class="font-sans">
        <div class="container mt-4">

            <div class="card">
                <div class="card-header text-center font-weight-bold">
                    Search for zip codes by city & state
                </div>
                <div class="card-body">
                    <form name="search-zipcode-form" id="search-zipcode-form" method="post" action="{{url('/')}}">
                        @csrf
                        <div class="input-group">
                            <input name="city" type="text" class="form-control" placeholder="City">
                            <select name="state_id" class="form-select">
                                <option selected disabled>State</option>
                                @foreach( $states as $state)
                                    <option value="{{ $state['id'] }}">{{ $state['abbreviation'] }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-2">
                            <ul>
                                @error('city')
                                <li>{{ $message }}</li>
                                @enderror
                                @error('state_id')
                                <li>The state field is required.</li>
                                @enderror
                            </ul>
                        </div>
                    @endif
                </div>

                @isset($result)
                <div class="container mb-4">
                    <h2>{{ count($result['result']) }} zip codes found for "{{ $result['city'] }} + {{ Arr::get($result, 'state.abbreviation') }}":</h2>
                    @if( $dbResult )
                        <h3>The result came from the database</h3>
                        <p>Data was originally stored at {{ $result['created_at'] }} (UTC)</p>
                    @else
                        <h3>The result came from the API directly</h3>
                    @endif
                    <ol class="list-group list-group-numbered">
                        @foreach( $result['result'] as $key => $item)
                            <li class="list-group-item">{{ $item['zipcode'] }}</li>
                        @endforeach
                    </ol>
                </div>
                @endisset
            </div>
        </div>
    </body>
</html>
