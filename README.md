
# Sendgrid Plugin

Adds support for Sendgrid as a mail driver in October CMS. After installing this plugin, Sendgrid will appear as a mail driver in the **Settings → Mail Configuration** area.

## Requirements

- October CMS 3.3.8 or above

### Installation

 1.  Run these two commands from your project root.

```bash

composer require miravo/sendgrid-plugin
composer require s-ichikawa/laravel-sendgrid-driver

```

2. Add this in config/services.php

       'sendgrid' => [
            'api_key' => env('SENDGRID_API_KEY'),
        ],

3. Add this in config/mail.php

        'mailers' => [
	        'sendgrid' => [
	            'transport' => 'sendgrid',
	        ],
	     ],

4. Go in the backend, Settings → Mail Configuration  and set your API Key.


## Notes

Sendgrid requires a plain text body version to send messages. If the plain text body is empty, Sendgrid will return an error from the API, such as:

    Client error: `POST https://api.sendgrid.com/v3/mail/send` resulted in a `400 Bad Request` response:{"errors":[{"message":"The content value must be a string at least one character in length.","field":"content.0.value"," (truncated...)

To avoid this error, you have two options: 

1. either include a plain text version for all your emails
2. tick the checkbox "Automatically convert HTML to plain text, if plain text is empty." 
