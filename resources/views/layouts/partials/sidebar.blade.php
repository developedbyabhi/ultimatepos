<!-- Left side column. contains the logo and sidebar -->
<aside class="side-bar tw-relative tw-hidden tw-h-full tw-bg-white tw-w-64 xl:tw-w-64 lg:tw-flex lg:tw-flex-col tw-shrink-0">

    <!-- sidebar: style can be found in sidebar.less -->

    {{-- <a href="{{route('home')}}" class="logo">
		<span class="logo-lg">{{ Session::get('business.name') }}</span>
	</a> --}}

    <a href="{{route('home')}}"
        class="tw-flex tw-items-center tw-justify-center tw-w-full tw-border-r tw-h-15 tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'primary'}}@endif-800 tw-shrink-0 tw-border-primary-500/30">
        <p class="tw-text-lg tw-font-medium tw-text-white side-bar-heading tw-text-center">
            {{ Session::get('business.name') }} <span class="tw-inline-block tw-w-3 tw-h-3 tw-bg-green-400 tw-rounded-full" title="Online"></span>
        </p>
    </a>

    <!-- User Info Section -->
    <div class="tw-px-4 tw-py-3 tw-border-b tw-border-gray-200">
        @if(Auth::user())
            <div class="tw-flex tw-items-center tw-mb-2">
                <div class="tw-w-8 tw-h-8 tw-rounded-full tw-bg-@if(!empty(session('business.theme_color'))){{session('business.theme_color')}}@else{{'primary'}}@endif-700 tw-flex tw-items-center tw-justify-center tw-text-white tw-font-bold">
                    {{ substr(Auth::user()->first_name, 0, 1) }}
                </div>
                <div class="tw-ml-2">
                    <p class="tw-text-sm tw-font-medium tw-text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    <p class="tw-text-xs tw-text-gray-500">
                        @if(session('login_time'))
                            Login: {{ \Carbon\Carbon::parse(session('login_time'))->format('M d, h:i A') }}
                        @else
                            Login time not available
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar Menu -->
    {!! Menu::render('admin-sidebar-menu', 'adminltecustom') !!}

    <!-- /.sidebar-menu -->
    <!-- /.sidebar -->
</aside>
