<?php

use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Cookie;

require_once 'vendor/autoload.php';

/**
* Illuminate/session
*
* Illuminate Sessions outside of laravel;
*
* Requires: illuminate/support
*           illuminate/container
*           illuminate/session
*           illuminate/config
*
* @todo Drivers other than the file driver
*
* @source https://github.com/illuminate/session
* @contributor Matt Stauffer
* @contributor Sam Jordan
* @contributor Jordon Brill
*/

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

// Init the container
$container = new Container;
$container->bind('app', $container);

$container['config'] = new Config(require __DIR__ . '/config/app.php');

$container['files'] = new Filesystem;

// NOTE: some of the items for the cookie (lifetime, expire_on_close)
// are set in the config file and others are set below to show the
// flexibility when using config
$container['config']['session.lottery'] = [2, 100]; // lottery--how often do they sweep storage location to clear old ones?
$container['config']['session.cookie'] = 'laravel_session';
$container['config']['session.path'] = '/';
$container['config']['session.domain'] = null;
$container['config']['session.driver'] = 'file';
$container['config']['session.files'] = __DIR__ . '/sessions';

// Now we need to fire up the session manager
$sessionManager = new SessionManager($container);
$container['session.store'] = $sessionManager->driver();
$container['session'] = $sessionManager;

// In order to maintain the session between requests, we need to populate the
// session ID from the supplied cookie
$cookieName = $container['session']->getName();

if (isset($_COOKIE[$cookieName])) {
    if ($sessionId = $_COOKIE[$cookieName]) {
        $container['session']->setId($sessionId);
    }
}

// Boot the session
$container['session']->start();
// END BOOTSTRAP---------------------------------------------------------------

// View
$app->get('/', function () use ($container) {
    echo 'Current state of <code>$test</code>: ';

    if ($container['session']->has('test')) {
        echo 'Set<br> Value: ' . $container['session']->get('test');
    } else {
        echo 'Not set';
    }

    echo '<hr><a href="/set">Set session variable</a>';
});

// Set
$app->get('/set', function () use ($container) {
    $var = randomVar();

    if ($container['session']->has('test')) {
        echo '<p><code>$test</code> is set. Overriding it to now be <code>' . $var . '</code></p>';
    } else {
        echo '<p><code>$test</code> is not set. Setting to be <code>' . $var . '</code></p>';
    }

    $container['session']->put('test', $var);

    // Save the session
    $container['session']->save();

    // The session is saved; now, we'll store the session ID in a cookie to allow for
    // the session to remain on future requests
    $cookie = new Cookie(
        $container['session']->getName(),
        $container['session']->getId(),
        time() + ($container['config']['session.lifetime'] * 60),
        '/',
        null,
        false
    );

    setcookie(
        $cookie->getName(),
        $cookie->getValue(),
        $cookie->getExpiresTime(),
        $cookie->getPath(),
        $cookie->getDomain()
    );

    echo '<hr><a href="/">View current value of session variable</a>';
});

function randomVar() {
    $names = [
        "Afghanistan",
        "Åland Islands",
        "Albania",
        "Algeria",
        "American Samoa",
        "Andorra",
        "Angola",
        "Anguilla",
        "Antarctica",
        "Antigua and Barbuda",
        "Argentina",
        "Armenia",
        "Aruba",
        "Australia",
        "Austria",
        "Azerbaijan",
        "Bahamas",
        "Bahrain",
        "Bangladesh",
        "Barbados",
        "Belarus",
        "Belgium",
        "Belize",
        "Benin",
        "Bermuda",
        "Bhutan",
        "Bolivia (Plurinational State of)",
        "Bonaire, Sint Eustatius and Saba",
        "Bosnia and Herzegovina",
        "Botswana",
        "Bouvet Island",
        "Brazil",
        "British Indian Ocean Territory",
        "Brunei Darussalam",
        "Bulgaria",
        "Burkina Faso",
        "Burundi",
        "Cambodia",
        "Cameroon",
        "Canada",
        "Cabo Verde",
        "Cayman Islands",
        "Central African Republic",
        "Chad",
        "Chile",
        "China",
        "Christmas Island",
        "Cocos (Keeling) Islands",
        "Colombia",
        "Comoros",
        "Congo",
        "Congo (Democratic Republic of the)",
        "Cook Islands",
        "Costa Rica",
        "Côte d'Ivoire",
        "Croatia",
        "Cuba",
        "Curaçao",
        "Cyprus",
        "Czech Republic",
        "Denmark",
        "Djibouti",
        "Dominica",
        "Dominican Republic",
        "Ecuador",
        "Egypt",
        "El Salvador",
        "Equatorial Guinea",
        "Eritrea",
        "Estonia",
        "Ethiopia",
        "Falkland Islands (Malvinas)",
        "Faroe Islands",
        "Fiji",
        "Finland",
        "France",
        "French Guiana",
        "French Polynesia",
        "French Southern Territories",
        "Gabon",
        "Gambia",
        "Georgia",
        "Germany",
        "Ghana",
        "Gibraltar",
        "Greece",
        "Greenland",
        "Grenada",
        "Guadeloupe",
        "Guam",
        "Guatemala",
        "Guernsey",
        "Guinea",
        "Guinea-Bissau",
        "Guyana",
        "Haiti",
        "Heard Island and McDonald Islands",
        "Holy See",
        "Honduras",
        "Hong Kong",
        "Hungary",
        "Iceland",
        "India",
        "Indonesia",
        "Iran (Islamic Republic of)",
        "Iraq",
        "Ireland",
        "Isle of Man",
        "Israel",
        "Italy",
        "Jamaica",
        "Japan",
        "Jersey",
        "Jordan",
        "Kazakhstan",
        "Kenya",
        "Kiribati",
        "Korea (Democratic People's Republic of)",
        "Korea (Republic of)",
        "Kuwait",
        "Kyrgyzstan",
        "Lao People's Democratic Republic",
        "Latvia",
        "Lebanon",
        "Lesotho",
        "Liberia",
        "Libya",
        "Liechtenstein",
        "Lithuania",
        "Luxembourg",
        "Macao",
        "Macedonia (the former Yugoslav Republic of)",
        "Madagascar",
        "Malawi",
        "Malaysia",
        "Maldives",
        "Mali",
        "Malta",
        "Marshall Islands",
        "Martinique",
        "Mauritania",
        "Mauritius",
        "Mayotte",
        "Mexico",
        "Micronesia (Federated States of)",
        "Moldova (Republic of)",
        "Monaco",
        "Mongolia",
        "Montenegro",
        "Montserrat",
        "Morocco",
        "Mozambique",
        "Myanmar",
        "Namibia",
        "Nauru",
        "Nepal",
        "Netherlands",
        "New Caledonia",
        "New Zealand",
        "Nicaragua",
        "Niger",
        "Nigeria",
        "Niue",
        "Norfolk Island",
        "Northern Mariana Islands",
        "Norway",
        "Oman",
        "Pakistan",
        "Palau",
        "Palestine, State of",
        "Panama",
        "Papua New Guinea",
        "Paraguay",
        "Peru",
        "Philippines",
        "Pitcairn",
        "Poland",
        "Portugal",
        "Puerto Rico",
        "Qatar",
        "Réunion",
        "Romania",
        "Russian Federation",
        "Rwanda",
        "Saint Barthélemy",
        "Saint Helena, Ascension and Tristan da Cunha",
        "Saint Kitts and Nevis",
        "Saint Lucia",
        "Saint Martin (French part)",
        "Saint Pierre and Miquelon",
        "Saint Vincent and the Grenadines",
        "Samoa",
        "San Marino",
        "Sao Tome and Principe",
        "Saudi Arabia",
        "Senegal",
        "Serbia",
        "Seychelles",
        "Sierra Leone",
        "Singapore",
        "Sint Maarten (Dutch part)",
        "Slovakia",
        "Slovenia",
        "Solomon Islands",
        "Somalia",
        "South Africa",
        "South Georgia and the South Sandwich Islands",
        "South Sudan",
        "Spain",
        "Sri Lanka",
        "Sudan",
        "Suriname",
        "Svalbard and Jan Mayen",
        "Swaziland",
        "Sweden",
        "Switzerland",
        "Syrian Arab Republic",
        "Taiwan, Province of China",
        "Tajikistan",
        "Tanzania, United Republic of",
        "Thailand",
        "Timor-Leste",
        "Togo",
        "Tokelau",
        "Tonga",
        "Trinidad and Tobago",
        "Tunisia",
        "Turkey",
        "Turkmenistan",
        "Turks and Caicos Islands",
        "Tuvalu",
        "Uganda",
        "Ukraine",
        "United Arab Emirates",
        "United Kingdom of Great Britain and Northern Ireland",
        "United States of America",
        "United States Minor Outlying Islands",
        "Uruguay",
        "Uzbekistan",
        "Vanuatu",
        "Venezuela (Bolivarian Republic of)",
        "Viet Nam",
        "Virgin Islands (British)",
        "Virgin Islands (U.S.)",
        "Wallis and Futuna",
        "Western Sahara",
        "Yemen",
        "Zambia",
    ];

    return $names[array_rand($names)];
}

$app->run();
