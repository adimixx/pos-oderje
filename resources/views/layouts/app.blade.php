@extends("layouts.main")

@section("contentMain")
    <main class="d-flex flex-column h-100">
        <div class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand w-25" href="{{ url('/') }}">
                    <div id="login-logo" class="w-75"></div>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="row mr-auto">
                        <div class="col my-auto">
                            @if (\Illuminate\Support\Facades\Cookie::get('business') !== null)
                                <div class="my-auto bg-success"><p class="text-light m-1">Business
                                        : {{\App\ojdb_business::find(\Illuminate\Support\Facades\Cookie::get('business'))->b_name}}</p>
                                </div>
                            @endif

                            @if (\Illuminate\Support\Facades\Cookie::get('merchant') !== null)
                                <div class="my-auto bg-primary"><p class="text-light m-1">Merchant
                                        : {{\App\ojdb_merchant::find(\Illuminate\Support\Facades\Cookie::get('merchant'))->m_name}}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row ml-auto">
                        <div class="col my-auto">
                            <h4 class="text-primary m-0">{{ Auth::user()->name }}</h4>
                        </div>
                        <div class="col">
                            <a class="btn btn-lg btn-danger btn-user btn-block" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <span class="text-light">{{ __('Logout') }}</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-grow-1">
            @yield('content')
        </div>
    </main>
@endsection
