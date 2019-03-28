<?php
Kirby::plugin('benediktengel/G-CalendarPlugin', [
  'snippets' => [
      'calendar' => __DIR__ . '/snippets/calendar.php'
  ],
  'options' => [
    'apiKey' => null,
    'calendarID' => null,
    'attributes' => null,
    'formatDate' => 'd.m.Y',
    'formatTime' => 'H:i',
    'upcoming' => true,
    'descriptionLength' => '300',
    'cutDescription' => true,
    'linkName' => 'Show more.'
  ]
]);
