@extends('layouts.dashboard')

@section('title', 'Event Details')

@section('page-title', $event->title)
@section('page-subtitle', $event->event_type)

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Event Information</h3>
            <div style="display: flex; gap: 10px;">
                @if($event->created_by === auth()->id())
                <button class="btn btn-danger" onclick="deleteEvent({{ $event->event_id }})">
                    <i class="fas fa-trash"></i> Delete
                </button>
                @endif
                <a href="{{ route('calendar.index') }}" class="btn btn-primary">
                    <i class="fas fa-calendar"></i> Back to Calendar
                </a>
            </div>
        </div>

        <div style="padding: 30px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <h4 style="color: var(--primary-color); margin-bottom: 20px;">
                        <i class="fas fa-info-circle"></i> Details
                    </h4>
                    
                    <div style="margin-bottom: 15px;">
                        <strong>Event Type:</strong><br>
                        <span class="badge" style="background: {{ $event->event_type === 'Meeting' ? '#007bff' : '#6c757d' }}; color: white; margin-top: 5px;">
                            {{ $event->event_type }}
                        </span>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <strong>Start:</strong><br>
                        {{ $event->start_datetime->format('F j, Y g:i A') }}
                    </div>

                    <div style="margin-bottom: 15px;">
                        <strong>End:</strong><br>
                        {{ $event->end_datetime->format('F j, Y g:i A') }}
                    </div>

                    @if($event->location)
                    <div style="margin-bottom: 15px;">
                        <strong>Location:</strong><br>
                        {{ $event->location }}
                    </div>
                    @endif

                    <div style="margin-bottom: 15px;">
                        <strong>Visibility:</strong><br>
                        <span class="badge badge-info">{{ $event->visibility }}</span>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <strong>Created By:</strong><br>
                        {{ $event->creator->employee->full_name ?? $event->creator->username }}
                    </div>
                </div>

                <div>
                    <h4 style="color: var(--primary-color); margin-bottom: 20px;">
                        <i class="fas fa-users"></i> Attendees ({{ $event->attendees->count() }})
                    </h4>
                    
                    @if($event->attendees->count() > 0)
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->attendees as $attendee)
                                <tr>
                                    <td>{{ $attendee->user->employee->full_name ?? $attendee->user->username }}</td>
                                    <td>
                                        @if($attendee->response_status === 'Accepted')
                                            <span class="badge badge-success">Accepted</span>
                                        @elseif($attendee->response_status === 'Declined')
                                            <span class="badge badge-danger">Declined</span>
                                        @elseif($attendee->response_status === 'Maybe')
                                            <span class="badge badge-warning">Maybe</span>
                                        @else
                                            <span class="badge badge-info">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($event->hasAttendee(auth()->id()))
                        <div style="margin-top: 20px;">
                            <form action="{{ route('calendar.respond', $event->event_id) }}" method="POST" style="display: flex; gap: 10px;">
                                @csrf
                                <button type="submit" name="response" value="Accepted" class="btn btn-success" style="flex: 1;">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                                <button type="submit" name="response" value="Maybe" class="btn btn-warning" style="flex: 1;">
                                    <i class="fas fa-question"></i> Maybe
                                </button>
                                <button type="submit" name="response" value="Declined" class="btn btn-danger" style="flex: 1;">
                                    <i class="fas fa-times"></i> Decline
                                </button>
                            </form>
                        </div>
                        @endif
                    @else
                        <p style="color: var(--text-light); text-align: center; padding: 20px;">
                            No attendees invited
                        </p>
                    @endif
                </div>
            </div>

            @if($event->description)
            <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid var(--border-color);">
                <h4 style="color: var(--primary-color); margin-bottom: 15px;">
                    <i class="fas fa-align-left"></i> Description
                </h4>
                <p style="white-space: pre-wrap;">{{ $event->description }}</p>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function deleteEvent(eventId) {
        if (confirm('Are you sure you want to delete this event? All attendees will be notified.')) {
            fetch(`/calendar/${eventId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '{{ route("calendar.index") }}';
                } else {
                    alert('Failed to delete event');
                }
            });
        }
    }
</script>
@endpush
