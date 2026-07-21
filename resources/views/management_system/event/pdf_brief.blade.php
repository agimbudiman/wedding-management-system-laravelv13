<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Event Brief - {{ $event->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #6D9C4C;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #41612A;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            color: #718096;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background-color: #F7FAFC;
            padding: 8px 15px;
            border-left: 5px solid #6D9C4C;
            font-weight: bold;
            color: #2D3748;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-size: 14px;
        }
        .day-header {
            background-color: #E2E8F0;
            padding: 5px 10px;
            font-weight: bold;
            color: #4A5568;
            margin-top: 15px;
            margin-bottom: 5px;
            font-size: 12px;
            border-radius: 4px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 8px 0;
            vertical-align: top;
        }
        .label {
            color: #718096;
            width: 150px;
            font-weight: bold;
            font-size: 13px;
        }
        .value {
            color: #2D3748;
            font-size: 13px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #EDF2F7;
            color: #4A5568;
            text-align: left;
            padding: 10px;
            font-size: 12px;
            border-bottom: 1px solid #CBD5E0;
        }
        table.data-table td {
            padding: 10px;
            font-size: 12px;
            border-bottom: 1px solid #E2E8F0;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #C6F6D5; color: #22543D; }
        .badge-info { background-color: #BEE3F8; color: #2A4365; }
        .badge-warning { background-color: #FEEBC8; color: #744210; }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #A0AEC0;
            padding: 20px 0;
            border-top: 1px solid #E2E8F0;
        }
        .page-break {
            page-break-after: always;
        }
        .notes-box {
            background-color: #FFFBEB;
            border: 1px solid #FEF3C7;
            padding: 15px;
            border-radius: 8px;
            font-size: 12px;
            color: #92400E;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Event Brief</h1>
        <p>Brilliant Management System - Confidential Document</p>
    </div>

    <div class="section">
        <div class="section-title">General Information</div>
        <table class="info-grid">
            <tr>
                <td class="label">Event Name</td>
                <td class="value">: {{ $event->name }}</td>
            </tr>
            <tr>
                <td class="label">Category</td>
                <td class="value">: {{ $event->category->name }}</td>
            </tr>
            <tr>
                <td class="label">Client Name</td>
                <td class="value">: {{ $event->client_name }}</td>
            </tr>
            <tr>
                <td class="label">Date</td>
                <td class="value">: {{ $event->date->format('l, d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Venue</td>
                <td class="value">: {{ $event->venue }}</td>
            </tr>
            <tr>
                <td class="label">Event Type</td>
                <td class="value">: {{ $event->type }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="value">: 
                    <span class="badge {{ $event->status === 'Completed' ? 'badge-success' : ($event->status === 'Upcoming' ? 'badge-info' : 'badge-warning') }}">
                        {{ $event->status }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    @if($event->groom_name || $event->bride_name)
    <div class="section">
        <div class="section-title">Couple Information</div>
        <table class="info-grid">
            @if($event->groom_name)
            <tr>
                <td class="label">Groom</td>
                <td class="value">: {{ $event->groom_name }}</td>
            </tr>
            @endif
            @if($event->bride_name)
            <tr>
                <td class="label">Bride</td>
                <td class="value">: {{ $event->bride_name }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Rundown / Schedule</div>
        @php $currentDay = 0; @endphp
        @forelse($event->rundowns->sortBy(['day', 'time_start']) as $rundown)
            @if($rundown->day != $currentDay)
                @php $currentDay = $rundown->day; @endphp
                <div class="day-header">DAY {{ $currentDay }}</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Time</th>
                            <th style="width: 35%;">Activity</th>
                            <th style="width: 45%;">Description / Details</th>
                        </tr>
                    </thead>
                    <tbody>
            @endif
                <tr>
                    <td>
                        {{ $rundown->time_start ? date('H:i', strtotime($rundown->time_start)) : '--:--' }} 
                        - 
                        {{ $rundown->time_end ? date('H:i', strtotime($rundown->time_end)) : 'Finish' }}
                    </td>
                    <td><strong>{{ $rundown->activity }}</strong></td>
                    <td>{{ $rundown->description ?? '-' }}</td>
                </tr>
            
            @php 
                $nextRundown = $event->rundowns->sortBy(['day', 'time_start'])->values()->get($loop->index + 1);
            @endphp
            @if(!$nextRundown || $nextRundown->day != $currentDay)
                    </tbody>
                </table>
            @endif
        @empty
            <table class="data-table">
                <tbody>
                    <tr>
                        <td style="text-align: center; color: #A0AEC0;">No rundown items added yet.</td>
                    </tr>
                </tbody>
            </table>
        @endforelse
    </div>

    <div class="page-break"></div>

    <div class="section">
        <div class="section-title">Team & Crew</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Name</th>
                    <th style="width: 30%;">Role</th>
                    <th style="width: 30%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($event->crews as $crew)
                <tr>
                    <td><strong>{{ $crew->name }}</strong></td>
                    <td>{{ $crew->pivot->is_leader ? 'Team Leader' : 'Crew Member' }}</td>
                    <td>{{ $crew->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #A0AEC0;">No crews assigned yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Vendors</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Vendor Name</th>
                    <th style="width: 30%;">Category</th>
                    <th style="width: 30%;">Contact</th>
                </tr>
            </thead>
            <tbody>
                @forelse($event->vendors as $vendor)
                <tr>
                    <td><strong>{{ $vendor->name }}</strong></td>
                    <td>{{ $vendor->category }}</td>
                    <td>{{ $vendor->phone }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #A0AEC0;">No vendors assigned yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Important Notes</div>
        <div class="notes-box">
            @if($event->notes && $event->notes->content)
                {!! nl2br(e($event->notes->content)) !!}
            @else
                No special notes for this event.
            @endif
        </div>
    </div>

    <div class="footer">
        <p>{{ \App\Models\WebsiteSetting::get('footer_brand', 'Brilliant Management System') }}</p>
        <p>Tel: {{ \App\Models\WebsiteSetting::get('contact_phone', '-') }} | Email: {{ \App\Models\WebsiteSetting::get('contact_email', '-') }}</p>
        <p>Generated on {{ date('d M Y H:i') }} | &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
