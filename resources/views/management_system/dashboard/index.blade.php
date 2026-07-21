@extends('layouts.management')

@section('title', 'Dashboard')

@section('styles')
    <style>
        .card-event-analytics {
            background: linear-gradient(135deg, #6D9C4C 0%, #41612A 92%) !important;
            color: #ffffff;
        }

        .category-select-item {
            transition: all 0.3s ease;
            border: 2px solid #edf2f7;
            border-radius: 15px;
            cursor: pointer;
            overflow: hidden;
        }

        .category-select-item:hover {
            border-color: var(--brilliant-green);
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .category-select-item.selected {
            border-color: var(--brilliant-green);
            background-color: #f0fff4;
        }

        .category-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .category-select-item:hover .category-icon-wrapper {
            background: var(--brilliant-green-light);
            color: var(--brilliant-green);
        }

        /* Calendar Styles */
        #calendar {
            -webkit-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            width: 100%;
            margin: 0 auto;
            background: #fff;
        }

        .header {
            height: 50px;
            width: 100%;
            background: #fff;
            color: #333;
            border-bottom: 1px solid #eee;
            text-align: center;
            position: relative;
            z-index: 100;
        }

        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 20px;
            line-height: 50px;
            font-weight: 400;
            letter-spacing: 1px;
        }

        .left,
        .right {
            position: absolute;
            width: 0px;
            height: 0px;
            border-style: solid;
            top: 50%;
            margin-top: -7.5px;
            cursor: pointer;
        }

        .left {
            border-width: 7.5px 10px 7.5px 0;
            border-color: transparent #888 transparent transparent;
            left: 20px;
        }

        .right {
            border-width: 7.5px 0 7.5px 10px;
            border-color: transparent transparent transparent #888;
            right: 20px;
        }

        .month {
            /*overflow: hidden;*/
            opacity: 0;
        }

        .month.new {
            -webkit-animation: fadeIn 1s ease-out;
            opacity: 1;
        }

        .month.in.next {
            -webkit-animation: moveFromTopFadeMonth .4s ease-out;
            -moz-animation: moveFromTopFadeMonth .4s ease-out;
            animation: moveFromTopFadeMonth .4s ease-out;
            opacity: 1;
        }

        .month.out.next {
            -webkit-animation: moveToTopFadeMonth .4s ease-in;
            -moz-animation: moveToTopFadeMonth .4s ease-in;
            animation: moveToTopFadeMonth .4s ease-in;
            opacity: 1;
        }

        .month.in.prev {
            -webkit-animation: moveFromBottomFadeMonth .4s ease-out;
            -moz-animation: moveFromBottomFadeMonth .4s ease-out;
            animation: moveFromBottomFadeMonth .4s ease-out;
            opacity: 1;
        }

        .month.out.prev {
            -webkit-animation: moveToBottomFadeMonth .4s ease-in;
            -moz-animation: moveToBottomFadeMonth .4s ease-in;
            animation: moveToBottomFadeMonth .4s ease-in;
            opacity: 1;
        }

        .week {
            background: #fff;
            border-bottom: 1px solid #f8f8f8;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .day {
            display: inline-block;
            width: 14.28%;
            padding: 5px;
            text-align: center;
            vertical-align: top;
            cursor: pointer;
            background: #fff;
            position: relative;
            z-index: 100;
            color: #444;
        }

        .day.other {
            color: #ccc;
        }

        .day.today {
            color: rgba(156, 202, 235, 1);
            font-weight: bold;
        }

        .day-name {
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 5px;
            color: #999;
            letter-spacing: .7px;
        }

        .day-number {
            font-size: 18px;
            letter-spacing: 1.5px;
        }

        .day .day-events {
            list-style: none;
            margin-top: 3px;
            text-align: center;
            height: 12px;
            line-height: 6px;
            overflow: hidden;
        }

        .day .day-events span {
            vertical-align: top;
            display: inline-block;
            padding: 0;
            margin: 0;
            width: 5px;
            height: 5px;
            line-height: 5px;
            margin: 0 1px;
        }

        .blue {
            background: rgba(156, 202, 235, 1);
        }

        .orange {
            background: rgba(247, 167, 0, 1);
        }

        .green {
            background: rgba(153, 198, 109, 1);
        }

        .yellow {
            background: rgba(249, 233, 0, 1);
        }

        .details {
            position: relative;
            width: 100%;
            height: 75px;
            background: #f9f9f9;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .details.in {
            -webkit-animation: moveFromTopFade .5s ease both;
            -moz-animation: moveFromTopFade .5s ease both;
            animation: moveFromTopFade .5s ease both;
        }

        .details.out {
            -webkit-animation: moveToTopFade .5s ease both;
            -moz-animation: moveToTopFade .5s ease both;
            animation: moveToTopFade .5s ease both;
        }

        .arrow {
            position: absolute;
            top: -6px;
            left: 50%;
            margin-left: -2px;
            width: 0px;
            height: 0px;
            border-style: solid;
            border-width: 0 5px 5px 5px;
            border-color: transparent transparent #eee transparent;
            transition: all 0.7s ease;
        }

        .events {
            height: 75px;
            padding: 7px 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .events.in {
            -webkit-animation: fadeIn .3s ease both;
            -moz-animation: fadeIn .3s ease both;
            animation: fadeIn .3s ease both;
        }

        .events.in {
            -webkit-animation-delay: .3s;
            -moz-animation-delay: .3s;
            animation-delay: .3s;
        }

        .details.out .events {
            -webkit-animation: fadeOutShrink .4s ease both;
            -moz-animation: fadeOutShink .4s ease both;
            animation: fadeOutShink .4s ease both;
        }

        .events.out {
            -webkit-animation: fadeOut .3s ease both;
            -moz-animation: fadeOut .3s ease both;
            animation: fadeOut .3s ease both;
        }

        .event {
            font-size: 14px;
            line-height: 20px;
            letter-spacing: .5px;
            padding: 2px 16px;
            vertical-align: top;
            color: #555;
        }

        .event.empty {
            color: #999;
        }

        .event-category {
            height: 10px;
            width: 10px;
            display: inline-block;
            margin: 6px 0 0;
            vertical-align: top;
        }

        .event span {
            display: inline-block;
            padding: 0 0 0 7px;
        }

        /* Animations */
        @-webkit-keyframes moveFromTopFade {
            from {
                opacity: .3;
                height: 0px;
                margin-top: 0px;
                -webkit-transform: translateY(-100%);
            }
        }

        @keyframes moveFromTopFade {
            from {
                height: 0px;
                margin-top: 0px;
                transform: translateY(-100%);
            }
        }

        @-webkit-keyframes moveToTopFade {
            to {
                opacity: .3;
                height: 0px;
                margin-top: 0px;
                opacity: 0.3;
                -webkit-transform: translateY(-100%);
            }
        }

        @keyframes moveToTopFade {
            to {
                height: 0px;
                transform: translateY(-100%);
            }
        }

        @-webkit-keyframes moveToTopFadeMonth {
            to {
                opacity: 0;
                -webkit-transform: translateY(-30%) scale(.95);
            }
        }

        @keyframes moveToTopFadeMonth {
            to {
                opacity: 0;
                -moz-transform: translateY(-30%);
            }
        }

        @-webkit-keyframes moveFromTopFadeMonth {
            from {
                opacity: 0;
                -webkit-transform: translateY(30%) scale(.95);
            }
        }

        @keyframes moveFromTopFadeMonth {
            from {
                opacity: 0;
                -moz-transform: translateY(30%);
            }
        }

        @-webkit-keyframes moveToBottomFadeMonth {
            to {
                opacity: 0;
                -webkit-transform: translateY(30%) scale(.95);
            }
        }

        @keyframes moveToBottomFadeMonth {
            to {
                opacity: 0;
                -moz-transform: translateY(30%);
            }
        }

        @-webkit-keyframes moveFromBottomFadeMonth {
            from {
                opacity: 0;
                -webkit-transform: translateY(-30%) scale(.95);
            }
        }

        @keyframes moveFromBottomFadeMonth {
            from {
                opacity: 0;
                -moz-transform: translateY(-30%);
            }
        }

        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
        }

        @-webkit-keyframes fadeOut {
            to {
                opacity: 0;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }

        @-webkit-keyframes fadeOutShink {
            to {
                opacity: 0;
                padding: 0px;
                height: 0px;
            }
        }

        @keyframes fadeOutShink {
            to {
                opacity: 0;
                padding: 0px;
                height: 0px;
            }
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        !function () {
            var today = moment();

            function Calendar(selector, events) {
                this.el = document.querySelector(selector);
                this.events = events;
                this.current = moment().date(1);
                this.draw();
            }

            Calendar.prototype.draw = function () {
                //Create Header
                this.drawHeader();

                //Draw Month
                this.drawMonth();
            }

            Calendar.prototype.drawHeader = function () {
                var self = this;
                if (!this.header) {
                    //Create the header elements
                    this.header = createElement('div', 'header');
                    this.header.className = 'header';

                    this.title = createElement('h1');

                    var right = createElement('div', 'right');
                    right.addEventListener('click', function () { self.nextMonth(); });

                    var left = createElement('div', 'left');
                    left.addEventListener('click', function () { self.prevMonth(); });

                    //Append the Elements
                    this.header.appendChild(this.title);
                    this.header.appendChild(right);
                    this.header.appendChild(left);
                    this.el.appendChild(this.header);
                }

                this.title.innerHTML = this.current.format('MMMM YYYY');
            }

            Calendar.prototype.drawMonth = function () {
                var self = this;

                if (this.month) {
                    this.oldMonth = this.month;
                    this.oldMonth.className = 'month out ' + (self.next ? 'next' : 'prev');
                    this.oldMonth.addEventListener('webkitAnimationEnd', function () {
                        self.oldMonth.parentNode.removeChild(self.oldMonth);
                        self.month = createElement('div', 'month');
                        self.backFill();
                        self.currentMonth();
                        self.fowardFill();
                        self.el.appendChild(self.month);
                        window.setTimeout(function () {
                            self.month.className = 'month in ' + (self.next ? 'next' : 'prev');
                        }, 16);
                    });
                } else {
                    this.month = createElement('div', 'month');
                    this.el.appendChild(this.month);
                    this.backFill();
                    this.currentMonth();
                    this.fowardFill();
                    this.month.className = 'month new';
                }
            }

            Calendar.prototype.backFill = function () {
                var clone = this.current.clone();
                var dayOfWeek = clone.day();

                if (!dayOfWeek) { return; }

                clone.subtract('days', dayOfWeek + 1);

                for (var i = dayOfWeek; i > 0; i--) {
                    this.drawDay(clone.add('days', 1));
                }
            }

            Calendar.prototype.fowardFill = function () {
                var clone = this.current.clone().add('months', 1).subtract('days', 1);
                var dayOfWeek = clone.day();

                if (dayOfWeek === 6) { return; }

                for (var i = dayOfWeek; i < 6; i++) {
                    this.drawDay(clone.add('days', 1));
                }
            }

            Calendar.prototype.currentMonth = function () {
                var clone = this.current.clone();

                while (clone.month() === this.current.month()) {
                    this.drawDay(clone);
                    clone.add('days', 1);
                }
            }

            Calendar.prototype.getWeek = function (day) {
                if (!this.week || day.day() === 0) {
                    this.week = createElement('div', 'week');
                    this.month.appendChild(this.week);
                }
            }

            Calendar.prototype.drawDay = function (day) {
                var self = this;
                this.getWeek(day);

                //Outer Day
                var outer = createElement('div', this.getDayClass(day));
                outer.addEventListener('click', function () {
                    self.openDay(this);
                });

                //Day Name
                var name = createElement('div', 'day-name', day.format('ddd'));

                //Day Number
                var number = createElement('div', 'day-number', day.format('DD'));


                //Events
                var events = createElement('div', 'day-events');
                this.drawEvents(day, events);

                outer.appendChild(name);
                outer.appendChild(number);
                outer.appendChild(events);
                this.week.appendChild(outer);
            }

            Calendar.prototype.drawEvents = function (day, element) {
                if (day.month() === this.current.month()) {
                    var todaysEvents = this.events.reduce(function (memo, ev) {
                        if (moment(ev.date).isSame(day, 'day')) {
                            memo.push(ev);
                        }
                        return memo;
                    }, []);

                    todaysEvents.forEach(function (ev) {
                        var evSpan = createElement('span', ev.color);
                        element.appendChild(evSpan);
                    });
                }
            }

            Calendar.prototype.getDayClass = function (day) {
                classes = ['day'];
                if (day.month() !== this.current.month()) {
                    classes.push('other');
                } else if (today.isSame(day, 'day')) {
                    classes.push('today');
                }
                return classes.join(' ');
            }

            Calendar.prototype.openDay = function (el) {
                var details, arrow;
                var dayNumber = +el.querySelectorAll('.day-number')[0].innerText || +el.querySelectorAll('.day-number')[0].textContent;
                var day = this.current.clone().date(dayNumber);

                var currentOpened = document.querySelector('.details');

                // Check if the clicked day is already open
                if (currentOpened && currentOpened.parentNode === el.parentNode && currentOpened.getAttribute('data-day') == dayNumber) {
                    // Toggle off: if clicking the same day, close it
                    currentOpened.className = 'details out';
                    currentOpened.addEventListener('animationend', function () {
                        if (currentOpened.parentNode) {
                            currentOpened.parentNode.removeChild(currentOpened);
                        }
                    }, { once: true });
                    return;
                }

                //Check to see if there is an open detais box on the current row
                if (currentOpened && currentOpened.parentNode === el.parentNode) {
                    details = currentOpened;
                    arrow = document.querySelector('.arrow');
                } else {
                    //Close the open events on differnt week row
                    if (currentOpened) {
                        currentOpened.className = 'details out';
                        currentOpened.addEventListener('animationend', function () {
                            if (currentOpened.parentNode) {
                                currentOpened.parentNode.removeChild(currentOpened);
                            }
                        }, { once: true });
                    }

                    //Create the Details Container
                    details = createElement('div', 'details in');
                    details.setAttribute('data-day', dayNumber);

                    //Create the arrow
                    var arrow = createElement('div', 'arrow');

                    details.appendChild(arrow);
                    el.parentNode.appendChild(details);
                }

                var todaysEvents = this.events.reduce(function (memo, ev) {
                    if (moment(ev.date).isSame(day, 'day')) {
                        memo.push(ev);
                    }
                    return memo;
                }, []);

                this.renderEvents(todaysEvents, details);

                arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft + (el.offsetWidth / 2) - 5 + 'px';
            }

            Calendar.prototype.renderEvents = function (events, ele) {
                //Remove any events in the current details element
                var currentWrapper = ele.querySelector('.events');
                var wrapper = createElement('div', 'events in' + (currentWrapper ? ' new' : ''));

                events.forEach(function (ev) {
                    var div = createElement('div', 'event');
                    var square = createElement('div', 'event-category ' + ev.color);
                    var span = createElement('span', '', ev.eventName);

                    div.appendChild(square);
                    div.appendChild(span);
                    wrapper.appendChild(div);
                });

                if (!events.length) {
                    var div = createElement('div', 'event empty');
                    var span = createElement('span', '', 'No Events');

                    div.appendChild(span);
                    wrapper.appendChild(div);
                }

                if (currentWrapper) {
                    currentWrapper.className = 'events out';
                    currentWrapper.addEventListener('animationend', function () {
                        currentWrapper.parentNode.removeChild(currentWrapper);
                        ele.appendChild(wrapper);
                    });
                } else {
                    ele.appendChild(wrapper);
                }
            }

            Calendar.prototype.nextMonth = function () {
                this.current.add('months', 1);
                this.next = true;
                this.draw();
            }

            Calendar.prototype.prevMonth = function () {
                this.current.subtract('months', 1);
                this.next = false;
                this.draw();
            }

            window.Calendar = Calendar;

            function createElement(tagName, className, innerText) {
                var ele = document.createElement(tagName);
                if (className) {
                    ele.className = className;
                }
                if (innerText) {
                    ele.innerText = ele.textContent = innerText;
                }
                return ele;
            }
        }();

        !function () {
            var data = @json($allEvents);

            var calendar = new Calendar('#calendar', data);
        }();
    </script>
@endpush

@section('content')
    <h1 class="page-title">Dashboard</h1>

    <div class="row g-4">
        <!-- Event Analytics & Daily Quotes Column -->
        <div class="col-lg-4 d-flex flex-column gap-4">
            <div class="card-widget card-event-analytics" style="height: auto;">
                <h5 class="mb-4 font-weight-bold">{{ $isCrew ? 'Your Event Analytics' : 'Event Analytics' }}</h5>

                <div class="d-flex align-items-center mb-3">
                    <span class="stat-item">{{ $completedEvents }}</span>
                    <span>{{ $isCrew ? 'Your Completed Events' : 'Total Completed Event' }}</span>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <span class="stat-item">{{ $upcomingEventsCount }}</span>
                    <span>{{ $isCrew ? 'Your Upcoming Events' : 'Total Upcoming Event' }}</span>
                </div>

                @if(!$isCrew)
                    <div class="d-flex align-items-center mb-3">
                        <span class="stat-item">{{ $inProgressEvents }}</span>
                        <span>Total In Progress Event</span>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="stat-item">{{ $inQueueEvents }}</span>
                        <span>Total In Queue Event</span>
                    </div>
                @endif
            </div>

            <!-- Daily Quotes Card -->
            <div class="card-widget d-flex flex-column justify-content-center align-items-center text-center p-4 position-relative"
                style="height: auto; flex-grow: 1; background: #fcfcc1ff; border: 1px solid rgba(109, 156, 76, 0.12); overflow: hidden; border-radius: 20px; min-height: 200px;">
                <div class="position-absolute"
                    style="top: -10px; left: 15px; opacity: 0.08; font-size: 5rem; font-family: Georgia, serif; color: #7ca361; pointer-events: none; user-select: none;">
                    “</div>
                <div class="position-absolute"
                    style="bottom: -50px; right: 15px; opacity: 0.08; font-size: 5rem; font-family: Georgia, serif; color: #7ca361; pointer-events: none; user-select: none;">
                    ”</div>

                <h6 class="text-uppercase tracking-wider text-muted small fw-bold mb-3"
                    style="letter-spacing: 0.1em; color: #7ca361 !important;">Daily Quotes</h6>
                
                <div id="quotesCarousel" class="carousel slide w-100" 
                    @if($slideshowActive == '1')
                        data-bs-ride="carousel" data-bs-interval="{{ $slideshowDuration * 1000 }}"
                    @else
                        data-bs-interval="false"
                    @endif>
                    <div class="carousel-inner">
                        @forelse($quotes as $index => $quote)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <p class="fst-italic text-dark mb-2 px-3"
                                    style="font-size: 1.05rem; line-height: 1.6; font-weight: 500; color: #2d3748;">
                                    "{{ $quote['text'] }}"
                                </p>
                                <span class="small text-muted fw-semibold d-block">— {{ $quote['author'] }}</span>
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <p class="fst-italic text-dark mb-2 px-3"
                                    style="font-size: 1.05rem; line-height: 1.6; font-weight: 500; color: #2d3748;">
                                    "Terus melangkah, karena masa depan yang cerah menanti Anda."
                                </p>
                                <span class="small text-muted fw-semibold d-block">— Brilliant Event</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Calendar Card -->
        <div class="col-lg-4">
            <div class="card-widget">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="m-0 text-muted">Event Calendar</h5>
                </div>
                <div id="calendar"></div>
                <!-- <div class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                Fitur Kalender Nonaktif.
                            </div> -->
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-lg-4">
            <div class="card-widget d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h5 class="m-0 text-muted">{{ $isCrew ? 'Your Upcoming Events' : 'Upcoming Events' }}</h5>
                </div>

                <div class="flex-grow-1">
                    @forelse($recentUpcomingEvents as $event)
                        <div class="mb-3">
                            <a href="{{ route('management.event.show', $event->id) }}" class="text-decoration-none">
                                <div class="fw-bold text-dark">{{ $event->name }}</div>
                                <div class="small text-muted">{{ $event->date->format('d F Y') }}</div>
                                <div class="small text-muted">{{ $event->venue }}</div>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-calendar-event fs-2 d-block mb-2"></i>
                            {{ $isCrew ? 'You have no upcoming events.' : 'No upcoming events.' }}
                        </div>
                    @endforelse
                </div>

                @if(!$isCrew)
                    <button class="btn btn-primary w-100 mt-3 d-flex align-items-center justify-content-center gap-2"
                        data-bs-toggle="modal" data-bs-target="#selectCategoryModal">
                        <i class="bi bi-plus-circle"></i> Add Event
                    </button>
                @endif
            </div>
        </div>

        <!-- Yearly Overview -->
        <div class="col-lg-7">
            <div class="card-widget">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="m-0 text-muted"><i class="bi bi-bar-chart-fill me-2"></i> Yearly Overview</h5>
                    <select id="yearSelector" class="form-select form-select-sm w-auto rounded-3 border-2 fw-semibold"
                        style="border-color: var(--brilliant-green-light); color: var(--brilliant-green-dark); cursor: pointer;">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="small mb-4 text-muted">
                    Traffic:
                    <span class="ms-2"><i class="bi bi-circle-fill text-warning x-small"></i> Low Activity</span>
                    <span class="ms-2"><i class="bi bi-circle-fill text-danger x-small"></i> High Activity</span>
                    <span class="ms-2"><i class="bi bi-circle-fill text-success x-small"></i> Moderate Activity</span>
                </div>

                <canvas id="yearlyChart" height="200"></canvas>
            </div>
        </div>

        <!-- Crew Information or Your Tasks -->
        <div class="col-lg-5">
            @if($isCrew)
                <div class="card-widget d-flex flex-column" style="height: 100%;">
                    <h5 class="mb-4 text-muted border-bottom pb-2">Your Tasks</h5>
                    <div class="flex-grow-1 overflow-auto" style="max-height: 350px;">
                        @forelse($yourTasks as $task)
                            <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-4 bg-light border border-light">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; color: {{ $task->is_completed ? 'var(--brilliant-green)' : '#718096' }};">
                                    <i class="bi {{ $task->is_completed ? 'bi-check-circle-fill' : 'bi-circle' }} fs-5"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate {{ $task->is_completed ? 'text-decoration-line-through text-muted' : '' }}">{{ $task->title }}</div>
                                    <div class="small text-muted text-truncate">{{ $task->event->name }}</div>
                                    @if($task->due_date)
                                        <div class="small text-danger mt-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-clock me-1"></i> Due: {{ $task->due_date->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('management.event.show', $task->event_id) }}#todo" class="btn btn-sm btn-outline-primary rounded-pill px-2" style="font-size: 0.7rem;">
                                    View
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-check fs-2 d-block mb-2"></i>
                                No tasks assigned to you.
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="card-widget d-flex flex-column justify-content-between" style="height: 100%;">
                    <div>
                        <h5 class="mb-4 text-muted border-bottom pb-2">Crew Information</h5>

                        <div class="text-center mb-4 p-3 bg-light rounded-4 d-flex justify-content-center align-items-center" style="height: 200px; border: 1px solid rgba(0,0,0,0.03);">
                            <img src="{{ asset('assets/crew_illustration.png') }}" alt="Crew Illustration" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>

                    <div class="row text-center py-3 bg-white rounded-3 shadow-sm border border-light mx-1">
                        <div class="col-4 border-end">
                            <div class="mb-2"><i class="fas fa-users text-primary fa-2x"></i></div>
                            <div class="text-muted small mb-1 fw-semibold">Total Crew</div>
                            <div class="h3 fw-bold m-0 text-dark">{{ $crewTotal }}</div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="mb-2"><i class="fas fa-user-check text-success fa-2x"></i></div>
                            <div class="text-muted small mb-1 fw-semibold">Available</div>
                            <div class="h3 fw-bold m-0 text-success">{{ $crewAvailable }}</div>
                        </div>
                        <div class="col-4">
                            <div class="mb-2"><i class="fas fa-user-slash text-danger fa-2x"></i></div>
                            <div class="text-muted small mb-1 fw-semibold">Not Available</div>
                            <div class="h3 fw-bold m-0 text-danger">{{ $crewNotAvailable }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>



    <!-- Category Selection Modal -->
    <div class="modal fade" id="selectCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold">Pilih Kategori Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Silakan pilih kategori event yang ingin Anda tambahkan.</p>
                    <div class="row g-3">
                        @forelse($categories as $category)
                            <div class="col-12">
                                <a href="{{ route('management.event.list', $category->slug) }}?showModal=true"
                                    class="text-decoration-none">
                                    <div class="category-select-item p-3 d-flex align-items-center">
                                        <div class="category-icon-wrapper">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                                            @else
                                                <i class="bi bi-tag-fill"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="m-0 fw-bold text-dark">{{ $category->name }}</h6>
                                            <p class="m-0 text-muted small">{{ Str::limit($category->description, 50) }}</p>
                                        </div>
                                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <p class="text-muted">Belum ada kategori event.</p>
                                <a href="{{ route('management.event') }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill">Kelola Kategori</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Threshold colors matching the traffic legend
        function getBarColor(value) {
            if (value >= 6) return '#e53e3e'; // High Activity (Red)
            if (value >= 3) return '#7ca361'; // Moderate Activity (Brilliant Green)
            if (value > 0) return '#ffc107';  // Low Activity (Yellow)
            return '#7ca361'; // Default fallback color for 0 height
        }

        const initialData = @json($monthlyActivityData);
        const initialColors = initialData.map(getBarColor);

        const ctx = document.getElementById('yearlyChart').getContext('2d');
        const yearlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Event Activity',
                    data: initialData,
                    backgroundColor: initialColors,
                    borderRadius: 5,
                    barThickness: 15,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: false },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });

        // Dynamic Year Selection AJAX
        const yearSelector = document.getElementById('yearSelector');
        if (yearSelector) {
            yearSelector.addEventListener('change', function () {
                const year = this.value;
                const url = `{{ route('management.dashboard.yearly-overview-data') }}?year=${year}`;

                // Subtle premium fade micro-animation
                ctx.canvas.style.opacity = '0.4';
                ctx.canvas.style.transition = 'opacity 0.25s ease-in-out';

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        yearlyChart.data.datasets[0].data = data;
                        yearlyChart.data.datasets[0].backgroundColor = data.map(getBarColor);
                        yearlyChart.update();

                        setTimeout(() => {
                            ctx.canvas.style.opacity = '1';
                        }, 50);
                    })
                    .catch(error => {
                        console.error('Error fetching yearly activity:', error);
                        ctx.canvas.style.opacity = '1';
                    });
            });
        }
    </script>

@endsection