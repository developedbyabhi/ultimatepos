@extends('layouts.app')
@section('title', 'Active session')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Active session</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Active Session List</h3>
                    <div class="box-tools">
                        <a href="{{ action([\App\Http\Controllers\HomeController::class, 'index']) }}" 
                            class="btn btn-sm btn-primary pull-right">
                            <i class="fa fa-dashboard"></i> 
Back To Dashboard                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        @if(count($sessions) > 0)
                            <table class="table table-bordered table-striped" id="sessions_table">
                                <thead>
                                    <tr>
                                        <th>@lang('User Name')</th>
                                        <th>@lang('Ip Address')</th>
                                        <th>@lang('User Agent')</th>
                                        <th>@lang('Last Activity')</th>
                                        <th>@lang('Logout Time')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                        <tr>
                                            <td>{{ $session->username ?? 'Unknown User'  }}</td>
                                            <td>{{ $session->ip_address }}</td>
                                            <td>
                                                <span class="text-muted" title="{{ $session->user_agent }}">
                                                    {{ \Str::limit($session->user_agent, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(!empty($session->last_activity))
                                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                                @else
                                                    @lang('session.unknown')
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($session->last_logout))
                                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_logout)->diffForHumans() }}
                                                @else
                                                    <span class="label label-success">Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($session->id != session()->getId())
                                                    <button class="btn btn-xs btn-danger delete-session" 
                                                        data-session-id="{{ $session->id }}">
                                                        <i class="fa fa-sign-out"></i> Force Logout
                                                    </button>
                                                @else
                                                    <span class="label label-success">
                                                        Current Session                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">
                                No Active Session
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize DataTable
        $('#sessions_table').DataTable({
            responsive: true,
            ordering: false
        });

        // Handle session deletion
        $(document).on('click', '.delete-session', function() {
            var sessionId = $(this).data('session-id');
            swal({
                title: LANG.sure,
                text: LANG.confirm_delete_session,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ route('sessions.destroy', ':id') }}".replace(':id', sessionId),
                        method: "DELETE",
                        data: { 
                            session_id: sessionId,
                            _method: 'DELETE',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.msg);
                                location.reload();
                            } else {
                                toastr.error(response.msg);
                            }
                        },
                        error: function() {
                            toastr.error(LANG.something_went_wrong);
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
