@extends('layout')

@section('title')Календарь@endsection
@section('main_content')
    <h1>Календарь отпусков</h1>
    <div class="container">
        <div id='calendar'></div>
    </div>

    <script>
        $(document).ready(function () {

            var SITEURL = "{{ url('/') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: SITEURL + "/fullcalender",
                displayEventTime: false,
                eventColor: '#beff51',
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    var title = prompt('Отпуск:', '{{ auth()->user()->name }}');
                    if (title) {
                        var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                        var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                        $.ajax({
                            url: SITEURL + "/fullcalenderAjax",
                            data: {
                                title: title,
                                start: start,
                                end: end,
                                type: 'add'
                            },
                            type: "POST",
                            success: function (data) {
                                displayMessage("Событие создано");

                                calendar.fullCalendar('renderEvent',
                                    {
                                        id: data.id,
                                        title: title,
                                        start: start,
                                        end: end,
                                        allDay: allDay,
                                        user_id: '{{ auth()->user()->id }}'
                                    },true);

                                calendar.fullCalendar('unselect');
                            }
                        });
                    }
                },
                eventDrop: function (event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                    if (event.approved == 1 || '{{ auth()->user()->id }}' == 1) {
                        displayMessage("Ошибка: отпуск уже одобрен!");
                    } else {
                        $.ajax({
                            url: SITEURL + '/fullcalenderAjax',
                            data: {
                                title: event.title,
                                start: start,
                                end: end,
                                id: event.id,
                                user_id: event.user_id,
                                type: 'update'
                            },
                            type: "POST",
                            success: function (response) {
                                if (response) {
                                    displayMessage("Событие перенесено");
                                } else {
                                    displayMessage("Ошибка: переносить можно только свой отпуск!");
                                }
                            },
                        });
                    }
                },
                eventClick: function (event) {
                    if ('{{ auth()->user()->id }}' == event.user_id && !event.approved) {
                        var deleteMsg = confirm("Удалить?");
                        if (deleteMsg) {
                            $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                    id: event.id,
                                    type: 'delete'
                                },
                                success: function (response) {
                                    calendar.fullCalendar('removeEvents', event.id);
                                    displayMessage("Запись удалена");
                                }
                            });
                        }
                    } else if ('{{ auth()->user()->id }}' == 1 ) {
                        var deleteMsg = confirm("Подтвердить отпуск?");
                        if (deleteMsg) {
                            $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                    id: event.id,
                                    type: 'approve',
                                    title: event.title,
                                },
                                success: function (response) {
                                    displayMessage("Запись подтверждена");
                                }
                            });
                        }
                    }


                }

            });

        });

        function displayMessage(message) {
            toastr.success(message, 'Отпуск');
        }

    </script>
@endsection





<!DOCTYPE html>
<html>
<head>
    <title>Laravel Fullcalender Tutorial Tutorial - ItSolutionStuff.com</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>



</body>
</html>