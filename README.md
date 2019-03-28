# google-calendar-plugin

Plugin to get easy the content of your Google calendar as a list in your Kirby-Website.

**Requirements:** [Kirby CMS](https://getkirby.com) >= v3.0.1

## Installation

There are three ways to get the plugin:

### Download

Download and copy this repository to `/site/plugins/`.
That's it. You're done.

### Git submodule

Go to the root of your Project, copy `git submodule add https://github.com/benediktengel/G´google-calendar-plugin.git site/plugins/google-calendar-plugin` in your command line and press enter.
Done. The plugin appears in `site/plugins`.

### Composer

Copy `composer require benediktengel/google-calendar-plugin` to your command line and press enter.
Done. Also here, the plugin appears in `site/plugins`.

## Setup

### 1. Set your API-Key and your calendarID in your `site/config/config.php`

```php
'benediktengel.google-calendar-plugin.apikey' => 'YOUR-API-KEY',
'benediktengel.google-calendar-plugin.calendarID' => 'YOUR-CALENDAR-ID'
```

Need Help? [How to get them.](/howtogetkey.md)

### 2. Place the snippet in your template

Find the right place for the calendar and add `<?= snippet('calendar') ?>`

### 3. Be glad

You did it.

## Options

-   Set your API-Key: `benediktengel.google-calendar-plugin.apikey' => 'YOUR-API-KEY'`
    -   required


-   Set your calendarID: `'benediktengel.google-calendar-plugin.calendarID' => 'YOUR-CALENDAR-ID'`
    -   required


-   Change the date format: `'benediktengel.google-calendar-plugin.formatDate' => 'd.m.Y'`
    -   default: `'d.m.Y'`
    -   optional


-   Change the time format: `'benediktengel.google-calendar-plugin.formatTime' => 'H:i'`
    -   default: `'H:i'`
    -   optional


-   What attributes of the events do you want: `'benediktengel.google-calendar-plugin.attributes' => []`
    -   default: `['title', 'dateStart','timeStart', 'dateEnd', 'timeEnd', 'description', 'location', 'url']`
    -   possible:
        -   `'title'`
        -   `'dateStart'`
        -   `'timeStart'`
        -   `'dateEnd'`
        -   `'timeEnd'`
        -   `'description'`
        -   `'location'`
        -   `'url'`
    -   optional


-   Show only upcoming events: `'benediktengel.google-calendar-plugin.upcoming' => true`
    -   default: `true`
    -   optional
    -   works only if `attributes` are set to default or the attribute `dateEnd` is set.


-   Shorten the displayed description: `'benediktengel.google-calendar-plugin.descriptionLength' => '300'`
    -   default: `'300'`
    -   optional
    -   works only if `cutDescription` is true


-   Don't short the description: `'benediktengel.google-calendar-plugin.cutDescription' => true`
    -   default: `true`
    -   optional


-   Name of the link: `'benediktengel.google-calendar-plugin.linkName' => 'Show more.'`
    -   default: `'Show more.'`
    -   optional


-   Style it!

    Each Event comes like this, so you can use the classes to style:

    ```html
    <div class="calendar-event">
        <h4 class="calendar-title">
            New Year's Eve party
        </h4>
        <p class="calendar-datetime">
            <span class="calendar-start">
                31.12.2018 20:00
            </span> -
            <span class="calendar-end">
                01.01.2019 08:00
            </span>
        </p>
        <p class="calendar-location">
            My place
        </p>
        <p class="calendar-description">
            Come to celebrate the new year together.
            <a class="calendar-link">
                Show more.
            </a>
        </p>
    </div>
    ```

## Roadmap

-   [ ] Section for the settings
-   [ ] month view
-   [ ] week view

Wishes? Write an [issue](https://github.com/BenediktEngel/google-calendar-plugin/issues/new) and use the label `enhancement`.

## Problems?

Write an [issue](https://github.com/BenediktEngel/google-calendar-plugin/issues/new) and maybe I can help you.

## License

MIT

## Credits

[Benedikt Engel](https://github.com/benediktengel), 2019

* * *

Want to support me? If yes, [buy me a coffee](buymeacoff.ee/Ij7WUef0o) ☕. [Beer](paypal.me/benediktengel) is also okay🍻.

Thanks!
