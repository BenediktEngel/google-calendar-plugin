<?php
$apikey;
$calendarID;
$attributes = array();
$events = array();
$today = new DateTime(DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s'))->format(option('benediktengel.google-calendar-plugin.formatDate')));
$i = 0;

// Get Values from config.php

// API-event & Calendar ID
if (option('benediktengel.google-calendar-plugin.apikey')!= null && option('benediktengel.google-calendar-plugin.calendarID')!= null) {
    $apikey = option('benediktengel.google-calendar-plugin.apikey');
    $calendarID = option('benediktengel.google-calendar-plugin.calendarID');
} else {
    if ((option('benediktengel.google-calendar-plugin.apikey')== null || option('benediktengel.google-calendar-plugin.apikey')== "")&& (option('benediktengel.google-calendar-plugin.calendarID')== null || option('benediktengel.google-calendar-plugin.calendarID')== "")) {
        trigger_error("Error: API-event and calendarID are missing!", E_USER_ERROR);
    } elseif (option('benediktengel.google-calendar-plugin.apikey')== null || option('benediktengel.google-calendar-plugin.apikey')== "") {
        trigger_error("Error: API-event is missing!", E_USER_ERROR);
    } elseif (option('benediktengel.google-calendar-plugin.calendarID')== null || option('benediktengel.google-calendar-plugin.calendarID')== "") {
        trigger_error("Error: CalendarID is missing!", E_USER_ERROR);
    }
}

// Attributes
if (option('benediktengel.google-calendar-plugin.attributes') != null) {
    foreach (option('benediktengel.google-calendar-plugin.attributes') as $attribute) {
        array_push($attributes, $attribute) ;
    }
}

// Get JSON with the events
$stream = @file_get_contents('https://www.googleapis.com/calendar/v3/calendars/'.$calendarID.'/events?singleEvents=true&orderBy=startTime&key='.$apikey);
if ($stream === false) {
    trigger_error("Error: GET Calendar doesn't work", E_USER_ERROR);
} else {
    $obj = json_decode($stream, true);
}

// No Attributes
if ($attributes == null) {
    foreach ($obj['items'] as $event) {
        $events[$i] = array();
        $events[$i] += ["title" => $event['summary']];
        $events[$i] += ["datestart" =>  new DateTime(DateTime::createFromFormat("Y-m-d?H:i:sP", $event['start']['dateTime'])->format(option('benediktengel.google-calendar-plugin.formatDate')))];
        $events[$i] += ["timestart" =>  new DateTime(DateTime::createFromFormat("Y-m-d?H:i:sP", $event['start']['dateTime'])->format(option('benediktengel.google-calendar-plugin.formatTime')))];
        $events[$i] += ["dateEnd" =>  new DateTime(DateTime::createFromFormat("Y-m-d?H:i:sP", $event['end']['dateTime'])->format(option('benediktengel.google-calendar-plugin.formatDate')))];
        $events[$i] += ["timeEnd" =>  new DateTime(DateTime::createFromFormat("Y-m-d?H:i:sP", $event['end']['dateTime'])->format(option('benediktengel.google-calendar-plugin.formatTime')))];
        $events[$i] += ["url" => $event['htmlLink']];
        if (isset($event['description'])) {
            $events[$i] += ["description" => $event['description']];
        }
        if (isset($event['location'])) {
            $events[$i] += ["location" => $event['location']];
        }
        $i++;
    }
}
// With Attributes
else {
    $i = 0;
    foreach ($obj['items'] as $event) {
        $events[$i] = array();
        foreach ($attributes as $attirbute) {
            if ($attirbute == 'title') {
                $events[$i] += ["title" => $event['summary']];
            }
        }
        foreach ($attributes as $attirbute) {
            if ($attirbute == 'dateStart') {
                $events[$i] += ["datestart" =>  new DateTime(DateTime::createFromFormat("Y-m-d?H:i:sP", $event['start']['dateTime'])->format(option('benediktengel.google-calendar-plugin.formatDate')))];
            }
        }
        foreach ($attributes as $attirbute) {
            if ($attirbute == 'timeStart') {
                $events[$i] += ["timestart" =>  new DateTime(DateTime::createFromFormat("Y-m-d?H:i:sP", $event['start']['dateTime'])->format(option('benediktengel.google-calendar-plugin.formatTime')))];
            }
        }
        foreach ($attributes as $attirbute) {
            if ($attirbute == 'dateEnd') {
                $events[$i] += ["dateEnd" =>  new DateTime(DateTime::createFromFormat("Y-m-d?H:i:sP", $event['end']['dateTime'])->format(option('benediktengel.google-calendar-plugin.formatDate')))];
            }
        }
        foreach ($attributes as $attirbute) {
            if ($attirbute == 'timeEnd') {
                $events[$i] += ["timeEnd" =>  new DateTime(DateTime::createFromFormat("Y-m-d?H:i:sP", $event['end']['dateTime'])->format(option('benediktengel.google-calendar-plugin.formatTime')))];
            }
        }
        foreach ($attributes as $attirbute) {
            if ($attirbute == 'description') {
                if (isset($event['description'])) {
                    $events[$i] += ["description" => $event['description']];
                }
            }
        }
        foreach ($attributes as $attirbute) {
            if ($attirbute == 'location') {
                if (isset($event['location'])) {
                    $events[$i] += ["location" => $event['location']];
                }
            }
        }
        foreach ($attributes as $attirbute) {
            if ($attirbute == 'url') {
                $events[$i] += ["url" => $event['htmlLink']];
            }
        }
        $i++;
    }
}


// Print the calendar only upcoming
if (sizeof($events) != 0) {
    if (option('benediktengel.google-calendar-plugin.upcoming') == true && isset($events[0]['dateEnd'])) {
        for ($s=0; $s < sizeof($events); $s++) {
            if ($today <= $events[$s]['dateEnd']) {
                echo "<div class='calendar-event'>" ;
                if (isset($events[$s]['title'])) {
                    echo "<h4 class='calendar-title'>".$events[$s]['title']."</h4>";
                }
                if (isset($events[$s]['datestart']) && isset($events[$s]['timestart']) && isset($events[$s]['dateEnd']) && isset($events[$s]['timeEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span> - <span class='calendar-end'>";
                    echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'))." ".$events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['datestart']) && isset($events[$s]['timestart']) && isset($events[$s]['dateEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span> - <span class='calendar-end'>";
                    echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['datestart']) && isset($events[$s]['timestart']) && isset($events[$s]['timeEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span> - <span class='calendar-end'>";
                    echo $events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['datestart']) && isset($events[$s]['dateEnd']) && isset($events[$s]['timeEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo "</span> - <span class='calendar-end'>";
                    echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'))." ".$events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['timestart']) && isset($events[$s]['dateEnd']) && isset($events[$s]['timeEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span> - <span class='calendar-end'>";
                    echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'))." ".$events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['datestart']) && isset($events[$s]['dateEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo "</span> - <span class='calendar-end'>";
                    echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['timestart']) && isset($events[$s]['timeEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span> - <span class='calendar-end'>";
                    echo $events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['datestart']) && isset($events[$s]['timestart'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['datestart'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'))."</span></p>";
                } elseif (isset($events[$s]['timestart'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-start'>";
                    echo $events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'))."</span></p>";
                } elseif (isset($events[$s]['dateEnd']) && isset($events[$s]['timeEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-end'>";
                    echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'))." ".$events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['dateEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-end'>";
                    echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                    echo "</span></p>";
                } elseif (isset($events[$s]['timeEnd'])) {
                    echo "<p class='calendar-datetime'><span class='calendar-end'>";
                    echo $events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                    echo "</span></p>";
                }
                if (isset($events[$s]['location'])) {
                    echo "<p class='calendar-location'>".$events[$s]['location']."</p>";
                }
                if (isset($events[$s]['description'])) {
                    if (option('benediktengel.google-calendar-plugin.cutDescription') == true) {
                        echo "<p class='calendar-description'>".substr($events[$s]['description'], 0, option('benediktengel.google-calendar-plugin.descriptionLength'))."... <a target='_blank' class='calendar-link' href='".$events[$s]['url']."'>".option('benediktengel.google-calendar-plugin.linkName')."</a></p>";
                    } else {
                        echo "<p class='calendar-description'>".$events[$s]['description']."<a target='_blank' class='calendar-link' href='".$events[$s]['url']."'>".option('benediktengel.google-calendar-plugin.linkName')."</a></p>";
                    }
                } elseif (isset($events[$s]['url'])) {
                    echo "<a target='_blank' class='calendar-link' href='".$events[$s]['url']."'>".option('benediktengel.google-calendar-plugin.linkName')."</a>";
                }
                echo "</div>";
            }
        }
    }
    // Print the calendar all events
    else {
        for ($s=0; $s < sizeof($events); $s++) {
            echo "<div class='calendar-event'>" ;
            if (isset($events[$s]['title'])) {
                echo "<h4 class='calendar-title'>".$events[$s]['title']."</h4>";
            }
            if (isset($events[$s]['datestart']) && isset($events[$s]['timestart']) && isset($events[$s]['dateEnd']) && isset($events[$s]['timeEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span> - <span class='calendar-end'>";
                echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'))." ".$events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span></p>";
            } elseif (isset($events[$s]['datestart']) && isset($events[$s]['timestart']) && isset($events[$s]['dateEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span> - <span class='calendar-end'>";
                echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo "</span></p>";
            } elseif (isset($events[$s]['datestart']) && isset($events[$s]['timestart']) && isset($events[$s]['timeEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span> - <span class='calendar-end'>";
                echo $events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span></p>";
            } elseif (isset($events[$s]['datestart']) && isset($events[$s]['dateEnd']) && isset($events[$s]['timeEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo "</span> - <span class='calendar-end'>";
                echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'))." ".$events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span></p>";
            } elseif (isset($events[$s]['timestart']) && isset($events[$s]['dateEnd']) && isset($events[$s]['timeEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span> - <span class='calendar-end'>";
                echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'))." ".$events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span></p>";
            } elseif (isset($events[$s]['datestart']) && isset($events[$s]['dateEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo "</span> - <span class='calendar-end'>";
                echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo "</span></p>";
            } elseif (isset($events[$s]['timestart']) && isset($events[$s]['timeEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span> - <span class='calendar-end'>";
                echo $events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span></p>";
            } elseif (isset($events[$s]['datestart']) && isset($events[$s]['timestart'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo " ".$events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span></p>";
            } elseif (isset($events[$s]['datestart'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['datestart']->format(option('benediktengel.google-calendar-plugin.formatDate'))."</span></p>";
            } elseif (isset($events[$s]['timestart'])) {
                echo "<p class='calendar-datetime'><span class='calendar-start'>";
                echo $events[$s]['timestart']->format(option('benediktengel.google-calendar-plugin.formatTime'))."</span></p>";
            } elseif (isset($events[$s]['dateEnd']) && isset($events[$s]['timeEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-end'>";
                echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'))." ".$events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span></p>";
            } elseif (isset($events[$s]['dateEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-end'>";
                echo $events[$s]['dateEnd']->format(option('benediktengel.google-calendar-plugin.formatDate'));
                echo "</span></p>";
            } elseif (isset($events[$s]['timeEnd'])) {
                echo "<p class='calendar-datetime'><span class='calendar-end'>";
                echo $events[$s]['timeEnd']->format(option('benediktengel.google-calendar-plugin.formatTime'));
                echo "</span></p>";
            }
            if (isset($events[$s]['location'])) {
                echo "<p class='calendar-location'>".$events[$s]['location']."</p>";
            }
            if (isset($events[$s]['description']) && isset($events[$s]['url'])) {
                if (option('benediktengel.google-calendar-plugin.cutDescription') == true) {
                    echo "<p class='calendar-description'>".substr($events[$s]['description'], 0, option('benediktengel.google-calendar-plugin.descriptionLength'))."... <a target='_blank' class='calendar-link' href='".$events[$s]['url']."'>".option('benediktengel.google-calendar-plugin.linkName')."</a></p>";
                } else {
                    echo "<p class='calendar-description'>".$events[$s]['description']."<a target='_blank' class='calendar-link' href='".$events[$s]['url']."'>".option('benediktengel.google-calendar-plugin.linkName')."</a></p>";
                }
            } elseif (isset($events[$s]['description'])) {
                if (option('benediktengel.google-calendar-plugin.cutDescription') == true) {
                    echo "<p class='calendar-description'>".substr($events[$s]['description'], 0, option('benediktengel.google-calendar-plugin.descriptionLength'))."... </p>";
                } else {
                    echo "<p class='calendar-description'>".$events[$s]['description']."</p>";
                }
            } elseif (isset($events[$s]['url'])) {
                echo "<a target='_blank' class='calendar-link' href='".$events[$s]['url']."'>".option('benediktengel.google-calendar-plugin.linkName')."</a>";
            }
            echo "</div>";
        }
    }
} else {
    echo "No Events.";
}
