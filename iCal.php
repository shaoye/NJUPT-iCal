<?php

$term_start_time = '2018-09-03';

//$json_string = file_get_contents('sample.json');
//$timetable = json_decode($json_string);

$account = @$_REQUEST['account'];
$password = @$_REQUEST['password'];
if (null == $account || null == $password)
{
     exit("请输入正确的地址格式: http://123.206.204.241/?account=学号&password=正方密码");
}
$client = new GuzzleHttp\Client();
$response = $client->request('GET', 'http://localhost:8000/zhengfang', [
    'query' => [
        'account' => $account,
        'password' => $password
    ]
]);

if ($response->getStatusCode() != 200)
{
    exit("Bad Network");
}

$body = $response->getBody();

$content = json_decode($body);

if (!$content->success)
{
    exit($content->message);
}
$timetable = $content->courses;


date_default_timezone_set('Asia/Shanghai');

$class_time = array(
    1 => new DateTimeImmutable($term_start_time. '08:00:00'),
    2 => new DateTimeImmutable($term_start_time. '08:50:00'),
    3 => new DateTimeImmutable($term_start_time. '09:50:00'),
    4 => new DateTimeImmutable($term_start_time. '10:40:00'),
    5 => new DateTimeImmutable($term_start_time. '11:30:00'),
    6 => new DateTimeImmutable($term_start_time. '13:45:00'),
    7 => new DateTimeImmutable($term_start_time. '14:35:00'),
    8 => new DateTimeImmutable($term_start_time. '15:35:00'),
    9 => new DateTimeImmutable($term_start_time. '16:25:00'),
    10 => new DateTimeImmutable($term_start_time. '18:30:00'),
    11 => new DateTimeImmutable($term_start_time. '19:25:00'),
    12 => new DateTimeImmutable($term_start_time. '20:20:00')
);

// Create new calendar
$vCalendar = new \Eluceo\iCal\Component\Calendar('-//shaoye1124@gmail.com//iCal-NJUPT 1.0//EN');

foreach ($timetable as $course ){

    $class_start_time = $class_time[$course->class_start]
        ->modify('+ '.($course->day-1) .' days')
        ->modify('+ '.($course->week_start-1)*7 .' days');
    $class_end_time = $class_time[$course->class_end]
        ->modify('+ '.($course->day-1) .' days')
        ->modify('+ '.($course->week_start-1)*7 .' days')
        ->modify('+ 45 minutes');
    $class_end_date = $class_time[$course->class_end]
        ->modify('+ '.($course->day-1) .' days')
        ->modify('+ '.($course->week_end-1)*7 .' days')
        ->modify('+ 45 minutes');
    $vEvent = new \Eluceo\iCal\Component\Event();
    $vEvent
        ->setDtStart($class_start_time)
        ->setDtEnd($class_end_time)
        ->setSummary($course->name)
        ->setLocation($course->room .' '. $course->teacher);

    // Set recurrence rule
    $recurrenceRule = new \Eluceo\iCal\Property\Event\RecurrenceRule();
    $recurrenceRule->setFreq(\Eluceo\iCal\Property\Event\RecurrenceRule::FREQ_WEEKLY);
    $recurrenceRule->setInterval($course->interval);
    $recurrenceRule->setUntil($class_end_date);
    $vEvent->addRecurrenceRule($recurrenceRule);
    $vEvent->setUseTimezone(true);
    // Add event to calendar
    $vCalendar->addComponent($vEvent);

}

// Set headers
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="NJUPT-iCal.ics"');

// Output
echo $vCalendar->render();
