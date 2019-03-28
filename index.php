<?php
Kirby::plugin('benediktengel/google-calendar-plugin', [
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
