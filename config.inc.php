//This Config file is for PHPMyadmin Docker container from official Repository.
// Single container to Access Multiple MySQL Container.
//Paste this file in Location - /etc/phpmyadmin/

<?php
require '/etc/phpmyadmin/config.secret.inc.php';

/* Ensure we got the environment */
$vars = [
    'PMA_ARBITRARY',
    'PMA_HOST',
    'PMA_HOSTS',
    'PMA_VERBOSE',
    'PMA_VERBOSES',
    'PMA_PORT',
    'PMA_PORTS',
    'PMA_SOCKET',
    'PMA_SOCKETS',
    'PMA_USER',
    'PMA_PASSWORD',
    'PMA_ABSOLUTE_URI',
    'PMA_CONTROLHOST',
    'PMA_CONTROLPORT',
    'PMA_PMADB',
    'PMA_CONTROLUSER',
    'PMA_CONTROLPASS',
    'PMA_QUERYHISTORYDB',
    'PMA_QUERYHISTORYMAX',
    'MAX_EXECUTION_TIME',
    'MEMORY_LIMIT',
    'PMA_UPLOADDIR',
    'PMA_SAVEDIR',
];

foreach ($vars as $var) {
    $env = getenv($var);
    if (!isset($_ENV[$var]) && $env !== false) {
        $_ENV[$var] = $env;
    }
}

if (isset($_ENV['PMA_QUERYHISTORYDB'])) {
    $cfg['QueryHistoryDB'] = (bool) $_ENV['PMA_QUERYHISTORYDB'];
}

if (isset($_ENV['PMA_QUERYHISTORYMAX'])) {
    $cfg['QueryHistoryMax'] = (int) $_ENV['PMA_QUERYHISTORYMAX'];
}

/* Arbitrary server connection */
if (isset($_ENV['PMA_ARBITRARY']) && $_ENV['PMA_ARBITRARY'] === '1') {
    $cfg['AllowArbitraryServer'] = true;
}

/* Play nice behind reverse proxies */
if (isset($_ENV['PMA_ABSOLUTE_URI'])) {
    $cfg['PmaAbsoluteUri'] = trim($_ENV['PMA_ABSOLUTE_URI']);
}

/* Define hosts and other settings directly */
$hosts = ['172.17.0.3', '192.168.120.85', '172.17.0.4'];
$verbose = ['Primary Server', 'Secondary Server', 'Third Server'];
$ports = ['3306', '3306', '3307']; // Default MySQL port

$sockets = [];

/* Server settings */
$serverIndex = 1;

foreach ($hosts as $i => $host) {
    $cfg['Servers'][$serverIndex]['host'] = $host;
    if (isset($verbose[$i])) {
        $cfg['Servers'][$serverIndex]['verbose'] = $verbose[$i];
    }
    if (isset($ports[$i])) {
        $cfg['Servers'][$serverIndex]['port'] = $ports[$i];
    }
    if (isset($_ENV['PMA_USER'])) {
        $cfg['Servers'][$serverIndex]['auth_type'] = 'config';
        $cfg['Servers'][$serverIndex]['user'] = $_ENV['PMA_USER'];
        $cfg['Servers'][$serverIndex]['password'] = isset($_ENV['PMA_PASSWORD']) ? $_ENV['PMA_PASSWORD'] : '';
    } else {
        $cfg['Servers'][$serverIndex]['auth_type'] = 'cookie';
    }
    if (isset($_ENV['PMA_PMADB'])) {
        $cfg['Servers'][$serverIndex]['pmadb'] = $_ENV['PMA_PMADB'];
        $cfg['Servers'][$serverIndex]['relation'] = 'pma__relation';
        $cfg['Servers'][$serverIndex]['table_info'] = 'pma__table_info';
        $cfg['Servers'][$serverIndex]['table_coords'] = 'pma__table_coords';
        $cfg['Servers'][$serverIndex]['pdf_pages'] = 'pma__pdf_pages';
        $cfg['Servers'][$serverIndex]['column_info'] = 'pma__column_info';
        $cfg['Servers'][$serverIndex]['bookmarktable'] = 'pma__bookmark';
        $cfg['Servers'][$serverIndex]['history'] = 'pma__history';
        $cfg['Servers'][$serverIndex]['recent'] = 'pma__recent';
        $cfg['Servers'][$serverIndex]['favorite'] = 'pma__favorite';
        $cfg['Servers'][$serverIndex]['table_uiprefs'] = 'pma__table_uiprefs';
        $cfg['Servers'][$serverIndex]['tracking'] = 'pma__tracking';
        $cfg['Servers'][$serverIndex]['userconfig'] = 'pma__userconfig';
        $cfg['Servers'][$serverIndex]['users'] = 'pma__users';
        $cfg['Servers'][$serverIndex]['usergroups'] = 'pma__usergroups';
        $cfg['Servers'][$serverIndex]['navigationhiding'] = 'pma__navigationhiding';
        $cfg['Servers'][$serverIndex]['savedsearches'] = 'pma__savedsearches';
        $cfg['Servers'][$serverIndex]['central_columns'] = 'pma__central_columns';
        $cfg['Servers'][$serverIndex]['designer_settings'] = 'pma__designer_settings';
        $cfg['Servers'][$serverIndex]['export_templates'] = 'pma__export_templates';
    }
    if (isset($_ENV['PMA_CONTROLHOST'])) {
        $cfg['Servers'][$serverIndex]['controlhost'] = $_ENV['PMA_CONTROLHOST'];
    }
    if (isset($_ENV['PMA_CONTROLPORT'])) {
        $cfg['Servers'][$serverIndex]['controlport'] = $_ENV['PMA_CONTROLPORT'];
    }
    if (isset($_ENV['PMA_CONTROLUSER'])) {
        $cfg['Servers'][$serverIndex]['controluser'] = $_ENV['PMA_CONTROLUSER'];
    }
    if (isset($_ENV['PMA_CONTROLPASS'])) {
        $cfg['Servers'][$serverIndex]['controlpass'] = $_ENV['PMA_CONTROLPASS'];
    }
    $cfg['Servers'][$serverIndex]['compress'] = false;
    $cfg['Servers'][$serverIndex]['AllowNoPassword'] = true;
    $serverIndex++;
}

foreach ($sockets as $i => $socket) {
    $cfg['Servers'][$serverIndex]['socket'] = $socket;
    $cfg['Servers'][$serverIndex]['host'] = 'localhost';
    $serverIndex++;
}

/* Uploads setup */
if (isset($_ENV['PMA_UPLOADDIR'])) {
    $cfg['UploadDir'] = $_ENV['PMA_UPLOADDIR'];
}

if (isset($_ENV['PMA_SAVEDIR'])) {
    $cfg['SaveDir'] = $_ENV['PMA_SAVEDIR'];
}

if (isset($_ENV['MAX_EXECUTION_TIME'])) {
    $cfg['ExecTimeLimit'] = $_ENV['MAX_EXECUTION_TIME'];
}

if (isset($_ENV['MEMORY_LIMIT'])) {
    $cfg['MemoryLimit'] = $_ENV['MEMORY_LIMIT'];
}

/* Include User Defined Settings Hook */
if (file_exists('/etc/phpmyadmin/config.user.inc.php')) {
    include '/etc/phpmyadmin/config.user.inc.php';
}

/* Support additional configurations */
if (is_dir('/etc/phpmyadmin/conf.d/')) {
    foreach (glob('/etc/phpmyadmin/conf.d/*.php') as $filename) {
        include $filename;
    }
}
